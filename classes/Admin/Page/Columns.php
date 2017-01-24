<?php

class AC_Admin_Page_Columns extends AC_Admin_Page {

	CONST OPTION_CURRENT = 'cpac_current_model';

	/**
	 * @var array
	 */
	private $notices;

	public function __construct() {

		$this->set_slug( 'columns' )
		     ->set_label( __( 'Admin Columns', 'codepress-admin-columns' ) )
		     ->set_default( true );

		// Requests
		add_action( 'admin_init', array( $this, 'handle_request' ) );
		add_action( 'admin_init', array( $this, 'set_current_list_screen_preference' ) );

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

		wp_enqueue_script( 'ac-admin-page-columns', AC()->get_plugin_url() . "assets/js/admin-page-columns{$minified}.js", array(
			'jquery',
			'dashboard',
			'jquery-ui-slider',
			'jquery-ui-sortable',
			'wp-pointer',
		), AC()->get_version() );

		wp_enqueue_style( 'ac-admin-page-columns-css', AC()->get_plugin_url() . 'assets/css/admin-page-columns' . AC()->minified() . '.css', array(), AC()->get_version(), 'all' );

		// Javascript translations
		wp_localize_script( 'ac-admin-page-columns', 'ac_i18n', array(
			'clone' => __( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
			'error' => __( 'Invalid response.', 'codepress-admin-columns' ),
		) );

		// Nonce
		wp_localize_script( 'ac-admin-page-columns', 'cpac', array(
			'_ajax_nonce' => wp_create_nonce( 'cpac-settings' ),
			'list_screen' => $this->get_current_list_screen()->get_key(),
		) );

		do_action( 'ac/settings_scripts' );
	}

	public function set_current_list_screen_preference() {
		if ( ! AC()->user_can_manage_admin_columns() || ! $this->is_current_screen() ) {
			return;
		}

		// List screen
		if ( $list_screen = filter_input( INPUT_GET, 'cpac_key' ) ) {
			$this->set_list_screen_preference( $list_screen );
		}
	}

	public function set_layout_preference( $layout ) {
		ac_helper()->user->update_meta_site( self::OPTION_CURRENT . '_layout', $layout );
	}

	public function get_layout_preference() {
		return ac_helper()->user->get_meta_site( self::OPTION_CURRENT . '_layout', true );
	}

	/**
	 * @return string
	 */
	private function get_first_available_list_screen() {
		$list_screens = AC()->get_list_screens();

		return array_shift( $list_screens );
	}

	/**
	 * @return AC_ListScreen|false
	 */
	public function get_current_list_screen() {
		$list_screen = AC()->get_list_screen( $this->get_list_screen_preference() );

		if ( ! $list_screen ) {
			$list_screen = $this->get_first_available_list_screen();
		}

		do_action( 'ac/settings/list_screen', $list_screen );

		return $list_screen;
	}

	/**
	 * @since 2.0
	 *
	 * @param AC_ListScreen $list_screen
	 * @param array $columns
	 * @param array $default_columns Default columns heading names.
	 */
	public function store( AC_ListScreen $list_screen, $column_data ) {

		if ( ! $column_data ) {
			return new WP_Error( 'no-settings', __( 'No columns settings available.', 'codepress-admin-columns' ) );
		}

		// TODO: to listscreen?

		$settings = array();

		$current_settings = $list_screen->get_settings();

		foreach ( $column_data as $key => $options ) {
			if ( empty( $options['type'] ) ) {
				continue;
			}

			$column = $list_screen->create_column( $options );

			if ( ! $column ) {
				continue;
			}

			// Skip duplicate original columns
			if ( $column->is_original() ) {
				$types = wp_list_pluck( $settings, 'type' );
				if ( in_array( $column->get_type(), $types, true ) ) {
					continue;
				}
			}

			$sanitized = array();

			// Sanitize data
			foreach ( $column->get_settings() as $setting ) {
				$sanitized += $setting->get_values();
			}

			// Encode site url
			if ( $setting = $column->get_setting( 'label' ) ) {
				$sanitized[ $setting->get_name() ] = $setting->get_encoded_label();
			}

			// New column, new key
			if ( ! in_array( $key, array_keys( $current_settings ), true ) ) {
				$key = uniqid();
			}

			$settings[ $key ] = array_merge( $options, $sanitized );
		}

		// store columns
		$result = $list_screen->store( $settings );

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
	 * @param string $message Message body
	 * @param string $type Updated or error
	 */
	public function notice( $message, $type = 'updated' ) {
		$this->notices[] = '<div class="cpac_message inline ' . esc_attr( $type ) . '"><p>' . $message . '</p></div>';
	}

	/**
	 * @since 1.0
	 */
	public function handle_request() {
		if ( ! AC()->user_can_manage_admin_columns() || ! $this->is_current_screen() ) {
			return;
		}

		switch ( filter_input( INPUT_POST, 'cpac_action' ) ) {

			case 'restore_by_type' :
				if ( $this->verify_nonce( 'restore-type' ) ) {

					$list_screen = $this->get_current_list_screen();
					$list_screen->delete();

					$this->notice( sprintf( __( 'Settings for %s restored successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $this->get_list_screen_message_label( $list_screen ) ) . "</strong>" ), 'updated' );
				}
				break;
		}
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

		if ( ! AC()->user_can_manage_admin_columns() ) {
			wp_die();
		}
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

		$column = $this->get_current_list_screen()->get_column_by_type( $type );

		if ( ! $column ) {
			wp_send_json_error( array(
				'type'  => 'message',
				'error' => $this->get_error_message_visit_list_screen( $this->get_current_list_screen() ),
			) );
		}

		// Not cloneable message
		if ( in_array( $type, $original_columns ) ) {
			wp_send_json_error( array(
				'type'  => 'message',
				'error' => sprintf(
					__( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
					'<strong>' . $column->get_label() . '</strong>' ),
			) );
		}

		// Placeholder message
		if ( $column instanceof AC_Column_PlaceholderInterface ) {
			wp_send_json_error( array(
				'type'  => 'message',
				'error' => $column->get_message(),
			) );
		}

		wp_send_json_success( $this->get_column_display( $column ) );
	}

	/**
	 * @since 2.2
	 */
	public function ajax_column_refresh() {
		$this->ajax_validate_request();

		$options = filter_input( INPUT_POST, 'columns', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$name = filter_input( INPUT_POST, 'column_name' );

		if ( empty( $options[ $name ] ) ) {
			wp_die();
		}

		$column = $this->get_current_list_screen()->create_column( $options[ $name ], $name );

		if ( ! $column ) {
			wp_die();
		}

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

		$stored = $this->store( $this->get_current_list_screen(), $formdata['columns'] );

		if ( is_wp_error( $stored ) ) {
			wp_send_json_error( array(
					'type'    => 'same-settings' === $stored->get_error_code() ? 'notice notice-warning' : 'error',
					'message' => $stored->get_error_message(),
				)
			);
		}

		wp_send_json_success(
			sprintf( __( 'Settings for %s updated successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $this->get_list_screen_message_label( $this->get_current_list_screen() ) ) . "</strong>" )
			. ' <a href="' . esc_attr( $this->get_current_list_screen()->get_screen_link() ) . '">' . esc_html( sprintf( __( 'View %s screen', 'codepress-admin-columns' ), $this->get_current_list_screen()->get_label() ) ) . '</a>'
		);
	}

	private function display_notices() {
		if ( $this->notices ) {
			echo implode( $this->notices );
		}
	}

	public function sort_by_label( $a, $b ) {
		return strcmp( $a->label, $b->label );
	}

	/**
	 * @return array
	 */
	private function get_grouped_list_screens() {
		$list_screens = array();

		foreach ( AC()->get_list_screens() as $list_screen ) {

			/**
			 * @param string $group Group slug
			 * @param string $key Listscreen key
			 */
			$group = apply_filters( 'ac/list_screen_group', $list_screen->get_group(), $list_screen->get_key() );

			$list_screens[ $group ][ $list_screen->get_key() ] = $list_screen->get_label();
		}

		$grouped = array();

		foreach ( AC()->list_screen_groups()->get_groups_sorted() as $group ) {
			$slug = $group['slug'];

			if ( empty( $list_screens[ $slug ] ) ) {
				continue;
			}

			if ( ! isset( $grouped[ $slug ] ) ) {
				$grouped[ $slug ]['title'] = $group['label'];
			}

			$grouped[ $slug ]['options'] = $list_screens[ $slug ];

			unset( $list_screens[ $slug ] );
		}

		return $grouped;
	}

	private function set_list_screen_preference( $list_screen_key ) {
		ac_helper()->user->update_meta_site( self::OPTION_CURRENT, $list_screen_key );
	}

	private function get_list_screen_preference() {
		return ac_helper()->user->get_meta_site( self::OPTION_CURRENT, true );
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
		$list_screen = $this->get_current_list_screen();
		?>

        <div class="columns-container<?php echo $list_screen->get_settings() ? ' stored' : ''; ?>" data-type="<?php echo esc_attr( $list_screen->get_key() ); ?>">
            <div class="main">
                <div class="menu">
                    <form>
						<?php $this->nonce_field( 'select-list-screen' ); ?>
                        <input type="hidden" name="page" value="<?php echo esc_attr( AC_Admin::MENU_SLUG ); ?>">

                        <select name="cpac_key" title="Select type" id="ac_list_screen">
							<?php foreach ( $this->get_grouped_list_screens() as $group ) : ?>
                                <optgroup label="<?php echo esc_attr( $group['title'] ); ?>">
									<?php foreach ( $group['options'] as $key => $label ) : ?>
                                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $list_screen->get_key() ); ?>><?php echo esc_html( $label ); ?></option>
									<?php endforeach; ?>
                                </optgroup>
							<?php endforeach; ?>
                        </select>
                        <span class="spinner"></span>

						<?php if ( $link = $list_screen->get_screen_link() ) : ?>
                            <a href="<?php echo esc_url( $link ); ?>" class="page-title-action view-link"><?php echo esc_html__( 'View', 'codepress-admin-columns' ); ?></a>
						<?php endif; ?>
                    </form>
                </div>

				<?php do_action( 'ac/settings/after_title', $list_screen ); ?>

            </div>

            <div class="columns-right">
                <div class="columns-right-inside">

					<?php if ( ! $list_screen->is_read_only() ) : ?>
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
                                <a class="button-primary submit update"><?php _e( 'Update' ); ?></a>
                                <a class="button-primary submit save"><?php _e( 'Save' ); ?></a>
                            </div>

                            <form class="form-reset" method="post">
                                <input type="hidden" name="cpac_key" value="<?php echo esc_attr( $list_screen->get_key() ); ?>"/>
                                <input type="hidden" name="cpac_action" value="restore_by_type"/>

								<?php $this->nonce_field( 'restore-type' ); ?>

								<?php $onclick = AC()->use_delete_confirmation() ? ' onclick="return confirm(\'' . esc_js( sprintf( __( "Warning! The %s columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ), "'" . $this->get_list_screen_message_label( $list_screen ) . "'" ) ) . '\');"' : ''; ?>
                                <input class="reset-column-type" type="submit"<?php echo $onclick; ?> value="<?php _e( 'Restore columns', 'codepress-admin-columns' ); ?>">
                                <span class="spinner"></span>
                            </form>

							<?php do_action( 'cac/settings/form_actions', $list_screen ); ?>

                        </div><!--form-actions-->
					<?php endif; ?>

					<?php do_action( 'ac/settings/sidebox', $list_screen ); ?>

					<?php if ( apply_filters( 'ac/show_banner', true ) ) : ?>

						<?php $active_promotion = $this->get_active_promotion(); ?>

                        <div class="sidebox" id="ac-pro-version">
                            <div class="padding-box">
                                <h3>
                                    <a href="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'banner', 'title' ) ); ?>">
										<?php _e( 'Upgrade to', 'codepress-admin-columns' ); ?>&nbsp;<span><?php _e( 'Pro', 'codepress-admin-columns' ); ?></span>
                                    </a>
                                </h3>

                                <div class="inside">
                                    <p><?php _e( 'Take Admin Columns to the next level:', 'codepress-admin-columns' ); ?></p>
                                    <ul>

										<?php

										$items = array(
											'sorting'       => __( 'Add sortable columns', 'codepress-admin-columns' ),
											'filtering'     => __( 'Add filterable columns', 'codepress-admin-columns' ),
											'editing'       => __( 'Edit your column content directly', 'codepress-admin-columns' ),
											'column-sets'   => __( 'Create multiple columns sets', 'codepress-admin-columns' ),
											'import-export' => __( 'Import &amp; Export settings', 'codepress-admin-columns' ),
										);

										foreach ( $items as $utm_content => $label ) : ?>
                                            <li>
                                                <a href="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'banner', 'usp-' . $utm_content ) ); ?>">
													<?php echo esc_html( $label ); ?>
                                                </a>
                                            </li>
										<?php endforeach; ?>

										<?php foreach ( AC()->addons()->get_addons_promo() as $addon ) : ?>
                                            <li class="acp-integration">
                                                <a href="<?php echo esc_url( $addon->get_link() ); ?>">
                                                    <img src="<?php echo esc_attr( $addon->get_image_url() ); ?>" alt="<?php echo esc_attr( $addon->get_title() ); ?>"> <?php _e( 'Columns', 'codepress-admin-columns' ); ?>
                                                </a>
                                            </li>
										<?php endforeach; ?>

                                    </ul>

                                    <p class="center nopadding">
										<?php if ( ! $active_promotion ) : ?>
                                            <a target="_blank" href="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'banner' ) ); ?>" class="more">
												<?php _e( 'Learn more about Pro', 'codepress-admin-columns' ); ?>
                                            </a>
										<?php endif; ?>
                                    </p>
                                </div>
                            </div>

							<?php if ( $active_promotion ) : ?>

                                <div class="padding-box ac-pro-deal">
									<?php $active_promotion->display(); ?>
                                </div>

							<?php else : ?>

                                <div class="padding-box ac-pro-newsletter">
                                    <h3>
										<?php echo esc_html( sprintf( __( 'Get %s Off!', 'codepress-admin-columns' ), '20%' ) ); ?>
                                    </h3>
                                    <div class="inside">
                                        <p>
											<?php echo esc_html( sprintf( __( "Submit your email and we'll send you a coupon for %s off.", 'codepress-admin-columns' ), '20%' ) ); ?>
                                        </p>
										<?php
										$user_data = get_userdata( get_current_user_id() );
										?>
                                        <form method="post" action="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'send-coupon' ) ); ?>" target="_blank">
                                            <input name="action" type="hidden" value="mc_upgrade_pro">
                                            <input name="EMAIL" placeholder="<?php esc_attr_e( "Your Email", 'codepress-admin-columns' ); ?>" value="<?php echo esc_attr( $user_data->user_email ); ?>">
                                            <input name="FNAME" placeholder="<?php esc_attr_e( "Your First Name", 'codepress-admin-columns' ); ?>">
                                            <input type="submit" value="<?php esc_attr_e( "Send me the coupon", 'codepress-admin-columns' ); ?>" class="acp-button">
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
                                    <p>
										<?php _e( "What's wrong? Need help? Let us know!", 'codepress-admin-columns' ); ?>
                                    </p>
                                    <p>
										<?php _e( 'Check out our extensive documentation, or you can open a support topic on WordPress.org!', 'codepress-admin-columns' ); ?>
                                    </p>
                                    <ul class="share">
                                        <li>
                                            <a href="<?php echo esc_url( ac_get_site_utm_url( 'documentation', 'feedback-docs-button' ) ); ?>" target="_blank">
                                                <div class="dashicons dashicons-editor-help"></div> <?php _e( 'Docs', 'codepress-admin-columns' ); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://wordpress.org/support/plugin/codepress-admin-columns" target="_blank">
                                                <div class="dashicons dashicons-wordpress"></div> <?php _e( 'Forums', 'codepress-admin-columns' ); ?>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div id="feedback-rate">
                                <div class="inside">
                                    <p>
										<?php _e( "Woohoo! We're glad to hear that!", 'codepress-admin-columns' ); ?>
                                    </p>
                                    <p>
										<?php _e( 'We would really love it if you could show your appreciation by giving us a rating on WordPress.org or tweet about Admin Columns!', 'codepress-admin-columns' ); ?>
                                    </p>
                                    <ul class="share">
                                        <li>
                                            <a href="http://wordpress.org/support/view/plugin-reviews/codepress-admin-columns#postform" target="_blank">
                                                <div class="dashicons dashicons-star-empty"></div> <?php _e( 'Rate', 'codepress-admin-columns' ); ?>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?php echo esc_url( add_query_arg( array( 'hashtags' => 'admincolumns', 'text' => urlencode( __( "I'm using Admin Columns for WordPress!", 'codepress-admin-columns' ) ), 'url' => urlencode( 'http://wordpress.org/plugins/codepress-admin-columns/' ), 'via' => ac_get_twitter_handle() ), 'https://twitter.com/intent/tweet' ) ); ?>" target="_blank">
                                                <div class="dashicons dashicons-twitter"></div> <?php _e( 'Tweet', 'codepress-admin-columns' ); ?>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'feedback-purchase-button' ) ); ?>" target="_blank">
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
								<?php printf( __( "For full documentation, bug reports, feature suggestions and other tips <a href='%s'>visit the Admin Columns website</a>", 'codepress-admin-columns' ), ac_get_site_utm_url( 'documentation', 'support' ) ); ?>
                            </p>
                        </div>
                    </div><!--plugin-support-->

                </div><!--.columns-right-inside-->
            </div><!--.columns-right-->

            <div class="columns-left">
				<?php if ( ! $list_screen->get_stored_default_headings() && ! $list_screen->is_read_only() ) : ?>
                    <div class="cpac-notice">
                        <p>
							<?php echo $this->get_error_message_visit_list_screen( $list_screen ); ?>
                        </p>
                    </div>
				<?php endif ?>

				<?php $this->display_notices(); ?>

                <div class="ajax-message"><p></p></div>

				<?php if ( $list_screen->is_read_only() ) : ?>
                    <div class="notice notice-warning below-h2">
                        <p><?php printf( __( 'The columns for %s are set up via PHP and can therefore not be edited', 'codepress-admin-columns' ), '<strong>' . esc_html( $list_screen->get_label() ) . '</strong>' ); ?></p>
                    </div>
				<?php endif; ?>

                <div class="ac-boxes<?php echo esc_attr( $list_screen->is_read_only() ? ' disabled' : '' ); ?>">

                    <div class="ac-columns">
                        <form method="post" action="<?php echo esc_attr( $this->get_link() ); ?>">

                            <input type="hidden" name="cpac_key" value="<?php echo esc_attr( $list_screen->get_key() ); ?>"/>
                            <input type="hidden" name="cpac_action" value="update_by_type"/>

							<?php $this->nonce_field( 'update-type' ); ?>

							<?php foreach ( $list_screen->get_columns() as $column ) {
								$this->display_column( $column );
							} ?>
                        </form>

                    </div><!--.cpac-columns-->

                    <div class="column-footer">
						<?php if ( ! $list_screen->is_read_only() ) : ?>
                            <div class="order-message">
								<?php _e( 'Drag and drop to reorder', 'codepress-admin-columns' ); ?>
                            </div>
                            <div class="button-container">
								<?php if ( apply_filters( 'ac/settings/enable_clear_columns_button', false ) ) : ?>
                                    <a class="clear-columns" data-clear-columns><?php _e( 'Clear all columns ', 'codepress-admin-columns' ) ?></a>
								<?php endif; ?>

                                <span class="spinner"></span>
                                <a class="button-primary submit update"><?php _e( 'Update' ); ?></a>
                                <a class="button-primary submit save"><?php _e( 'Save' ); ?></a>
                                <a class="add_column button">+ <?php _e( 'Add Column', 'codepress-admin-columns' ); ?></a>
                            </div>
						<?php endif; ?>
                    </div><!--.cpac-column-footer-->

                </div><!--.ac-boxes-->

				<?php do_action( 'ac/settings/after_columns', $list_screen ); ?>

            </div><!--.columns-left-->
            <div class="clear"></div>

            <div id="add-new-column-template">
				<?php $this->display_column_template( $list_screen ); ?>
            </div>

        </div><!--.columns-container-->

        <div class="clear"></div>

		<?php
	}

	/**
	 * Get first custom group column
	 */
	private function display_column_template( AC_ListScreen $list_screen ) {
		$columns = array();

		foreach ( $list_screen->get_column_types() as $column_type ) {
			if ( 'custom' === $column_type->get_group() ) {
				$columns[ $column_type->get_label() ] = $column_type;
			}
		}

		array_multisort( array_keys( $columns ), SORT_NATURAL, $columns );

		/** @var AC_Column $column */
		$column = array_shift( $columns );

		$this->display_column( $column );
	}

	/**
	 * @since 2.0
	 */
	public function display_column( AC_Column $column ) { ?>

        <div class="ac-column ac-<?php echo esc_attr( $column->get_type() ); ?>"
                data-type="<?php echo esc_attr( $column->get_type() ); ?>"
                data-original="<?php echo esc_attr( $column->is_original() ); ?>"
                data-column-name="<?php echo esc_attr( $column->get_name() ); ?>">

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

									foreach ( $column->get_settings() as $setting ) {
										if ( $setting instanceof AC_Settings_HeaderInterface ) {
											echo $setting->render_header();
										}
									}

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
									<?php echo $column->get_setting( 'label' )->get_value(); // do not escape ?>
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
								<?php echo ac_helper()->html->strip_attributes( $column->get_label(), array( 'style', 'class' ) ); ?>
                            </div>
                        </td>
                        <td class="column_edit" data-toggle="column">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

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
            </div>
        </div>
		<?php
	}

}