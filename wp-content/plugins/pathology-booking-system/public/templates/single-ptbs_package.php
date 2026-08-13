<?php
/**
 * Single Health Checkup Package Info Page Template (with Parameter Tree Accordion)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$package_id      = get_the_ID();
$price           = floatval( get_post_meta( $package_id, '_ptbs_price', true ) );
$linked_test_ids = get_post_meta( $package_id, '_ptbs_linked_test_ids', true );
if ( ! is_array( $linked_test_ids ) ) {
    $linked_test_ids = array();
}

// Fetch linked pathology tests data
$linked_tests = array();
$total_parameters_count = 0;

if ( ! empty( $linked_test_ids ) ) {
    foreach ( $linked_test_ids as $tid ) {
        $test_post = get_post( $tid );
        if ( $test_post && 'publish' === $test_post->post_status ) {
            $raw_params = get_post_meta( $tid, '_ptbs_parameters', true );
            $p_list     = ! empty( $raw_params ) ? array_filter( array_map( 'trim', explode( "\n", $raw_params ) ) ) : array();
            $total_parameters_count += count( $p_list );

            $linked_tests[] = array(
                'id'          => $tid,
                'title'       => $test_post->post_title,
                'parameters'  => $p_list,
                'fasting_req' => get_post_meta( $tid, '_ptbs_fasting_req', true ),
                'sample_type' => get_post_meta( $tid, '_ptbs_sample_type', true ),
            );
        }
    }
}
?>

<div class="ptbs-single-wrapper" style="background:#f8fafc; padding: 40px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div class="container" style="max-width: 1140px; margin: 0 auto; padding: 0 16px;">
        
        <!-- Breadcrumbs -->
        <div style="font-size:13px; color:#64748b; margin-bottom:16px;">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:#64748b; text-decoration:none;">Home</a> &nbsp;/&nbsp; 
            <span>Health Checkup Packages</span> &nbsp;/&nbsp; 
            <strong style="color:#0f172a;"><?php the_title(); ?></strong>
        </div>

        <div style="display:grid; grid-template-columns: 1.8fr 1fr; gap:30px; align-items:start;">
            
            <!-- Main Content Area -->
            <div>
                <!-- Package Hero Box -->
                <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:28px; margin-bottom:24px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px;">
                        <span style="background:#eff6ff; color:#1d4ed8; font-weight:700; font-size:12px; padding:4px 10px; border-radius:6px;">🎁 FULL BODY HEALTH PROFILE</span>
                        <?php if ( $total_parameters_count > 0 ) : ?>
                            <span style="background:#f0fdf4; color:#166534; font-weight:700; font-size:12px; padding:4px 10px; border-radius:6px;"><?php printf( esc_html__( 'INCLUDES %d PARAMETERS', 'pathology-booking-system' ), $total_parameters_count ); ?></span>
                        <?php endif; ?>
                    </div>

                    <h1 style="font-size:30px; font-weight:800; color:#0f172a; margin:0 0 14px 0; line-height:1.3;"><?php the_title(); ?></h1>
                    
                    <div style="font-size:15px; color:#475569; line-height:1.6; margin-bottom:16px;">
                        <?php the_excerpt(); ?>
                    </div>

                    <!-- Quick Highlights -->
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; background:#f8fafc; padding:16px; border-radius:12px; border:1px solid #f1f5f9;">
                        <div>
                            <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">🧪 Profiles Included</div>
                            <div style="font-size:15px; font-weight:700; color:#0f172a; margin-top:2px;"><?php echo count( $linked_tests ); ?> Diagnostic Profiles</div>
                        </div>
                        <div>
                            <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">📊 Total Biomarkers</div>
                            <div style="font-size:15px; font-weight:700; color:#2563eb; margin-top:2px;"><?php echo $total_parameters_count; ?> Tests Covered</div>
                        </div>
                        <div>
                            <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">🚚 Home Collection</div>
                            <div style="font-size:15px; font-weight:700; color:#16a34a; margin-top:2px;">Included (Free)</div>
                        </div>
                    </div>
                </div>

                <!-- Parameter Accordion Tree -->
                <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:28px; margin-bottom:24px;">
                    <h3 style="font-size:20px; font-weight:700; color:#0f172a; margin-top:0; margin-bottom:16px;">🌳 Parameter Accordion Tree (<?php echo count( $linked_tests ); ?> Profiles)</h3>
                    
                    <?php if ( ! empty( $linked_tests ) ) : ?>
                        <div class="ptbs-accordion-tree" style="display:flex; flex-direction:column; gap:12px;">
                            <?php foreach ( $linked_tests as $idx => $lt ) : ?>
                                <div class="ptbs-accordion-item" style="border:1px solid #e2e8f0; border-radius:10px; overflow:hidden;">
                                    <div class="ptbs-accordion-header" style="background:#f8fafc; padding:16px; cursor:pointer; display:flex; justify-content:space-between; align-items:center; user-select:none;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <span style="background:#2563eb; color:#ffffff; font-weight:800; width:26px; height:26px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:12px;"><?php echo ($idx + 1); ?></span>
                                            <strong style="font-size:16px; color:#0f172a;"><?php echo esc_html( $lt['title'] ); ?></strong>
                                            <span style="background:#eff6ff; color:#1d4ed8; font-size:12px; padding:2px 8px; border-radius:12px; font-weight:600;">
                                                <?php printf( esc_html__( '%d Parameters', 'pathology-booking-system' ), count( $lt['parameters'] ) ); ?>
                                            </span>
                                        </div>
                                        <span class="ptbs-accordion-icon" style="font-weight:bold; font-size:18px; color:#64748b;">+</span>
                                    </div>
                                    <div class="ptbs-accordion-body" style="display:none; padding:16px; background:#ffffff; border-top:1px solid #f1f5f9;">
                                        <?php if ( ! empty( $lt['parameters'] ) ) : ?>
                                            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:8px;">
                                                <?php foreach ( $lt['parameters'] as $p ) : ?>
                                                    <div style="font-size:13px; color:#334155; display:flex; align-items:center; gap:6px;">
                                                        <span style="color:#16a34a; font-weight:bold;">✓</span> <?php echo esc_html( $p ); ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else : ?>
                                            <p style="color:#94a3b8; font-size:13px; margin:0;"><?php esc_html_e( 'Detailed biomarkers parameters list included.', 'pathology-booking-system' ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p style="color:#64748b;"><?php esc_html_e( 'No individual test profiles linked to this package yet.', 'pathology-booking-system' ); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Overview & Description -->
                <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:28px; margin-bottom:24px;">
                    <h3 style="font-size:20px; font-weight:700; color:#0f172a; margin-top:0; margin-bottom:14px;">ℹ️ Package Details & Instructions</h3>
                    <div class="ptbs-single-content" style="line-height:1.7; color:#334155; font-size:15px;">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>

            <!-- Sticky Booking Card Sidebar -->
            <div style="position:sticky; top:100px;">
                <div style="background:#ffffff; border:2px solid #2563eb; border-radius:16px; padding:24px; box-shadow:0 10px 30px rgba(37,99,235,0.08);">
                    <div style="font-size:12px; font-weight:700; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.5px;">HEALTH CHECKUP OFFER</div>
                    <div style="display:flex; align-items:baseline; gap:8px; margin:8px 0 16px 0;">
                        <span style="font-size:32px; font-weight:800; color:#16a34a;">₹<?php echo esc_html( number_format( $price, 2 ) ); ?></span>
                    </div>

                    <ul style="list-style:none; padding:0; margin:0 0 20px 0; font-size:13px; color:#475569;">
                        <li style="padding:4px 0;">🎁 <strong>Includes <?php echo $total_parameters_count; ?> Biomarker Tests</strong></li>
                        <li style="padding:4px 0;">🏡 <strong>Free Home Sample Collection</strong></li>
                        <li style="padding:4px 0;">📄 <strong>Digital PDF Report within 24 Hours</strong></li>
                        <li style="padding:4px 0;">🩺 <strong>Free Doctor Report Consultation</strong></li>
                    </ul>

                    <button type="button" class="ptbs-btn ptbs-btn-primary full-width ptbs-single-add-cart" 
                            data-id="<?php echo esc_attr( $package_id ); ?>" 
                            data-type="package" 
                            data-title="<?php echo esc_attr( get_the_title() ); ?>" 
                            data-price="<?php echo esc_attr( $price ); ?>" 
                            style="width:100%; padding:14px; font-size:16px; font-weight:700; border-radius:10px; cursor:pointer;">
                        🎁 Add Package to Booking Cart
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
get_footer();
