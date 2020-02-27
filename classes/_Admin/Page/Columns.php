<?php

namespace AC\_Admin\Page;

use AC\_Admin\Assets;
use AC\_Admin\Page;
use AC\Ajax;
use AC\Asset\Localizable;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Column;
use AC\Controller\ListScreenRequest;
use AC\ListScreen;
use AC\UnitializedListScreens;
use AC\View;

class Columns extends Page implements Assets, Localizable {

	const SLUG = 'columns';

	/**
	 * @var ListScreenRequest
	 */
	private $controller;

	/**
	 * @var Location\Absolute
	 */
	private $location;

	/**
	 * @var UnitializedListScreens
	 */
	private $uninitialized;

	/**
	 * @var array
	 */
	// todo
	private $notices = [];



	public function __construct( ListScreenRequest $controller, Location\Absolute $location, UnitializedListScreens $uninitialized ) {
		parent::__construct( self::SLUG, __( 'Admin Columns', 'codepress-admin-columns' ) );

		$this->controller = $controller;
		$this->location = $location;
		$this->uninitialized = $uninitialized;
	}

	public function get_assets() {
		return [
			new Style( 'jquery-ui-lightness', $this->location->with_suffix( 'assets/ui-theme/jquery-ui-1.8.18.custom.css' ) ),
			new Script( 'jquery-ui-slider' ),
			new Script(
				'ac-admin-page-columns',
				$this->location->with_suffix( 'assets/js/admin-page-columns.js' ),
				[
					'jquery',
					'dashboard',
					'jquery-ui-slider',
					'jquery-ui-sortable',
					'wp-pointer',
				]
			),
			new Style( 'ac-admin-page-columns-css', $this->location->with_suffix( 'assets/css/admin-page-columns.css' ) ),
			new Style( 'ac-select2' ),
			new Script( 'ac-select2' ),
		];
	}

	public function localize() {
		$list_screen = $this->controller->get_list_screen();

		if ( null === $list_screen ) {
			return;
		}

		$params = [
			'_ajax_nonce'                => wp_create_nonce( Ajax\Handler::NONCE_ACTION ),
			'list_screen'                => $list_screen->get_key(),
			'layout'                     => $list_screen->get_layout_id(),
			'original_columns'           => [],
			'uninitialized_list_screens' => [],
			'i18n'                       => [
				'clone'  => __( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
				'error'  => __( 'Invalid response.', 'codepress-admin-columns' ),
				'errors' => [
					'save_settings'  => __( 'There was an error during saving the column settings.', 'codepress-admin-columns' ),
					'loading_column' => __( 'The column could not be loaded because of an unknown error', 'codepress-admin-columns' ),
				],
			],
		];

		foreach ( $this->uninitialized->get_list_screens() as $list_screen ) {

			$key = $list_screen->get_key();

			$params['uninitialized_list_screens'][ $key ] = [
				'screen_link' => add_query_arg( [ 'save-default-headings' => '1', 'list_screen' => $key ], $list_screen->get_screen_link() ),
				'label'       => $list_screen->get_label(),
			];
		}

		wp_localize_script( 'ac-admin-page-columns', 'AC', $params );

	}

	public function render() {
		ob_start();

		$list_screen = $this->controller->get_list_screen();

		if ( $this->uninitialized->has_list_screen( $list_screen->get_key() ) ) {
			$this->render_loading_screen();

			return '';
		}

		?>

		<div class="ac-admin<?php echo $list_screen->get_settings() ? ' stored' : ''; ?>" data-type="<?php echo esc_attr( $list_screen->get_key() ); ?>">
			<div class="main">

				<?php
				// todo
				//$this->menu->render();

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
							$delete_confirmation_message = sprintf( __( "Warning! The %s columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ), "'" . $list_screen->get_title() . "'" );
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

					// todo
						//echo new Admin\Parts\Banner();

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

		$modal = new View();

		echo $modal->set_template( 'admin/modal-pro' );

		return ob_get_clean();
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
	 * @param string $label
	 * @param string $main_label
	 *
	 * @return string
	 */
	private function get_truncated_side_label( $label, $main_label = '' ) {
		if ( 34 < ( strlen( $label ) + ( strlen( $main_label ) * 1.1 ) ) ) {
			$label = substr( $label, 0, 34 - ( strlen( $main_label ) * 1.1 ) ) . '...';
		}

		return $label;
	}

	private function render_loading_screen() {
		$modal = new View( array(
			'message' => 'Loading columns',
		) );

		echo $modal->set_template( 'admin/loading-message' );
	}

}