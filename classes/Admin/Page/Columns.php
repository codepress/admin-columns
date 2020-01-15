<?php

namespace AC\Admin\Page;

use AC\Admin;
use AC\Ajax;
use AC\Column;
use AC\Controller;
use AC\DefaultColumns;
use AC\ListScreen;
use AC\ListScreenRepository\SortStrategy\ManualOrder;
use AC\Message\Notice;
use AC\Registrable;
use AC\UnitializedListScreens;
use AC\View;

class Columns extends Admin\Page
	implements Admin\Helpable, Registrable {

	const NAME = 'columns';

	/**
	 * @var array
	 */
	private $notices = [];

	/** @var Controller\ListScreenRequest */
	private $controller;

	/**
	 * @var Admin\Section\ListScreenMenu
	 */
	private $menu;

	/** @var UnitializedListScreens */
	private $uninitialized;

	public function __construct( Controller\ListScreenRequest $controller, Admin\Section\ListScreenMenu $menu, UnitializedListScreens $uninitialized ) {
		parent::__construct( self::NAME, __( 'Admin Columns', 'codepress-admin-columns' ) );

		$this->controller = $controller;
		$this->menu = $menu;
		$this->uninitialized = $uninitialized;
	}

	public function register() {
		$this->maybe_show_notice();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * @return ListScreen
	 */
	private function get_list_screen() {
		return $this->controller->get_list_screen();
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		$list_screen = $this->controller->get_list_screen();

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

		$params['_ajax_nonce'] = wp_create_nonce( Ajax\Handler::NONCE_ACTION );
		$params['list_screen'] = $list_screen->get_key();
		$params['layout'] = $list_screen->get_layout_id();
		$params['original_columns'] = [];
		$params['i18n'] = array(
			'clone'  => __( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
			'error'  => __( 'Invalid response.', 'codepress-admin-columns' ),
			'errors' => array(
				'save_settings'  => __( 'There was an error during saving the column settings.', 'codepress-admin-columns' ),
				'loading_column' => __( 'The column could not be loaded because of an unknown error', 'codepress-admin-columns' ),
			),
		);

		$params['uninitialized_list_screens'] = [];

		foreach ( $this->uninitialized->get_list_screens() as $list_screen ) {
			/** @var ListScreen $list_screen */
			$key = $list_screen->get_key();
			$params['uninitialized_list_screens'][ $key ] = array(
				'screen_link' => add_query_arg( [ 'save-default-headings' => '1', 'list_screen' => $key ], $list_screen->get_screen_link() ),
				'label'       => $list_screen->get_label(),
			);
		}

		wp_enqueue_style( 'ac-select2' );
		wp_enqueue_script( 'ac-select2' );

		wp_localize_script( 'ac-admin-page-columns', 'AC', $params );

		do_action( 'ac/settings/scripts' );
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
		$this->notices[] = '<div class="ac-message inline ' . esc_attr( $type ) . '">' . $message . '</div>';
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

	private function maybe_show_notice() {
		$list_screen = $this->get_list_screen();

		// todo
		$default_columns = new DefaultColumns();

		if ( ! $list_screen->is_read_only() && ! $default_columns->get( $list_screen->get_key() ) ) {

			$first_visit_link = add_query_arg( array( 'ac_action' => 'first-visit' ), $list_screen->get_screen_link() );

			$notice = new Notice( sprintf( __( 'Please visit the %s screen once to load all available columns', 'codepress-admin-columns' ), ac_helper()->html->link( $first_visit_link, $list_screen->get_label() ) ) );
			$notice
				->set_type( Notice::WARNING )
				->set_id( 'visit-ls' )
				->register();
		}

		if ( $list_screen->is_read_only() ) {
			$this->notice(
				sprintf( '%s<p>%s</p>',
					// todo: style icon
					ac_helper()->icon->dashicon( [ 'icon' => 'lock', 'class' => '-icon' ] ),
					$this->get_read_only_message( $list_screen )
				),
				'notice notice-warning'
			);
		}
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return string
	 */
	private function get_read_only_message( ListScreen $list_screen ) {
		$message = sprintf( __( 'The columns are read only and can not be edited.', 'codepress-admin-columns' ) );

		return apply_filters( 'ac/read_only_message', $message, $list_screen );
	}

	// todo: move to pro
	private function __render_submenu_view( $page_link, $list_screen_key, $current_id = false ) {
		$list_screens = $this->repository->find_all( [
			'key'  => $list_screen_key,
			'sort' => new ManualOrder(),
		] );

		if ( $list_screens->count() <= 1 ) {
			return;
		}

		ob_start();
		foreach ( $list_screens as $list_screen ) : ?>
			<li data-screen="<?php echo esc_attr( $list_screen->get_layout_id() ); ?>">
				<a class="<?php echo $list_screen->get_layout_id() === $current_id ? 'current' : ''; ?>" href="<?php echo add_query_arg( [ 'layout_id' => $list_screen->get_layout_id() ], $page_link ); ?>"><?php echo esc_html( $list_screen->get_title() ? $list_screen->get_title() : __( '(no name)', 'codepress-admin-columns' ) ); ?></a>
			</li>
		<?php endforeach;

		$items = ob_get_clean();

		$menu = new View( array(
			'items' => $items,
		) );

		echo $menu->set_template( 'admin/edit-submenu' );
	}

	private function render_loading_screen() {
		$modal = new View( array(
			'message' => 'Loading columns',
		) );

		echo $modal->set_template( 'admin/loading-message' );
	}

	/**
	 * Display
	 */
	public function render() {
		$list_screen = $this->get_list_screen();

		// todo: use UnitializedColumns
		$default_columns = new DefaultColumns();

		if ( ! $default_columns->exists( $list_screen->get_key() ) ) {
			$this->render_loading_screen();

			return;
		}

		?>

		<div class="ac-admin<?php echo $list_screen->get_settings() ? ' stored' : ''; ?>" data-type="<?php echo esc_attr( $list_screen->get_key() ); ?>">
			<div class="main">

				<?php
				$this->menu->render();

				// todo: move to Pro
				//$this->render_submenu_view( $list_screen->get_edit_link(), $list_screen->get_key(), $list_screen->get_layout_id() );

				do_action( 'ac/settings/after_title', $list_screen );
				?>

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

						echo new Admin\Parts\Banner();

						$feedback = new View();

						echo $feedback->set_template( 'admin/side-feedback' );

					endif; ?>

					<?php

					$support = new View();

					echo $support->set_template( 'admin/side-support' );

					?>

				</div><!--.ac-right-inner-->
			</div><!--.ac-right-->

			<div class="ac-left">
				<form method="post" id="listscreen_settings" class="<?= $list_screen->is_read_only() ? '-disabled' : ''; ?>">
					<?php

					echo implode( $this->notices );

					$columns = new View( array(
						'class'          => $list_screen->is_read_only() ? ' disabled' : '',
						'list_screen'    => $list_screen->get_key(),
						'list_screen_id' => $list_screen->get_layout_id(),
						'title'          => $list_screen->get_title(),
						'columns'        => $list_screen->get_columns(),
						'show_actions'   => ! $list_screen->is_read_only(),
						'show_clear_all' => apply_filters( 'ac/enable_clear_columns_button', false ),
					) );

					do_action( 'ac/settings/before_columns', $list_screen );

					echo $columns->set_template( 'admin/edit-columns' );

					do_action( 'ac/settings/after_columns', $list_screen );

					?>
				</form>

			</div><!--.ac-left-->
			<div class="clear"></div>

			<div id="add-new-column-template">
				<?php $this->display_column_template( $list_screen ); ?>
			</div>


		</div><!--.ac-admin-->

		<div class="clear"></div>

		<?php

		$modal = new View( array(
			'price' => ac_get_lowest_price(),
		) );

		echo $modal->set_template( 'admin/modal-pro' );
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

		$column_keys = array_keys( $columns );
		array_multisort( $column_keys, SORT_NATURAL, $columns );

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

		$view = new View( array(
			'column' => $column,
		) );

		echo $view->set_template( 'admin/edit-column' );
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