<?php

namespace AC\Table;

use AC;
use AC\Asset;
use AC\Capabilities;
use AC\Form;
use AC\ListScreen;
use AC\Registrable;
use AC\Settings;
use WP_Post;

final class Screen implements Registrable {

	/**
	 * @var ListScreen $list_screen
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
	 * @var Asset\Location\Absolute
	 */
	private $location;

	public function __construct( Asset\Location\Absolute $location, ListScreen $list_screen ) {
		$this->location = $location;
		$this->list_screen = $list_screen;
	}

	/**
	 * Register hooks
	 */
	public function register() {
		$controller = new AC\ScreenController( $this->list_screen );
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
	 * Set the primary columns for the Admin Columns columns. Used to place the actions bar.
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

		// Tooltip
		$script = new Asset\Script( 'jquery-qtip2', $this->location->with_suffix( 'assets/external/qtip2/jquery.qtip.min.js' ), [ 'jquery' ] );
		$script->register();

		$style = new Asset\Style( 'jquery-qtip2', $this->location->with_suffix( 'assets/external/qtip2/jquery.qtip.min.css' ) );
		$style->enqueue();

		$script = new Asset\Script( 'ac-table', $this->location->with_suffix( 'assets/js/table.js' ), [ 'jquery', 'jquery-qtip2' ] );
		$script->enqueue();

		$style = new Asset\Style( 'ac-table', $this->location->with_suffix( 'assets/css/table.css' ) );
		$style->enqueue();

		wp_localize_script( 'ac-table', 'AC', [
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
	 * @deprecated 3.2.5
	 */
	public function get_current_list_screen() {
		_deprecated_function( __METHOD__, '3.2.5', 'AC\Table\Screen::get_list_screen()' );

		return $this->get_list_screen();
	}

	/**
	 * @return ListScreen
	 */
	public function get_list_screen() {
		return $this->list_screen;
	}

	/**
	 * Applies the width setting to the table headers
	 */
	private function display_width_styles() {
		if ( ! $this->list_screen->get_settings() ) {
			return;
		}

		// CSS: columns width
		$css_column_width = false;

		foreach ( $this->list_screen->get_columns() as $column ) {
			/* @var Settings\Column\Width $setting */
			$setting = $column->get_setting( 'width' );

			$width = $setting->get_display_width();

			if ( $width ) {
				$css_column_width .= '.ac-' . esc_attr( $this->list_screen->get_key() ) . ' .wrap table th.column-' . esc_attr( $column->get_name() ) . ' { width: ' . $width . ' !important; }';
				$css_column_width .= 'body.acp-overflow-table.ac-' . esc_attr( $this->list_screen->get_key() ) . ' .wrap th.column-' . esc_attr( $column->get_name() ) . ' { min-width: ' . $width . ' !important; }';
			}
		}

		if ( ! $css_column_width ) {
			return;
		}

		?>

		<style>
			@media screen and (min-width: 783px) {
			<?php echo $css_column_width; ?>
			}
		</style>

		<?php
	}

	/**
	 * Admin header scripts
	 * @since 3.1.4
	 */
	public function admin_head_scripts() {
		$this->display_width_styles();

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
		if ( ! $this->get_buttons() ) {
			return;
		}
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
	 * @param Form\Element $option
	 */
	public function register_screen_option( AC\Form\Element $option ) {
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
			<?php

			foreach ( $this->screen_options as $option ) {
				echo $option->render();
			}

			?>
		</fieldset>

		<?php

		$html .= ob_get_clean();

		return $html;
	}

}