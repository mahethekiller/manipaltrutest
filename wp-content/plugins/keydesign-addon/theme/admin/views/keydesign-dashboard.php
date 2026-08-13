<!-- <h1 style="margin-bottom: 20px;" class="kdadmin-dashboard-title"><?php esc_html_e( 'Ekko Dashboard', 'ekko' ); ?></h1> -->
<div class="kdadmin-dashboard wrap">
	<div class="kdadmin-welcome-box postbox">
		<div class="inside">
			<?php
      		  function keydesignDeactivate() {
			    update_option( 'keydesign-verify', 'no' );
			  }
			  if (isset($_GET['deactivate'])) {
			    keydesignDeactivate();
			  }
			if (get_option( 'keydesign-verify' ) == 'no' ) { ?>
			<div class="kdadmin-activate-column">
				<h3><?php esc_html_e( 'Activate Ekko to access demo content and other premium features.', 'keydesign' ); ?></h3>
				<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank"><span class="dashicons dashicons-info"></span>How to find your purchase code?</a><br>
				<span class="dashicons dashicons-info" target="_blank"></span>Use only one purchase code per single end product (each domain). Read more about license terms <a href="https://www.ekko-wp.com/documentation/knowledge-base/licensing/" target="_blank">here</a>.
			<?php include('keydesign-envato-api.php');?>
			</div>
			<?php } else { ?>
 			<div class="kdadmin-activate-column">
				<?php $purchase_code = get_option( 'envato_purchase_code_23714045', false ); ?>
				<h3 class="activated"><?php esc_html_e( 'Ekko theme is registered.', 'keydesign' ); ?></h3>
				<?php if ( $purchase_code != '' ) : ?><p class="activated-subtitle"><?php esc_html_e( 'Your purchase code is:', 'keydesign' ); ?> <span><?php echo esc_html( $purchase_code ); ?></span></p><?php endif; ?>
				<a href="<?php echo admin_url( 'admin.php?page=ekko-dashboard&deactivate=true'); ?>" class="deactivate-ekko"><span class="dashicons dashicons-dismiss"></span>Deactivate</a>
			</div>
			<?php } ?>

	        <div class="kdadmin-column-container">
				<h2>Start using Ekko</h2>
				<div class="kdadmin-panel-column">
					<h3><span class="dashboard-icon iconsmind-Data-Download"></span><?php esc_html_e( 'One Click Install', 'keydesign' ); ?></h3>
					<p><?php esc_html_e( 'Quickly and easily import the theme demo contents.', 'keydesign' ); ?></p>
					<a class="kdadmin-button" href="<?php echo esc_url( admin_url( 'admin.php?page=import-demos' ) ); ?>"><?php esc_html_e( 'Import Demos', 'keydesign' ); ?></a>
				</div>
				<div class="kdadmin-panel-column">
					<h3><span class="dashboard-icon iconsmind-Gears"></span><?php esc_html_e( 'Customization Options', 'keydesign' ); ?></h3>
					<p><?php esc_html_e( 'Make quick changes using the theme options panel.', 'keydesign' ); ?></p>
					<a class="kdadmin-button" href="<?php echo esc_url( admin_url( 'admin.php?page=theme-options' ) ); ?>"><?php esc_html_e( 'Theme Options', 'keydesign' ); ?></a>
				</div>
				<div class="kdadmin-panel-column system-requirements">
					<h3><?php esc_html_e( 'System Requirements', 'keydesign' ); ?></h3>

	<div class="kdadmin-req-wrapper">
		<?php if ( wp_get_theme( 'ekko' )->exists() && defined('EKKO_THEME_VERSION') ) : ?>
				<div class="sys-row theme-version">
					<div class="sys-label"><?php esc_html_e( 'Theme version', 'keydesign' ) ?></div>
					<div class="sys-desc"><?php esc_html_e( 'The version of Ekko installed on your site.', 'keydesign' ) ?></div>
					<div class="sys-current"><?php echo EKKO_THEME_VERSION; ?></div>
				</div>
			<?php endif; ?>
				<div class="sys-row wp-version">
					<div class="sys-label"><?php esc_html_e( 'WordPress version', 'keydesign' ) ?></div>
					<div class="sys-desc"><?php esc_html_e( 'The version of WordPress installed on your site.', 'keydesign' ) ?></div>
					<div class="sys-current"><?php echo get_bloginfo( 'version' ); ?></div>
				</div>
				<div class="sys-row">
					<div class="sys-label"><?php esc_html_e( 'WordPress memory limit', 'keydesign' ) ?></div>
					<div class="sys-desc"><?php esc_html_e( 'The maximum amount of memory (RAM) that your site can use at one time.', 'keydesign' ) ?></div>
					<div class="sys-current">
						<?php
							if ( function_exists( 'ini_get' ) ) {
								$memory_limit = ini_get('memory_limit');
								$memory_limit_min = '128M';
								$memory_limit_byte = wp_convert_hr_to_bytes($memory_limit);
								$memory_limit_min_byte = wp_convert_hr_to_bytes($memory_limit_min);

								if ( $memory_limit_byte < $memory_limit_min_byte ) {
										echo '<span class="sys-error"><span class="dashicons dashicons-warning"></span>' . sprintf( esc_html__( '%1$s - We recommend setting memory to at least '.$memory_limit_min.'.', 'keydesign' ), esc_html( $memory_limit ) ) .'</span>';
								} else {
										echo '<span class="sys-valid"><span class="dashicons dashicons-yes"></span>' . esc_html( $memory_limit ) . ' / 128M</span>';
								}
							}
					 	?>
					</div>
				</div>
				<!-- <h3><?php esc_html_e( 'Server environment', 'keydesign' ) ?></h3> -->
				<div class="sys-row">
					<div class="sys-label"><?php esc_html_e( 'PHP version', 'keydesign' ) ?></div>
					<div class="sys-desc"><?php esc_html_e( 'The version of PHP installed on your hosting server.', 'keydesign' ) ?></div>
					<div class="sys-current">
						<?php
							if ( version_compare( phpversion(), '7.2', '<' ) ) {
								echo '<span class="sys-error"><span class="dashicons dashicons-warning"></span>' . sprintf( esc_html__( '%1$s - We recommend using PHP version 7.2 or above for greater performance and security.', 'keydesign' ), esc_html( phpversion() ) ) .'</span>';
							} else {
								echo '<span class="sys-valid"><span class="dashicons dashicons-yes"></span>' . esc_html( phpversion() ) . '</span>';
							}
						?>
					</div>
				</div>
				<div class="sys-row">
					<div class="sys-label"><?php esc_html_e( 'PHP post max size', 'keydesign' ) ?></div>
					<div class="sys-desc"><?php esc_html_e( 'The largest filesize that can be contained in one post.', 'keydesign' ) ?></div>
					<div class="sys-current">
						<?php
							if ( function_exists( 'ini_get' ) ) {
								$post_max_size = ini_get( 'post_max_size' );
								$post_max_size_min = '64M';
								$post_max_size_byte = wp_convert_hr_to_bytes($post_max_size);
								$post_max_size_min_byte = wp_convert_hr_to_bytes($post_max_size_min);

								if ( $post_max_size_byte < $post_max_size_min_byte ) {
										echo '<span class="sys-error"><span class="dashicons dashicons-warning"></span>' . sprintf( esc_html__( '%1$s - We recommend setting post max size to at least '.$post_max_size_min.'.', 'keydesign' ), esc_html( $post_max_size ) ) .'</span>';
								} else {
										echo '<span class="sys-valid"><span class="dashicons dashicons-yes"></span>' . esc_html( $post_max_size ) . ' / 64M</span>';
								}
							}
						?>
					</div>
				</div>
				<div class="sys-row">
					<div class="sys-label"><?php esc_html_e( 'PHP time limit', 'keydesign' ) ?></div>
					<div class="sys-desc"><?php esc_html_e( 'Time that your site will spend on a single operation before timing out.', 'keydesign' ) ?></div>
					<div class="sys-current">
						<?php
							if ( function_exists( 'ini_get' ) ) {
								$max_execution_time = ini_get( 'max_execution_time' );
								$max_execution_time_min = '120';

								if ( $max_execution_time < $max_execution_time_min ) {
										echo '<span class="sys-error"><span class="dashicons dashicons-warning"></span>' . sprintf( esc_html__( '%1$s - We recommend setting max execution time to at least '.$max_execution_time_min.'.', 'keydesign' ), esc_html( $max_execution_time ) ) .'</span>';
								} else {
										echo '<span class="sys-valid"><span class="dashicons dashicons-yes"></span>' . esc_html( $max_execution_time ) . ' / 120</span>';
								}
							}
						?>
					</div>
				</div>
				<div class="sys-row">
					<div class="sys-label"><?php esc_html_e( 'PHP max input vars', 'keydesign' ) ?></div>
					<div class="sys-desc"><?php esc_html_e( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'keydesign' ) ?></div>
					<div class="sys-current">
						<?php
							if ( function_exists( 'ini_get' ) ) {
								$max_input_vars = ini_get( 'max_input_vars' );
								$max_input_vars_min = '1000';

								if ( $max_input_vars < $max_input_vars_min ) {
										echo '<span class="sys-error"><span class="dashicons dashicons-warning"></span>' . sprintf( esc_html__( '%1$s - We recommend setting max input vars to at least '.$max_input_vars_min.'.', 'keydesign' ), esc_html( $max_input_vars ) ) .'</span>';
								} else {
										echo '<span class="sys-valid"><span class="dashicons dashicons-yes"></span>' . esc_html( $max_input_vars ) . ' / 1000</span>';
								}
							}
						?>
					</div>
				</div>
				<div class="sys-row">
					<div class="sys-label"><?php esc_html_e( 'Max upload size', 'keydesign' ) ?></div>
					<div class="sys-desc"><?php esc_html_e( 'The largest filesize that can be uploaded to your WordPress installation.', 'keydesign' ) ?></div>
					<div class="sys-current">
						<?php
							$max_upload_size = wp_max_upload_size();
							$max_upload_size_min = '32M';
							$max_upload_size_byte = wp_convert_hr_to_bytes($max_upload_size);
							$max_upload_size_min_byte = wp_convert_hr_to_bytes($max_upload_size_min);

							if ( $max_upload_size_byte < $max_upload_size_min_byte ) {
									echo '<span class="sys-error"><span class="dashicons dashicons-warning"></span>' . sprintf( esc_html__( '%1$s - We recommend setting max upload size to at least '.$max_upload_size_min.'.', 'keydesign' ), esc_html( size_format( $max_upload_size ) ) ) .'</span>';
							} else {
									echo '<span class="sys-valid"><span class="dashicons dashicons-yes"></span>' . esc_html( size_format( $max_upload_size ) ) . ' / 32M</span>';
							}
						?>
					</div>
				</div>
	</div>

				</div>
			</div>
			<div class="kdadmin-column-container">
				<h2>Help & Support</h2>
				<div class="kdadmin-panel-column">
					<h3><span class="dashboard-icon iconsmind-Open-Book"></span><?php esc_html_e( 'Theme Documentation', 'keydesign' ); ?></h3>
					<p><?php esc_html_e( 'Helpful information about theme setup, capabilities, features and options.', 'keydesign' ); ?></p>
					<a class="kdadmin-button" href="https://www.ekko-wp.com/documentation/" target="_blank"><?php esc_html_e( 'Read Documentation', 'keydesign' ); ?></a>
				</div>
				<div class="kdadmin-panel-column">
					<h3><span class="dashboard-icon iconsmind-Support"></span><?php esc_html_e( 'Support Center', 'keydesign' ); ?></h3>
					<p><?php esc_html_e( 'The best place to ask for support.
					Solve any issue with the help of our experts.', 'keydesign' ); ?></p>
					<a class="kdadmin-button" href="https://keydesign.ticksy.com/" target="_blank"><?php esc_html_e( 'Get Support', 'keydesign' ); ?></a>
				</div>
				<div class="kdadmin-panel-column">
					<h3><span class="dashboard-icon iconsmind-Video-5"></span><?php esc_html_e( 'Video tutorials', 'keydesign' ); ?></h3>
					<p><?php esc_html_e( 'Learn how to use the theme
					with step by step video tutorials.', 'keydesign' ); ?></p>
					<a class="kdadmin-button" href="https://www.youtube.com/watch?v=GLceSZ8qoa4&list=PLKM37Brx2eGCGtYhqfIWPNt41C6Pfd2_n" target="_blank"><?php esc_html_e( 'View Tutorials', 'keydesign' ); ?></a>
				</div>
				<div class="kdadmin-panel-column">
					<h3><span class="dashboard-icon iconsmind-Wordpress"></span><?php esc_html_e( 'How to use WordPress', 'keydesign' ); ?></h3>
					<p><?php esc_html_e( 'Learn how to use WordPress by reading the official starting guide.', 'keydesign' ); ?></p>
					<a class="kdadmin-button" href="https://wordpress.org/support/" target="_blank"><?php esc_html_e( 'WordPress Tutorials', 'keydesign' ); ?></a>
				</div>

			</div>

		</div>
	</div>

</div>
