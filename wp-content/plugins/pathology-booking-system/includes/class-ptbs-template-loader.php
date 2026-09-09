<?php
/**
 * Template Loader & Hierarchy Locator Class
 *
 * Handles locating and loading plugin templates, allowing themes and child themes
 * to override default plugin templates cleanly.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_Template_Loader {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_filter( 'template_include', array( $this, 'template_loader' ) );
    }

    /**
     * Intercept and load custom templates for CPTs and Taxonomies
     *
     * @param string $template
     * @return string
     */
    public function template_loader( $template ) {
        if ( is_embed() ) {
            return $template;
        }

        $file = '';

        if ( is_singular( 'ptbs_test' ) ) {
            $file = 'single-ptbs_test.php';
        } elseif ( is_singular( 'ptbs_package' ) ) {
            $file = 'single-ptbs_package.php';
        } elseif ( is_singular( 'ptbs_center_location' ) ) {
            $file = 'single-ptbs_center_location.php';
        } elseif ( is_tax( array( 'ptbs_category', 'ptbs_subcategory' ) ) ) {
            $file = 'taxonomy-ptbs_category.php';
        } elseif ( is_tax( 'ptbs_condition' ) ) {
            $file = 'taxonomy-ptbs_condition.php';
        } elseif ( is_post_type_archive( 'ptbs_test' ) || is_post_type_archive( 'ptbs_package' ) ) {
            $file = 'archive-ptbs_test.php';
        }

        if ( $file ) {
            $located = self::locate_template( $file );
            if ( $located ) {
                return $located;
            }
        }

        return $template;
    }

    /**
     * Locate a template file in theme or plugin fallback
     *
     * Priority:
     * 1. {child-theme}/pathology-booking-system/{template_name}
     * 2. {parent-theme}/pathology-booking-system/{template_name}
     * 3. {child-theme}/{template_name}
     * 4. {parent-theme}/{template_name}
     * 5. {plugin}/public/templates/{template_name}
     *
     * @param string $template_name
     * @param string $template_path
     * @param string $default_path
     * @return string
     */
    public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
        if ( ! $template_path ) {
            $template_path = 'pathology-booking-system/';
        }

        if ( ! $default_path ) {
            $default_path = PTBS_DIR_PATH . 'public/templates/';
        }

        // Look within the active theme & child theme directory
        $template = locate_template( array(
            trailingslashit( $template_path ) . $template_name,
            $template_name,
        ) );

        // Get default template from plugin fallback
        if ( ! $template && file_exists( $default_path . $template_name ) ) {
            $template = $default_path . $template_name;
        }

        // Return filterable locate template
        return apply_filters( 'ptbs_locate_template', $template, $template_name, $template_path );
    }

    /**
     * Get and render template partial with extracted variables
     *
     * @param string $template_name
     * @param array $args
     * @param string $template_path
     * @param string $default_path
     */
    public static function get_template_part( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
        if ( ! empty( $args ) && is_array( $args ) ) {
            extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
        }

        $located = self::locate_template( $template_name, $template_path, $default_path );

        if ( file_exists( $located ) ) {
            include $located;
        }
    }
}

// Initialize loader
PTBS_Template_Loader::get_instance();
