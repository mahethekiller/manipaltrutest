<?php
/**
 * Custom Nav Menu Fields class definition.
 */

class Custom_Nav_Menu_Fields {

	/**
	 * Holds meta key.
	 *
	 * @var string
	 */
	private $meta_key = 'kd-wp-menu-custom-fields';

	/**
	 * Construct method.
	 */
	public function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 */
	protected function setup_hooks() {
		/**
		 * Action
		 */
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'wp_nav_menu_item_custom_fields' ), 10, 2 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'wp_update_nav_menu_item' ), 10, 2 );

		/**
		 * Filter
		 */
	 	add_filter( 'wp_nav_menu_objects', array( $this, 'wp_nav_menu_objects' ), 10, 2 );
	 	add_filter( 'nav_menu_item_title', array( $this, 'kd_badge_nav_fe' ), 10, 2 );
	}

	/**
	 * Get menu item meta data.
	 *
	 * @param int     $menu_item_id Menu item ID.
	 * @param boolean $from_cache Whether to fetch from cache.
	 *
	 * @return array Meta data.
	 */
	private function get_nav_menu_meta_data( $menu_item_id, $from_cache = true ) {
		$data = array();
		if ( $from_cache ) {
			$data = $this->get_nav_menu_cached_meta_data( $menu_item_id );

			if ( false !== $data ) {
				return $data;
			}
		}

		$data = get_post_meta( $menu_item_id, $this->meta_key, true );

		return $data;
	}

	/**
	 * Add custom fields on menu item edit screen.
	 *
	 * @param int    $id Current menu item ID.
	 * @param object $item Current menu object.
	 *
	 * @return void
	 */
	public function wp_nav_menu_item_custom_fields( $id, $item ) {
		$data = $this->get_nav_menu_meta_data( $id, false );

		wp_nonce_field( $this->meta_key . '-' . $id, $this->meta_key . '-' . $id );

		?>
		<p class="field-badge-label description description-thin">
	    <label for="edit-menu-item-badge-label-<?php echo esc_attr( $id ); ?>">
	      <?php esc_html_e( 'Badge Label', 'keydesign' ); ?><br>
				<input type="text" id="edit-menu-item-badge-label-<?php echo esc_attr( $id ); ?>" class="widefat code menu-item-badge-label-<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $this->meta_key ); ?>-badge-label[<?php echo esc_attr( $id );?>]" value="<?php echo ( ! empty( $data['badge-label'] ) ? esc_html( $data['badge-label'] ) : '' ); ?>" />
	    </label>
		</p>

		<p class="field-badge-color description description-thin">
	    <label for="edit-menu-item-badge-color-<?php echo esc_attr( $id ); ?>">
	      <?php esc_html_e( 'Badge Color', 'keydesign' ); ?>
	    </label>
			<input type="text" id="edit-menu-item-badge-color-<?php echo esc_attr( $id ); ?>" class="widefat code nav-color-field menu-item-badge-color-<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $this->meta_key ); ?>-badge-color[<?php echo esc_attr( $id );?>]" value="<?php echo ( ! empty( $data['badge-color'] ) ? esc_html( $data['badge-color'] ) : '' ); ?>" />
		</p>

		<?php
	}

	/**
	 * Function called when menu item edit form is submitted.
	 *
	 * @param int $menu_id Menu ID.
	 * @param int $item_id Item ID.
	 *
	 * @return void
	 */
	public function wp_update_nav_menu_item( $menu_id, $item_id ) {
		$nonce = filter_input( INPUT_POST, $this->meta_key . '-' . $item_id, FILTER_DEFAULT );
		$nonce = sanitize_text_field( $nonce );
	
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, $this->meta_key . '-' . $item_id ) ) {
			return;
		}
	
		$keys = array(
			'badge-label',
			'badge-color',
		);
	
		$data = array();
		foreach ( $keys as $key ) {
			if ( isset( $_POST[ $this->meta_key . '-' . $key ][ $item_id ] ) ) {
				$data[ $key ] = sanitize_text_field( wp_unslash( $_POST[ $this->meta_key . '-' . $key ][ $item_id ] ) );
			}
		}
	
		update_post_meta( $item_id, $this->meta_key, $data );
		$this->cache_nav_menu_meta_data( $item_id, $data );
	}

	/**
	 * Function to filter nav menu objects.
	 *
	 * @param array $sorted_items Menu items after being sorted.
	 * @param array $args Menu arguments.
	 *
	 * @return array Sorted menu items.
	 */
	public function wp_nav_menu_objects( $sorted_items, $args ) {
		global $nav_menu_custom_fields;
		if ( empty( $nav_menu_custom_fields ) || ! is_array( $nav_menu_custom_fields ) ) {
			$nav_menu_custom_fields = array();
		}

		foreach ( $sorted_items as $item ) {
			$data = $this->get_nav_menu_meta_data( $item->ID );

			if ( ! empty( $data ) ) {
				$nav_menu_custom_fields[ $item->ID ] = $data;
			}
		}

		return $sorted_items;
	}

	/**
	 * Function to filter nav menu objects.
	 */
	public function kd_badge_nav_fe( $title, $item ) {
		global $nav_menu_custom_fields;

		if ( empty( $nav_menu_custom_fields ) || ! is_array( $nav_menu_custom_fields ) ) {
			return $title;
		}

		$badge_label = $badge_color = '';

		if ( ! empty( $nav_menu_custom_fields ) || is_array( $nav_menu_custom_fields ) ) {
			if ( ! empty( $nav_menu_custom_fields[ $item->ID ]['badge-label'] ) ) {
				$badge_label = $nav_menu_custom_fields[ $item->ID ]['badge-label'];
			}
			if ( ! empty( $nav_menu_custom_fields[ $item->ID ]['badge-color'] ) ) {
				$badge_color = $nav_menu_custom_fields[ $item->ID ]['badge-color'];
			}
			$badge_color_style = '';
			if ( '' != $badge_color ) {
				$badge_color_style = 'style="background-color:'.esc_attr( $badge_color ).'1f;color:'.esc_attr( $badge_color ).';"';
			}

			if ( ! empty( $badge_label ) ) {
				$title .= '<span class="menu-item-badge" '.$badge_color_style.'>' . esc_html( $badge_label ) . '</span>';
			}
		}

		return $title;
	}

	/**
	 * Function to get transient data.
	 *
	 * @param int $item_id Menu item ID.
	 *
	 * @return array|boolean Transient data or false.
	 */
	private function get_nav_menu_cached_meta_data( $item_id ) {
		$data = get_transient( $this->meta_key . '-' . $item_id );
		if ( false !== $data ) {
			return $data;
		}

		return false;
	}

	/**
	 * Function to set transient data.
	 *
	 * @param int   $item_id Menu item ID.
	 * @param array $data Data to be stored in transient.
	 *
	 * @return void
	 */
	private function cache_nav_menu_meta_data( $item_id, $data ) {
		set_transient( $this->meta_key . '-' . $item_id, $data, DAY_IN_SECONDS );
	}

}
new Custom_Nav_Menu_Fields;
