<?php

namespace AC\Admin\Page;

use AC\Admin;
use AC\Autoloader;
use AC\Column;
use AC\Integrations;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenGroups;
use AC\PluginInformation;
use AC\View;
use AC\Preferences;

class Columns extends Admin\Page
	implements Admin\Helpable {

	/**
	 * @var array
	 */
	private $notices;

	/** @var ListScreen */
	private $list_screen;

	public function __construct() {
		parent::__construct( 'columns', __( 'Admin Columns', 'codepress-admin-columns' ) );
	}

	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_footer', array( $this, 'display_modal' ) );
	}

	private function preferences() {
		return new Preferences\Site( 'settings' );
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		$this->list_screen = $this->get_list_screen();

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
			'list_screen'      => $this->list_screen->get_key(),
			'layout'           => $this->list_screen->get_layout_id(),
			'original_columns' => $this->list_screen->get_original_columns(),
			'i18n'             => array(
				'clone' => __( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
				'error' => __( 'Invalid response.', 'codepress-admin-columns' ),
			),
		) );

		do_action( 'ac/settings/scripts' );
	}

	public function get_list_screen() {
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

		do_action( 'ac/settings/list_screen', $list_screen );

		return $list_screen;
	}

	/**
	 * Nonce Field
	 *
	 * @param string $action
	 */
	private function nonce_field( $action ) {
		wp_nonce_field( $action, '_ac_nonce', false );
	}

	/**
	 * @return string $label
	 */
	private function get_list_screen_message_label() {
		return apply_filters( 'ac/settings/list_screen_message_label', $this->list_screen->get_label(), $this->list_screen );
	}

	/**
	 * @param string $message Message body
	 * @param string $type    Updated or error
	 */
	public function notice( $message, $type = 'updated' ) {
		$this->notices[] = '<div class="ac-message inline ' . esc_attr( $type ) . '"><p>' . $message . '</p></div>';
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

	/**
	 * @param        $label
	 * @param string $mainlabel
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
	 * @return Admin\Promo|false
	 */
	public function get_active_promotion() {
		$classes = Autoloader::instance()->get_class_names_from_dir( 'AC\Admin\Promo' );

		foreach ( $classes as $class ) {

			/* @var Admin\Promo $promo */
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
	 * @return string
	 */
	private function get_read_only_message() {
		$message = sprintf( __( 'The columns for %s are set up via PHP and can therefore not be edited.', 'codepress-admin-columns' ), '<strong>' . esc_html( $this->list_screen->get_label() ) . '</strong>' );

		return apply_filters( 'ac/read_only_message', $message, $this->list_screen );
	}

	/**
	 * @return \AC\Integration[]
	 */
	private function get_missing_integrations() {
		$missing = array();

		foreach ( new Integrations() as $integration ) {
			$integration_plugin = new PluginInformation( $integration->get_basename() );

			if ( $integration->is_plugin_active() && ! $integration_plugin->is_active() ) {
				$missing[] = $integration;
			}
		}

		return $missing;
	}

	/**
	 * @return string
	 */
	private function get_error_message_visit_list_screen() {
		return sprintf( __( 'Please visit the %s screen once to load all available columns', 'codepress-admin-columns' ), ac_helper()->html->link( $this->list_screen->get_screen_link(), $this->list_screen->get_label() ) );
	}

	/**
	 * Display
	 */
	public function display() {

		$this->list_screen = $this->get_list_screen();
		?>

		<div class="ac-admin<?php echo $this->list_screen->get_settings() ? ' stored' : ''; ?>" data-type="<?php echo esc_attr( $this->list_screen->get_key() ); ?>">
			<div class="main">

				<?php
				$menu = new View( array(
					'items'       => $this->get_grouped_list_screens(),
					'current'     => $this->list_screen->get_key(),
					'screen_link' => $this->list_screen->get_screen_link(),
				) );
				echo $menu->set_template( 'admin/edit-menu' );
				?>

				<?php do_action( 'ac/settings/after_title', $this->list_screen ); ?>

			</div>

			<div class="ac-right">
				<div class="ac-right-inner">

					<?php if ( ! $this->list_screen->is_read_only() ) : ?>
						<div class="sidebox form-actions">
							<?php $mainlabel = __( 'Store settings', 'codepress-admin-columns' ); ?>
							<h3>
								<span class="left"><?php echo esc_html( $mainlabel ); ?></span>
								<?php if ( 18 > strlen( $mainlabel ) && ( $truncated_label = $this->get_truncated_side_label( $this->list_screen->get_label(), $mainlabel ) ) ) : ?>
									<span class="right contenttype"><?php echo esc_html( $truncated_label ); ?></span>
								<?php else : ?>
									<span class="clear contenttype"><?php echo esc_html( $this->list_screen->get_label() ); ?></span>
								<?php endif; ?>
							</h3>

							<div class="form-update">
								<a class="button-primary submit update"><?php _e( 'Update' ); ?></a>
								<a class="button-primary submit save"><?php _e( 'Save' ); ?></a>
							</div>

							<form class="form-reset" method="post">
								<input type="hidden" name="list_screen" value="<?php echo esc_attr( $this->list_screen->get_key() ); ?>"/>
								<input type="hidden" name="layout" value="<?php echo esc_attr( $this->list_screen->get_layout_id() ); ?>"/>
								<input type="hidden" name="cpac_action" value="restore_by_type"/>

								<?php $this->nonce_field( 'restore-type' ); ?>

								<?php $onclick = AC()->use_delete_confirmation() ? ' onclick="return confirm(\'' . esc_js( sprintf( __( "Warning! The %s columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ), "'" . $this->get_list_screen_message_label() . "'" ) ) . '\');"' : ''; ?>
								<input class="reset-column-type" type="submit"<?php echo $onclick; ?> value="<?php _e( 'Restore columns', 'codepress-admin-columns' ); ?>">
								<span class="spinner"></span>
							</form>

							<?php do_action( 'ac/settings/form_actions', $this ); ?>

						</div><!--form-actions-->
					<?php endif; ?>

					<?php do_action( 'ac/settings/sidebox', $this->list_screen ); ?>

					<?php if ( apply_filters( 'ac/show_banner', true ) ) : ?>

						<?php
						$feedback = new View( array(
							'promo'        => $this->get_active_promotion(),
							'integrations' => $this->get_missing_integrations(),
							'discount'     => $this->get_discount_percentage(),
							'price'        => $this->get_lowest_pro_price(),
						) );
						echo $feedback->set_template( 'admin/side-banner' );
						?>

						<?php
						$feedback = new View();
						echo $feedback->set_template( 'admin/side-feedback' );
						?>

					<?php endif; ?>

					<?php
					$feedback = new View();
					echo $feedback->set_template( 'admin/side-support' );
					?>

				</div><!--.ac-right-inner-->
			</div><!--.ac-right-->

			<div class="ac-left">
				<?php if ( ! $this->list_screen->get_stored_default_headings() && ! $this->list_screen->is_read_only() ) : ?>
					<div class="notice notice-warning">
						<p>
							<?php echo $this->get_error_message_visit_list_screen(); ?>
						</p>
					</div>
				<?php endif ?>

				<?php $this->display_notices(); ?>

				<?php if ( $this->list_screen->is_read_only() ) : ?>
					<div class="ac-notice notice-warning below-h2">
						<p><?php echo $this->get_read_only_message(); ?></p>
					</div>
				<?php endif; ?>

				<div class="ac-boxes<?php echo esc_attr( $this->list_screen->is_read_only() ? ' disabled' : '' ); ?>">

					<div class="ac-columns">
						<form method="post">

							<input type="hidden" name="list_screen" value="<?php echo esc_attr( $this->list_screen->get_key() ); ?>"/>
							<input type="hidden" name="cpac_action" value="update_by_type"/>

							<?php $this->nonce_field( 'update-type' ); ?>

							<?php
							foreach ( $this->list_screen->get_columns() as $column ) {
								$this->display_column( $column );
							}
							?>
						</form>

					</div>

					<div class="column-footer">
						<?php if ( ! $this->list_screen->is_read_only() ) : ?>
							<div class="button-container">
								<?php

								/**
								 * Display a clear button below the column settings. The clear button removes all column settings from the current page.
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

				<?php do_action( 'ac/settings/after_columns', $this->list_screen ); ?>

			</div><!--.ac-left-->
			<div class="clear"></div>

			<div id="add-new-column-template">
				<?php $this->display_column_template(); ?>
			</div>


		</div><!--.ac-admin-->

		<div class="clear"></div>

		<?php
	}

	/**
	 * @param bool $group
	 *
	 * @return Column|false
	 */
	private function get_column_template_by_group( $group = false ) {
		$types = $this->list_screen->get_column_types();

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

	private function display_column_template() {
		$column = $this->get_column_template_by_group( 'custom' );

		if ( ! $column ) {
			$column = $this->get_column_template_by_group();
		}

		$this->display_column( $column );
	}

	/**
	 * @since 2.0
	 *
	 * @param Column $column
	 */
	public function display_column( Column $column ) {
		$view = new View( array(
			'column' => $column,
		) );

		echo $view->set_template( 'admin/edit-column' );
	}

	public function display_modal() {
		$view = new View( array(
			'price' => $this->get_lowest_pro_price(),
		) );

		echo $view->set_template( 'admin/modal-pro' );
	}

	/**
	 * @return Admin\HelpTab[]
	 */
	public function get_help_tabs() {
		return array(
			new Admin\HelpTab\Introduction(),
			new Admin\HelpTab\Basics(),
			new Admin\HelpTab\CustomField(),
		);
	}

}