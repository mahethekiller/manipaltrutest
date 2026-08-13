<?php
/**
 * Database installer & SQL query manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_DB {

    /**
     * Create required SQL tables using dbDelta()
     */
    public static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // 1. Bookings Table
        $table_bookings = $wpdb->prefix . 'ptbs_bookings';
        $sql_bookings   = "CREATE TABLE {$table_bookings} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            booking_number VARCHAR(50) NOT NULL,
            user_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            city_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            city_name VARCHAR(100) NOT NULL DEFAULT '',
            booking_type ENUM('home_collection', 'lab_visit') NOT NULL DEFAULT 'home_collection',
            patient_name VARCHAR(191) NOT NULL,
            patient_age INT(3) UNSIGNED NOT NULL DEFAULT 30,
            patient_gender VARCHAR(20) NOT NULL DEFAULT 'male',
            patient_phone VARCHAR(50) NOT NULL DEFAULT '',
            patient_email VARCHAR(191) NOT NULL DEFAULT '',
            patients_json TEXT NULL,
            address_line1 TEXT NULL,
            pincode VARCHAR(20) NULL,
            booking_date DATE NOT NULL,
            booking_slot VARCHAR(50) NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL DEFAULT '0.00',
            gateway VARCHAR(50) NOT NULL DEFAULT 'razorpay',
            payment_status VARCHAR(50) NOT NULL DEFAULT 'pending',
            transaction_id VARCHAR(191) NULL,
            fulfillment_status VARCHAR(50) NOT NULL DEFAULT 'pending',
            report_file_url TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY booking_number (booking_number),
            KEY user_id (user_id),
            KEY city_id (city_id)
        ) {$charset_collate};";

        // 2. Booking Items Table
        $table_items = $wpdb->prefix . 'ptbs_booking_items';
        $sql_items   = "CREATE TABLE {$table_items} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            booking_id BIGINT(20) UNSIGNED NOT NULL,
            item_type ENUM('test', 'package') NOT NULL,
            item_id BIGINT(20) UNSIGNED NOT NULL,
            item_name VARCHAR(191) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            PRIMARY KEY (id),
            KEY booking_id (booking_id)
        ) {$charset_collate};";

        // 3. City Specific Pricing Table
        $table_prices = $wpdb->prefix . 'ptbs_city_prices';
        $sql_prices   = "CREATE TABLE {$table_prices} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            city_id BIGINT(20) UNSIGNED NOT NULL,
            item_id BIGINT(20) UNSIGNED NOT NULL,
            custom_price DECIMAL(10,2) NOT NULL,
            is_available TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (id),
            UNIQUE KEY city_item (city_id, item_id)
        ) {$charset_collate};";

        // 4. Family Members Table
        $table_family = $wpdb->prefix . 'ptbs_family_members';
        $sql_family   = "CREATE TABLE {$table_family} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            full_name VARCHAR(191) NOT NULL,
            relation VARCHAR(50) NOT NULL DEFAULT 'Self',
            age INT(3) UNSIGNED NOT NULL,
            gender VARCHAR(20) NOT NULL,
            phone VARCHAR(50) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql_bookings );
        dbDelta( $sql_items );
        dbDelta( $sql_prices );
        dbDelta( $sql_family );
    }

    /**
     * Insert new booking safely
     */
    public static function insert_booking( $data ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ptbs_bookings';

        // Ensure table schema includes patients_json
        if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) !== $table_name ) {
            self::create_tables();
        }

        $inserted = $wpdb->insert(
            $table_name,
            array(
                'booking_number'     => sanitize_text_field( $data['booking_number'] ),
                'user_id'            => absint( $data['user_id'] ),
                'city_id'            => absint( $data['city_id'] ),
                'city_name'          => sanitize_text_field( $data['city_name'] ),
                'booking_type'       => sanitize_text_field( $data['booking_type'] ),
                'patient_name'       => sanitize_text_field( $data['patient_name'] ),
                'patient_age'        => absint( isset( $data['patient_age'] ) ? $data['patient_age'] : 30 ),
                'patient_gender'     => sanitize_text_field( isset( $data['patient_gender'] ) ? $data['patient_gender'] : 'male' ),
                'patient_phone'      => sanitize_text_field( $data['patient_phone'] ),
                'patient_email'      => sanitize_email( $data['patient_email'] ),
                'patients_json'      => isset( $data['patients_json'] ) ? $data['patients_json'] : '',
                'address_line1'      => sanitize_textarea_field( $data['address_line1'] ),
                'pincode'            => sanitize_text_field( $data['pincode'] ),
                'booking_date'       => sanitize_text_field( $data['booking_date'] ),
                'booking_slot'       => sanitize_text_field( $data['booking_slot'] ),
                'total_amount'       => floatval( $data['total_amount'] ),
                'gateway'            => sanitize_text_field( $data['gateway'] ),
                'payment_status'     => sanitize_text_field( $data['payment_status'] ),
                'transaction_id'     => sanitize_text_field( $data['transaction_id'] ),
                'fulfillment_status' => 'pending',
            )
        );

        if ( $inserted ) {
            return $wpdb->insert_id;
        }

        return false;
    }

    /**
     * Insert booking item
     */
    public static function insert_booking_item( $booking_id, $item_type, $item_id, $item_name, $price ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ptbs_booking_items';

        $wpdb->insert(
            $table_name,
            array(
                'booking_id' => absint( $booking_id ),
                'item_type'  => sanitize_text_field( $item_type ),
                'item_id'    => absint( $item_id ),
                'item_name'  => sanitize_text_field( $item_name ),
                'price'      => floatval( $price ),
            )
        );
    }

    /**
     * Get Family Members for User
     */
    public static function get_family_members( $user_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ptbs_family_members';

        if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) !== $table_name ) {
            self::create_tables();
        }

        return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE user_id = %d ORDER BY id ASC", absint( $user_id ) ), ARRAY_A );
    }

    /**
     * Insert Family Member
     */
    public static function insert_family_member( $user_id, $name, $relation, $age, $gender, $phone = '' ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ptbs_family_members';

        if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) !== $table_name ) {
            self::create_tables();
        }

        return $wpdb->insert(
            $table_name,
            array(
                'user_id'   => absint( $user_id ),
                'full_name' => sanitize_text_field( $name ),
                'relation'  => sanitize_text_field( $relation ),
                'age'       => absint( $age ),
                'gender'    => sanitize_text_field( $gender ),
                'phone'     => sanitize_text_field( $phone ),
            )
        );
    }

    /**
     * Delete Family Member
     */
    public static function delete_family_member( $member_id, $user_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ptbs_family_members';

        return $wpdb->delete(
            $table_name,
            array(
                'id'      => absint( $member_id ),
                'user_id' => absint( $user_id ),
            )
        );
    }
}
