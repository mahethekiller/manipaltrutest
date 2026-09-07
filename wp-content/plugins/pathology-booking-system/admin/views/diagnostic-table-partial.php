<?php
/**
 * Diagnostic Management Dynamic Datatable Partial View
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$tab = isset( $tab ) ? sanitize_key( $tab ) : 'category';
?>

<div class="ptbs-toolbar">
    <div class="ptbs-search-box">
        <span class="ptbs-search-icon">🔍</span>
        <input type="text" id="ptbs_table_search" placeholder="Search catalog items...">
    </div>
</div>

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
            if ( 'category' === $tab ) {
                $terms = get_terms( array( 'taxonomy' => 'ptbs_category', 'hide_empty' => false ) );
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    $i = 1;
                    foreach ( $terms as $term ) {
                        $status    = get_term_meta( $term->term_id, '_ptbs_status', true ) ?: 'Active';
                        $is_active = ( 'Inactive' !== $status );
                        ?>
                        <tr>
                            <td style="text-align:center; font-weight:700; color:#94a3b8;"><?php echo esc_html( $i++ ); ?></td>
                            <td style="font-weight:700; color:#0f172a;"><?php echo esc_html( $term->name ); ?></td>
                            <td style="text-align:center;">
                                <label class="ptbs-switch">
                                    <input type="checkbox" class="ptbs-status-toggle-input" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-type="category" <?php checked( $is_active ); ?>>
                                    <span class="ptbs-slider"></span>
                                </label>
                            </td>
                            <td style="text-align:center;">
                                <button class="ptbs-action-btn ptbs-edit-item-btn" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-tab="category">✏️ Edit</button>
                                <button class="ptbs-action-btn delete ptbs-delete-item-btn" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-type="category">🗑️</button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="4" style="text-align:center; padding:30px; color:#94a3b8;">No Categories found.</td></tr>';
                }
            } elseif ( 'subcategory' === $tab ) {
                $terms = get_terms( array( 'taxonomy' => 'ptbs_subcategory', 'hide_empty' => false ) );
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    $i = 1;
                    foreach ( $terms as $term ) {
                        $status    = get_term_meta( $term->term_id, '_ptbs_status', true ) ?: 'Active';
                        $is_active = ( 'Inactive' !== $status );
                        ?>
                        <tr>
                            <td style="text-align:center; font-weight:700; color:#94a3b8;"><?php echo esc_html( $i++ ); ?></td>
                            <td style="font-weight:700; color:#0f172a;"><?php echo esc_html( $term->name ); ?></td>
                            <td style="text-align:center;">
                                <label class="ptbs-switch">
                                    <input type="checkbox" class="ptbs-status-toggle-input" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-type="subcategory" <?php checked( $is_active ); ?>>
                                    <span class="ptbs-slider"></span>
                                </label>
                            </td>
                            <td style="text-align:center;">
                                <button class="ptbs-action-btn ptbs-edit-item-btn" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-tab="subcategory">✏️ Edit</button>
                                <button class="ptbs-action-btn delete ptbs-delete-item-btn" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-type="subcategory">🗑️</button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="4" style="text-align:center; padding:30px; color:#94a3b8;">No Sub Categories found.</td></tr>';
                }
            } elseif ( 'condition' === $tab ) {
                $terms = get_terms( array( 'taxonomy' => 'ptbs_condition', 'hide_empty' => false ) );
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    $i = 1;
                    foreach ( $terms as $term ) {
                        $status    = get_term_meta( $term->term_id, '_ptbs_status', true ) ?: 'Active';
                        $is_active = ( 'Inactive' !== $status );
                        ?>
                        <tr>
                            <td style="text-align:center; font-weight:700; color:#94a3b8;"><?php echo esc_html( $i++ ); ?></td>
                            <td style="font-weight:700; color:#0f172a;"><?php echo esc_html( $term->name ); ?></td>
                            <td style="text-align:center;">
                                <label class="ptbs-switch">
                                    <input type="checkbox" class="ptbs-status-toggle-input" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-type="condition" <?php checked( $is_active ); ?>>
                                    <span class="ptbs-slider"></span>
                                </label>
                            </td>
                            <td style="text-align:center;">
                                <button class="ptbs-action-btn ptbs-edit-item-btn" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-tab="condition">✏️ Edit</button>
                                <button class="ptbs-action-btn delete ptbs-delete-item-btn" data-id="<?php echo esc_attr( $term->term_id ); ?>" data-type="condition">🗑️</button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="4" style="text-align:center; padding:30px; color:#94a3b8;">No Conditions found.</td></tr>';
                }
            } elseif ( 'test' === $tab ) {
                $posts = get_posts( array( 'post_type' => 'ptbs_test', 'posts_per_page' => -1 ) );
                if ( ! empty( $posts ) ) {
                    $i = 1;
                    foreach ( $posts as $p ) {
                        $price     = get_post_meta( $p->ID, '_ptbs_price', true );
                        $status    = get_post_meta( $p->ID, '_ptbs_status', true ) ?: 'Active';
                        $is_active = ( 'Inactive' !== $status );
                        $cats      = wp_get_post_terms( $p->ID, 'ptbs_category', array( 'fields' => 'names' ) );
                        $cat_list  = ! empty( $cats ) && ! is_wp_error( $cats ) ? implode( ', ', $cats ) : '—';
                        ?>
                        <tr>
                            <td style="text-align:center; font-weight:700; color:#94a3b8;"><?php echo esc_html( $i++ ); ?></td>
                            <td style="font-weight:700; color:#0f172a;"><?php echo esc_html( $p->post_title ); ?></td>
                            <td style="font-weight:700; color:#0284c7;">₹<?php echo esc_html( number_format( floatval( $price ), 2 ) ); ?></td>
                            <td style="color:#64748b; font-size:13px;"><?php echo esc_html( $cat_list ); ?></td>
                            <td style="text-align:center;">
                                <label class="ptbs-switch">
                                    <input type="checkbox" class="ptbs-status-toggle-input" data-id="<?php echo esc_attr( $p->ID ); ?>" data-type="test" <?php checked( $is_active ); ?>>
                                    <span class="ptbs-slider"></span>
                                </label>
                            </td>
                            <td style="text-align:center;">
                                <button class="ptbs-action-btn ptbs-edit-item-btn" data-id="<?php echo esc_attr( $p->ID ); ?>" data-tab="test">✏️ Edit</button>
                                <button class="ptbs-action-btn delete ptbs-delete-item-btn" data-id="<?php echo esc_attr( $p->ID ); ?>" data-type="test">🗑️</button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="6" style="text-align:center; padding:30px; color:#94a3b8;">No Pathology Tests found.</td></tr>';
                }
            } elseif ( 'package' === $tab ) {
                $posts = get_posts( array( 'post_type' => 'ptbs_package', 'posts_per_page' => -1 ) );
                if ( ! empty( $posts ) ) {
                    $i = 1;
                    foreach ( $posts as $p ) {
                        $price     = get_post_meta( $p->ID, '_ptbs_price', true );
                        $status    = get_post_meta( $p->ID, '_ptbs_status', true ) ?: 'Active';
                        $is_active = ( 'Inactive' !== $status );
                        $cats      = wp_get_post_terms( $p->ID, 'ptbs_category', array( 'fields' => 'names' ) );
                        $cat_list  = ! empty( $cats ) && ! is_wp_error( $cats ) ? implode( ', ', $cats ) : '—';
                        ?>
                        <tr>
                            <td style="text-align:center; font-weight:700; color:#94a3b8;"><?php echo esc_html( $i++ ); ?></td>
                            <td style="font-weight:700; color:#0f172a;"><?php echo esc_html( $p->post_title ); ?></td>
                            <td style="font-weight:700; color:#0d9488;">₹<?php echo esc_html( number_format( floatval( $price ), 2 ) ); ?></td>
                            <td style="color:#64748b; font-size:13px;"><?php echo esc_html( $cat_list ); ?></td>
                            <td style="text-align:center;">
                                <label class="ptbs-switch">
                                    <input type="checkbox" class="ptbs-status-toggle-input" data-id="<?php echo esc_attr( $p->ID ); ?>" data-type="package" <?php checked( $is_active ); ?>>
                                    <span class="ptbs-slider"></span>
                                </label>
                            </td>
                            <td style="text-align:center;">
                                <button class="ptbs-action-btn ptbs-edit-item-btn" data-id="<?php echo esc_attr( $p->ID ); ?>" data-tab="package">✏️ Edit</button>
                                <button class="ptbs-action-btn delete ptbs-delete-item-btn" data-id="<?php echo esc_attr( $p->ID ); ?>" data-type="package">🗑️</button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="6" style="text-align:center; padding:30px; color:#94a3b8;">No Health Packages found.</td></tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>
