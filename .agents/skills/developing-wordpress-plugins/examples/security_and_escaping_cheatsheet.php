<?php
/**
 * Security, Sanitization, Escaping & Nonce Verification Examples
 * Strict WordPress.org Directory Compliance Cheatsheet
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Example 1: Secure Form Handler (Admin Page / POST submission)
 */
function my_unique_plugin_handle_form_submission() {
    // 1. Check user capability
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Unauthorized user capability.', 'my-unique-plugin' ) );
    }

    // 2. Verify Nonce
    if ( ! isset( $_POST['my_plugin_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['my_plugin_nonce_field'] ) ), 'my_plugin_save_action' ) ) {
        wp_die( esc_html__( 'Security check failed. Invalid nonce.', 'my-unique-plugin' ) );
    }

    // 3. Input Sanitization
    $item_name  = isset( $_POST['item_name'] ) ? sanitize_text_field( wp_unslash( $_POST['item_name'] ) ) : '';
    $item_desc  = isset( $_POST['item_desc'] ) ? sanitize_textarea_field( wp_unslash( $_POST['item_desc'] ) ) : '';
    $item_count = isset( $_POST['item_count'] ) ? absint( $_POST['item_count'] ) : 0;
    $item_email = isset( $_POST['item_email'] ) ? sanitize_email( wp_unslash( $_POST['item_email'] ) ) : '';

    // 4. Save to Database / Options API
    update_option( 'my_unique_plugin_item_name', $item_name );
    update_option( 'my_unique_plugin_item_desc', $item_desc );
    update_option( 'my_unique_plugin_item_count', $item_count );
    update_option( 'my_unique_plugin_item_email', $item_email );

    // 5. Redirect back safely
    wp_safe_redirect( add_query_arg( 'settings-updated', 'true', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_my_plugin_save_form', 'my_unique_plugin_handle_form_submission' );

/**
 * Example 2: Secure AJAX Callback
 */
function my_unique_plugin_ajax_handler() {
    // 1. Verify Nonce
    check_ajax_referer( 'my_plugin_ajax_nonce', 'security' );

    // 2. Capability Check
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Permission denied.', 'my-unique-plugin' ) ) );
    }

    // 3. Sanitize
    $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

    if ( ! $post_id ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid Post ID.', 'my-unique-plugin' ) ) );
    }

    // Process work...
    wp_send_json_success( array(
        'message' => esc_html__( 'Action processed successfully.', 'my-unique-plugin' ),
        'post_id' => $post_id,
    ) );
}
add_action( 'wp_ajax_my_plugin_ajax_action', 'my_unique_plugin_ajax_handler' );

/**
 * Example 3: Secure Database Query with $wpdb->prepare()
 */
function my_unique_plugin_get_user_records( $user_status, $limit = 10 ) {
    global $wpdb;

    $table_name  = $wpdb->prefix . 'my_custom_table';
    $user_status = sanitize_key( $user_status );
    $limit       = absint( $limit );

    // ALWAYS use prepare() when interpolating variables into SQL!
    $sql = $wpdb->prepare(
        "SELECT * FROM {$table_name} WHERE status = %s ORDER BY created_at DESC LIMIT %d",
        $user_status,
        $limit
    );

    return $wpdb->get_results( $sql, ARRAY_A );
}

/**
 * Example 4: Secure Late Escaping in HTML Rendering
 */
function my_unique_plugin_render_admin_page() {
    $item_name  = get_option( 'my_unique_plugin_item_name', '' );
    $item_desc  = get_option( 'my_unique_plugin_item_desc', '' );
    $item_link  = 'https://example.com/item';
    $allowed_html = array(
        'strong' => array(),
        'em'     => array(),
        'a'      => array( 'href' => array(), 'title' => array(), 'target' => array() ),
    );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__( 'Plugin Settings', 'my-unique-plugin' ); ?></h1>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'my_plugin_save_action', 'my_plugin_nonce_field' ); ?>
            <input type="hidden" name="action" value="my_plugin_save_form" />
            
            <label for="item_name"><?php echo esc_html__( 'Item Name:', 'my-unique-plugin' ); ?></label>
            <input type="text" id="item_name" name="item_name" value="<?php echo esc_attr( $item_name ); ?>" class="regular-text" />

            <label for="item_desc"><?php echo esc_html__( 'Description:', 'my-unique-plugin' ); ?></label>
            <textarea id="item_desc" name="item_desc"><?php echo esc_textarea( $item_desc ); ?></textarea>

            <p><a href="<?php echo esc_url( $item_link ); ?>"><?php echo esc_html__( 'View Item Page', 'my-unique-plugin' ); ?></a></p>

            <!-- Allowing specific dynamic HTML safely -->
            <p><?php echo wp_kses( __( 'Dynamic text with <strong>formatting</strong> allowed.', 'my-unique-plugin' ), $allowed_html ); ?></p>

            <?php submit_button( __( 'Save Settings', 'my-unique-plugin' ) ); ?>
        </form>
    </div>
    <?php
}
