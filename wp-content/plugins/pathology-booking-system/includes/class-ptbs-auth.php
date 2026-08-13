<?php
/**
 * User Registration, Form Login, Google OAuth & Account Security Engine
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_Auth {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action( 'wp_ajax_nopriv_ptbs_register_user', array( $this, 'ajax_register_user' ) );
        add_action( 'wp_ajax_nopriv_ptbs_login_user', array( $this, 'ajax_login_user' ) );
        add_action( 'wp_ajax_nopriv_ptbs_google_login', array( $this, 'ajax_google_login' ) );
        add_action( 'wp_ajax_ptbs_google_login', array( $this, 'ajax_google_login' ) );

        // Auth User Actions
        add_action( 'wp_ajax_ptbs_change_password', array( $this, 'ajax_change_password' ) );
        add_action( 'wp_ajax_ptbs_update_profile', array( $this, 'ajax_update_profile' ) );
        add_action( 'wp_ajax_ptbs_save_family_member', array( $this, 'ajax_save_family_member' ) );
        add_action( 'wp_ajax_ptbs_delete_family_member', array( $this, 'ajax_delete_family_member' ) );
        add_action( 'wp_ajax_ptbs_get_family_members', array( $this, 'ajax_get_family_members' ) );
    }

    /**
     * AJAX User Registration Handler
     */
    public function ajax_register_user() {
        check_ajax_referer( 'ptbs_auth_nonce', 'security' );

        $email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        $name     = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
        $password = isset( $_POST['password'] ) ? $_POST['password'] : '';
        $phone    = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';

        if ( empty( $email ) || empty( $password ) || empty( $name ) ) {
            wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'pathology-booking-system' ) ) );
        }

        if ( email_exists( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'An account with this email address already exists.', 'pathology-booking-system' ) ) );
        }

        $username = sanitize_user( current( explode( '@', $email ) ) . '_' . rand( 100, 999 ) );

        $user_id = wp_create_user( $username, $password, $email );

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
        }

        // Set user details & role
        wp_update_user( array(
            'ID'           => $user_id,
            'display_name' => $name,
            'role'         => 'ptbs_patient',
        ) );

        if ( ! empty( $phone ) ) {
            update_user_meta( $user_id, 'ptbs_patient_phone', $phone );
        }

        // Auto log in user
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id, true );

        $user_obj       = get_user_by( 'id', $user_id );
        $family_members = PTBS_DB::get_family_members( $user_id );

        wp_send_json_success( array(
            'message'        => __( 'Registration successful!', 'pathology-booking-system' ),
            'display_name'   => $user_obj->display_name,
            'email'          => $user_obj->user_email,
            'phone'          => get_user_meta( $user_id, 'ptbs_patient_phone', true ),
            'family_members' => $family_members,
            'redirect'       => get_permalink(),
        ) );
    }

    /**
     * AJAX User Form Login Handler
     */
    public function ajax_login_user() {
        check_ajax_referer( 'ptbs_auth_nonce', 'security' );

        $email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        $password = isset( $_POST['password'] ) ? $_POST['password'] : '';

        if ( empty( $email ) || empty( $password ) ) {
            wp_send_json_error( array( 'message' => __( 'Please provide both email and password.', 'pathology-booking-system' ) ) );
        }

        $user = get_user_by( 'email', $email );
        if ( ! $user ) {
            wp_send_json_error( array( 'message' => __( 'No user account found for this email.', 'pathology-booking-system' ) ) );
        }

        $creds = array(
            'user_login'    => $user->user_login,
            'user_password' => $password,
            'remember'      => true,
        );

        $signon = wp_signon( $creds, false );

        if ( is_wp_error( $signon ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid email or password credentials.', 'pathology-booking-system' ) ) );
        }

        $family_members = PTBS_DB::get_family_members( $user->ID );

        wp_send_json_success( array(
            'message'        => __( 'Login successful!', 'pathology-booking-system' ),
            'display_name'   => $user->display_name,
            'email'          => $user->user_email,
            'phone'          => get_user_meta( $user->ID, 'ptbs_patient_phone', true ),
            'family_members' => $family_members,
            'redirect'       => get_permalink(),
        ) );
    }

    /**
     * AJAX Google One-Tap / OAuth Sign-In Handler
     */
    public function ajax_google_login() {
        check_ajax_referer( 'ptbs_auth_nonce', 'security' );

        $id_token = isset( $_POST['id_token'] ) ? sanitize_text_field( wp_unslash( $_POST['id_token'] ) ) : '';

        if ( empty( $id_token ) ) {
            wp_send_json_error( array( 'message' => __( 'Missing Google authentication token.', 'pathology-booking-system' ) ) );
        }

        // Validate token with Google Identity API endpoint
        $verify_url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode( $id_token );
        $response   = wp_remote_get( $verify_url );

        if ( is_wp_error( $response ) ) {
            wp_send_json_error( array( 'message' => __( 'Unable to reach Google OAuth service.', 'pathology-booking-system' ) ) );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( empty( $data['email'] ) || empty( $data['email_verified'] ) ) {
            wp_send_json_error( array( 'message' => __( 'Google Token validation failed or email unverified.', 'pathology-booking-system' ) ) );
        }

        $email = sanitize_email( $data['email'] );
        $name  = ! empty( $data['name'] ) ? sanitize_text_field( $data['name'] ) : 'Google Patient';

        $user = get_user_by( 'email', $email );

        if ( ! $user ) {
            // Register new Google patient user
            $random_pass = wp_generate_password( 16, true );
            $username    = sanitize_user( current( explode( '@', $email ) ) . '_g' . rand( 10, 99 ) );
            $user_id     = wp_create_user( $username, $random_pass, $email );

            if ( is_wp_error( $user_id ) ) {
                wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
            }

            wp_update_user( array(
                'ID'           => $user_id,
                'display_name' => $name,
                'role'         => 'ptbs_patient',
            ) );

            $user = get_user_by( 'id', $user_id );
        }

        // Log in user
        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID, true );

        $family_members = PTBS_DB::get_family_members( $user->ID );

        wp_send_json_success( array(
            'message'        => __( 'Logged in successfully with Google!', 'pathology-booking-system' ),
            'display_name'   => $user->display_name,
            'email'          => $user->user_email,
            'phone'          => get_user_meta( $user->ID, 'ptbs_patient_phone', true ),
            'family_members' => $family_members,
            'redirect'       => get_permalink(),
        ) );
    }

    /**
     * AJAX Change Password Handler
     */
    public function ajax_change_password() {
        check_ajax_referer( 'ptbs_auth_nonce', 'security' );

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'Please log in to change your password.', 'pathology-booking-system' ) ) );
        }

        $user             = wp_get_current_user();
        $current_password = isset( $_POST['current_password'] ) ? $_POST['current_password'] : '';
        $new_password     = isset( $_POST['new_password'] ) ? $_POST['new_password'] : '';
        $confirm_password = isset( $_POST['confirm_password'] ) ? $_POST['confirm_password'] : '';

        if ( empty( $current_password ) || empty( $new_password ) || empty( $confirm_password ) ) {
            wp_send_json_error( array( 'message' => __( 'Please fill in all password fields.', 'pathology-booking-system' ) ) );
        }

        if ( ! wp_check_password( $current_password, $user->user_pass, $user->ID ) ) {
            wp_send_json_error( array( 'message' => __( 'Your current password is incorrect.', 'pathology-booking-system' ) ) );
        }

        if ( strlen( $new_password ) < 8 ) {
            wp_send_json_error( array( 'message' => __( 'New password must be at least 8 characters long.', 'pathology-booking-system' ) ) );
        }

        if ( $new_password !== $confirm_password ) {
            wp_send_json_error( array( 'message' => __( 'New password and confirmation password do not match.', 'pathology-booking-system' ) ) );
        }

        // Update Password
        wp_set_password( $new_password, $user->ID );

        // Re-authenticate user cookie so they remain logged in
        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID, true );

        wp_send_json_success( array( 'message' => __( 'Your password has been changed successfully!', 'pathology-booking-system' ) ) );
    }

    /**
     * AJAX Update Profile Handler
     */
    public function ajax_update_profile() {
        check_ajax_referer( 'ptbs_auth_nonce', 'security' );

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'Please log in.', 'pathology-booking-system' ) ) );
        }

        $user_id      = get_current_user_id();
        $display_name = isset( $_POST['display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['display_name'] ) ) : '';
        $phone        = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
        $address      = isset( $_POST['address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['address'] ) ) : '';
        $pincode      = isset( $_POST['pincode'] ) ? sanitize_text_field( wp_unslash( $_POST['pincode'] ) ) : '';

        if ( ! empty( $display_name ) ) {
            wp_update_user( array(
                'ID'           => $user_id,
                'display_name' => $display_name,
            ) );
        }

        update_user_meta( $user_id, 'ptbs_patient_phone', $phone );
        update_user_meta( $user_id, 'ptbs_patient_address', $address );
        update_user_meta( $user_id, 'ptbs_patient_pincode', $pincode );

        wp_send_json_success( array( 'message' => __( 'Account profile updated successfully!', 'pathology-booking-system' ) ) );
    }

    /**
     * AJAX Save Family Member Handler
     */
    public function ajax_save_family_member() {
        check_ajax_referer( 'ptbs_auth_nonce', 'security' );

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'Please log in.', 'pathology-booking-system' ) ) );
        }

        $user_id  = get_current_user_id();
        $name     = '';
        if ( isset( $_POST['full_name'] ) && ! empty( $_POST['full_name'] ) ) {
            $name = sanitize_text_field( wp_unslash( $_POST['full_name'] ) );
        } elseif ( isset( $_POST['patient_name'] ) && ! empty( $_POST['patient_name'] ) ) {
            $name = sanitize_text_field( wp_unslash( $_POST['patient_name'] ) );
        } elseif ( isset( $_POST['name'] ) && ! empty( $_POST['name'] ) ) {
            $name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
        }

        $relation = isset( $_POST['relation'] ) ? sanitize_text_field( wp_unslash( $_POST['relation'] ) ) : 'Other';
        $age      = isset( $_POST['age'] ) ? absint( $_POST['age'] ) : ( isset( $_POST['patient_age'] ) ? absint( $_POST['patient_age'] ) : 30 );
        $gender   = isset( $_POST['gender'] ) ? sanitize_text_field( wp_unslash( $_POST['gender'] ) ) : ( isset( $_POST['patient_gender'] ) ? sanitize_text_field( wp_unslash( $_POST['patient_gender'] ) ) : 'male' );
        $phone    = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : ( isset( $_POST['patient_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['patient_phone'] ) ) : '' );

        if ( empty( $name ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter a valid family member name.', 'pathology-booking-system' ) ) );
        }

        PTBS_DB::insert_family_member( $user_id, $name, $relation, $age, $gender, $phone );

        wp_send_json_success( array(
            'message' => __( 'Family profile saved successfully!', 'pathology-booking-system' ),
            'members' => PTBS_DB::get_family_members( $user_id ),
        ) );
    }

    /**
     * AJAX Delete Family Member Handler
     */
    public function ajax_delete_family_member() {
        check_ajax_referer( 'ptbs_auth_nonce', 'security' );

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'Please log in.', 'pathology-booking-system' ) ) );
        }

        $user_id   = get_current_user_id();
        $member_id = isset( $_POST['member_id'] ) ? absint( $_POST['member_id'] ) : 0;

        if ( $member_id > 0 ) {
            PTBS_DB::delete_family_member( $member_id, $user_id );
        }

        wp_send_json_success( array(
            'message' => __( 'Family profile removed.', 'pathology-booking-system' ),
            'members' => PTBS_DB::get_family_members( $user_id ),
        ) );
    }

    /**
     * AJAX Fetch Family Members list
     */
    public function ajax_get_family_members() {
        check_ajax_referer( 'ptbs_auth_nonce', 'security' );

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'members' => array() ) );
        }

        $user_id = get_current_user_id();
        wp_send_json_success( array(
            'members' => PTBS_DB::get_family_members( $user_id ),
        ) );
    }
}
