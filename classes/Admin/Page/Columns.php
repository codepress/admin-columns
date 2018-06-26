<?php

namespace AC\Admin\Page;

use AC\Admin;
use AC\Admin\Page;
use AC\Admin\Promo;
use AC\Autoloader;
use AC\Capabilities;
use AC\Column;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenGroups;
use AC\Preferences;
use AC\Settings;

class Columns extends Page {

	/**
	 * @var array
	 */
	private $notices;

	/**
	 * @var ListScreen
	 */
	private $current_list_screen;

	public function __construct() {
		$this->set_slug( 'columns' )
		     ->set_label( __( 'Admin Columns', 'codepress-admin-columns' ) )
		     ->set_default( true );
	}

	public function register() {
		add_action( 'current_screen', array( $this, 'set_current_list_screen' ) );
		add_action( 'admin_init', array( $this, 'handle_request' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_footer', array( $this, 'display_modal' ) );

		// Ajax calls
		add_action( 'wp_ajax_ac_column_select', array( $this, 'ajax_column_select' ) );
		add_action( 'wp_ajax_ac_column_refresh', array( $this, 'ajax_column_refresh' ) );
		add_action( 'wp_ajax_ac_columns_save', array( $this, 'ajax_columns_save' ) );
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		if ( ! $this->is_current_screen() ) {
			return;
		}

		$list_screen = $this->get_current_list_screen();

		if ( ! $list_screen ) {
			return;
		}

		// Width slider
		wp_enqueue_style( 'jquery-ui-lightness', AC()->get_url() . 'assets/ui-theme/jquery-ui-1.8.18.custom.css', array(), AC()->get_version() );
		wp_enqueue_script( 'jquery-ui-slider' );

		wp_enqueue_script( 'ac-admin-page-columns', AC()->get_url() . "assets/js/admin-page-columns.js", array(
			'jquery',
			'dashboard',
			'jquery-ui-slider',
			'jquery-ui-sortable',
			'wp-pointer',
		), AC()->get_version() );

		wp_enqueue_style( 'ac-admin-page-columns-css', AC()->get_url() . 'assets/css/admin-page-columns.css', array(), AC()->get_version() );

		wp_localize_script( 'ac-admin-page-columns', 'AC', array(
			'_ajax_nonce'      => wp_create_nonce( 'ac-settings' ),
			'list_screen'      => $list_screen->get_key(),
			'layout'           => $list_screen->get_layout_id(),
			'original_columns' => $list_screen->get_original_columns(),
			'i18n'             => array(
				'clone' => __( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
				'error' => __( 'Invalid response.', 'codepress-admin-columns' ),
			),
		) );

		do_action( 'ac/settings/scripts' );
	}

	public function set_current_list_screen() {
		if ( ! current_user_can( Capabilities::MANAGE ) || ! $this->is_current_screen() ) {
			return;
		}

		// User selected
		$list_screen = ListScreenFactory::create( filter_input( INPUT_GET, 'list_screen' ) );

		// Preference
		if ( ! $list_screen ) {
			$list_screen = ListScreenFactory::create( $this->preferences()->get( 'list_screen' ) );
		}

		// First one
		if ( ! $list_screen ) {
			$list_screen = ListScreenFactory::create( key( AC()->get_list_screens() ) );
		}

		// Load table headers
		if ( ! $list_screen->get_original_columns() ) {
			$list_screen->set_original_columns( $list_screen->get_default_column_headers() );
		}

		$this->preferences()->set( 'list_screen', $list_screen->get_key() );

		$this->current_list_screen = $list_screen;

		do_action( 'ac/settings/list_screen', $list_screen );
	}

	/**
	 * @return ListScreen
	 */
	public function get_current_list_screen() {
		return $this->current_list_screen;
	}

	/**
	 * Handle request
	 */
	public function handle_request() {
		if ( ! current_user_can( Capabilities::MANAGE ) || ! $this->is_current_screen() ) {
			return;
		}

		// Handle requests
		switch ( filter_input( INPUT_POST, 'cpac_action' ) ) {

			case 'restore_by_type' :
				if ( $this->verify_nonce( 'restore-type' ) ) {

					$list_screen = ListScreenFactory::create( filter_input( INPUT_POST, 'list_screen' ), filter_input( INPUT_POST, 'layout' ) );
					$list_screen->delete();

					$this->notice( sprintf( __( 'Settings for %s restored successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $this->get_list_screen_message_label( $list_screen ) ) . "</strong>" ), 'updated' );
				}
				break;
		}

		do_action( 'ac/settings/handle_request', $this );
	}

	/**
	 * @since 3.0
	 *
	 * @param ListScreen $list_screen
	 *
	 * @return string $label
	 */
	private function get_list_screen_message_label( $list_screen ) {
		return apply_filters( 'ac/settings/list_screen_message_label', $list_screen->get_label(), $list_screen );
	}

	/**
	 * @param string $message Message body
	 * @param string $type    Updated or error
	 */

	public function notice( $message, $type = 'updated' ) {
		$this->notices[] = '<div class="ac-message inline ' . esc_attr( $type ) . '"><p>' . $message . '</p></div>';
	}

	/**
	 * @param Column $column
	 *
	 * @return string
	 */
	private function get_column_display( Column $column ) {
		ob_start();

		$this->display_column( $column );

		return ob_get_clean();
	}

	/**
	 * Check is the ajax request is valid and user is allowed to make it
	 *
	 * @since 3.0
	 * @return ListScreen
	 */
	private function ajax_validate_request() {
		check_ajax_referer( 'ac-settings' );

		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			wp_die();
		}

		$list_screen = ListScreenFactory::create( filter_input( INPUT_POST, 'list_screen' ), filter_input( INPUT_POST, 'layout' ) );

		if ( ! $list_screen ) {
			wp_die();
		}

		// Load default headings
		if ( ! $list_screen->get_stored_default_headings() ) {
			$list_screen->set_original_columns( (array) filter_input( INPUT_POST, 'original_columns', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ) );
		}

		return $list_screen;
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return string
	 */
	private function get_error_message_visit_list_screen( $list_screen ) {
		return sprintf( __( 'Please visit the %s screen once to load all available columns', 'codepress-admin-columns' ), ac_helper()->html->link( $list_screen->get_screen_link(), $list_screen->get_label() ) );
	}

	/**
	 * Display HTML markup for column type
	 *
	 * @since 3.0
	 */
	public function ajax_column_select() {
		$list_screen = $this->ajax_validate_request();

		$column = $list_screen->get_column_by_type( filter_input( INPUT_POST, 'type' ) );

		if ( ! $column ) {
			wp_send_json_error( array(
				'type'  => 'message',
				'error' => $this->get_error_message_visit_list_screen( $list_screen ),
			) );
		}

		$current_original_columns = (array) filter_input( INPUT_POST, 'current_original_columns', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		// Not cloneable message
		if ( in_array( $column->get_type(), $current_original_columns ) ) {
			wp_send_json_error( array(
				'type'  => 'message',
				'error' => sprintf(
					__( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
					'<strong>' . $column->get_label() . '</strong>' ),
			) );
		}

		// Placeholder message
		if ( $column instanceof Column\Placeholder ) {
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
		$list_screen = $this->ajax_validate_request();

		$options = filter_input( INPUT_POST, 'columns', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$name = filter_input( INPUT_POST, 'column_name' );

		if ( empty( $options[ $name ] ) ) {
			wp_die();
		}

		$settings = $options[ $name ];

		$settings['name'] = $name;

		$column = $list_screen->create_column( $settings );

		if ( ! $column ) {
			wp_die();
		}

		wp_send_json_success( $this->get_column_display( $column ) );
	}

	/**
	 * @since 2.5
	 */
	public function ajax_columns_save() {
		$list_screen = $this->ajax_validate_request();

		parse_str( $_POST['data'], $formdata );

		if ( ! isset( $formdata['columns'] ) ) {
			wp_send_json_error( array(
					'type'    => 'error',
					'message' => __( 'You need at least one column', 'codepress-admin-columns' ),
				)
			);
		}

		$result = $list_screen->store( $formdata['columns'] );

		$view_link = ac_helper()->html->link( $list_screen->get_screen_link(), sprintf( __( 'View %s screen', 'codepress-admin-columns' ), $list_screen->get_label() ) );

		if ( is_wp_error( $result ) ) {

			if ( 'same-settings' === $result->get_error_code() ) {
				wp_send_json_error( array(
						'type'    => 'notice notice-warning',
						'message' => sprintf( __( 'You are trying to store the same settings for %s.', 'codepress-admin-columns' ), "<strong>" . $this->get_list_screen_message_label( $list_screen ) . "</strong>" ) . ' ' . $view_link,
					)
				);
			}

			wp_send_json_error( array(
					'type'    => 'error',
					'message' => $result->get_error_message(),
				)
			);
		}

		wp_send_json_success(
			sprintf( __( 'Settings for %s updated successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $this->get_list_screen_message_label( $list_screen ) ) . "</strong>" ) . ' ' . $view_link
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
			$list_screens[ $list_screen->get_group() ][ $list_screen->get_key() ] = $list_screen->get_label();
		}

		$grouped = array();

		foreach ( ListScreenGroups::get_groups()->get_groups_sorted() as $group ) {
			$slug = $group['slug'];

			if ( empty( $list_screens[ $slug ] ) ) {
				continue;
			}

			if ( ! isset( $grouped[ $slug ] ) ) {
				$grouped[ $slug ]['title'] = $group['label'];
			}

			natcasesort( $list_screens[ $slug ] );

			$grouped[ $slug ]['options'] = $list_screens[ $slug ];

			unset( $list_screens[ $slug ] );
		}

		return $grouped;
	}

	private function preferences() {
		return new Preferences\Site( 'settings' );
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
	 * @return Promo|false
	 */
	public function get_active_promotion() {
		$classes = Autoloader::instance()->get_class_names_from_dir( 'AC\Admin\Promo' );

		foreach ( $classes as $class ) {

			/* @var Promo $promo */
			$promo = new $class;

			if ( $promo->is_active() ) {
				return $promo;
			}
		}

		return false;
	}

	/**
	 * @return int
	 */
	private function get_discount_percentage() {
		return 10;
	}

	/**
	 * @return int
	 */
	private function get_lowest_pro_price() {
		return 49;
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return string
	 */
	private function get_read_only_message( ListScreen $list_screen ) {
		$message = sprintf( __( 'The columns for %s are set up via PHP and can therefore not be edited.', 'codepress-admin-columns' ), '<strong>' . esc_html( $list_screen->get_label() ) . '</strong>' );

		return apply_filters( 'ac/read_only_message', $message, $list_screen );
	}

	/**
	 * Display
	 */
	public function display() {
		$list_screen = $this->get_current_list_screen();

		?>

		<div class="ac-admin<?php echo $list_screen->get_settings() ? ' stored' : ''; ?>" data-type="<?php echo esc_attr( $list_screen->get_key() ); ?>">
			<div class="main">
				<div class="menu">
					<form>
						<?php $this->nonce_field( 'select-list-screen' ); ?>
						<input type="hidden" name="page" value="<?php echo esc_attr( Admin::MENU_SLUG ); ?>">

						<select name="list_screen" title="<?php esc_attr_e( 'Select type', 'codepress-admin-columns' ); ?>" id="ac_list_screen">
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
							<a href="<?php echo esc_url( $link ); ?>" class="page-title-action view-link"><?php esc_html_e( 'View', 'codepress-admin-columns' ); ?></a>
						<?php endif; ?>
					</form>
				</div>

				<?php do_action( 'ac/settings/after_title', $list_screen ); ?>

			</div>

			<div class="ac-right">
				<div class="ac-right-inner">

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
								<input type="hidden" name="list_screen" value="<?php echo esc_attr( $list_screen->get_key() ); ?>"/>
								<input type="hidden" name="layout" value="<?php echo esc_attr( $list_screen->get_layout_id() ); ?>"/>
								<input type="hidden" name="cpac_action" value="restore_by_type"/>

								<?php $this->nonce_field( 'restore-type' ); ?>

								<?php $onclick = AC()->use_delete_confirmation() ? ' onclick="return confirm(\'' . esc_js( sprintf( __( "Warning! The %s columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ), "'" . $this->get_list_screen_message_label( $list_screen ) . "'" ) ) . '\');"' : ''; ?>
								<input class="reset-column-type" type="submit"<?php echo $onclick; ?> value="<?php _e( 'Restore columns', 'codepress-admin-columns' ); ?>">
								<span class="spinner"></span>
							</form>

							<?php do_action( 'ac/settings/form_actions', $this ); ?>

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
									<ul class="features">
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
												<a href="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'banner', 'usp-' . $utm_content ) ); ?>"><?php echo esc_html( $label ); ?></a>
											</li>
										<?php endforeach; ?>

									</ul>

									<?php if ( $promos = AC()->addons()->get_missing_addons() ) : ?>
										<strong><?php _e( 'Extra Columns for:', 'codepress-admin-columns' ); ?></strong>
										<ul>
											<?php foreach ( $promos as $addon ) : ?>
												<li class="acp-integration">
													<a href="<?php echo esc_url( $addon->get_link() ); ?>" target="_blank"><?php $addon->display_promo(); ?></a>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>

									<p class="center">
										<?php echo ac_helper()->html->link( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'banner' ), sprintf( __( 'Prices starting from %s', 'codepress-admin-columns' ), '$' . $this->get_lowest_pro_price() ), array( 'class' => 'ac-pro-prices' ) ); ?>
									</p>
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
										<?php echo esc_html( sprintf( __( 'Get %s Off!', 'codepress-admin-columns' ), $this->get_discount_percentage() . '%' ) ); ?>
									</h3>
									<div class="inside">
										<p>
											<?php echo esc_html( sprintf( __( "Submit your email and we'll send you a discount for %s off.", 'codepress-admin-columns' ), $this->get_discount_percentage() . '%' ) ); ?>
										</p>
										<?php
										$user_data = get_userdata( get_current_user_id() );
										?>
										<form method="post" action="<?php echo esc_url( ac_get_site_utm_url( 'upgrade-to-admin-columns-pro', 'send-coupon' ) ); ?>" target="_blank">
											<input name="action" type="hidden" value="mc_upgrade_pro">
											<input name="EMAIL" placeholder="<?php esc_attr_e( "Your Email", 'codepress-admin-columns' ); ?>" value="<?php echo esc_attr( $user_data->user_email ); ?>" required>
											<input name="FNAME" placeholder="<?php esc_attr_e( "Your First Name", 'codepress-admin-columns' ); ?>" required>
											<input type="submit" value="<?php esc_attr_e( "Send me the discount", 'codepress-admin-columns' ); ?>" class="acp-button">
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
								<?php printf( __( "For full documentation, bug reports, feature suggestions and other tips <a href='%s'>visit the Admin Columns website</a>.", 'codepress-admin-columns' ), ac_get_site_utm_url( 'documentation', 'support' ) ); ?>
							</p>
						</div>
					</div><!--plugin-support-->

				</div><!--.ac-right-inner-->
			</div><!--.ac-right-->

			<div class="ac-left">
				<?php if ( ! $list_screen->get_stored_default_headings() && ! $list_screen->is_read_only() ) : ?>
					<div class="notice notice-warning">
						<p>
							<?php echo $this->get_error_message_visit_list_screen( $list_screen ); ?>
						</p>
					</div>
				<?php endif ?>

				<?php $this->display_notices(); ?>

				<?php if ( $list_screen->is_read_only() ) : ?>
					<div class="ac-notice notice-warning below-h2">
						<p><?php echo $this->get_read_only_message( $list_screen ); ?></p>
					</div>
				<?php endif; ?>

				<div class="ac-boxes<?php echo esc_attr( $list_screen->is_read_only() ? ' disabled' : '' ); ?>">

					<div class="ac-columns">
						<form method="post" action="<?php echo esc_attr( $this->get_link() ); ?>">

							<input type="hidden" name="list_screen" value="<?php echo esc_attr( $list_screen->get_key() ); ?>"/>
							<input type="hidden" name="cpac_action" value="update_by_type"/>

							<?php $this->nonce_field( 'update-type' ); ?>

							<?php

							/**
							 * Columns
							 */
							foreach ( $list_screen->get_columns() as $column ) {
								$this->display_column( $column );
							}
							?>
						</form>

					</div>

					<div class="column-footer">
						<?php if ( ! $list_screen->is_read_only() ) : ?>
							<div class="order-message">
								<svg class="order-message__icon" width="18" height="18">
									<use xlink:href="<?php echo esc_url( AC()->get_url() ); ?>/assets/images/symbols.svg#arrow-left-top"/>
								</svg>
								<?php _e( 'Drag and drop to reorder', 'codepress-admin-columns' ); ?>
							</div>
							<div class="button-container">
								<?php

								/**
								 * Display a clear button below the column settings. The clear button removes all column settings from the current page.
								 *
								 * @since 3.0
								 *
								 * @param bool
								 */
								if ( apply_filters( 'ac/enable_clear_columns_button', false ) ) :
									?>
									<a class="clear-columns" data-clear-columns><?php _e( 'Clear all columns ', 'codepress-admin-columns' ) ?></a>
								<?php endif; ?>

								<span class="spinner"></span>
								<a class="button-primary submit update"><?php _e( 'Update' ); ?></a>
								<a class="button-primary submit save"><?php _e( 'Save' ); ?></a>
								<a class="add_column button">+ <?php _e( 'Add Column', 'codepress-admin-columns' ); ?></a>
							</div>
						<?php endif; ?>
					</div>

				</div><!--.ac-boxes-->

				<?php do_action( 'ac/settings/after_columns', $list_screen ); ?>

			</div><!--.ac-left-->
			<div class="clear"></div>

			<div id="add-new-column-template">
				<?php $this->display_column_template( $list_screen ); ?>
			</div>


		</div><!--.ac-admin-->

		<div class="clear"></div>

		<?php
	}

	/**
	 * @param ListScreen $list_screen
	 * @param string     $group
	 *
	 * @return Column|false
	 */
	private function get_column_template_by_group( ListScreen $list_screen, $group = false ) {
		$types = $list_screen->get_column_types();

		if ( ! $group ) {
			return array_shift( $types );
		}

		$columns = array();

		foreach ( $types as $column_type ) {
			if ( $group === $column_type->get_group() ) {
				$columns[ $column_type->get_label() ] = $column_type;
			}
		}

		array_multisort( array_keys( $columns ), SORT_NATURAL, $columns );

		$column = array_shift( $columns );

		if ( ! $column ) {
			return false;
		}

		return $column;
	}

	/**
	 * Get first custom group column
	 */
	private function display_column_template( ListScreen $list_screen ) {
		$column = $this->get_column_template_by_group( $list_screen, 'custom' );

		if ( ! $column ) {
			$column = $this->get_column_template_by_group( $list_screen );
		}

		$this->display_column( $column );
	}

	/**
	 * @since 2.0
	 */
	public function display_column( Column $column ) { ?>

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
										if ( $setting instanceof Settings\Header ) {
											echo $setting->render_header();
										}
									}

									/**
									 * Fires in the meta-element for column options, which is displayed right after the column label
									 *
									 * @since 2.0
									 *
									 * @param Column $column_instance Column class instance
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

	public function display_modal() {
		if ( ! $this->is_current_screen() ) {
			return;
		}

		?>
		<div class="ac-modal" id="ac-modal-pro">
			<div class="ac-modal__dialog -mascot">
				<div class="ac-modal__dialog__header">
					<?php _e( 'Do you like Admin Columns?', 'codepress-admin-columns' ); ?>
					<button class="ac-modal__dialog__close">
						<span class="dashicons dashicons-no"></span>
					</button>
				</div>
				<div class="ac-modal__dialog__content">
					<p class="ac-modal__dialog__content__lead">
						<?php _e( 'Upgrade to PRO, and take Admin Columns to the next level:', 'codepress-admin-columns' ); ?>
					</p>
					<ul class="ac-modal__dialog__list">
						<li><?php _e( 'Sort & Filter on all your content', 'codepress-admin-columns' ); ?></li>
						<li><?php _e( 'Directly edit your content from the overview', 'codepress-admin-columns' ); ?></li>
						<li><?php _e( 'Export all column data to CSV', 'codepress-admin-columns' ); ?></li>
						<li><?php _e( 'Create multiple column groups per overview', 'codepress-admin-columns' ); ?></li>
						<li><?php _e( 'Get add-ons for ACF, WooCommerce and many more', 'codepress-admin-columns' ); ?></li>
					</ul>
				</div>
				<div class="ac-modal__dialog__footer">
					<a class="button button-primary" target="_blank" href="<?php echo esc_url( ac_get_site_utm_url( 'admin-columns-pro', 'upgrade' ) ); ?>"><?php _e( 'Upgrade', 'codepress-admin-columns' ); ?></a>
					<span class="ac-modal__dialog__footer__content"><?php echo sprintf( __( 'Only %s for 1 site', 'codepress-admin-columns' ), '$' . $this->get_lowest_pro_price() ); ?></span>
					<svg class="ac-modal__dialog__mascot">
						<use xlink:href="<?php echo esc_url( AC()->get_url() ); ?>/assets/images/symbols.svg#zebra-thumbs-up"/>
					</svg>
				</div>
			</div>
		</div>

		<?php
	}
}