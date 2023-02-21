<?php

namespace AC\Admin\Page;

use AC\Admin;
use AC\Admin\Banner;
use AC\Admin\Preference;
use AC\Admin\RenderableHead;
use AC\Admin\ScreenOption;
use AC\Admin\Section\Partial\Menu;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Column;
use AC\Controller\Middleware;
use AC\DefaultColumnsRepository;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\ListScreenTypes;
use AC\Renderable;
use AC\Request;
use AC\Type\ListScreenId;
use AC\Type\Url;
use AC\Type\Url\Documentation;
use AC\Type\Url\Site;
use AC\Type\Url\Tweet;
use AC\Type\Url\UtmTags;
use AC\View;
use ACP\Search\TableScreenFactory;

class Columns implements Enqueueables, Admin\ScreenOptions, Renderable, RenderableHead {

	const NAME = 'columns';

	/**
	 * @var Location\Absolute
	 */
	private $location;

	/**
	 * @var DefaultColumnsRepository
	 */
	private $default_columns;

	/**
	 * @var Menu
	 */
	private $menu;

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @var Renderable
	 */
	private $head;

	/**
	 * @var Preference\ListScreen
	 */
	private $preference;

	/**
	 * @var bool
	 */
	private $is_acp_active;

	/**
	 * @var bool
	 */
	private $is_network;

	public function __construct(
		Location\Absolute $location,
		DefaultColumnsRepository $default_columns,
		Menu $menu,
		Storage $storage,
		Renderable $head,
		Preference\ListScreen $preference,
		bool $is_acp_active,
		bool $is_network = false
	) {
		$this->location = $location;
		$this->default_columns = $default_columns;
		$this->menu = $menu;
		$this->storage = $storage;
		$this->head = $head;
		$this->preference = $preference;
		$this->is_acp_active = $is_acp_active;
		$this->is_network = $is_network;
	}

	public function render_head() {
		return $this->head;
	}

	// TODO remove
	public function get_list_screen_from_request():? ListScreen {
		$request = new Request();
		$request->add_middleware(
			new Middleware\ListScreenAdmin( $this->storage, $this->preference, $this->is_network )
		);

		$list_id = $request->get( Middleware\ListScreenAdmin::PARAM_LIST_ID );

		if ( ListScreenId::is_valid_id( $list_id ) ) {
			return $this->storage->find( new ListScreenId( $list_id ) );
		}

		return $request->get( Middleware\ListScreenAdmin::PARAM_LIST_KEY );


//		return ListScreenTypes::instance()->get_list_screen_by_key( $list_key );
	}

	public function get_assets() {
		return new Assets( [
			new Style( 'jquery-ui-lightness', $this->location->with_suffix( 'assets/ui-theme/jquery-ui-1.8.18.custom.css' ) ),
			new Script( 'jquery-ui-slider' ),
			new Admin\Asset\Columns(
				'ac-admin-page-columns',
				$this->location->with_suffix( 'assets/js/admin-page-columns.js' ),
				$this->default_columns,
				// TODO refactor next...
				$this->get_list_screen_from_request()
			),
			new Style( 'ac-admin-page-columns-css', $this->location->with_suffix( 'assets/css/admin-page-columns.css' ) ),
			new Style( 'ac-select2' ),
			new Script( 'ac-select2' ),
		] );
	}

	private function get_column_id() {
		return new ScreenOption\ColumnId( new Admin\Preference\ScreenOptions() );
	}

	private function get_column_type() {
		return new ScreenOption\ColumnType( new Admin\Preference\ScreenOptions() );
	}

	private function get_list_screen_id() {
		return new ScreenOption\ListScreenId( new Admin\Preference\ScreenOptions() );
	}

	private function get_list_screen_type() {
		return new ScreenOption\ListScreenType( new Admin\Preference\ScreenOptions() );
	}

	public function get_screen_options() {
		return [
			$this->get_column_id(),
			$this->get_column_type(),
			$this->get_list_screen_id(),
			$this->get_list_screen_type(),
		];
	}

	private function set_preference_screen( ListScreen $list_screen ) {
		$this->preference->set_last_visited_list_key( $list_screen->get_key() );

		if ( $list_screen->has_id() ) {
			$this->preference->set_list_id( $list_screen->get_key(), $list_screen->get_id()->get_id() );
		}
	}

	private function get_tweet_url(): Url {
		return new Tweet(
			__( "I'm using Admin Columns for WordPress!", 'codepress-admin-columns' ),
			new Url\WordpressPluginRepo(),
			Tweet::TWITTER_HANDLE,
			'admincolumns'
		);
	}

	public function render() {
		// TODO
//		$list_screen = $this->get_list_screen_from_request();
//
//		if ( ! $list_screen ) {
//			return '';
//		}

//		$this->set_preference_screen( $list_screen );

		$list_key = 'post';

		if ( ! $this->default_columns->exists( $list_key ) ) {
			$modal = new View( [
				'message' => 'Loading columns',
			] );
			$modal->set_template( 'admin/loading-message' );

			return $this->menu->render( $list_key, true ) . $modal->render();
		}

		$classes = [];

		if ( $list_screen->get_settings() ) {
			$classes[] = 'stored';
		}

		if ( $this->get_list_screen_id()->is_active() ) {
			$classes[] = 'show-list-screen-id';
		}

		if ( $this->get_list_screen_type()->is_active() ) {
			$classes[] = 'show-list-screen-type';
		}

		ob_start();
		?>
		<h1 class="screen-reader-text"><?= __( 'Columns', 'codepress-admin-columns' ); ?></h1>
		<div class="ac-admin <?= esc_attr( implode( ' ', $classes ) ); ?>" data-type="<?= esc_attr( $list_screen->get_key() ); ?>">
			<div class="ac-admin__header">

				<?= $this->menu->render( $list_screen ); ?>

				<?php do_action( 'ac/settings/after_title', $list_screen ); ?>

			</div>
			<div class="ac-admin__wrap">

				<div class="ac-admin__sidebar">
					<?php if ( ! $list_screen->is_read_only() ) : ?>

						<?php

						$label_main = __( 'Store settings', 'codepress-admin-columns' );
						$label_second = sprintf( '<span class="clear contenttype">%s</span>', esc_html( $list_screen->get_label() ) );
						if ( 18 > strlen( $label_main ) && ( $truncated_label = $this->get_truncated_side_label( $list_screen->get_label(), $label_main ) ) ) {
							$label_second = sprintf( '<span class="right contenttype">%s</span>', esc_html( $truncated_label ) );
						}

						$delete_confirmation_message = false;

						if ( apply_filters( 'ac/delete_confirmation', true ) ) {
							$delete_confirmation_message = sprintf( __( "Warning! The %s columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ), "'" . $list_screen->get_title() . "'" );
						}

						$actions = new View( [
							'label_main'                  => $label_main,
							'label_second'                => $label_second,
							'list_screen_key'             => $list_screen->get_key(),
							'list_screen_id'              => $list_screen->get_layout_id(),
							'delete_confirmation_message' => $delete_confirmation_message,
						] );

						echo $actions->set_template( 'admin/edit-actions' );

					endif; ?>

					<?php do_action( 'ac/settings/sidebox', $list_screen ); ?>

					<?php if ( apply_filters( 'ac/show_banner', true ) ) : ?>

						<?= new Banner() ?>

						<?php
						$view = new View( [
							'documentation_url' => ( new UtmTags( new Documentation(), 'feedback-docs-button' ) )->get_url(),
							'upgrade_url'       => ( new UtmTags( new Site( Site::PAGE_ABOUT_PRO ), 'feedback-purchase-button' ) )->get_url(),
							'tweet_url'         => $this->get_tweet_url()->get_url(),
							'review_url'        => ( new Url\WordpressPluginReview() )->get_url(),
						] );

						echo $view->set_template( 'admin/side-feedback' );
						?>

					<?php endif; ?>

					<?php
					$view = new View( [
						'documentation_url' => ( new UtmTags( new Documentation(), 'support' ) )->get_url(),
					] );
					echo $view->set_template( 'admin/side-support' );
					?>

				</div>

				<div class="ac-admin__main">

					<?php do_action( 'ac/settings/notice', $list_screen ); ?>

					<div id="listscreen_settings" data-form="listscreen" class="<?= $list_screen->is_read_only() ? '-disabled' : ''; ?>">
						<?php

						$classes = [];

						if ( $list_screen->is_read_only() ) {
							$classes[] = 'disabled';
						}

						if ( $this->get_column_id()->is_active() ) {
							$classes[] = 'show-column-id';
						}

						if ( $this->get_column_type()->is_active() ) {
							$classes[] = 'show-column-type';
						}

						$columns = new View( [
							'class'          => implode( ' ', $classes ),
							'list_screen'    => $list_screen->get_key(),
							'list_screen_id' => $list_screen->get_layout_id(),
							'title'          => $list_screen->get_title(),
							'columns'        => $list_screen->get_columns(),
							'show_actions'   => ! $list_screen->is_read_only(),
							'show_clear_all' => apply_filters( 'ac/enable_clear_columns_button', false ),
						] );

						do_action( 'ac/settings/before_columns', $list_screen );

						echo $columns->set_template( 'admin/edit-columns' );

						do_action( 'ac/settings/after_columns', $list_screen );

						if ( ! $this->is_acp_active ) {
							echo ( new View() )->set_template( 'admin/list-screen-settings-mockup' )->render();
						}

						?>
					</div>

				</div>

			</div>

			<div id="add-new-column-template">
				<?= $this->render_column_template( $list_screen ); ?>
			</div>

		</div>

		<div class="clear"></div>

		<?php

		$modal = new View( [
			'upgrade_url' => ( new UtmTags( new Site( Site::PAGE_ABOUT_PRO ), 'upgrade' ) )->get_url(),
		] );

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

		$columns = [];

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
	 *
	 * @return string
	 */
	private function render_column_template( ListScreen $list_screen ) {
		$column = $this->get_column_template_by_group( $list_screen->get_column_types(), 'custom' );

		if ( ! $column ) {
			$column = $this->get_column_template_by_group( $list_screen->get_column_types() );
		}

		$view = new View( [
			'column' => $column,
		] );

		return $view->set_template( 'admin/edit-column' )->render();
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

}