<?php

namespace AC\Admin\Page;

use AC\Admin;
use AC\Autoloader;
use AC\Capabilities;
use AC\Column;
use AC\Integrations;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenGroups;
use AC\Message\Notice;
use AC\PluginInformation;
use AC\Preferences;
use AC\Registrable;
use AC\View;

class Columns extends Admin\Page
	implements Admin\Helpable, Registrable {

	/**
	 * @var array
	 */
	private $notices = array();

	public function __construct() {
		parent::__construct( 'columns', __( 'Admin Columns', 'codepress-admin-columns' ) );
	}

	public function register() {
		$this->maybe_show_notice();
		$this->handle_request();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	private function maybe_show_notice() {
		$list_screen = $this->get_list_screen();

		if ( ! $list_screen->get_stored_default_headings() && ! $list_screen->is_read_only() ) {
			$notice = new Notice( sprintf( __( 'Please visit the %s screen once to load all available columns', 'codepress-admin-columns' ), ac_helper()->html->link( $list_screen->get_screen_link(), $list_screen->get_label() ) ) );
			$notice
				->set_type( Notice::WARNING )
				->register();
		}

		if ( $list_screen->is_read_only() ) {
			$notice = new Notice( $this->get_read_only_message( $list_screen ) );
			$notice
				->set_type( Notice::INFO )
				->register();
		}
	}

	/**
	 * @param string $action
	 *
	 * @return bool
	 */
	private function verify_nonce( $action ) {
		return wp_verify_nonce( filter_input( INPUT_POST, '_ac_nonce' ), $action );
	}

	private function handle_request() {
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		switch ( filter_input( INPUT_POST, 'action' ) ) {

			case 'restore_by_type' :
				if ( $this->verify_nonce( 'restore-type' ) ) {

					$list_screen = ListScreenFactory::create( filter_input( INPUT_POST, 'list_screen' ), filter_input( INPUT_POST, 'layout' ) );
					$list_screen->delete();

					$notice = new Notice( sprintf( __( 'Settings for %s restored successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $this->get_list_screen_message_label( $list_screen ) ) . "</strong>" ) );
					$notice->register();
				}
				break;
		}

		do_action( 'ac/settings/handle_request', $this );
	}

	private function preferences() {
		return new Preferences\Site( 'settings' );
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		$list_screen = $this->get_list_screen();

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

	public function get_list_screen() {
		// User selected
		$list_screen = ListScreenFactory::create( filter_input( INPUT_GET, 'list_screen' ) );

		// Preference
		$preference = ListScreenFactory::create( $this->preferences()->get( 'list_screen' ) );

		if ( ! $list_screen ) {
			$list_screen = $preference;
		}

		// First one
		if ( ! $list_screen ) {
			$list_screen = ListScreenFactory::create( key( AC()->get_list_screens() ) );
		}

		// Load table headers
		if ( ! $list_screen->get_original_columns() ) {
			$list_screen->set_original_columns( $list_screen->get_default_column_headers() );
		}

		if ( $preference !== $list_screen->get_key() ) {
			$this->preferences()->set( 'list_screen', $list_screen->get_key() );
		}

		do_action( 'ac/settings/list_screen', $list_screen );

		return $list_screen;
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return string $label
	 */
	private function get_list_screen_message_label( ListScreen $list_screen ) {
		return apply_filters( 'ac/settings/list_screen_message_label', $list_screen->get_label(), $list_screen );
	}

	/**
	 * @param string $message Message body
	 * @param string $type    Updated or error
	 */
	public function notice( $message, $type = 'updated' ) {
		$this->notices[] = '<div class="ac-message inline ' . esc_attr( $type ) . '"><p>' . $message . '</p></div>';
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
	 * @param ListScreen $list_screen
	 *
	 * @return string
	 */
	private function get_read_only_message( ListScreen $list_screen ) {
		$message = sprintf( __( 'The columns for %s are set up via PHP and can therefore not be edited.', 'codepress-admin-columns' ), '<strong>' . esc_html( $list_screen->get_label() ) . '</strong>' );

		return apply_filters( 'ac/read_only_message', $message, $list_screen );
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
	 * Display
	 */
	public function display() {

		$list_screen = $this->get_list_screen();
		?>

		<div class="ac-admin<?php echo $list_screen->get_settings() ? ' stored' : ''; ?>" data-type="<?php echo esc_attr( $list_screen->get_key() ); ?>">
			<div class="main">

				<?php
				$menu = new View( array(
					'items'       => $this->get_grouped_list_screens(),
					'current'     => $list_screen->get_key(),
					'screen_link' => $list_screen->get_screen_link(),
				) );
				echo $menu->set_template( 'admin/edit-menu' );
				?>

				<?php do_action( 'ac/settings/after_title', $list_screen ); ?>

			</div>

			<div class="ac-right">
				<div class="ac-right-inner">

					<?php if ( ! $list_screen->is_read_only() ) : ?>

						<?php

						$label_main = __( 'Store settings', 'codepress-admin-columns' );
						$label_second = sprintf( '<span class="clear contenttype">%s</span>', esc_html( $list_screen->get_label() ) );
						if ( 18 > strlen( $label_main ) && ( $truncated_label = $this->get_truncated_side_label( $list_screen->get_label(), $label_main ) ) ) {
							$label_second = sprintf( '<span class="right contenttype">%s</span>', esc_html( $truncated_label ) );
						}

						$delete_confirmation_message = false;

						if ( AC()->use_delete_confirmation() ) {
							$delete_confirmation_message = sprintf( __( "Warning! The %s columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ), "'" . $this->get_list_screen_message_label( $list_screen ) . "'" );
						}

						$actions = new View( array(
							'label_main'                  => $label_main,
							'label_second'                => $label_second,
							'list_screen_key'             => $list_screen->get_key(),
							'list_screen_id'              => $list_screen->get_layout_id(),
							'delete_confirmation_message' => $delete_confirmation_message,
						) );
						echo $actions->set_template( 'admin/edit-actions' );

					endif; ?>

					<?php do_action( 'ac/settings/sidebox', $list_screen ); ?>

					<?php if ( apply_filters( 'ac/show_banner', true ) ) : ?>

						<?php
						$banner = new View( array(
							'promo'        => $this->get_active_promotion(),
							'integrations' => $this->get_missing_integrations(),
							'discount'     => $this->get_discount_percentage(),
							'price'        => $this->get_lowest_pro_price(),
						) );
						echo $banner->set_template( 'admin/side-banner' );
						?>

						<?php
						$feedback = new View();
						echo $feedback->set_template( 'admin/side-feedback' );
						?>

					<?php endif; ?>

					<?php
					$support = new View();
					echo $support->set_template( 'admin/side-support' );
					?>

				</div><!--.ac-right-inner-->
			</div><!--.ac-right-->

			<div class="ac-left">
				<?php

				$columns = new View( array(
					'notices'        => $this->notices,
					'class'          => $list_screen->is_read_only() ? ' disabled' : '',
					'list_screen'    => $list_screen->get_key(),
					'columns'        => $list_screen->get_columns(),
					'show_actions'   => ! $list_screen->is_read_only(),
					'show_clear_all' => apply_filters( 'ac/enable_clear_columns_button', false ),
				) );
				echo $columns->set_template( 'admin/edit-columns' );
				?>

				<?php do_action( 'ac/settings/after_columns', $list_screen ); ?>

			</div><!--.ac-left-->
			<div class="clear"></div>

			<div id="add-new-column-template">
				<?php $this->display_column_template( $list_screen ); ?>
			</div>


		</div><!--.ac-admin-->

		<div class="clear"></div>

		<?php

		$this->display_modal();
	}

	/**
	 * @param array $column_types
	 * @param bool  $group
	 *
	 * @return Column|false
	 */
	private function get_column_template_by_group( $column_types, $group = false ) {
		if ( ! $group ) {
			return array_shift( $column_types );
		}

		$columns = array();

		foreach ( $column_types as $column_type ) {
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
	 * @param ListScreen $list_screen
	 */
	private function display_column_template( ListScreen $list_screen ) {
		$column = $this->get_column_template_by_group( $list_screen->get_column_types(), 'custom' );

		if ( ! $column ) {
			$column = $this->get_column_template_by_group( $list_screen->get_column_types() );
		}

		$this->display_column( $column );
	}

	/**
	 * @since 2.0
	 *
	 * @param Column $column
	 */
	private function display_column( Column $column ) {
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