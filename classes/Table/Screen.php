<?php

namespace AC\Table;

use AC;
use AC\Asset;
use AC\Capabilities;
use AC\ColumnSize;
use AC\Form;
use AC\ListScreen;
use AC\Registrable;
use AC\Renderable;
use AC\ScreenController;
use AC\Settings;
use WP_Post;

final class Screen implements Registrable {

	/**
	 * @var Asset\Location\Absolute
	 */
	private $location;

	/**
	 * @var ListScreen
	 */
	private $list_screen;

	/**
	 * @var Form\Element[]
	 */
	private $screen_options;

	/**
	 * @var Button[]
	 */
	private $buttons = [];

	/**
	 * @var ColumnSize\ListStorage
	 */
	private $column_size_list_storage;

	/**
	 * @var ColumnSize\UserStorage
	 */
	private $column_size_user_storage;

	public function __construct( Asset\Location\Absolute $location, ListScreen $list_screen, ColumnSize\ListStorage $column_size_list_storage, ColumnSize\UserStorage $column_size_user_storage ) {
		$this->location = $location;
		$this->list_screen = $list_screen;
		$this->column_size_list_storage = $column_size_list_storage;
		$this->column_size_user_storage = $column_size_user_storage;
	}

	/**
	 * Register hooks
	 */
	public function register() {
		$controller = new ScreenController( $this->list_screen );
		$controller->register();

		$render = new TableFormView( $this->list_screen->get_meta_type(), sprintf( '<input type="hidden" name="layout" value="%s">', $this->list_screen->get_layout_id() ) );
		$render->register();

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		add_action( 'admin_footer', [ $this, 'admin_footer_scripts' ] );
		add_action( 'admin_head', [ $this, 'admin_head_scripts' ] );
		add_action( 'admin_head', [ $this, 'register_settings_button' ] );
		add_filter( 'admin_body_class', [ $this, 'admin_class' ] );
		add_filter( 'list_table_primary_column', [ $this, 'set_primary_column' ], 20 );
		add_action( 'admin_footer', [ $this, 'render_actions' ] );
		add_filter( 'screen_settings', [ $this, 'screen_options' ] );
	}

	/**
	 * @return Button[]
	 */
	public function get_buttons() {
		return array_merge( [], ...$this->buttons );
	}

	/**
	 * @param Button $button
	 * @param int    $priority
	 *
	 * @return bool
	 */
	public function register_button( Button $button, $priority = 10 ) {
		$this->buttons[ $priority ][] = $button;

		ksort( $this->buttons, SORT_NUMERIC );

		return true;
	}

	/**
	 * Set the primary columns. Used to place the actions bar.
	 *
	 * @param $default
	 *
	 * @return int|null|string
	 * @since 2.5.5
	 */
	public function set_primary_column( $default ) {

		if ( ! $this->list_screen->get_column_by_name( $default ) ) {
			$default = key( $this->list_screen->get_columns() );
		}

		// If actions column is present, set it as primary
		foreach ( $this->list_screen->get_columns() as $column ) {
			if ( 'column-actions' === $column->get_type() ) {
				$default = $column->get_name();

				if ( $this->list_screen instanceof ListScreen\Media ) {

					// Add download button to the actions column
					add_filter( 'media_row_actions', [ $this, 'set_media_row_actions' ], 10, 2 );
				}
			}
		}

		// Set inline edit data if the default column (title) is not present
		if ( $this->list_screen instanceof ListScreen\Post && 'title' !== $default ) {
			add_filter( 'page_row_actions', [ $this, 'set_inline_edit_data' ], 20, 2 );
			add_filter( 'post_row_actions', [ $this, 'set_inline_edit_data' ], 20, 2 );
		}

		// Remove inline edit action if the default column (author) is not present
		if ( $this->list_screen instanceof ListScreen\Comment && 'comment' !== $default ) {
			add_filter( 'comment_row_actions', [ $this, 'remove_quick_edit_from_actions' ], 20, 2 );
		}

		return $default;
	}

	/**
	 * Add a download link to the table screen
	 *
	 * @param array   $actions
	 * @param WP_Post $post
	 *
	 * @return array
	 */
	public function set_media_row_actions( $actions, $post ) {
		$link_attributes = [
			'download' => '',
			'title'    => __( 'Download', 'codepress-admin-columns' ),
		];
		$actions['download'] = ac_helper()->html->link( wp_get_attachment_url( $post->ID ), __( 'Download', 'codepress-admin-columns' ), $link_attributes );

		return $actions;
	}

	/**
	 * Sets the inline data when the title columns is not present on a AC\ListScreen_Post screen
	 *
	 * @param array   $actions
	 * @param WP_Post $post
	 *
	 * @return array
	 */
	public function set_inline_edit_data( $actions, $post ) {
		get_inline_data( $post );

		return $actions;
	}

	/**
	 * Remove quick edit from actions
	 *
	 * @param array $actions
	 *
	 * @return array
	 */
	public function remove_quick_edit_from_actions( $actions ) {
		unset( $actions['quickedit'] );

		return $actions;
	}

	/**
	 * Adds a body class which is used to set individual column widths
	 *
	 * @param string $classes body classes
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function admin_class( $classes ) {
		$classes .= ' ac-' . $this->list_screen->get_key();

		return apply_filters( 'ac/table/body_class', $classes, $this );
	}

	/**
	 * @since 3.2.5
	 */
	public function register_settings_button() {
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		$edit_button = new Settings\Option\EditButton();

		if ( ! $edit_button->is_enabled() ) {
			return;
		}

		$edit_link = $this->list_screen->get_edit_link();

		if ( ! $edit_link ) {
			return;
		}

		$button = new Button( 'edit-columns' );
		$button->set_label( __( 'Edit columns', 'codepress-admin-columns' ) )
		       ->set_url( $edit_link )
		       ->set_dashicon( 'admin-generic' );

		$this->register_button( $button, 1 );
	}

	/**
	 * @since 2.2.4
	 */
	public function admin_scripts() {
		$script = new Asset\Script( 'ac-table', $this->location->with_suffix( 'assets/js/table.js' ), [ 'jquery' ] );
		$script->enqueue();

		$style = new Asset\Style( 'ac-table', $this->location->with_suffix( 'assets/css/table.css' ) );
		$style->enqueue();

		wp_localize_script( 'ac-table', 'AC',
			[
				'assets'           => $this->location->with_suffix( 'assets/' )->get_url(),
				'list_screen'      => $this->list_screen->get_key(),
				'layout'           => $this->list_screen->get_layout_id(),
				'column_types'     => $this->get_column_types_mapping(),
				'ajax_nonce'       => wp_create_nonce( 'ac-ajax' ),
				'table_id'         => $this->list_screen->get_table_attr_id(),
				'screen'           => $this->get_current_screen_id(),
				'meta_type'        => $this->list_screen->get_meta_type(),
				'list_screen_link' => $this->get_list_screen_clear_link(),
			]
		);

		wp_localize_script( 'ac-table', 'AC_I18N',
			[
				'value_loading' => __( 'Loading...', 'codepress-admin-columns' ),
				'edit'          => __( 'Edit', 'codepress-admin-columns' ),
			]
		);

		/**
		 * @param ListScreen $list_screen
		 */
		do_action( 'ac/table_scripts', $this->list_screen, $this );

		// Column specific scripts
		foreach ( $this->list_screen->get_columns() as $column ) {
			$column->scripts();
		}
	}

	/**
	 * @return string
	 */
	private function get_list_screen_clear_link() {

		$query_args_whitelist = [
			'layout',
			'orderby',
			'order',
		];

		switch ( true ) {
			case $this->list_screen instanceof ListScreen\Post :
				$query_args_whitelist[] = 'post_status';
				break;
			case $this->list_screen instanceof ListScreen\User :
				$query_args_whitelist[] = 'role';
				break;
			case $this->list_screen instanceof ListScreen\Comment :
				$query_args_whitelist[] = 'comment_status';
				break;
		}

		$args = [];

		foreach ( $query_args_whitelist as $query_arg ) {
			if ( isset( $_GET[ $query_arg ] ) ) {
				$args[ $query_arg ] = $_GET[ $query_arg ];
			}
		}

		return add_query_arg( $args, $this->list_screen->get_screen_link() );
	}

	/**
	 * @return false|string
	 */
	private function get_current_screen_id() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return false;
		}

		return $screen->id;
	}

	/**
	 * @return array
	 */
	private function get_column_types_mapping() {
		$types = [];
		foreach ( $this->list_screen->get_columns() as $column ) {
			$types[ $column->get_name() ] = $column->get_type();
		}

		return $types;
	}

	/**
	 * @return ListScreen
	 */
	public function get_list_screen() {
		return $this->list_screen;
	}

	/**
	 * Admin header scripts
	 * @since 3.1.4
	 */
	public function admin_head_scripts() {
		$inline_style = new AC\Table\InlineStyle\ColumnSize(
			$this->list_screen,
			$this->column_size_list_storage,
			$this->column_size_user_storage
		);

		echo $inline_style->render();

		/**
		 * Add header scripts that only apply to column screens.
		 *
		 * @param ListScreen
		 * @param self
		 *
		 * @since 3.1.4
		 */
		do_action( 'ac/admin_head', $this->list_screen, $this );
	}

	/**
	 * Admin footer scripts
	 * @since 1.4.0
	 */
	public function admin_footer_scripts() {
		/**
		 * Add footer scripts that only apply to column screens.
		 *
		 * @param ListScreen
		 * @param self
		 *
		 * @since 2.3.5
		 */
		do_action( 'ac/admin_footer', $this->list_screen, $this );
	}

	/**
	 * @since 3.2.5
	 */
	public function render_actions() {
		?>
		<div id="ac-table-actions" class="ac-table-actions">

			<?php $this->render_buttons(); ?>

			<?php do_action( 'ac/table/actions', $this ); ?>
		</div>
		<?php
	}

	private function render_buttons() {
		?>
		<div class="ac-table-actions-buttons">
			<?php
			foreach ( $this->get_buttons() as $button ) {
				$button->render();
			}
			?>
		</div>
		<?php
	}

	/**
	 * @param Renderable $option
	 */
	public function register_screen_option( Renderable $option ) {
		$this->screen_options[] = $option;
	}

	/**
	 * @param string $html
	 *
	 * @return string
	 */
	public function screen_options( $html ) {
		if ( empty( $this->screen_options ) ) {
			return $html;
		}

		ob_start();
		?>

		<fieldset class='acp-screen-option-prefs'>
			<legend><?= __( 'Admin Columns', 'codepress-admin-columns' ); ?></legend>
			<div class="acp-so-container">
				<?php

				foreach ( $this->screen_options as $option ) {
					echo $option->render();
				}

				?>
			</div>
		</fieldset>

		<?php

		$html .= ob_get_clean();

		return $html;
	}

}