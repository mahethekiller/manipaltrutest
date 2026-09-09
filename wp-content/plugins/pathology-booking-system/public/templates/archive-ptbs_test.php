<?php
/**
 * Template Name: Pathology Archive
 * Description: Default fallback archive template for Pathology Tests & Packages
 */

get_header();
?>

<div class="ptbs-archive-wrapper" style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; color:#1e293b; background:#f8fafc; padding-bottom:60px;">
    
    <!-- Hero Banner -->
    <div style="background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color:#fff; padding:60px 20px; text-align:center;">
        <div style="max-width:1100px; margin:0 auto;">
            <span style="background:rgba(2,132,199,0.2); color:#38bdf8; font-weight:700; font-size:13px; padding:6px 16px; border-radius:20px; text-transform:uppercase; letter-spacing:1px; display:inline-block; margin-bottom:12px;">
                🔬 Lab Catalog Archive
            </span>
            <h1 style="font-size:36px; font-weight:800; color:#fff; margin:0 0 16px 0;"><?php esc_html_e( 'Pathology Tests & Health Packages', 'pathology-booking-system' ); ?></h1>
            <p style="font-size:16px; color:#94a3b8; max-width:700px; margin:0 auto;"><?php esc_html_e( 'Browse our comprehensive list of lab tests, diagnostic profiles, and full body health checkups.', 'pathology-booking-system' ); ?></p>
        </div>
    </div>

    <!-- Main Content Container -->
    <div style="max-width:1100px; margin:40px auto 0 auto; padding:0 20px;">
        <?php echo do_shortcode( '[pathology_booking]' ); ?>
    </div>

</div>

<?php
get_footer();
