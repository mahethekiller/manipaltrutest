<?php
/**
 * Catalog Importer & Sync Manager for Pathology Booking System
 * Includes Center Locations mappings
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_Importer {

    /**
     * Run high-performance import from data.json into WordPress database
     *
     * @param bool $fresh If true, wipes existing ptbs_test, ptbs_package, and ptbs_center_location posts before importing
     * @return array Results summary
     */
    public static function run( $fresh = false ) {
        global $wpdb;

        $json_file = PTBS_DIR_PATH . 'data.json';
        if ( ! file_exists( $json_file ) ) {
            $json_file = dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/manipaltesttt/data.json';
        }

        if ( ! file_exists( $json_file ) ) {
            return array(
                'success' => false,
                'message' => 'data.json not found. Please place data.json in the plugin directory.'
            );
        }

        $raw_json = file_get_contents( $json_file );
        $data = json_decode( $raw_json, true );
        if ( ! $data || empty( $data['tests'] ) || empty( $data['packages'] ) ) {
            return array(
                'success' => false,
                'message' => 'Invalid or corrupted data.json.'
            );
        }

        $table_prefix = $wpdb->prefix;

        if ( $fresh ) {
            $wpdb->query( "DELETE pm FROM {$table_prefix}postmeta pm INNER JOIN {$table_prefix}posts p ON pm.post_id = p.ID WHERE p.post_type IN ('ptbs_test', 'ptbs_package', 'ptbs_center_location')" );
            $wpdb->query( "DELETE tr FROM {$table_prefix}term_relationships tr INNER JOIN {$table_prefix}posts p ON tr.object_id = p.ID WHERE p.post_type IN ('ptbs_test', 'ptbs_package', 'ptbs_center_location')" );
            $wpdb->query( "DELETE FROM {$table_prefix}posts WHERE post_type IN ('ptbs_test', 'ptbs_package', 'ptbs_center_location')" );
        }

        // Helper for term creation/retrieval
        $term_cache = array();
        $find_term = function( $name, $taxonomy ) use ( $wpdb, $table_prefix, &$term_cache ) {
            $key = strtolower( trim( $name ) );
            if ( isset( $term_cache[$taxonomy][$key] ) ) {
                return $term_cache[$taxonomy][$key];
            }
            $row = $wpdb->get_row( $wpdb->prepare(
                "SELECT t.term_id, tt.term_taxonomy_id FROM {$table_prefix}terms t INNER JOIN {$table_prefix}term_taxonomy tt ON t.term_id = tt.term_id WHERE t.name = %s AND tt.taxonomy = %s LIMIT 1",
                $name, $taxonomy
            ) );
            if ( $row ) {
                $term_cache[$taxonomy][$key] = array( (int)$row->term_id, (int)$row->term_taxonomy_id );
                return $term_cache[$taxonomy][$key];
            }
            return null;
        };

        $create_term = function( $name, $slug, $taxonomy, $parent_tt_id = 0 ) use ( $wpdb, $table_prefix, $find_term, &$term_cache ) {
            $found = $find_term( $name, $taxonomy );
            if ( $found ) return $found;

            $safe_slug = $slug ?: sanitize_title( $name );
            $wpdb->insert( "{$table_prefix}terms", array( 'name' => $name, 'slug' => $safe_slug, 'term_group' => 0 ) );
            $term_id = (int)$wpdb->insert_id;

            $wpdb->insert( "{$table_prefix}term_taxonomy", array( 'term_id' => $term_id, 'taxonomy' => $taxonomy, 'description' => '', 'parent' => $parent_tt_id, 'count' => 0 ) );
            $tt_id = (int)$wpdb->insert_id;

            $term_cache[$taxonomy][strtolower( trim( $name ) )] = array( $term_id, $tt_id );
            return array( $term_id, $tt_id );
        };

        // 1. Categories
        $category_map = array();
        foreach ( $data['categories'] as $cat ) {
            if ( empty( $cat['name'] ) ) continue;
            list( $tid, $ttid ) = $create_term( $cat['name'], $cat['slug'] ?? '', 'ptbs_category', 0 );
            update_term_meta( $tid, '_ptbs_status', 'Active' );
            $category_map[strtolower( trim( $cat['name'] ) )] = array( $tid, $ttid );
        }

        // 2. Subcategories
        $subcategory_map = array();
        foreach ( $data['subcategories'] as $sub ) {
            if ( empty( $sub['name'] ) ) continue;
            $parent_cat = strtolower( trim( $sub['category_name'] ?? '' ) );
            $parent_tid = isset( $category_map[$parent_cat] ) ? $category_map[$parent_cat][0] : 0;
            list( $tid, $ttid ) = $create_term( $sub['name'], $sub['slug'] ?? '', 'ptbs_subcategory', $parent_tid );
            update_term_meta( $tid, '_ptbs_status', $sub['status'] ?? 'Active' );
            update_term_meta( $tid, '_ptbs_parent_category_id', (string)$parent_tid );
            if ( ! empty( $sub['image'] ) ) {
                update_term_meta( $tid, '_ptbs_image_url', $sub['image'] );
            }
            $subcategory_map[strtolower( trim( $sub['name'] ) )] = array( $tid, $ttid );
        }

        // 3. Conditions
        $condition_map = array();
        $condition_id_map = array();
        foreach ( $data['conditions'] as $cond ) {
            if ( empty( $cond['name'] ) ) continue;
            list( $tid, $ttid ) = $create_term( $cond['name'], $cond['slug'] ?? '', 'ptbs_condition', 0 );
            update_term_meta( $tid, '_ptbs_status', $cond['status'] ?? 'Active' );
            if ( ! empty( $cond['image'] ) ) {
                update_term_meta( $tid, '_ptbs_image_url', $cond['image'] );
            }
            $condition_map[strtolower( trim( $cond['name'] ) )] = array( $tid, $ttid );
            if ( ! empty( $cond['id'] ) ) {
                $condition_id_map[$cond['id']] = array( $tid, $ttid );
            }
        }

        // 4. Center Locations (ptbs_center_location CPT)
        $center_name_to_post_id = array();
        $center_count = 0;
        if ( ! empty( $data['center_locations'] ) ) {
            foreach ( $data['center_locations'] as $center ) {
                $cname = $center['name'];
                if ( empty( $cname ) ) continue;
                $ckey = strtolower( trim( $cname ) );

                $existing_id = $wpdb->get_var( $wpdb->prepare(
                    "SELECT ID FROM {$table_prefix}posts WHERE post_title = %s AND post_type = 'ptbs_center_location' LIMIT 1",
                    $cname
                ) );

                if ( $existing_id ) {
                    $center_name_to_post_id[$ckey] = (int)$existing_id;
                } else {
                    $cslug = $center['slug'] ?: sanitize_title( $cname );
                    $wpdb->insert( "{$table_prefix}posts", array(
                        'post_author'       => 1,
                        'post_date'         => current_time( 'mysql' ),
                        'post_date_gmt'     => current_time( 'mysql', 1 ),
                        'post_content'      => '',
                        'post_title'        => $cname,
                        'post_status'       => 'publish',
                        'comment_status'    => 'closed',
                        'ping_status'       => 'closed',
                        'post_name'         => $cslug,
                        'post_modified'     => current_time( 'mysql' ),
                        'post_modified_gmt' => current_time( 'mysql', 1 ),
                        'post_type'         => 'ptbs_center_location',
                    ) );
                    $cid = (int)$wpdb->insert_id;
                    $wpdb->update( "{$table_prefix}posts", array(
                        'guid' => home_url( "/?post_type=ptbs_center_location&p={$cid}" )
                    ), array( 'ID' => $cid ) );

                    $center_name_to_post_id[$ckey] = $cid;
                    $center_count++;
                }
            }
        }

        // 5. Lab Tests
        $test_name_to_post_id = array();
        $test_count = 0;
        $tests_with_centers = 0;

        foreach ( $data['tests'] as $test ) {
            $post_title = $test['name'];
            $post_slug  = $test['slug'] ?: sanitize_title( $post_title );
            $post_status = ( $test['status'] === 'Active' ) ? 'publish' : 'draft';
            $post_content = $test['method'] ? "Diagnostic Method: " . $test['method'] : "";

            $wpdb->insert( "{$table_prefix}posts", array(
                'post_author'           => 1,
                'post_date'             => current_time( 'mysql' ),
                'post_date_gmt'         => current_time( 'mysql', 1 ),
                'post_content'          => $post_content,
                'post_title'            => $post_title,
                'post_status'           => $post_status,
                'comment_status'        => 'closed',
                'ping_status'           => 'closed',
                'post_name'             => $post_slug,
                'post_modified'         => current_time( 'mysql' ),
                'post_modified_gmt'     => current_time( 'mysql', 1 ),
                'post_type'             => 'ptbs_test',
            ) );
            $post_id = (int)$wpdb->insert_id;

            $wpdb->update( "{$table_prefix}posts", array(
                'guid' => home_url( "/?post_type=ptbs_test&p={$post_id}" )
            ), array( 'ID' => $post_id ) );

            // Map center location IDs
            $test_center_ids = array();
            if ( ! empty( $test['center_locations'] ) ) {
                foreach ( $test['center_locations'] as $cloc_name ) {
                    $cloc_key = strtolower( trim( $cloc_name ) );
                    if ( isset( $center_name_to_post_id[$cloc_key] ) ) {
                        $test_center_ids[] = $center_name_to_post_id[$cloc_key];
                    }
                }
                $test_center_ids = array_values( array_unique( $test_center_ids ) );
                if ( ! empty( $test_center_ids ) ) {
                    $tests_with_centers++;
                }
            }

            $metas = array(
                '_ptbs_code'                => $test['code'] ?? '',
                '_ptbs_price'               => (string)($test['price'] ?? 0),
                '_ptbs_main_test_name'      => $test['name'],
                '_ptbs_cutoff_time'         => $test['cutoff'] ?? '',
                '_ptbs_method'              => $test['method'] ?? '',
                '_ptbs_sample_type'         => $test['specimen'] ?? '',
                '_ptbs_tat_hours'           => $test['report_availability'] ?? '',
                '_ptbs_status'              => $test['status'] ?? 'Active',
                '_ptbs_excel_id'            => $test['id'] ?? '',
                '_ptbs_center_location_ids' => serialize( $test_center_ids )
            );

            foreach ( $metas as $k => $v ) {
                $wpdb->insert( "{$table_prefix}postmeta", array( 'post_id' => $post_id, 'meta_key' => $k, 'meta_value' => $v ) );
            }

            // Categories
            foreach ( $test['categories'] as $cname ) {
                $ck = strtolower( trim( $cname ) );
                if ( isset( $category_map[$ck] ) ) {
                    $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO {$table_prefix}term_relationships (object_id, term_taxonomy_id, term_order) VALUES (%d, %d, 0)", $post_id, $category_map[$ck][1] ) );
                }
            }

            // Subcategories
            foreach ( $test['subcategories'] as $sname ) {
                $sk = strtolower( trim( $sname ) );
                if ( isset( $subcategory_map[$sk] ) ) {
                    $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO {$table_prefix}term_relationships (object_id, term_taxonomy_id, term_order) VALUES (%d, %d, 0)", $post_id, $subcategory_map[$sk][1] ) );
                }
            }

            // Conditions
            foreach ( $test['conditions'] as $cdname ) {
                $cdk = strtolower( trim( $cdname ) );
                if ( isset( $condition_map[$cdk] ) ) {
                    $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO {$table_prefix}term_relationships (object_id, term_taxonomy_id, term_order) VALUES (%d, %d, 0)", $post_id, $condition_map[$cdk][1] ) );
                }
            }

            $test_name_to_post_id[strtolower( trim( $post_title ) )] = $post_id;
            $test_count++;
        }

        // 6. Packages
        $alias_map = array( 'crp (quantitative)' => 'c-reactive protein; crp (quantitative)' );
        $pkg_count = 0;
        $total_links = 0;

        foreach ( $data['packages'] as $pkg ) {
            $post_title   = $pkg['name'];
            $post_slug    = $pkg['slug'] ?: sanitize_title( $post_title );
            $post_status  = ( $pkg['status'] === 'Active' ) ? 'publish' : 'draft';
            $post_content = $pkg['description'] ?? '';

            $wpdb->insert( "{$table_prefix}posts", array(
                'post_author'           => 1,
                'post_date'             => current_time( 'mysql' ),
                'post_date_gmt'         => current_time( 'mysql', 1 ),
                'post_content'          => $post_content,
                'post_title'            => $post_title,
                'post_status'           => $post_status,
                'comment_status'        => 'closed',
                'ping_status'           => 'closed',
                'post_name'             => $post_slug,
                'post_modified'         => current_time( 'mysql' ),
                'post_modified_gmt'     => current_time( 'mysql', 1 ),
                'post_type'             => 'ptbs_package',
            ) );
            $pkg_post_id = (int)$wpdb->insert_id;

            $wpdb->update( "{$table_prefix}posts", array(
                'guid' => home_url( "/?post_type=ptbs_package&p={$pkg_post_id}" )
            ), array( 'ID' => $pkg_post_id ) );

            $linked_post_ids = array();
            foreach ( $pkg['tests_included'] as $t_inc ) {
                $tname = strtolower( trim( $t_inc['name'] ) );
                if ( isset( $alias_map[$tname] ) ) $tname = $alias_map[$tname];

                if ( isset( $test_name_to_post_id[$tname] ) ) {
                    $linked_post_ids[] = $test_name_to_post_id[$tname];
                } else {
                    foreach ( $test_name_to_post_id as $cand => $pid ) {
                        if ( strpos( $cand, $tname ) !== false || strpos( $tname, $cand ) !== false ) {
                            $linked_post_ids[] = $pid;
                            break;
                        }
                    }
                }
            }
            $linked_post_ids = array_values( array_unique( $linked_post_ids ) );
            $total_links += count( $linked_post_ids );

            // Package center location IDs
            $pkg_center_ids = array();
            if ( ! empty( $pkg['center_locations'] ) ) {
                foreach ( $pkg['center_locations'] as $cloc_name ) {
                    $cloc_key = strtolower( trim( $cloc_name ) );
                    if ( isset( $center_name_to_post_id[$cloc_key] ) ) {
                        $pkg_center_ids[] = $center_name_to_post_id[$cloc_key];
                    }
                }
                $pkg_center_ids = array_values( array_unique( $pkg_center_ids ) );
            }

            $pkg_metas = array(
                '_ptbs_code'                => $pkg['code'] ?? '',
                '_ptbs_price'               => (string)($pkg['price'] ?? 0),
                '_ptbs_mrp'              => (string)($pkg['mrp'] ?? 0),
                '_ptbs_parameters_count' => (string)$pkg['tests_count'],
                '_ptbs_status'           => $pkg['status'] ?? 'Active',
                '_ptbs_linked_test_ids'  => serialize( $linked_post_ids ),
                '_ptbs_center_location_ids' => serialize( $pkg_center_ids ),
                '_ptbs_excel_id'         => $pkg['id'] ?? ''
            );

            if ( ! empty( $pkg['image'] ) ) {
                $pkg_metas['_ptbs_image_url'] = $pkg['image'];
            }

            foreach ( $pkg_metas as $k => $v ) {
                $wpdb->insert( "{$table_prefix}postmeta", array( 'post_id' => $pkg_post_id, 'meta_key' => $k, 'meta_value' => $v ) );
            }

            // Categories
            foreach ( $pkg['categories'] as $cname ) {
                $ck = strtolower( trim( $cname ) );
                if ( isset( $category_map[$ck] ) ) {
                    $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO {$table_prefix}term_relationships (object_id, term_taxonomy_id, term_order) VALUES (%d, %d, 0)", $pkg_post_id, $category_map[$ck][1] ) );
                }
            }

            // Subcategories
            foreach ( $pkg['subcategories'] as $sname ) {
                $sk = strtolower( trim( $sname ) );
                if ( isset( $subcategory_map[$sk] ) ) {
                    $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO {$table_prefix}term_relationships (object_id, term_taxonomy_id, term_order) VALUES (%d, %d, 0)", $pkg_post_id, $subcategory_map[$sk][1] ) );
                }
            }

            // Conditions
            foreach ( $pkg['conditions'] as $cd_item ) {
                $cid = $cd_item['id'] ?? '';
                $cdname = strtolower( trim( $cd_item['name'] ?? '' ) );
                if ( $cid && isset( $condition_id_map[$cid] ) ) {
                    $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO {$table_prefix}term_relationships (object_id, term_taxonomy_id, term_order) VALUES (%d, %d, 0)", $pkg_post_id, $condition_id_map[$cid][1] ) );
                } elseif ( isset( $condition_map[$cdname] ) ) {
                    $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO {$table_prefix}term_relationships (object_id, term_taxonomy_id, term_order) VALUES (%d, %d, 0)", $pkg_post_id, $condition_map[$cdname][1] ) );
                }
            }

            $pkg_count++;
        }

        // Recompute counts
        $wpdb->query( "
            UPDATE {$table_prefix}term_taxonomy tt 
            SET count = (
                SELECT COUNT(*) 
                FROM {$table_prefix}term_relationships tr 
                WHERE tr.term_taxonomy_id = tt.term_taxonomy_id
            )
            WHERE tt.taxonomy IN ('ptbs_category', 'ptbs_subcategory', 'ptbs_condition')
        " );

        return array(
            'success'            => true,
            'categories_count'   => count( $category_map ),
            'subcategories_count'=> count( $subcategory_map ),
            'conditions_count'   => count( $condition_map ),
            'centers_count'      => count( $center_name_to_post_id ),
            'tests_count'        => $test_count,
            'packages_count'     => $pkg_count,
            'links_count'        => $total_links,
            'message'            => sprintf(
                'Successfully synced %d Tests (%d with Center Locations), %d Packages (%d test links), %d Center Locations, %d Subcategories, and %d Conditions!',
                $test_count, $tests_with_centers, $pkg_count, $total_links, count( $center_name_to_post_id ), count( $subcategory_map ), count( $condition_map )
            )
        );
    }
}
