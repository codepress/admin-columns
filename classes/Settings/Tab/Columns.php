<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Tab_Columns extends AC_Settings_TabAbstract {

	CONST OPTION_CURRENT = 'cpac_current_model';

	public function __construct() {
		$this
			->set_slug( 'columns' )
			->set_label( __( 'Admin Columns', 'codepress-admin-columns' ) )
			->set_default( true );

		add_action( 'admin_init', array( $this, 'handle_column_request' ) );

		add_action( 'wp_ajax_cpac_column_refresh', array( $this, 'ajax_column_refresh' ) );
		add_action( 'wp_ajax_cpac_columns_update', array( $this, 'ajax_columns_save' ) );
	}

	/**
	 * Restore all column defaults
	 *
	 * @since 1.0
	 */
	private function restore_all() {
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'cpac_options_%'" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'cpac_layouts%'" );

		cpac_admin_message( __( 'Default settings succesfully restored.', 'codepress-admin-columns' ), 'updated' );
	}

	/**
	 * @since 2.0
	 *
	 * @param AC_StorageModel $storage_model
	 * @param array $columns
	 * @param array $default_columns Default columns heading names.
	 */
	private function store( AC_StorageModel $storage_model, $column_data ) {

		if ( ! $column_data ) {
			return new WP_Error( 'no-settings', __( 'No columns settings available.', 'codepress-admin-columns' ) );
		}

		// sanitize user inputs
		foreach ( $column_data as $name => $options ) {
			if ( $column = $storage_model->get_column_by_name( $name ) ) {

				if ( ! empty( $options['label'] ) ) {

					// Label can not contains the character ":"" and "'", because
					// CPAC_Column::get_sanitized_label() will return an empty string
					// and make an exception for site_url()
					// Enable data:image url's
					if ( false === strpos( $options['label'], site_url() ) && false === strpos( $options['label'], 'data:' ) ) {
						$options['label'] = str_replace( ':', '', $options['label'] );
						$options['label'] = str_replace( "'", '', $options['label'] );
					}
				}

				if ( isset( $options['width'] ) ) {
					$options['width'] = is_numeric( $options['width'] ) ? trim( $options['width'] ) : '';
				}

				if ( isset( $options['date_format'] ) ) {
					$options['date_format'] = trim( $options['date_format'] );
				}

				$column_data[ $name ] = $column->sanitize_options( $options );

				// Sanitize Label: Need to replace the url for images etc, so we do not have url problem on exports
				// this can not be done by CPAC_Column::sanitize_storage() because 3rd party plugins are not available there
				if ( isset( $column_data[ $name ]['label'] ) ) {
					$column_data[ $name ]['label'] = stripslashes( str_replace( site_url(), '[cpac_site_url]', trim( $column_data[ $name ]['label'] ) ) );
				}
			}
		}

		// store columns
		$settings = new AC_Settings( $storage_model->get_key(), $storage_model->layouts()->get_layout() );
		$result = $settings->store( $column_data );

		// reset object
		$storage_model->flush_columns();

		if ( ! $result ) {
			return new WP_Error( 'same-settings', sprintf( __( 'You are trying to store the same settings for %s.', 'codepress-admin-columns' ), "<strong>" . $storage_model->layouts()->get_label_or_layout_name() . "</strong>" ) );
		}

		/**
		 * Fires after a new column setup is stored in the database
		 * Primarily used when columns are saved through the Admin Columns settings screen
		 *
		 * @since 2.2.9
		 *
		 * @param array $columns List of columns ([column id] => (array) [column properties])
		 * @param AC_StorageModel $storage_model_instance Storage model instance
		 */
		do_action( 'cac/storage_model/columns_stored', $columns, $storage_model );

		return true;
	}

	/**
	 * @since 1.0
	 */
	public function handle_column_request() {

		$action = filter_input( INPUT_POST, 'cpac_action' );
		$nonce = filter_input( INPUT_POST, '_cpac_nonce' );

		if ( ! $action || ! $nonce || ! cac_is_setting_screen() || ! current_user_can( 'manage_admin_columns' ) ) {
			return;
		}

		switch ( $action ) :

			case 'restore_by_type' :
				$key = filter_input( INPUT_POST, 'cpac_key' );

				if ( $key && wp_verify_nonce( $nonce, 'restore-type' ) ) {
					if ( $storage_model = cpac()->get_storage_model( $key ) ) {

						// TODO: user AC_Settings
						if ( isset( $_POST['cpac_layout'] ) ) {
							$storage_model->layouts()->set_layout( $_POST['cpac_layout'] );
						}

						delete_option( $storage_model->get_storage_id() );

						$storage_model->flush_columns();

						cpac_settings_message( sprintf( __( 'Settings for %s restored successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $storage_model->layouts()->get_label_or_layout_name() ) . "</strong>" ), 'updated' );
					}
				}
				break;

			case 'restore_all' :
				if ( wp_verify_nonce( $nonce, 'restore-all' ) ) {
					$this->restore_all();
				}
				break;

		endswitch;
	}

	/**
	 * @since 2.2
	 */
	public function ajax_column_refresh() {
		check_ajax_referer( 'cpac-settings' );

		if ( ! current_user_can( 'manage_admin_columns' ) ) {
			wp_die();
		}

		$column_name = filter_input( INPUT_POST, 'column' );

		if ( empty( $_POST['formdata'] ) || ! $column_name || ! isset( $_POST['layout'] ) ) {
			wp_die();
		}

		parse_str( $_POST['formdata'], $formdata );

		if ( empty( $formdata['cpac_key'] ) ) {
			wp_die();
		}

		$storage_model = cpac()->get_storage_model( $formdata['cpac_key'] );

		if ( ! $storage_model ) {
			wp_die();
		}

		$storage_model->layouts()->set_layout( $_POST['layout'] );

		if ( empty( $formdata[ $storage_model->key ][ $column_name ] ) ) {
			wp_die();
		}

		$columndata = $formdata[ $storage_model->key ][ $column_name ];

		$column = $storage_model->create_column_instance( $columndata['type'], $columndata['clone'] );

		if ( ! $column ) {
			wp_die();
		}

		// Add stored options; Used by columns that switch field types.
		$column->set_stored_options( $columndata );

		// Only trigger for newly added columns.
		// TODO: make separate ajax call for "Add column".
		if ( ! isset( $columndata['label'] ) ) {
			$column->set_default_option( 'label', $column->get_type_label() );
		}

		ob_start();
		$this->display_column( $column );
		wp_send_json_success( ob_get_clean() );
	}

	/**
	 * @since 2.5
	 */
	public function ajax_columns_save() {
		check_ajax_referer( 'cpac-settings' );

		if ( ! current_user_can( 'manage_admin_columns' ) ) {
			wp_die();
		}

		$storage_model = cpac()->get_storage_model( filter_input( INPUT_POST, 'storage_model' ) );

		if ( ! $storage_model ) {
			wp_die();
		}

		$storage_model->layouts()->set_layout( filter_input( INPUT_POST, 'layout' ) );

		parse_str( $_POST['data'], $formdata );

		if ( ! isset( $formdata[ $storage_model->key ] ) ) {
			wp_send_json_error( array(
					'type'    => 'error',
					'message' => __( 'You need at least one column', 'codepress-admin-columns' ),
				)
			);
		}

		$stored = $this->store( $storage_model, $formdata[ $storage_model->key ] );

		if ( is_wp_error( $stored ) ) {
			wp_send_json_error( array(
					'type'    => 'same-settings' === $stored->get_error_code() ? 'notice notice-warning' : 'error',
					'message' => $stored->get_error_message(),
				)
			);
		}

		wp_send_json_success(
			sprintf( __( 'Settings for %s updated successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $storage_model->layouts()->get_label_or_layout_name() ) . "</strong>" )
			. ' <a href="' . esc_attr( $storage_model->get_link() ) . '">' . esc_html( sprintf( __( 'View %s screen', 'codepress-admin-columns' ), $storage_model->label ) ) . '</a>'
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
		foreach ( cpac()->get_storage_models() as $_storage_model ) {
			$grouped[ $_storage_model->get_menu_type() ][] = (object) array(
				'key'   => $_storage_model->key,
				'link'  => $_storage_model->settings_url(),
				'label' => $_storage_model->label,
			);
			usort( $grouped[ $_storage_model->get_menu_type() ], array( $this, 'sort_by_label' ) );
		}

		return $grouped;
	}

	private function set_user_model_preference( $storage_model_key ) {
		update_user_meta( get_current_user_id(), self::OPTION_CURRENT, $storage_model_key );
	}

	private function get_user_model_preference() {
		return cpac()->get_storage_model( get_user_meta( get_current_user_id(), self::OPTION_CURRENT, true ) );
	}

	/**
	 * Initialize current storage model
	 *
	 * @return bool|AC_StorageModel
	 */
	private function get_settings_storage_model() {

		if ( isset( $_REQUEST['cpac_key'] ) ) {

			// By request
			if ( $_storage_model = cpac()->get_storage_model( $_REQUEST['cpac_key'] ) ) {
				$storage_model = $_storage_model;
			} // User preference
			else if ( $_storage_model = $this->get_user_model_preference() ) {
				$storage_model = $_storage_model;
			} // First one served
			else {
				$storage_model = cpac()->get_first_storage_model();
			}

			$this->set_user_model_preference( $storage_model->key );
		}
		else {

			// User preference
			if ( $exists = $this->get_user_model_preference() ) {
				$storage_model = $exists;
			} // First one served
			else {
				$storage_model = cpac()->get_first_storage_model();
			}
		}

		$storage_model->layouts()->init_settings_layout();

		return $storage_model;
	}

	public function display() {
		$storage_model = $this->get_settings_storage_model();
		?>

		<div class="columns-container<?php echo $storage_model->has_stored_columns() ? ' stored' : ''; ?>" data-type="<?php echo esc_attr( $storage_model->get_key() ); ?>" data-layout="<?php echo esc_attr( $storage_model->layouts()->get_layout() ); ?>">
			<div class="main">
				<div class="menu">
					<select title="Select type" id="cpac_storage_modal_select">
						<?php foreach ( $this->get_grouped_models() as $menu_type => $models ) : ?>
							<optgroup label="<?php echo esc_attr( $menu_type ); ?>">
								<?php foreach ( $models as $model ) : ?>
									<option value="<?php echo esc_attr( $model->link ); ?>" <?php selected( $model->key, $storage_model->get_key() ); ?>><?php echo esc_html( $model->label ); ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
					<span class="spinner"></span>

					<?php $storage_model->screen_link(); ?>
				</div>

				<?php do_action( 'cac/settings/after_title', $storage_model ); ?>

			</div>

			<div class="columns-right">
				<div class="columns-right-inside">
					<?php if ( ! $storage_model->is_using_php_export() ) : ?>
						<div class="sidebox form-actions">
							<?php $label = __( 'Store settings', 'codepress-admin-columns' ); ?>
							<h3>
								<span class="left"><?php echo esc_html( $label ); ?></span>
								<?php if ( 18 > strlen( $label ) && ( $truncated_label = $storage_model->get_truncated_side_label( $label ) ) ) : ?>
									<span class="right contenttype"><?php echo esc_html( $truncated_label ); ?></span>
								<?php else : ?>
									<span class="clear contenttype"><?php echo esc_html( $storage_model->label ); ?></span>
								<?php endif; ?>
							</h3>

							<div class="form-update">
								<a href="javascript:;" class="button-primary submit update"><?php _e( 'Update' ); ?></a>
								<a href="javascript:;" class="button-primary submit save"><?php _e( 'Save' ); ?></a>
							</div>

							<form class="form-reset" method="post">
								<input type="hidden" name="cpac_key" value="<?php echo esc_attr( $storage_model->get_key() ); ?>"/>
								<input type="hidden" name="cpac_action" value="restore_by_type"/>
								<input type="hidden" name="cpac_layout" value="<?php echo esc_attr( $storage_model->layouts()->get_layout() ); ?>"/>
								<?php wp_nonce_field( 'restore-type', '_cpac_nonce' ); ?>

								<?php $onclick = cpac()->use_delete_confirmation() ? ' onclick="return confirm(\'' . esc_js( sprintf( __( "Warning! The %s columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ), "'" . $storage_model->layouts()->get_label_or_layout_name() . "'" ) ) . '\');"' : ''; ?>
								<input class="reset-column-type" type="submit"<?php echo $onclick; ?> value="<?php _e( 'Restore columns', 'codepress-admin-columns' ); ?>">
								<span class="spinner"></span>
							</form>

							<?php do_action( 'cac/settings/form_actions', $storage_model ); ?>

						</div><!--form-actions-->
					<?php endif; ?>

					<?php do_action( 'cac/settings/sidebox', $storage_model ); ?>

					<?php if ( ! cpac_is_pro_active() ) : ?>

						<?php $url_args = array(
							'utm_source'   => 'plugin-installation',
							'utm_medium'   => 'banner',
							'utm_campaign' => 'plugin-installation',
						); ?>
						<div class="sidebox" id="pro-version">
							<div class="padding-box cta">
								<h3>
									<a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'title' ) ), ac_get_site_url() ); ?>"><?php _e( 'Get Admin Columns Pro', 'codepress-admin-columns' ) ?></a>
								</h3>

								<div class="inside">
									<ul>
										<li>
											<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-sorting' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><?php _e( 'Add Sorting', 'codepress-admin-columns' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-filtering' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><?php _e( 'Add Filtering', 'codepress-admin-columns' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-import-export' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><?php _e( 'Add Import/Export', 'codepress-admin-columns' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-editing' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><?php _e( 'Add Inline Edit', 'codepress-admin-columns' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-columns-sets' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ); ?>"><?php _e( 'Multiple Column Sets', 'codepress-admin-columns' ); ?></a>
										</li>
									</ul>
									<p>
										<?php printf( __( "Check out <a href='%s'>Admin Columns Pro</a> for more details!", 'codepress-admin-columns' ), add_query_arg( array_merge( $url_args, array( 'utm_content' => 'cta' ) ), ac_get_site_url() ) ); ?>
									</p>
								</div>
							</div>
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
									<?php _e( 'Check the <strong>Help</strong> section in the top-right screen.', 'codepress-admin-columns' ); ?>
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
				<?php if ( ! $storage_model->settings()->get_default_headings() && ! $storage_model->is_using_php_export() ): ?>
					<div class="cpac-notice">
						<p>
							<?php echo sprintf( __( 'Please visit the %s screen once to load all available columns', 'codepress-admin-columns' ), "<a href='" . esc_url( $storage_model->get_link() ) . "'>" . esc_html( $storage_model->label ) . "</a>" ); ?>
						</p>
					</div>
				<?php endif ?>

				<?php $this->messages(); ?>

				<div class="ajax-message"><p></p></div>

				<?php if ( $storage_model->is_using_php_export() ) : ?>
					<div class="notice notice-warning below-h2">
						<p><?php printf( __( 'The columns for %s are set up via PHP and can therefore not be edited', 'codepress-admin-columns' ), '<strong>' . esc_html( $storage_model->label ) . '</strong>' ); ?></p>
					</div>
				<?php endif; ?>

				<div class="cpac-boxes<?php echo esc_attr( $storage_model->is_using_php_export() ? ' disabled' : '' ); ?>">

					<div class="cpac-columns">
						<form method="post" action="<?php echo esc_attr( $storage_model->get_edit_link() ); ?>">

							<input type="hidden" name="cpac_key" value="<?php echo esc_attr( $storage_model->get_key() ); ?>"/>
							<input type="hidden" name="cpac_action" value="update_by_type"/>
							<input type="hidden" name="cpac_layout" value="<?php echo esc_attr( $storage_model->layouts()->get_layout() ); ?>"/>

							<?php do_action( 'cac/settings/form_columns', $storage_model ); ?>

							<?php wp_nonce_field( 'update-type', '_cpac_nonce' ); ?>

							<?php
							foreach ( $storage_model->get_columns() as $column ) {
								$this->display_column( $column );
							}
							?>
						</form>

					</div><!--.cpac-columns-->

					<div class="column-footer">
						<?php if ( ! $storage_model->is_using_php_export() ) : ?>
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

				</div><!--.cpac-boxes-->

				<?php do_action( 'cac/settings/after_columns', $storage_model ); ?>

			</div><!--.columns-left-->
			<div class="clear"></div>

			<div class="for-cloning-only" style="display:none">
				<?php
				foreach ( $storage_model->get_column_types() as $column ) {
					$this->display_column( $column );
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
	private function display_column( CPAC_Column $column ) {
		?>

		<div class="cpac-column <?php echo esc_attr( implode( ' ', array_filter( array( "cpac-box-" . $column->get_type(), $column->get_property( 'classes' ) ) ) ) ); ?>" data-type="<?php echo esc_attr( $column->get_type() ); ?>"<?php echo $column->get_property( 'is_cloneable' ) ? ' data-clone="' . esc_attr( $column->get_property( 'clone' ) ) . '"' : ''; ?> data-default="<?php echo esc_attr( $column->is_default() ); ?>">
			<input type="hidden" class="column-name" name="<?php $column->field_settings->attr_name( 'column-name' ); ?>" value="<?php echo esc_attr( $column->get_name() ); ?>"/>
			<input type="hidden" class="type" name="<?php $column->field_settings->attr_name( 'type' ); ?>" value="<?php echo esc_attr( $column->get_type() ); ?>"/>
			<input type="hidden" class="clone" name="<?php $column->field_settings->attr_name( 'clone' ); ?>" value="<?php echo esc_attr( $column->get_property( 'clone' ) ); ?>"/>

			<div class="column-meta">
				<table class="widefat">
					<tbody>
					<tr>
						<td class="column_sort">
							<span class="cpacicon-move"></span>
						</td>
						<td class="column_label">
							<div class="inner">
								<div class="meta">

									<span title="<?php echo esc_attr( __( 'width', 'codepress-admin-columns' ) ); ?>" class="width" data-indicator-id="">
										<?php echo $column->get_width() ? esc_html( $column->get_width() . $column->get_width_unit() ) : ''; ?>
									</span>

									<?php
									/**
									 * Fires in the meta-element for column options, which is displayed right after the column label
									 *
									 * @since 2.0
									 *
									 * @param CPAC_Column $column_instance Column class instance
									 */
									do_action( 'cac/column/settings_meta', $column );

									/**
									 * @deprecated 2.2 Use cac/column/settings_meta instead
									 */
									do_action( 'cac/column/label', $column );
									?>

								</div>
								<a class="toggle" href="javascript:;"><?php echo $column->get_label(); // do not escape ?></a>
								<a class="edit-button" href="javascript:;"><?php _e( 'Edit', 'codepress-admin-columns' ); ?></a>
								<a class="close-button" href="javascript:;"><?php _e( 'Close', 'codepress-admin-columns' ); ?></a>
								<?php if ( $column->get_property( 'is_cloneable' ) ) : ?>
									<a class="clone-button" href="#"><?php _e( 'Clone', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<a class="remove-button" href="javascript:;"><?php _e( 'Remove', 'codepress-admin-columns' ); ?></a>
							</div>
						</td>
						<td class="column_type">
							<div class="inner">
								<a href="#"><?php echo $column->get_type_label(); // do not escape ?></a>
							</div>
						</td>
						<td class="column_edit">
						</td>
					</tr>
					</tbody>
				</table>
			</div><!--.column-meta-->

			<div class="column-form">
				<table class="widefat">
					<tbody>

					<?php
					$column->field_settings->field( array(
						'type'            => 'select',
						'name'            => 'type',
						'label'           => __( 'Type', 'codepress-admin-columns' ),
						'description'     => __( 'Choose a column type.', 'codepress-admin-columns' ) . '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $column->get_type() . '</em><em>' . __( 'Name', 'codepress-admin-columns' ) . ': ' . $column->get_name() . '</em>',
						'grouped_options' => $column->get_storage_model()->get_grouped_columns(),
						'default'         => $column->get_type(),
					) );

					$column->field_settings->field( array(
						'type'        => 'text',
						'name'        => 'label',
						'placeholder' => $column->get_type_label(),
						'label'       => __( 'Label', 'codepress-admin-columns' ),
						'description' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
						'hidden'      => $column->get_property( 'hide_label' ),
					) );

					$column->field_settings->field( array(
						'type'  => 'width',
						'name'  => 'width',
						'label' => __( 'Width', 'codepress-admin-columns' ),
					) );

					/**
					 * Fires directly before the custom options for a column are displayed in the column form
					 *
					 * @since 2.0
					 *
					 * @param CPAC_Column $column_instance Column class instance
					 */
					do_action( 'cac/column/settings_before', $column );

					/**
					 * Load specific column settings.
					 *
					 */
					$column->display_settings();

					if ( $column->get_property( 'use_before_after' ) ) {
						$column->field_settings->before_after();
					}

					/**
					 * Fires directly after the custom options for a column are displayed in the column form
					 *
					 * @since 2.0
					 *
					 * @param CPAC_Column $column_instance Column class instance
					 */
					do_action( 'cac/column/settings_after', $column );
					?>

					<tr class="column_action section">
						<td class="label"></td>
						<td class="input">
							<p>
								<a href="#" class="close-button"><?php _e( 'Close', 'codepress-admin-columns' ); ?></a>
								<?php if ( $column->get_property( 'is_cloneable' ) ) : ?>
									<a class="clone-button" href="#"><?php _e( 'Clone', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<a href="#" class="remove-button"><?php _e( 'Remove' ); ?></a>
							</p>
						</td>
					</tr>

					</tbody>
				</table>
			</div><!--.column-form-->
		</div><!--.cpac-column-->
		<?php
	}

}