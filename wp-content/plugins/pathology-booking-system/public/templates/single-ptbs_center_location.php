<?php
/**
 * Template Name: Single Center Location
 * Template Post Type: ptbs_center_location
 */

get_header();

$center_id      = get_the_ID();
$center_title   = get_the_title();
$center_content = get_the_content();
$image_url      = get_the_post_thumbnail_url( $center_id, 'full' );

// Get meta details
$phone     = get_post_meta( $center_id, '_ptbs_phone', true ) ?: '+91 1800-123-4567';
$email     = get_post_meta( $center_id, '_ptbs_email', true ) ?: 'info@manipaltrutest.com';
$address   = get_post_meta( $center_id, '_ptbs_address', true ) ?: '123 Health Care Avenue, Main City';
$hours     = get_post_meta( $center_id, '_ptbs_hours', true ) ?: 'Mon - Sat: 07:00 AM - 08:00 PM';
$cities    = wp_get_post_terms( $center_id, 'ptbs_city', array( 'fields' => 'names' ) );
$city_name = ! empty( $cities ) && ! is_wp_error( $cities ) ? $cities[0] : '';

// Find all tests linked to this center location
$all_tests = get_posts( array(
    'post_type'      => 'ptbs_test',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
) );

$available_tests = array();
foreach ( $all_tests as $t ) {
    $loc_ids = get_post_meta( $t->ID, '_ptbs_center_location_ids', true );
    if ( ! is_array( $loc_ids ) ) {
        $loc_ids = @unserialize( $loc_ids );
    }
    if ( is_array( $loc_ids ) && ( empty( $loc_ids ) || in_array( $center_id, $loc_ids ) ) ) {
        $available_tests[] = $t;
    }
}

// Find all packages linked to this center location
$all_pkgs = get_posts( array(
    'post_type'      => 'ptbs_package',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
) );

$available_pkgs = array();
foreach ( $all_pkgs as $p ) {
    $loc_ids = get_post_meta( $p->ID, '_ptbs_center_location_ids', true );
    if ( ! is_array( $loc_ids ) ) {
        $loc_ids = @unserialize( $loc_ids );
    }
    if ( is_array( $loc_ids ) && ( empty( $loc_ids ) || in_array( $center_id, $loc_ids ) ) ) {
        $available_pkgs[] = $p;
    }
}
?>

<div class="ptbs-single-center-wrapper" style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; color:#1e293b; background:#f8fafc; padding-bottom:60px;">
    
    <!-- Hero Banner -->
    <div style="background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color:#fff; padding:60px 20px; text-align:center;">
        <div style="max-width:1100px; margin:0 auto;">
            <span style="background:rgba(2,132,199,0.2); color:#38bdf8; font-weight:700; font-size:13px; padding:6px 16px; border-radius:20px; text-transform:uppercase; letter-spacing:1px; display:inline-block; margin-bottom:12px;">
                📍 <?php echo esc_html( $city_name ? $city_name . ' Diagnostic Center' : 'Diagnostic Lab Center' ); ?>
            </span>
            <h1 style="font-size:36px; font-weight:800; color:#fff; margin:0 0 16px 0;"><?php echo esc_html( $center_title ); ?></h1>
            <p style="font-size:16px; color:#94a3b8; max-width:700px; margin:0 auto 24px auto;">
                <?php echo esc_html( $address ); ?>
            </p>

            <div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap; font-size:14px; font-weight:600; color:#e2e8f0;">
                <div>📞 <?php echo esc_html( $phone ); ?></div>
                <div>✉️ <?php echo esc_html( $email ); ?></div>
                <div>⏰ <?php echo esc_html( $hours ); ?></div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div style="max-width:1100px; margin:-30px auto 0 auto; padding:0 20px;">
        
        <!-- Info Cards -->
        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:24px; margin-bottom:40px;">
            
            <div style="background:#fff; border-radius:12px; padding:28px; border:1px solid #e2e8f0; box-shadow:0 4px 15px rgba(0,0,0,0.04);">
                <h3 style="font-size:20px; font-weight:700; color:#0f172a; margin-top:0; margin-bottom:16px;">About Center Location</h3>
                <div style="color:#475569; line-height:1.7;">
                    <?php if ( ! empty( $center_content ) ) : ?>
                        <?php echo wp_kses_post( $center_content ); ?>
                    <?php else : ?>
                        <p>Welcome to <strong><?php echo esc_html( $center_title ); ?></strong>. Our state-of-the-art diagnostic center offers fully automated blood testing, health checkups, home sample collection, and high-accuracy pathology reporting.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div style="background:#fff; border-radius:12px; padding:28px; border:1px solid #e2e8f0; box-shadow:0 4px 15px rgba(0,0,0,0.04); display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center;">
                <div style="font-size:42px; margin-bottom:12px;">🏥</div>
                <h4 style="font-size:18px; font-weight:700; color:#0f172a; margin:0 0 8px 0;">Need Sample Collection?</h4>
                <p style="font-size:14px; color:#64748b; margin-bottom:20px;">Book home collection or center visit appointment online instantly.</p>
                <a href="<?php echo esc_url( home_url( '/lab/' ) ); ?>" style="background:#0284c7; color:#fff; text-decoration:none; padding:12px 28px; border-radius:8px; font-weight:700; font-size:15px; display:inline-block;">Book Appointment Now</a>
            </div>

        </div>

        <!-- Available Tests Section -->
        <div style="margin-bottom:40px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h2 style="font-size:24px; font-weight:800; color:#0f172a; margin:0;">🔬 Tests Available at this Center (<?php echo count( $available_tests ); ?>)</h2>
            </div>

            <?php if ( ! empty( $available_tests ) ) : ?>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:20px;">
                    <?php foreach ( array_slice( $available_tests, 0, 12 ) as $test_post ) :
                        $price = get_post_meta( $test_post->ID, '_ptbs_price', true );
                        $code  = get_post_meta( $test_post->ID, '_ptbs_code', true );
                    ?>
                        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:20px; box-shadow:0 4px 12px rgba(0,0,0,0.03); display:flex; flex-direction:column; justify-content:space-between;">
                            <div>
                                <span style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; background:#f1f5f9; padding:3px 8px; border-radius:4px;"><?php echo esc_html( $code ?: 'TEST' ); ?></span>
                                <h3 style="font-size:17px; font-weight:700; color:#0f172a; margin:10px 0 6px 0;"><?php echo esc_html( $test_post->post_title ); ?></h3>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; border-top:1px solid #f1f5f9; padding-top:14px;">
                                <div>
                                    <span style="font-size:20px; font-weight:800; color:#0284c7;">₹<?php echo esc_html( number_format( floatval( $price ), 2 ) ); ?></span>
                                </div>
                                <a href="<?php echo esc_url( get_permalink( $test_post->ID ) ); ?>" style="background:#f1f5f9; color:#0f172a; text-decoration:none; padding:8px 16px; border-radius:6px; font-weight:600; font-size:13px;">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p style="color:#64748b;">No pathology tests currently cataloged for this center.</p>
            <?php endif; ?>
        </div>

        <!-- Available Packages Section -->
        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h2 style="font-size:24px; font-weight:800; color:#0f172a; margin:0;">📦 Health Packages Available (<?php echo count( $available_pkgs ); ?>)</h2>
            </div>

            <?php if ( ! empty( $available_pkgs ) ) : ?>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:20px;">
                    <?php foreach ( array_slice( $available_pkgs, 0, 12 ) as $pkg_post ) :
                        $price = get_post_meta( $pkg_post->ID, '_ptbs_price', true );
                        $mrp   = get_post_meta( $pkg_post->ID, '_ptbs_mrp', true );
                    ?>
                        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:20px; box-shadow:0 4px 12px rgba(0,0,0,0.03); display:flex; flex-direction:column; justify-content:space-between;">
                            <div>
                                <h3 style="font-size:17px; font-weight:700; color:#0f172a; margin:0 0 6px 0;"><?php echo esc_html( $pkg_post->post_title ); ?></h3>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; border-top:1px solid #f1f5f9; padding-top:14px;">
                                <div>
                                    <span style="font-size:20px; font-weight:800; color:#0d9488;">₹<?php echo esc_html( number_format( floatval( $price ), 2 ) ); ?></span>
                                    <?php if ( $mrp > $price ) : ?>
                                        <span style="text-decoration:line-through; color:#94a3b8; font-size:13px; margin-left:6px;">₹<?php echo esc_html( number_format( floatval( $mrp ), 2 ) ); ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo esc_url( get_permalink( $pkg_post->ID ) ); ?>" style="background:#0d9488; color:#fff; text-decoration:none; padding:8px 16px; border-radius:6px; font-weight:600; font-size:13px;">View Package</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<?php
get_footer();
