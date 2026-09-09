<?php
/**
 * Diagnostic Management Dynamic Datatable Partial View
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$tab      = isset( $tab ) ? sanitize_key( $tab ) : 'category';
$paged    = isset( $paged ) ? max( 1, absint( $paged ) ) : 1;
$per_page = isset( $per_page ) ? max( 5, absint( $per_page ) ) : 15;
$search   = isset( $search ) ? sanitize_text_field( $search ) : '';

$total_items = 0;
$total_pages = 1;
?>

<?php if ( 'sync' !== $tab ) : ?>
<div class="ptbs-toolbar">
    <div class="ptbs-search-box">
        <span class="ptbs-search-icon">🔍</span>
        <input type="text" id="ptbs_table_search" value="<?php echo esc_attr( $search ); ?>" placeholder="Search catalog items...">
    </div>
    <div style="display:flex; align-items:center; gap:10px;">
        <label style="font-size:13px; font-weight:600; color:#64748b;">Per Page:</label>
        <select id="ptbs_per_page_select" style="padding:6px 12px; border-radius:6px; border:1px solid #cbd5e1; font-size:13px; font-weight:600;">
            <option value="10" <?php selected( $per_page, 10 ); ?>>10</option>
            <option value="15" <?php selected( $per_page, 15 ); ?>>15</option>
            <option value="25" <?php selected( $per_page, 25 ); ?>>25</option>
            <option value="50" <?php selected( $per_page, 50 ); ?>>50</option>
            <option value="100" <?php selected( $per_page, 100 ); ?>>100</option>
        </select>
    </div>
</div>
<?php endif; ?>

<div class="ptbs-table-card">
    <table class="ptbs-table">
        <thead>
            <tr>
                <th style="width:60px; text-align:center;">S. No</th>
                <th>Title</th>
                <?php if ( 'test' === $tab || 'package' === $tab ) : ?>
                    <th>Price</th>
                    <th>Categories</th>
                <?php endif; ?>
                <th style="width:120px; text-align:center;">Status</th>
                <th style="width:140px; text-align:center;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( in_array( $tab, array( 'category', 'subcategory', 'condition' ), true ) ) {
                $taxonomy = 'ptbs_' . $tab;
                $args = array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                    'search'     => $search,
                );
                $all_terms   = get_terms( $args );
                $total_items = ! is_wp_error( $all_terms ) ? count( $all_terms ) : 0;
                $total_pages = ceil( $total_items / $per_page );

                $offset_args = array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                    'search'     => $search,
                    'number'     => $per_page,
                    'offset'     => ( $paged - 1 ) * $per_page,
                );
                $terms = get_terms( $offset_args );

                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    $i = ( ( $paged - 1 ) * $per_page ) + 1;
                    foreach ( $terms as $term ) {
                        $status    = get_term_meta( $term->term_id, '_ptbs_status', true ) ?: 'Active';
                        $is_active = ( 'Inactive' !== $status );
                        ?>
                        <tr>
                            <td style="text-align:center; font-weight:700; color:#94a3b8;"><?php echo esc_html( $i++ ); ?></td>
                            <td style="font-weight:700; color:#0f172a;"><?php echo esc_html( $term->name ); ?></td>
                            <td style="text-align:center;">
                                <label class="ptbs-switch">
                                    <input type="checkbox" class="ptbs-status-toggle-input" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-type="<?php echo esc_attr( $tab ); ?>" <?php checked( $is_active ); ?>>
                                    <span class="ptbs-slider"></span>
                                </label>
                            </td>
                            <td style="text-align:center;">
                                <button class="ptbs-action-btn ptbs-edit-item-btn" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-tab="<?php echo esc_attr( $tab ); ?>">✏️ Edit</button>
                                <button class="ptbs-action-btn delete ptbs-delete-item-btn" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-type="<?php echo esc_attr( $tab ); ?>">🗑️</button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="4" style="text-align:center; padding:30px; color:#94a3b8;">No items found.</td></tr>';
                }
            } elseif ( 'test' === $tab || 'package' === $tab || 'center_location' === $tab ) {
                $cpt = 'ptbs_' . $tab;
                $query_args = array(
                    'post_type'      => $cpt,
                    'post_status'    => 'publish',
                    'posts_per_page' => $per_page,
                    'paged'          => $paged,
                    's'              => $search,
                );
                $query       = new WP_Query( $query_args );
                $total_items = $query->found_posts;
                $total_pages = $query->max_num_pages ?: 1;

                if ( $query->have_posts() ) {
                    $i = ( ( $paged - 1 ) * $per_page ) + 1;
                    while ( $query->have_posts() ) {
                        $query->the_post();
                        $pid       = get_the_ID();
                        $price     = get_post_meta( $pid, '_ptbs_price', true );
                        $status    = get_post_meta( $pid, '_ptbs_status', true ) ?: 'Active';
                        $is_active = ( 'Inactive' !== $status );
                        $permalink = get_permalink( $pid );
                        $cats      = wp_get_post_terms( $pid, 'ptbs_category', array( 'fields' => 'names' ) );
                        $cat_list  = ! empty( $cats ) && ! is_wp_error( $cats ) ? implode( ', ', $cats ) : '—';
                        ?>
                        <tr>
                            <td style="text-align:center; font-weight:700; color:#94a3b8;"><?php echo esc_html( $i++ ); ?></td>
                            <td style="font-weight:700; color:#0f172a;">
                                <?php the_title(); ?>
                                <?php if ( 'center_location' === $tab ) : 
                                    $st_names     = wp_get_post_terms( $pid, 'ptbs_state', array( 'fields' => 'names' ) );
                                    $ct_names     = wp_get_post_terms( $pid, 'ptbs_city', array( 'fields' => 'names' ) );
                                    $st_str       = ( ! empty( $st_names ) && ! is_wp_error( $st_names ) ) ? implode( ', ', $st_names ) : '';
                                    $ct_str       = ( ! empty( $ct_names ) && ! is_wp_error( $ct_names ) ) ? implode( ', ', $ct_names ) : '';
                                    $location_str = implode( ', ', array_filter( array( $ct_str, $st_str ) ) );
                                ?>
                                    <div style="font-size:12px; color:#64748b; margin-top:2px;">
                                        📍 <?php echo esc_html( $location_str ?: 'Location Not Set' ); ?>
                                    </div>
                                    <div style="font-size:12px; margin-top:4px;">
                                        <a href="<?php echo esc_url( $permalink ); ?>" target="_blank" style="color:#0284c7; text-decoration:none; font-weight:600;">🔗 View Page: <?php echo esc_url( $permalink ); ?></a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <?php if ( 'center_location' !== $tab ) : ?>
                                <td style="font-weight:700; color:<?php echo 'test' === $tab ? '#0284c7' : '#0d9488'; ?>;">₹<?php echo esc_html( number_format( floatval( $price ), 2 ) ); ?></td>
                                <td style="color:#64748b; font-size:13px;"><?php echo esc_html( $cat_list ); ?></td>
                            <?php endif; ?>
                            <td style="text-align:center;">
                                <label class="ptbs-switch">
                                    <input type="checkbox" class="ptbs-status-toggle-input" data-id="<?php echo esc_attr( $pid ); ?>" data-type="<?php echo esc_attr( $tab ); ?>" <?php checked( $is_active ); ?>>
                                    <span class="ptbs-slider"></span>
                                </label>
                            </td>
                            <td style="text-align:center;">
                                <button class="ptbs-action-btn ptbs-edit-item-btn" data-id="<?php echo esc_attr( $pid ); ?>" data-tab="<?php echo esc_attr( $tab ); ?>">✏️ Edit</button>
                                <button class="ptbs-action-btn delete ptbs-delete-item-btn" data-id="<?php echo esc_attr( $pid ); ?>" data-type="<?php echo esc_attr( $tab ); ?>">🗑️</button>
                            </td>
                        </tr>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<tr><td colspan="6" style="text-align:center; padding:30px; color:#94a3b8;">No items found.</td></tr>';
                }
            } elseif ( 'sync' === $tab ) {
                $c_tests   = wp_count_posts( 'ptbs_test' )->publish + wp_count_posts( 'ptbs_test' )->draft;
                $c_pkgs    = wp_count_posts( 'ptbs_package' )->publish + wp_count_posts( 'ptbs_package' )->draft;
                $c_subs    = wp_count_terms( array( 'taxonomy' => 'ptbs_subcategory', 'hide_empty' => false ) );
                $c_conds   = wp_count_terms( array( 'taxonomy' => 'ptbs_condition', 'hide_empty' => false ) );
                $json_path = PTBS_DIR_PATH . 'data.json';
                $json_size = file_exists( $json_path ) ? round( filesize( $json_path ) / 1024, 1 ) . ' KB' : 'Missing';
                ?>
                <tr>
                    <td colspan="6" style="padding:24px; background:#f8fafc;">
                        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px;">
                            <h3 style="font-size:18px; font-weight:700; color:#0f172a; margin-top:0; margin-bottom:8px;">
                                🔄 Diagnostics Catalog Synchronization Status
                            </h3>
                            <p style="color:#64748b; font-size:14px; margin-bottom:20px;">
                                Synchronize all 729 Lab Tests, 35 Health Packages, 30 Subcategories, and 25 Conditions directly from the indexed catalog file into the WordPress database.
                            </p>

                            <!-- Stat Cards Grid -->
                            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:16px; margin-bottom:24px;">
                                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px; text-align:center;">
                                    <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">Tests</div>
                                    <div style="font-size:24px; font-weight:800; color:#0284c7; margin:4px 0;"><?php echo esc_html( $c_tests ); ?></div>
                                </div>
                                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px; text-align:center;">
                                    <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">Packages</div>
                                    <div style="font-size:24px; font-weight:800; color:#0d9488; margin:4px 0;"><?php echo esc_html( $c_pkgs ); ?></div>
                                </div>
                                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px; text-align:center;">
                                    <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">Subcategories</div>
                                    <div style="font-size:24px; font-weight:800; color:#6366f1; margin:4px 0;"><?php echo esc_html( $c_subs ); ?></div>
                                </div>
                                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px; text-align:center;">
                                    <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">Conditions</div>
                                    <div style="font-size:24px; font-weight:800; color:#f59e0b; margin:4px 0;"><?php echo esc_html( $c_conds ); ?></div>
                                </div>
                            </div>

                            <!-- Sync Form -->
                            <form id="ptbs_sync_catalog_form" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:20px;">
                                <div style="margin-bottom:16px;">
                                    <label style="display:flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:#334155; cursor:pointer;">
                                        <input type="checkbox" name="clean_existing" value="1" checked style="width:18px; height:18px;">
                                        <span>Fresh Sync (Recommended: Cleans existing tests/packages and rebuilds fresh relationships)</span>
                                    </label>
                                </div>
                                <button type="submit" id="ptbs_run_sync_btn" class="ptbs-btn-primary" style="background:#0284c7; padding:10px 24px; font-weight:700;">
                                    ⚡ Run High-Performance Catalog Sync Now
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<?php if ( 'sync' !== $tab ) : ?>
<!-- Pagination Footer Bar -->
<div class="ptbs-pagination-bar" style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding:12px 16px; background:#fff; border-radius:8px; border:1px solid #e2e8f0;">
    <div style="font-size:13px; color:#64748b; font-weight:600;">
        Showing <?php echo esc_html( min( ( ( $paged - 1 ) * $per_page ) + 1, $total_items ) ); ?> to <?php echo esc_html( min( $paged * $per_page, $total_items ) ); ?> of <?php echo esc_html( $total_items ); ?> entries
    </div>
    <div class="ptbs-pagination-btns" style="display:flex; gap:6px;">
        <button class="ptbs-page-btn" data-page="<?php echo esc_attr( max( 1, $paged - 1 ) ); ?>" <?php disabled( $paged <= 1 ); ?> style="padding:6px 12px; border-radius:6px; border:1px solid #cbd5e1; background:#fff; cursor:pointer; font-weight:600; font-size:13px;">‹ Prev</button>
        
        <?php
        $start = max( 1, $paged - 2 );
        $end   = min( $total_pages, $paged + 2 );
        for ( $p = $start; $p <= $end; $p++ ) :
            $active = ( $p === $paged ) ? 'background:#0284c7; color:#fff; border-color:#0284c7;' : 'background:#fff; color:#334155; border-color:#cbd5e1;';
        ?>
            <button class="ptbs-page-btn" data-page="<?php echo esc_attr( $p ); ?>" style="padding:6px 12px; border-radius:6px; border:1px solid #cbd5e1; <?php echo esc_attr( $active ); ?> cursor:pointer; font-weight:600; font-size:13px;"><?php echo esc_html( $p ); ?></button>
        <?php endfor; ?>

        <button class="ptbs-page-btn" data-page="<?php echo esc_attr( min( $total_pages, $paged + 1 ) ); ?>" <?php disabled( $paged >= $total_pages ); ?> style="padding:6px 12px; border-radius:6px; border:1px solid #cbd5e1; background:#fff; cursor:pointer; font-weight:600; font-size:13px;">Next ›</button>
    </div>
</div>
<?php endif; ?>
