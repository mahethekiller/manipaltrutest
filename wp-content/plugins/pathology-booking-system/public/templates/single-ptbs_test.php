<?php
/**
 * Single Pathology Test Info Page Template
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$test_id     = get_the_ID();
$code        = get_post_meta( $test_id, '_ptbs_code', true );
$price       = floatval( get_post_meta( $test_id, '_ptbs_price', true ) );
$sample_type = get_post_meta( $test_id, '_ptbs_sample_type', true );
$fasting_req = get_post_meta( $test_id, '_ptbs_fasting_req', true );
$tat_hours   = get_post_meta( $test_id, '_ptbs_tat_hours', true );
$parameters  = get_post_meta( $test_id, '_ptbs_parameters', true );
$cities      = get_the_terms( $test_id, 'ptbs_city' );

$param_list  = ! empty( $parameters ) ? array_filter( array_map( 'trim', explode( "\n", $parameters ) ) ) : array();
?>

<div class="ptbs-single-wrapper" style="background:#f8fafc; padding: 40px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div class="container" style="max-width: 1140px; margin: 0 auto; padding: 0 16px;">
        
        <!-- Breadcrumbs -->
        <div style="font-size:13px; color:#64748b; margin-bottom:16px;">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:#64748b; text-decoration:none;">Home</a> &nbsp;/&nbsp; 
            <span>Pathology Tests</span> &nbsp;/&nbsp; 
            <strong style="color:#0f172a;"><?php the_title(); ?></strong>
        </div>

        <div style="display:grid; grid-template-columns: 1.8fr 1fr; gap:30px; align-items:start;">
            
            <!-- Main Content Area -->
            <div>
                <!-- Header Box -->
                <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:28px; margin-bottom:24px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px;">
                        <?php if ( ! empty( $code ) ) : ?>
                            <span style="background:#eff6ff; color:#1d4ed8; font-weight:700; font-size:12px; padding:4px 10px; border-radius:6px;">TEST CODE: <?php echo esc_html( $code ); ?></span>
                        <?php endif; ?>
                        <span style="background:#f0fdf4; color:#166534; font-weight:700; font-size:12px; padding:4px 10px; border-radius:6px;">ISO / NABL ACCREDITED LAB</span>
                    </div>

                    <h1 style="font-size:28px; font-weight:800; color:#0f172a; margin:0 0 16px 0; line-height:1.3;"><?php the_title(); ?></h1>

                    <!-- Key Metric Chips Bar -->
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; background:#f8fafc; padding:16px; border-radius:12px; border:1px solid #f1f5f9;">
                        <div>
                            <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">🩸 Sample Required</div>
                            <div style="font-size:14px; font-weight:600; color:#0f172a; margin-top:2px;"><?php echo esc_html( ! empty( $sample_type ) ? $sample_type : 'Blood' ); ?></div>
                        </div>
                        <div>
                            <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">🍽️ Fasting Rule</div>
                            <div style="font-size:14px; font-weight:600; color:#0f172a; margin-top:2px;"><?php echo esc_html( ! empty( $fasting_req ) ? $fasting_req : 'Not Required' ); ?></div>
                        </div>
                        <div>
                            <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">⏳ Report Delivery</div>
                            <div style="font-size:14px; font-weight:600; color:#0f172a; margin-top:2px;"><?php echo esc_html( ! empty( $tat_hours ) ? $tat_hours : '24 Hours' ); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Parameters Measured Card -->
                <?php if ( ! empty( $param_list ) ) : ?>
                    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:28px; margin-bottom:24px;">
                        <h3 style="font-size:20px; font-weight:700; color:#0f172a; margin-top:0; margin-bottom:16px;">🧪 Parameters Measured (<?php echo count( $param_list ); ?>)</h3>
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap:10px;">
                            <?php foreach ( $param_list as $p ) : ?>
                                <div style="display:flex; align-items:center; gap:8px; background:#f8fafc; padding:10px 14px; border-radius:8px; border:1px solid #f1f5f9; font-size:14px; font-weight:500;">
                                    <span style="color:#2563eb;">✓</span> <?php echo esc_html( $p ); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Overview & Description -->
                <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:28px; margin-bottom:24px;">
                    <h3 style="font-size:20px; font-weight:700; color:#0f172a; margin-top:0; margin-bottom:14px;">ℹ️ Overview & Test Description</h3>
                    <div class="ptbs-single-content" style="line-height:1.7; color:#334155; font-size:15px;">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>

            <!-- Sticky Booking Card Sidebar -->
            <div style="position:sticky; top:100px;">
                <div style="background:#ffffff; border:2px solid #2563eb; border-radius:16px; padding:24px; box-shadow:0 10px 30px rgba(37,99,235,0.08);">
                    <div style="font-size:12px; font-weight:700; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.5px;">SPECIAL DIAGNOSTIC PRICE</div>
                    <div style="display:flex; align-items:baseline; gap:8px; margin:8px 0 16px 0;">
                        <span style="font-size:32px; font-weight:800; color:#16a34a;">₹<?php echo esc_html( number_format( $price, 2 ) ); ?></span>
                    </div>

                    <ul style="list-style:none; padding:0; margin:0 0 20px 0; font-size:13px; color:#475569;">
                        <li style="padding:4px 0;">🏡 <strong>Free Home Sample Collection</strong></li>
                        <li style="padding:4px 0;">📄 <strong>Digital PDF Report in 24 Hours</strong></li>
                        <li style="padding:4px 0;">🩺 <strong>100% Certified NABL Partner Labs</strong></li>
                    </ul>

                    <button type="button" class="ptbs-btn ptbs-btn-primary full-width ptbs-single-add-cart" 
                            data-id="<?php echo esc_attr( $test_id ); ?>" 
                            data-type="test" 
                            data-title="<?php echo esc_attr( get_the_title() ); ?>" 
                            data-price="<?php echo esc_attr( $price ); ?>" 
                            style="width:100%; padding:14px; font-size:16px; font-weight:700; border-radius:10px; cursor:pointer;">
                        🛒 Add Test to Booking Cart
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
get_footer();
