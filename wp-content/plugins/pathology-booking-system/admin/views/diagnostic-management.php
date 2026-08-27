<?php
/**
 * Diagnostic Management Main Controller & Datatable Router
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'category';
$action      = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : 'list';
$item_id     = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
$message     = '';

// ----------------------------------------------------
// Form Submission Processing
// ----------------------------------------------------
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['ptbs_diag_nonce'] ) && wp_verify_nonce( $_POST['ptbs_diag_nonce'], 'ptbs_diag_action' ) ) {
    
    // 1. Save Category Form
    if ( isset( $_POST['ptbs_action_type'] ) && 'save_category' === $_POST['ptbs_action_type'] ) {
        $cat_name = sanitize_text_field( wp_unslash( $_POST['category_name'] ?? '' ) );
        $image_id = absint( $_POST['category_image_id'] ?? 0 );
        $status   = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );

        if ( ! empty( $cat_name ) ) {
            if ( $item_id > 0 ) {
                wp_update_term( $item_id, 'ptbs_category', array( 'name' => $cat_name ) );
                $term_id = $item_id;
            } else {
                $term = wp_insert_term( $cat_name, 'ptbs_category' );
                $term_id = is_array( $term ) ? $term['term_id'] : 0;
            }

            if ( $term_id > 0 ) {
                update_term_meta( $term_id, '_ptbs_image_id', $image_id );
                update_term_meta( $term_id, '_ptbs_status', $status );
                $message = __( 'Category saved successfully.', 'pathology-booking-system' );
                $action  = 'list';
            }
        }
    }

    // 2. Save SubCategory Form
    if ( isset( $_POST['ptbs_action_type'] ) && 'save_subcategory' === $_POST['ptbs_action_type'] ) {
        $subcat_name = sanitize_text_field( wp_unslash( $_POST['subcategory_name'] ?? '' ) );
        $parent_id   = absint( $_POST['parent_category_id'] ?? 0 );
        $image_id    = absint( $_POST['subcategory_image_id'] ?? 0 );
        $status      = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );

        if ( ! empty( $subcat_name ) ) {
            if ( $item_id > 0 ) {
                wp_update_term( $item_id, 'ptbs_subcategory', array( 'name' => $subcat_name, 'parent' => $parent_id ) );
                $term_id = $item_id;
            } else {
                $term = wp_insert_term( $subcat_name, 'ptbs_subcategory', array( 'parent' => $parent_id ) );
                $term_id = is_array( $term ) ? $term['term_id'] : 0;
            }

            if ( $term_id > 0 ) {
                update_term_meta( $term_id, '_ptbs_image_id', $image_id );
                update_term_meta( $term_id, '_ptbs_status', $status );
                update_term_meta( $term_id, '_ptbs_parent_category_id', $parent_id );
                $message = __( 'Sub Category saved successfully.', 'pathology-booking-system' );
                $action  = 'list';
            }
        }
    }

    // 3. Save Condition Form
    if ( isset( $_POST['ptbs_action_type'] ) && 'save_condition' === $_POST['ptbs_action_type'] ) {
        $cond_name = sanitize_text_field( wp_unslash( $_POST['condition_name'] ?? '' ) );
        $image_id  = absint( $_POST['condition_image_id'] ?? 0 );
        $status    = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );

        if ( ! empty( $cond_name ) ) {
            if ( $item_id > 0 ) {
                wp_update_term( $item_id, 'ptbs_condition', array( 'name' => $cond_name ) );
                $term_id = $item_id;
            } else {
                $term = wp_insert_term( $cond_name, 'ptbs_condition' );
                $term_id = is_array( $term ) ? $term['term_id'] : 0;
            }

            if ( $term_id > 0 ) {
                update_term_meta( $term_id, '_ptbs_image_id', $image_id );
                update_term_meta( $term_id, '_ptbs_status', $status );
                $message = __( 'Condition saved successfully.', 'pathology-booking-system' );
                $action  = 'list';
            }
        }
    }

    // 4. Save Test Form
    if ( isset( $_POST['ptbs_action_type'] ) && 'save_test' === $_POST['ptbs_action_type'] ) {
        $test_title = sanitize_text_field( wp_unslash( $_POST['test_name'] ?? '' ) );
        $price      = floatval( $_POST['price'] ?? 0 );
        $main_name  = sanitize_text_field( wp_unslash( $_POST['main_test_name'] ?? '' ) );
        $code       = sanitize_text_field( wp_unslash( $_POST['test_code'] ?? '' ) );
        $cutoff     = sanitize_text_field( wp_unslash( $_POST['cutoff_time'] ?? '' ) );
        $method     = sanitize_text_field( wp_unslash( $_POST['method'] ?? '' ) );
        $specimen   = sanitize_text_field( wp_unslash( $_POST['specimen'] ?? '' ) );
        $delivery   = sanitize_text_field( wp_unslash( $_POST['report_delivery'] ?? '' ) );
        $cat_id     = absint( $_POST['category_id'] ?? 0 );
        $subcat_id  = absint( $_POST['subcategory_id'] ?? 0 );
        $cond_ids   = isset( $_POST['condition_ids'] ) && is_array( $_POST['condition_ids'] ) ? array_map( 'absint', $_POST['condition_ids'] ) : array();

        $status     = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );

        $post_data = array(
            'post_title'  => $test_title,
            'post_type'   => 'ptbs_test',
            'post_status' => 'publish',
        );

        if ( $item_id > 0 ) {
            $post_data['ID'] = $item_id;
            $post_id = wp_update_post( $post_data );
        } else {
            $post_id = wp_insert_post( $post_data );
        }

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            update_post_meta( $post_id, '_ptbs_price', $price );
            update_post_meta( $post_id, '_ptbs_main_test_name', $main_name );
            update_post_meta( $post_id, '_ptbs_code', $code );
            update_post_meta( $post_id, '_ptbs_cutoff_time', $cutoff );
            update_post_meta( $post_id, '_ptbs_method', $method );
            update_post_meta( $post_id, '_ptbs_sample_type', $specimen );
            update_post_meta( $post_id, '_ptbs_tat_hours', $delivery );
            update_post_meta( $post_id, '_ptbs_status', $status );

            if ( $cat_id ) wp_set_post_terms( $post_id, array( $cat_id ), 'ptbs_category' );
            if ( $subcat_id ) wp_set_post_terms( $post_id, array( $subcat_id ), 'ptbs_subcategory' );
            if ( ! empty( $cond_ids ) ) wp_set_post_terms( $post_id, $cond_ids, 'ptbs_condition' );

            $message = __( 'Test details saved successfully.', 'pathology-booking-system' );
            $action  = 'list';
        }
    }

    // 5. Save Package Form
    if ( isset( $_POST['ptbs_action_type'] ) && 'save_package' === $_POST['ptbs_action_type'] ) {
        $pkg_title    = sanitize_text_field( wp_unslash( $_POST['package_name'] ?? '' ) );
        $price        = floatval( $_POST['price'] ?? 0 );
        $mrp          = floatval( $_POST['mrp'] ?? 0 );
        $code         = sanitize_text_field( wp_unslash( $_POST['test_code'] ?? '' ) );
        $params_count = absint( $_POST['parameters'] ?? 0 );
        $meta_title   = sanitize_text_field( wp_unslash( $_POST['meta_title'] ?? '' ) );
        $meta_kw      = sanitize_text_field( wp_unslash( $_POST['meta_keywords'] ?? '' ) );
        $meta_desc    = sanitize_textarea_field( wp_unslash( $_POST['meta_description'] ?? '' ) );
        $desc         = sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) );
        $faq          = sanitize_textarea_field( wp_unslash( $_POST['faq'] ?? '' ) );
        $status       = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );
        $image_id     = absint( $_POST['package_image_id'] ?? 0 );
        $linked_tests = isset( $_POST['linked_test_ids'] ) && is_array( $_POST['linked_test_ids'] ) ? array_values( array_filter( array_map( 'absint', $_POST['linked_test_ids'] ) ) ) : array();

        $post_data = array(
            'post_title'   => $pkg_title,
            'post_content' => $desc,
            'post_type'    => 'ptbs_package',
            'post_status'  => 'publish',
        );

        if ( $item_id > 0 ) {
            $post_data['ID'] = $item_id;
            $post_id = wp_update_post( $post_data );
        } else {
            $post_id = wp_insert_post( $post_data );
        }

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            update_post_meta( $post_id, '_ptbs_price', $price );
            update_post_meta( $post_id, '_ptbs_mrp', $mrp );
            update_post_meta( $post_id, '_ptbs_code', $code );
            update_post_meta( $post_id, '_ptbs_parameters_count', $params_count );
            update_post_meta( $post_id, '_ptbs_meta_title', $meta_title );
            update_post_meta( $post_id, '_ptbs_meta_keywords', $meta_kw );
            update_post_meta( $post_id, '_ptbs_meta_description', $meta_desc );
            update_post_meta( $post_id, '_ptbs_faqs', $faq );
            update_post_meta( $post_id, '_ptbs_status', $status );
            update_post_meta( $post_id, '_ptbs_linked_test_ids', $linked_tests );

            $cond_ids = isset( $_POST['condition_ids'] ) && is_array( $_POST['condition_ids'] ) ? array_map( 'absint', $_POST['condition_ids'] ) : array();
            if ( ! empty( $cond_ids ) ) {
                wp_set_post_terms( $post_id, $cond_ids, 'ptbs_condition' );
            }

            if ( $image_id > 0 ) {
                set_post_thumbnail( $post_id, $image_id );
            }

            $message = __( 'Package details saved successfully.', 'pathology-booking-system' );
            $action  = 'list';
        }
    }
}
?>

<div class="ptbs-diagnostic-wrap" style="background:#fff; padding:24px; border-radius:12px; margin-top:20px; box-shadow:0 4px 15px rgba(0,0,0,0.05); font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen,Ubuntu,Cantarell,sans-serif;">

    <?php if ( ! empty( $message ) ) : ?>
        <div class="notice notice-success is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
    <?php endif; ?>

    <!-- Title Bar -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 style="font-size:26px; font-weight:700; color:#1e293b; margin:0;">
            <?php esc_html_e( 'Diagnostic Management', 'pathology-booking-system' ); ?>
        </h1>
        <?php if ( 'list' === $action ) : ?>
            <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'ptbs-diagnostic-management', 'tab' => $current_tab, 'action' => 'add' ), admin_url( 'admin.php' ) ) ); ?>" class="button button-primary" style="background:#00a896; border-color:#00a896; font-size:14px; padding:6px 20px; border-radius:6px; font-weight:600;">
                + <?php
                    if ( 'category' === $current_tab ) esc_html_e( 'Add New Category', 'pathology-booking-system' );
                    elseif ( 'subcategory' === $current_tab ) esc_html_e( 'Add New Sub Category', 'pathology-booking-system' );
                    elseif ( 'condition' === $current_tab ) esc_html_e( 'Add New Condition', 'pathology-booking-system' );
                    elseif ( 'test' === $current_tab ) esc_html_e( 'Add New Test', 'pathology-booking-system' );
                    elseif ( 'package' === $current_tab ) esc_html_e( 'Add New Package', 'pathology-booking-system' );
                ?>
            </a>
        <?php endif; ?>
    </div>

    <!-- Navigation Tabs -->
    <div class="ptbs-tabs" style="display:flex; gap:16px; border-bottom:2px solid #e2e8f0; margin-bottom:24px;">
        <?php
        $tabs = array(
            'category'    => __( 'Category Management', 'pathology-booking-system' ),
            'subcategory' => __( 'SubCategory Management', 'pathology-booking-system' ),
            'condition'   => __( 'Condition Management', 'pathology-booking-system' ),
            'test'        => __( 'Test Management', 'pathology-booking-system' ),
            'package'     => __( 'Package Management', 'pathology-booking-system' ),
        );
        foreach ( $tabs as $tab_key => $tab_label ) :
            $active = ( $current_tab === $tab_key ) ? 'border-bottom:3px solid #00a896; color:#00a896; font-weight:700;' : 'color:#64748b; font-weight:500;';
        ?>
            <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'ptbs-diagnostic-management', 'tab' => $tab_key, 'action' => 'list' ), admin_url( 'admin.php' ) ) ); ?>" style="padding:10px 4px; text-decoration:none; font-size:15px; <?php echo esc_attr( $active ); ?>">
                <?php echo esc_html( $tab_label ); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Screen Content Routing -->
    <?php if ( 'add' === $action || 'edit' === $action ) : ?>
        
        <!-- BACK BUTTON & HEADING -->
        <div style="margin-bottom:20px;">
            <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'ptbs-diagnostic-management', 'tab' => $current_tab, 'action' => 'list' ), admin_url( 'admin.php' ) ) ); ?>" style="text-decoration:none; color:#1e293b; font-size:22px; font-weight:700;">
                ‹ <?php
                    if ( 'category' === $current_tab ) echo ( 'edit' === $action ? __( 'Edit Category Details', 'pathology-booking-system' ) : __( 'Add New Category Details', 'pathology-booking-system' ) );
                    elseif ( 'subcategory' === $current_tab ) echo ( 'edit' === $action ? __( 'Edit Sub Category Details', 'pathology-booking-system' ) : __( 'Add New Sub Category Details', 'pathology-booking-system' ) );
                    elseif ( 'condition' === $current_tab ) echo ( 'edit' === $action ? __( 'Edit Condition Details', 'pathology-booking-system' ) : __( 'Add New Condition Details', 'pathology-booking-system' ) );
                    elseif ( 'test' === $current_tab ) echo ( 'edit' === $action ? __( 'Edit Test Details', 'pathology-booking-system' ) : __( 'Add New Test Details', 'pathology-booking-system' ) );
                    elseif ( 'package' === $current_tab ) echo ( 'edit' === $action ? __( 'Edit Package Details', 'pathology-booking-system' ) : __( 'Add New Package Details', 'pathology-booking-system' ) );
                ?>
            </a>
        </div>

        <form method="post" action="" style="max-width:900px;">
            <?php wp_nonce_field( 'ptbs_diag_action', 'ptbs_diag_nonce' ); ?>

            <?php
            if ( 'category' === $current_tab ) {
                include PTBS_DIR_PATH . 'admin/views/category-form.php';
            } elseif ( 'subcategory' === $current_tab ) {
                include PTBS_DIR_PATH . 'admin/views/subcategory-form.php';
            } elseif ( 'condition' === $current_tab ) {
                include PTBS_DIR_PATH . 'admin/views/condition-form.php';
            } elseif ( 'test' === $current_tab ) {
                include PTBS_DIR_PATH . 'admin/views/test-form.php';
            } elseif ( 'package' === $current_tab ) {
                include PTBS_DIR_PATH . 'admin/views/package-form.php';
            }
            ?>

            <!-- FORM BUTTONS -->
            <div style="margin-top:30px; display:flex; gap:16px; border-top:1px solid #e2e8f0; padding-top:20px;">
                <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'ptbs-diagnostic-management', 'tab' => $current_tab, 'action' => 'list' ), admin_url( 'admin.php' ) ) ); ?>" class="button" style="background:#e2e8f0; border:none; color:#475569; font-weight:600; padding:8px 30px; border-radius:6px;">
                    <?php esc_html_e( 'CANCEL', 'pathology-booking-system' ); ?>
                </a>
                <button type="submit" class="button button-primary" style="background:#0b4f8c; border-color:#0b4f8c; font-weight:600; padding:8px 30px; border-radius:6px;">
                    <?php esc_html_e( 'SAVE & PUBLISH', 'pathology-booking-system' ); ?>
                </button>
            </div>
        </form>

    <?php else : ?>

        <!-- DATATABLE VIEW -->
        <table class="wp-list-table widefat fixed striped table-view-list" style="border-radius:8px; overflow:hidden; border:1px solid #e2e8f0;">
            <thead>
                <tr style="background:#00a896; color:#fff;">
                    <th style="color:#fff; font-weight:700; width:60px; text-align:center; padding:12px;">S. No</th>
                    <th style="color:#fff; font-weight:700; padding:12px;">Title</th>
                    <th style="color:#fff; font-weight:700; padding:12px; width:120px; text-align:center;">Status</th>
                    <th style="color:#fff; font-weight:700; padding:12px; width:100px; text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ( 'category' === $current_tab ) {
                    $terms = get_terms( array( 'taxonomy' => 'ptbs_category', 'hide_empty' => false ) );
                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        $i = 1;
                        foreach ( $terms as $term ) {
                            $status = get_term_meta( $term->term_id, '_ptbs_status', true ) ?: 'Active';
                            $edit_url = add_query_arg( array( 'page' => 'ptbs-diagnostic-management', 'tab' => 'category', 'action' => 'edit', 'id' => $term->term_id ), admin_url( 'admin.php' ) );
                            echo '<tr>';
                            echo '<td style="text-align:center;">' . esc_html( $i++ ) . '</td>';
                            echo '<td style="font-weight:600; color:#1e293b;">' . esc_html( $term->name ) . '</td>';
                            echo '<td style="text-align:center;"><span style="color:#16a34a; font-weight:700;">' . esc_html( $status ) . '</span></td>';
                            echo '<td style="text-align:center;"><a href="' . esc_url( $edit_url ) . '" style="text-decoration:none; font-size:18px; font-weight:bold; color:#64748b;">⋮ Edit</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" style="text-align:center; padding:20px;">' . esc_html__( 'No Categories found.', 'pathology-booking-system' ) . '</td></tr>';
                    }
                } elseif ( 'subcategory' === $current_tab ) {
                    $terms = get_terms( array( 'taxonomy' => 'ptbs_subcategory', 'hide_empty' => false ) );
                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        $i = 1;
                        foreach ( $terms as $term ) {
                            $status = get_term_meta( $term->term_id, '_ptbs_status', true ) ?: 'Active';
                            $edit_url = add_query_arg( array( 'page' => 'ptbs-diagnostic-management', 'tab' => 'subcategory', 'action' => 'edit', 'id' => $term->term_id ), admin_url( 'admin.php' ) );
                            echo '<tr>';
                            echo '<td style="text-align:center;">' . esc_html( $i++ ) . '</td>';
                            echo '<td style="font-weight:600; color:#1e293b;">' . esc_html( $term->name ) . '</td>';
                            echo '<td style="text-align:center;"><span style="color:#16a34a; font-weight:700;">' . esc_html( $status ) . '</span></td>';
                            echo '<td style="text-align:center;"><a href="' . esc_url( $edit_url ) . '" style="text-decoration:none; font-size:18px; font-weight:bold; color:#64748b;">⋮ Edit</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" style="text-align:center; padding:20px;">' . esc_html__( 'No Sub Categories found.', 'pathology-booking-system' ) . '</td></tr>';
                    }
                } elseif ( 'condition' === $current_tab ) {
                    $terms = get_terms( array( 'taxonomy' => 'ptbs_condition', 'hide_empty' => false ) );
                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        $i = 1;
                        foreach ( $terms as $term ) {
                            $status = get_term_meta( $term->term_id, '_ptbs_status', true ) ?: 'Active';
                            $edit_url = add_query_arg( array( 'page' => 'ptbs-diagnostic-management', 'tab' => 'condition', 'action' => 'edit', 'id' => $term->term_id ), admin_url( 'admin.php' ) );
                            echo '<tr>';
                            echo '<td style="text-align:center;">' . esc_html( $i++ ) . '</td>';
                            echo '<td style="font-weight:600; color:#1e293b;">' . esc_html( $term->name ) . '</td>';
                            echo '<td style="text-align:center;"><span style="color:#16a34a; font-weight:700;">' . esc_html( $status ) . '</span></td>';
                            echo '<td style="text-align:center;"><a href="' . esc_url( $edit_url ) . '" style="text-decoration:none; font-size:18px; font-weight:bold; color:#64748b;">⋮ Edit</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" style="text-align:center; padding:20px;">' . esc_html__( 'No Conditions found.', 'pathology-booking-system' ) . '</td></tr>';
                    }
                } elseif ( 'test' === $current_tab ) {
                    $posts = get_posts( array( 'post_type' => 'ptbs_test', 'posts_per_page' => -1 ) );
                    if ( ! empty( $posts ) ) {
                        $i = 1;
                        foreach ( $posts as $p ) {
                            $status = get_post_meta( $p->ID, '_ptbs_status', true ) ?: 'Active';
                            $status_color = ( 'Inactive' === $status ) ? '#dc2626' : '#16a34a';
                            $edit_url = add_query_arg( array( 'page' => 'ptbs-diagnostic-management', 'tab' => 'test', 'action' => 'edit', 'id' => $p->ID ), admin_url( 'admin.php' ) );
                            echo '<tr>';
                            echo '<td style="text-align:center;">' . esc_html( $i++ ) . '</td>';
                            echo '<td style="font-weight:600; color:#1e293b;">' . esc_html( $p->post_title ) . '</td>';
                            echo '<td style="text-align:center;"><span style="color:' . esc_attr( $status_color ) . '; font-weight:700;">' . esc_html( $status ) . '</span></td>';
                            echo '<td style="text-align:center;"><a href="' . esc_url( $edit_url ) . '" style="text-decoration:none; font-size:18px; font-weight:bold; color:#64748b;">⋮ Edit</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" style="text-align:center; padding:20px;">' . esc_html__( 'No Pathology Tests found.', 'pathology-booking-system' ) . '</td></tr>';
                    }
                } elseif ( 'package' === $current_tab ) {
                    $posts = get_posts( array( 'post_type' => 'ptbs_package', 'posts_per_page' => -1 ) );
                    if ( ! empty( $posts ) ) {
                        $i = 1;
                        foreach ( $posts as $p ) {
                            $status = get_post_meta( $p->ID, '_ptbs_status', true ) ?: 'Active';
                            $status_color = ( 'Inactive' === $status ) ? '#dc2626' : '#16a34a';
                            $edit_url = add_query_arg( array( 'page' => 'ptbs-diagnostic-management', 'tab' => 'package', 'action' => 'edit', 'id' => $p->ID ), admin_url( 'admin.php' ) );
                            echo '<tr>';
                            echo '<td style="text-align:center;">' . esc_html( $i++ ) . '</td>';
                            echo '<td style="font-weight:600; color:#1e293b;">' . esc_html( $p->post_title ) . '</td>';
                            echo '<td style="text-align:center;"><span style="color:' . esc_attr( $status_color ) . '; font-weight:700;">' . esc_html( $status ) . '</span></td>';
                            echo '<td style="text-align:center;"><a href="' . esc_url( $edit_url ) . '" style="text-decoration:none; font-size:18px; font-weight:bold; color:#64748b;">⋮ Edit</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" style="text-align:center; padding:20px;">' . esc_html__( 'No Health Packages found.', 'pathology-booking-system' ) . '</td></tr>';
                    }
                }
                ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>

<script>
jQuery(document).ready(function($) {
    // Image Media Uploader
    $('#ptbs_cat_img_btn').on('click', function(e) {
        e.preventDefault();
        var frame = wp.media({
            title: 'Select Image',
            button: { text: 'Use Image' },
            multiple: false
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#ptbs_cat_img_id').val(attachment.id);
            $('#ptbs_cat_img_preview').html('<img src="' + attachment.url + '" style="max-width:100px; border-radius:6px;">');
        });
        frame.open();
    });

    // Initialize Select2 Tagging for Package Tests Involved
    if ($('#ptbs_package_tests_select2').length) {
        $('#ptbs_package_tests_select2').select2({
            placeholder: 'Search & select tests...',
            allowClear: true,
            width: '100%'
        });
    }

    // Initialize Select2 for Center Locations Multi-Select
    if ($('.ptbs-select2-multi').length) {
        $('.ptbs-select2-multi').select2({
            placeholder: 'Search & select center locations...',
            allowClear: true,
            width: '100%'
        });
    }
});
</script>
