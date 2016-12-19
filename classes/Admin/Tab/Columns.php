<?php

class AC_Admin_Tab_Columns extends AC_Admin_Tab {

	CONST OPTION_CURRENT = 'cpac_current_model';

	/**
	 * @var AC_ListScreen $list_screen
	 */
	private $list_screen;

	public function __construct() {
		$this->set_slug( 'columns' )
		     ->set_label( __( 'Admin Columns', 'codepress-admin-columns' ) )
		     ->set_default( true );

		// Requests
		add_action( 'admin_init', array( $this, 'handle_column_request' ) );

		// Ajax calls
		add_action( 'wp_ajax_cpac_column_select', array( $this, 'ajax_column_select' ) );
		add_action( 'wp_ajax_cpac_column_refresh', array( $this, 'ajax_column_refresh' ) );
		add_action( 'wp_ajax_cpac_columns_save', array( $this, 'ajax_columns_save' ) );
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		$minified = AC()->minified();

		// Width slider
		wp_enqueue_style( 'jquery-ui-lightness', AC()->get_plugin_url() . 'assets/ui-theme/jquery-ui-1.8.18.custom.css', array(), AC()->get_version(), 'all' );
		wp_enqueue_script( 'jquery-ui-slider' );

		wp_enqueue_script( 'ac-admin-tab-columns', AC()->get_plugin_url() . "assets/js/admin-tab-columns{$minified}.js", array(
			'jquery',
			'dashboard',
			'jquery-ui-slider',
			'jquery-ui-sortable',
			'wp-pointer',
		), AC()->get_version() );

		wp_enqueue_style( 'ac-admin-tab-columns-css', AC()->get_plugin_url() . 'assets/css/admin-tab-columns' . AC()->minified() . '.css', array(), AC()->get_version(), 'all' );

		// Javascript translations
		wp_localize_script( 'ac-admin-tab-columns', 'cpac_i18n', array(
			'clone' => __( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
			'error' => __( 'Invalid response.', 'codepress-admin-columns' ),
		) );

		// Nonce
		wp_localize_script( 'ac-admin-tab-columns', 'cpac', array(
			'_ajax_nonce' => wp_create_nonce( 'cpac-settings' ),
			'list_screen' => $this->get_list_screen()->get_key(),
		) );
	}

	/**
	 * @since 2.0
	 *
	 * @param AC_ListScreen $list_screen
	 * @param array $columns
	 * @param array $default_columns Default columns heading names.
	 */
	private function store( AC_ListScreen $list_screen, $column_data ) {

		if ( ! $column_data ) {
			return new WP_Error( 'no-settings', __( 'No columns settings available.', 'codepress-admin-columns' ) );
		}

		foreach ( $column_data as $name => $options ) {
			// set clone
			$clone = str_replace( $options['type'] . '-', '', $name );

			if ( is_numeric( $clone ) ) {
				$options['clone'] = $clone;
			}

			$sanitized = array();

			// sanitize data
			if ( $column = $list_screen->create_column( $options ) ) {
				foreach ( $column->get_settings() as $setting ) {
					$sanitized += $setting->get_values();
				}
			}

			$column_data[ $name ] = array_merge( $options, $sanitized );
		}

		// store columns
		$result = $list_screen->settings()->store( $column_data );

		// reset object
		$list_screen->flush_columns();

		if ( ! $result ) {
			return new WP_Error( 'same-settings', sprintf( __( 'You are trying to store the same settings for %s.', 'codepress-admin-columns' ), "<strong>" . $this->get_list_screen_message_label( $list_screen ) . "</strong>" ) );
		}

		/**
		 * Fires after a new column setup is stored in the database
		 * Primarily used when columns are saved through the Admin Columns settings screen
		 *
		 * @since NEWVERSION
		 *
		 * @param AC_ListScreen $list_screen
		 */
		do_action( 'ac/columns_stored', $list_screen );

		return true;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param AC_ListScreen $list_screen
	 *
	 * @return string $label
	 */
	private function get_list_screen_message_label( $list_screen ) {
		return apply_filters( 'ac/settings/list_screen_message_label', $list_screen->get_label(), $list_screen );
	}

	/**
	 * @since 1.0
	 */
	public function handle_column_request() {
		$action = filter_input( INPUT_POST, 'cpac_action' );
		$nonce = filter_input( INPUT_POST, '_cpac_nonce' );

		if ( ! $action || ! $nonce || ! current_user_can( 'manage_admin_columns' ) || ! $this->is_current_screen() ) {
			return;
		}

		switch ( $action ) :
			case 'restore_by_type' :
				$key = filter_input( INPUT_POST, 'cpac_key' );

				if ( $key && wp_verify_nonce( $nonce, 'restore-type' ) ) {

					if ( $list_screen = $this->get_list_screen() ) {
						$list_screen->settings()->delete();
						$list_screen->flush_columns();

						cpac_settings_message( sprintf( __( 'Settings for %s restored successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $this->get_list_screen_message_label( $list_screen ) ) . "</strong>" ), 'updated' );
					}
				}
				break;
		endswitch;
	}

	/**
	 * @param AC_Column $column
	 *
	 * @return string
	 */
	private function get_column_display( AC_Column $column ) {
		ob_start();
		$this->display_column( $column );

		return ob_get_clean();
	}

	/**
	 * Check is the ajax request is valid and user is allowed to make it
	 *
	 * @since NEWVERSION
	 */
	private function ajax_validate_request() {
		check_ajax_referer( 'cpac-settings' );

		if ( ! current_user_can( 'manage_admin_columns' ) ) {
			wp_die();
		}

		// make sure a list screen is set on AJAX requests
		$this->set_current_list_screen();
	}

	/**
	 * @param AC_ListScreen $list_screen
	 *
	 * @return string
	 */
	private function get_error_message_visit_list_screen( $list_screen ) {
		return sprintf( __( 'Please visit the %s screen once to load all available columns', 'codepress-admin-columns' ), "<a href='" . esc_url( $list_screen->get_screen_link() ) . "'>" . esc_html( $list_screen->get_label() ) . "</a>" );
	}

	/**
	 * Display HTML markup for column type
	 *
	 * @since NEWVERSION
	 */
	public function ajax_column_select() {
		$this->ajax_validate_request();

		$type = filter_input( INPUT_POST, 'type' );
		$original_columns = (array) filter_input( INPUT_POST, 'original_columns', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		$column = $this->get_list_screen()->get_column_by_type( $type );

		if ( ! $column ) {
			wp_send_json_error( array(
				'type'  => 'message',
				'error' => $this->get_error_message_visit_list_screen( $this->get_list_screen() ),
			) );
		}

		// Not cloneable message
		if ( in_array( $type, $original_columns ) ) {
			wp_send_json_error( array(
				'type'  => 'message',
				'error' => sprintf(
					__( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
					'<strong>' . $column->get_setting( 'type' )->get_value( 'clean_label' ) . '</strong>' ),
			) );
		}

		// Placeholder message
		if ( $column instanceof AC_Column_PlaceholderInterface ) {
			wp_send_json_error( array(
				'type'  => 'message',
				'error' => $this->get_placeholder_message( array( 'label' => $column->get_label(), 'type' => $column->get_type(), 'url' => $column->get_url() ) ),
			) );
		}

		wp_send_json_success( $this->get_column_display( $column ) );
	}

	/**
	 * @param array $args
	 *
	 * @return string HTML error message
	 */
	private function get_placeholder_message( $args = array() ) {
		$defaults = array(
			'label' => '',
			'url'   => '',
			'type'  => '',
		);

		$data = (object) wp_parse_args( $args, $defaults );

		if ( ! $data->label ) {
			return false;
		}

		ob_start();
		?>

		<p>
			<strong><?php printf( __( "The %s column is only available in Admin Columns Pro - Business or Developer.", 'codepress-admin-columns' ), $data->label ); ?></strong>
		</p>

		<p>
			<?php printf( __( "If you have a business or developer licence please download & install your %s add-on from the <a href='%s'>add-ons tab</a>.", 'codepress-admin-columns' ), $data->label, admin_url( 'options-general.php?page=codepress-admin-columns&tab=addons' ) ); ?>
		</p>

		<p>
			<?php printf( __( "Admin Columns Pro offers full %s integration, allowing you to easily display and edit %s fields from within your overview.", 'codepress-admin-columns' ), $data->label, $data->label ); ?>
		</p>
		<a target="_blank" href="<?php echo add_query_arg( array(
			'utm_source'   => 'plugin-installation',
			'utm_medium'   => $data->type,
			'utm_campaign' => 'plugin-installation',
		), $data->url ); ?>" class="button button-primary"><?php _e( 'Find out more', 'codepress-admin-columns' ); ?></a>

		<?php

		return ob_get_clean();
	}

	/**
	 * @since 2.2
	 */
	public function ajax_column_refresh() {
		$this->ajax_validate_request();

		$options = filter_input( INPUT_POST, 'columns', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$name = filter_input( INPUT_POST, 'column_name' );
		$clone = filter_input( INPUT_POST, 'column_clone', FILTER_VALIDATE_INT );

		if ( null === $clone || ! $name || empty( $options[ $name ] ) ) {
			wp_die();
		}

		if ( $clone ) {
			$options[ $name ]['clone'] = $clone;
		}

		$column = $this->get_list_screen()->create_column( $options[ $name ] );

		if ( ! $column ) {
			wp_die();
		}

		$column->set_options( $options[ $name ] );

		wp_send_json_success( $this->get_column_display( $column ) );
	}

	/**
	 * @since 2.5
	 */
	public function ajax_columns_save() {
		$this->ajax_validate_request();

		parse_str( $_POST['data'], $formdata );

		if ( ! isset( $formdata['columns'] ) ) {
			wp_send_json_error( array(
					'type'    => 'error',
					'message' => __( 'You need at least one column', 'codepress-admin-columns' ),
				)
			);
		}

		$stored = $this->store( $this->get_list_screen(), $formdata['columns'] );

		if ( is_wp_error( $stored ) ) {
			wp_send_json_error( array(
					'type'    => 'same-settings' === $stored->get_error_code() ? 'notice notice-warning' : 'error',
					'message' => $stored->get_error_message(),
				)
			);
		}

		wp_send_json_success(
			sprintf( __( 'Settings for %s updated successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $this->get_list_screen_message_label( $this->get_list_screen() ) ) . "</strong>" )
			. ' <a href="' . esc_attr( $this->get_list_screen()->get_screen_link() ) . '">' . esc_html( sprintf( __( 'View %s screen', 'codepress-admin-columns' ), $this->get_list_screen()->get_label() ) ) . '</a>'
		);
	}

	private function messages() {
		if ( ! empty( $GLOBALS['cpac_settings_messages'] ) ) {
			echo implode( $GLOBALS['cpac_settings_messages'] );
		}
	}

	public function sort_by_label( $a, $b ) {
		return strcmp( $a->label, $b->label );
	}

	private function get_grouped_models() {
		$grouped = array();
		foreach ( AC()->get_list_screens() as $_list_screen ) {
			$grouped[ $_list_screen->get_menu_type() ][] = (object) array(
				'key'   => $_list_screen->get_key(),
				'link'  => $_list_screen->get_edit_link(),
				'label' => $_list_screen->get_label(),
			);
			usort( $grouped[ $_list_screen->get_menu_type() ], array( $this, 'sort_by_label' ) );
		}

		return $grouped;
	}

	private function set_user_model_preference( $list_screen_key ) {
		update_user_meta( get_current_user_id(), self::OPTION_CURRENT, $list_screen_key );
	}

	private function get_user_model_preference() {
		return AC()->get_list_screen( get_user_meta( get_current_user_id(), self::OPTION_CURRENT, true ) );
	}

	/**
	 * @param string $main_label
	 *
	 * @return string
	 */
	private function get_truncated_side_label( $label, $mainlabel = '' ) {
		if ( 34 < ( strlen( $label ) + ( strlen( $mainlabel ) * 1.1 ) ) ) {
			$label = substr( $label, 0, 34 - ( strlen( $mainlabel ) * 1.1 ) ) . '...';
		}

		return $label;
	}

	/**
	 * Set current list screen
	 */
	private function set_current_list_screen() {
		$current_list_screen = AC()->get_default_list_screen();

		if ( $list_screen = $this->get_user_model_preference() ) {
			$current_list_screen = $list_screen;
		}

		if ( isset( $_REQUEST['cpac_key'] ) ) {
			if ( $list_screen = AC()->get_list_screen( $_REQUEST['cpac_key'] ) ) {
				$current_list_screen = $list_screen;
			}

			$this->set_user_model_preference( $current_list_screen->get_key() );
		}

		do_action( 'ac/settings/list_screen', $current_list_screen );

		$this->list_screen = $current_list_screen;
	}

	/**
	 * @return AC_ListScreen
	 */
	public function get_list_screen() {
		if ( null === $this->list_screen ) {
			$this->set_current_list_screen();
		}

		return $this->list_screen;
	}

	/**
	 * @return AC_Admin_Promo|false
	 */
	public function get_active_promotion() {
		$classes = AC()->autoloader()->get_class_names_from_dir( AC()->get_plugin_dir() . 'classes/Admin/Promo', 'AC_' );

		foreach ( $classes as $class ) {

			/* @var AC_Admin_Promo $promo */
			$promo = new $class;

			if ( $promo->is_active() ) {
				return $promo;
			}
		}

		return false;
	}

	/**
	 * Display
	 */
	public function display() {
		$list_screen = $this->get_list_screen();
		?>

		<div class="columns-container<?php echo $list_screen->settings()->get_settings() ? ' stored' : ''; ?>" data-type="<?php echo esc_attr( $list_screen->get_key() ); ?>">
			<div class="main">
				<div class="menu">
					<select title="Select type" id="ac_list_screen">
						<?php foreach ( $this->get_grouped_models() as $menu_type => $models ) : ?>
							<optgroup label="<?php echo esc_attr( $menu_type ); ?>">
								<?php foreach ( $models as $model ) : ?>
									<option value="<?php echo esc_attr( $model->link ); ?>" <?php selected( $model->key, $list_screen->get_key() ); ?>><?php echo esc_html( $model->label ); ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
					<span class="spinner"></span>

					<?php if ( $link = $list_screen->get_screen_link() ) : ?>
						<a href="<?php echo esc_url( $link ); ?>" class="page-title-action view-link"><?php echo esc_html__( 'View', 'codepress-admin-columns' ); ?></a>
					<?php endif; ?>
				</div>

				<?php do_action( 'cac/settings/after_title', $list_screen ); ?>

			</div>

			<div class="columns-right">
				<div class="columns-right-inside">

					<?php if ( ! $list_screen->is_using_php_export() ) : ?>
						<div class="sidebox form-actions">
							<?php $mainlabel = __( 'Store settings', 'codepress-admin-columns' ); ?>
							<h3>
								<span class="left"><?php echo esc_html( $mainlabel ); ?></span>
								<?php if ( 18 > strlen( $mainlabel ) && ( $truncated_label = $this->get_truncated_side_label( $list_screen->get_label(), $mainlabel ) ) ) : ?>
									<span class="right contenttype"><?php echo esc_html( $truncated_label ); ?></span>
								<?php else : ?>
									<span class="clear contenttype"><?php echo esc_html( $list_screen->get_label() ); ?></span>
								<?php endif; ?>
							</h3>

							<div class="form-update">
								<a href="javascript:;" class="button-primary submit update"><?php _e( 'Update' ); ?></a>
								<a href="javascript:;" class="button-primary submit save"><?php _e( 'Save' ); ?></a>
							</div>

							<form class="form-reset" method="post">
								<input type="hidden" name="cpac_key" value="<?php echo esc_attr( $list_screen->get_key() ); ?>"/>
								<input type="hidden" name="cpac_action" value="restore_by_type"/>
								<?php wp_nonce_field( 'restore-type', '_cpac_nonce' ); ?>

								<?php $onclick = AC()->use_delete_confirmation() ? ' onclick="return confirm(\'' . esc_js( sprintf( __( "Warning! The %s columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ), "'" . $this->get_list_screen_message_label( $list_screen ) . "'" ) ) . '\');"' : ''; ?>
								<input class="reset-column-type" type="submit"<?php echo $onclick; ?> value="<?php _e( 'Restore columns', 'codepress-admin-columns' ); ?>">
								<span class="spinner"></span>
							</form>

							<?php do_action( 'cac/settings/form_actions', $list_screen ); ?>

						</div><!--form-actions-->
					<?php endif; ?>

					<?php do_action( 'cac/settings/sidebox', $list_screen ); ?>

					<?php if ( apply_filters( 'ac/show_banner', true ) ) : ?>

						<?php $url_args = array(
							'utm_source'   => 'plugin-installation',
							'utm_medium'   => 'banner',
							'utm_campaign' => 'plugin-installation',
						);

						$active_promotion = $this->get_active_promotion();

						?>
						<div class="sidebox" id="ac-pro-version">
							<div class="padding-box">
								<h3>
									<a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'title' ) ), ac_get_site_url() ); ?>"><?php _e( 'Upgrade to', 'codepress-admin-columns' ); ?>&nbsp;<span>Pro</span></a>
								</h3>

								<div class="inside">
									<p><?php _e( 'Take Admin Columns to the next level:', 'codepress-admin-columns' ); ?></p>
									<ul>
										<li>
											<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-sorting' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><?php _e( 'Add sortable columns', 'codepress-admin-columns' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-filtering' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><?php _e( 'Add filterable columns', 'codepress-admin-columns' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-editing' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><?php _e( 'Edit your column content', 'codepress-admin-columns' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-columns-sets' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><?php _e( 'Create multiple columns sets', 'codepress-admin-columns' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-import-export' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><?php _e( 'Import &amp; Export settings', 'codepress-admin-columns' ); ?></a>
										</li>
										<?php if ( ac_is_acf_active() ) : ?>
											<li class="acp-integration">
												<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-import-export' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><img class="acf" src="<?php echo CPAC_URL; ?>assets/images/acf-logo.png" alt="ACF"> <?php _e( 'Columns', 'codepress-admin-columns' ); ?></a>
											</li>
										<?php endif; ?>
										<?php if ( ac_is_woocommerce_active() ) : ?>
											<li class="acp-integration">
												<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-import-export' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><img class="woocommerce" src="<?php echo CPAC_URL; ?>assets/images/woocommerce-logo.png" alt="WooCommerce"> <?php _e( 'Columns', 'codepress-admin-columns' ); ?></a>
											</li>
										<?php endif; ?>
									</ul>

									<?php if ( ! $active_promotion ) : ?>
										<a target="_blank" href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'promo' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>" class="acp-button"><?php echo __( 'Learn more about Pro' ); ?></a>
									<?php endif; ?>
								</div>
							</div>

							<?php if ( $active_promotion ) : ?>

								<div class="padding-box ac-pro-deal">
									<h3><?php echo esc_html( $active_promotion->get_title() ); ?></h3>
									<a target="_blank" href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'cta' ) ), $active_promotion->get_url() ) ); ?>" class="acp-button"><?php echo esc_html( sprintf( __( 'Get %s Off', 'codepress-admin-columns' ), $active_promotion->get_discount() . '%' ) ); ?></a>
								</div>

							<?php else : ?>

								<div class="padding-box ac-pro-newsletter">
									<h3><?php echo esc_html( sprintf( __( 'Get %s Off', 'codepress-admin-columns' ), '20%' ) ); ?></h3>
									<div class="inside">
										<p><?php esc_html( sprintf( __( "Submit your email and we'll send you a coupon for %s off your upgrade to the pro version", 'codepress-admin-columns' ), '20%' ) ); ?></p>
										<?php
										$user_data = get_userdata( get_current_user_id() );
										?>
										<form method="post" action="<?php echo ac_get_site_url() . '/upgrade-to-admin-columns-pro/'; ?>" target="_blank">
											<input name="action" type="hidden" value="mc_upgrade_pro">
											<input name="EMAIL" placeholder="Your Email" value="<?php echo $user_data->user_email; ?>">
											<input name="FNAME" placeholder="Your First Name">
											<input name="LNAME" placeholder="Your Last Name">
											<input type="submit" value="Send me the coupon" class="acp-button">
										</form>
									</div>
								</div>

							<?php endif; ?>

						</div>

						<div class="sidebox" id="direct-feedback">
							<div id="feedback-choice">
								<h3><?php _e( 'Are you happy with Admin Columns?', 'codepress-admin-columns' ); ?></h3>

								<div class="inside">
									<a href="#" class="yes"><?php _e( 'Yes' ); ?></a>
									<a href="#" class="no"><?php _e( 'No' ); ?></a>
								</div>
							</div>
							<div id="feedback-support">
								<div class="inside">
									<p><?php _e( "What's wrong? Need help? Let us know!", 'codepress-admin-columns' ); ?></p>

									<p><?php _e( 'Check out our extensive documentation, or you can open a support topic on WordPress.org!', 'codepress-admin-columns' ); ?></p>
									<ul class="share">
										<li>
											<a href="<?php echo add_query_arg( array(
												'utm_source'   => 'plugin-installation',
												'utm_medium'   => 'feedback-docs-button',
												'utm_campaign' => 'plugin-installation',
											), ac_get_site_url( 'documentation' ) ); ?>" target="_blank">
												<div class="dashicons dashicons-editor-help"></div> <?php _e( 'Docs', 'codepress-admin-columns' ); ?>
											</a>
										</li>
										<li>
											<a href="https://wordpress.org/support/plugin/codepress-admin-columns"
												target="_blank">
												<div class="dashicons dashicons-wordpress"></div> <?php _e( 'Forums', 'codepress-admin-columns' ); ?>
											</a>
										</li>
									</ul>
									<div class="clear"></div>
								</div>
							</div>
							<div id="feedback-rate">
								<div class="inside">
									<p><?php _e( "Woohoo! We're glad to hear that!", 'codepress-admin-columns' ); ?></p>

									<p><?php _e( 'We would really love it if you could show your appreciation by giving us a rating on WordPress.org or tweet about Admin Columns!', 'codepress-admin-columns' ); ?></p>
									<ul class="share">
										<li>
											<a href="http://wordpress.org/support/view/plugin-reviews/codepress-admin-columns#postform" target="_blank">
												<div class="dashicons dashicons-star-empty"></div> <?php _e( 'Rate', 'codepress-admin-columns' ); ?>
											</a>
										</li>

										<li>
											<a href="<?php echo add_query_arg( array(
												'hashtags' => 'admincolumns',
												'text'     => urlencode( __( "I'm using Admin Columns for WordPress!", 'codepress-admin-columns' ) ),
												'url'      => urlencode( 'http://wordpress.org/plugins/codepress-admin-columns/' ),
												'via'      => 'wpcolumns',
											), 'https://twitter.com/intent/tweet' ); ?>" target="_blank">
												<div class="dashicons dashicons-twitter"></div> <?php _e( 'Tweet', 'codepress-admin-columns' ); ?>
											</a>
										</li>

										<li>
											<a href="<?php echo add_query_arg( array(
												'utm_source'   => 'plugin-installation',
												'utm_medium'   => 'feedback-purchase-button',
												'utm_campaign' => 'plugin-installation',
											), ac_get_site_url() ); ?>" target="_blank">
												<div class="dashicons dashicons-cart"></div> <?php _e( 'Buy Pro', 'codepress-admin-columns' ); ?>
											</a>
										</li>
									</ul>
									<div class="clear"></div>
								</div>
							</div>
						</div>

					<?php endif; ?>

					<div class="sidebox" id="plugin-support">
						<h3><?php _e( 'Support', 'codepress-admin-columns' ); ?></h3>

						<div class="inside">
							<?php if ( version_compare( get_bloginfo( 'version' ), '3.2', '>' ) ) : ?>
								<p>
									<?php _e( "Check the <strong>Help</strong> section in the top-right screen.", 'codepress-admin-columns' ); ?>
								</p>
							<?php endif; ?>
							<p>
								<?php printf( __( "For full documentation, bug reports, feature suggestions and other tips <a href='%s'>visit the Admin Columns website</a>", 'codepress-admin-columns' ), ac_get_site_url( 'documentation' ) ); ?>
							</p>
						</div>
					</div><!--plugin-support-->

				</div><!--.columns-right-inside-->
			</div><!--.columns-right-->

			<div class="columns-left">
				<?php if ( ! $list_screen->settings()->get_default_headings() && ! $list_screen->is_using_php_export() ) : ?>
					<div class="cpac-notice">
						<p>
							<?php echo $this->get_error_message_visit_list_screen( $list_screen ); ?>
						</p>
					</div>
				<?php endif ?>

				<?php $this->messages(); ?>

				<div class="ajax-message"><p></p></div>

				<?php if ( $list_screen->is_using_php_export() ) : ?>
					<div class="notice notice-warning below-h2">
						<p><?php printf( __( 'The columns for %s are set up via PHP and can therefore not be edited', 'codepress-admin-columns' ), '<strong>' . esc_html( $list_screen->get_label() ) . '</strong>' ); ?></p>
					</div>
				<?php endif; ?>

				<div class="ac-boxes<?php echo esc_attr( $list_screen->is_using_php_export() ? ' disabled' : '' ); ?>">

					<div class="ac-columns">
						<form method="post" action="<?php echo esc_attr( $this->get_link() ); ?>">

							<input type="hidden" name="cpac_key" value="<?php echo esc_attr( $list_screen->get_key() ); ?>"/>
							<input type="hidden" name="cpac_action" value="update_by_type"/>

							<?php do_action( 'cac/settings/form_columns', $list_screen ); ?>

							<?php wp_nonce_field( 'update-type', '_cpac_nonce' ); ?>

							<?php foreach ( $list_screen->get_columns() as $column ) {
								$this->display_column( $column );
							} ?>
						</form>

					</div><!--.cpac-columns-->

					<div class="column-footer">
						<?php if ( ! $list_screen->is_using_php_export() ) : ?>
							<div class="order-message">
								<?php _e( 'Drag and drop to reorder', 'codepress-admin-columns' ); ?>
							</div>
							<div class="button-container">
								<?php if ( apply_filters( 'ac/settings/enable_clear_columns_button', false ) ) : ?>
									<a href="javascript:;" class="clear-columns" data-clear-columns><?php _e( 'Clear all columns ', 'codepress-admin-columns' ) ?></a>
								<?php endif; ?>

								<span class="spinner"></span>
								<a href="javascript:;" class="button-primary submit update"><?php _e( 'Update' ); ?></a>
								<a href="javascript:;" class="button-primary submit save"><?php _e( 'Save' ); ?></a>
								<a href="javascript:;" class="add_column button">+ <?php _e( 'Add Column', 'codepress-admin-columns' ); ?></a>
							</div>
						<?php endif; ?>
					</div><!--.cpac-column-footer-->

				</div><!--.ac-boxes-->

				<?php do_action( 'cac/settings/after_columns', $list_screen ); ?>

			</div><!--.columns-left-->
			<div class="clear"></div>

			<div id="add-new-column-template">
				<?php
				foreach ( $list_screen->get_column_types() as $column_type ) {
					if ( ! $column_type->is_original() ) {
						$this->display_column( $column_type );
						break;
					}
				}
				?>
			</div>

		</div><!--.columns-container-->

		<div class="clear"></div>

		<?php
	}

	/**
	 * @since 2.0
	 */
	private function display_column( AC_Column $column ) { ?>

		<div class="ac-column ac-<?php echo esc_attr( $column->get_type() ); ?>" data-type="<?php echo esc_attr( $column->get_type() ); ?>" data-clone="<?php echo esc_attr( $column->get_clone() ); ?>" data-original="<?php echo esc_attr( $column->is_original() ); ?>" data-column-name="<?php echo esc_attr( $column->get_name() ); ?>">

			<div class="ac-column-header">
				<table class="widefat">
					<tbody>
					<tr>
						<td class="column_sort">
							<span class="cpacicon-move"></span>
						</td>
						<td class="column_label">
							<div class="inner">
								<div class="meta">
									<?php

									$headers = array();

									foreach ( $column->get_settings() as $setting ) {
										if ( $setting instanceof AC_Settings_HeaderInterface ) {
											$headers[] = $setting->render_header();
										}
									}

									echo implode( "\n", array_filter( $headers ) );

									/**
									 * Fires in the meta-element for column options, which is displayed right after the column label
									 *
									 * @since 2.0
									 *
									 * @param AC_Column $column_instance Column class instance
									 */
									do_action( 'ac/column/header', $column );

									?>
								</div>
								<a class="toggle" data-toggle="column">
									<?php echo $column->get_setting( 'label' )->get_value(); //get_label(); // do not escape ?>
								</a>
								<a class="edit-button" data-toggle="column"><?php _e( 'Edit', 'codepress-admin-columns' ); ?></a>
								<a class="close-button" data-toggle="column"><?php _e( 'Close', 'codepress-admin-columns' ); ?></a>
								<?php if ( ! $column->is_original() ) : ?>
									<a class="clone-button" href="#"><?php _e( 'Clone', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<a class="remove-button"><?php _e( 'Remove', 'codepress-admin-columns' ); ?></a>
							</div>
						</td>
						<td class="column_type">
							<div class="inner" data-toggle="column">
								<?php echo $column->get_label(); ?>
							</div>
						</td>
						<td class="column_edit" data-toggle="column">
						</td>
					</tr>
					</tbody>
				</table>
			</div><!--.column-meta-->

			<div class="ac-column-body">
				<div class="ac-column-settings">

					<?php

					foreach ( $column->get_settings() as $setting ) {
						echo $setting->render() . "\n";
					}

					?>

					<table class="ac-column-setting ac-column-setting-actions">
						<tr>
							<td class="col-label"></td>
							<td class="col-settings">
								<p>
									<a href="#" class="close-button" data-toggle="column"><?php _e( 'Close', 'codepress-admin-columns' ); ?></a>
									<?php if ( ! $column->is_original() ) : ?>
										<a class="clone-button" href="#"><?php _e( 'Clone', 'codepress-admin-columns' ); ?></a>
									<?php endif; ?>
									<a href="#" class="remove-button"><?php _e( 'Remove' ); ?></a>
								</p>
							</td>
						</tr>

					</table>
				</div>
			</div><!--.ac-column-body-->
		</div><!--.ac-column-->
		<?php
	}

}