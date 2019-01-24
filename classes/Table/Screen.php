<?php

namespace AC\Table;

use AC;
use AC\Capabilities;
use AC\Form;
use AC\ListScreen;
use AC\Registrable;
use AC\Settings;

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
	private $buttons = array();

	/**
	 * @param ListScreen $list_screen
	 */
	public function __construct( ListScreen $list_screen ) {
		$this->list_screen = $list_screen;
	}

	/**
	 * Register hooks
	 */
	public function register() {
		new AC\ScreenController( $this->list_screen );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_head_scripts' ) );
		add_action( 'admin_head', array( $this, 'register_settings_button' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
		add_filter( 'list_table_primary_column', array( $this, 'set_primary_column' ), 20 );
		add_action( 'admin_footer', array( $this, 'render_actions' ) );
		add_filter( 'screen_settings', array( $this, 'screen_options' ) );
	}

	/**
	 * @return Button[]
	 */
	public function get_buttons() {
		$buttons = array();

		foreach ( $this->buttons as $button ) {
			$buttons = array_merge( $buttons, $button );
		}

		return $buttons;
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
	 * @since 2.5.5
	 *
	 * @param $default
	 *
	 * @return int|null|string
	 */
	public function set_primary_column( $default ) {
		if ( $this->list_screen ) {

			if ( ! $this->list_screen->get_column_by_name( $default ) ) {
				$default = key( $this->list_screen->get_columns() );
			}

			// If actions column is present, set it as primary
			foreach ( $this->list_screen->get_columns() as $column ) {
				if ( 'column-actions' == $column->get_type() ) {
					$default = $column->get_name();

					if ( $this->list_screen instanceof ListScreen\Media ) {

						// Add download button to the actions column
						add_filter( 'media_row_actions', array( $this, 'set_media_row_actions' ), 10, 2 );
					}
				}
			};

			// Set inline edit data if the default column (title) is not present
			if ( $this->list_screen instanceof ListScreen\Post && 'title' !== $default ) {
				add_filter( 'page_row_actions', array( $this, 'set_inline_edit_data' ), 20, 2 );
				add_filter( 'post_row_actions', array( $this, 'set_inline_edit_data' ), 20, 2 );
			}

			// Remove inline edit action if the default column (author) is not present
			if ( $this->list_screen instanceof ListScreen\Comment && 'comment' !== $default ) {
				add_filter( 'comment_row_actions', array( $this, 'remove_quick_edit_from_actions' ), 20, 2 );
			}
		}

		return $default;
	}

	/**
	 * Add a download link to the table screen
	 *
	 * @param array    $actions
	 * @param \WP_Post $post
	 *
	 * @return array
	 */
	public function set_media_row_actions( $actions, $post ) {
		$link_attributes = array(
			'download' => '',
			'title'    => __( 'Download', 'codepress-admin-columns' ),
		);
		$actions['download'] = ac_helper()->html->link( wp_get_attachment_url( $post->ID ), __( 'Download', 'codepress-admin-columns' ), $link_attributes );

		return $actions;
	}

	/**
	 * Sets the inline data when the title columns is not present on a AC\ListScreen_Post screen
	 *
	 * @param array    $actions
	 * @param \WP_Post $post
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
	 * @since 1.4.0
	 *
	 * @param string $classes body classes
	 *
	 * @return string
	 */
	public function admin_class( $classes ) {
		$classes .= " ac-" . $this->list_screen->get_key();

		return apply_filters( 'ac/table/body_class', $classes, $this );
	}

	/**
	 * @since 3.2.5
	 */
	public function register_settings_button() {
		$edit_link = $this->get_edit_link();

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
		wp_register_script( 'jquery-qtip2', AC()->get_url() . "external/qtip2/jquery.qtip.min.js", array( 'jquery' ), AC()->get_version() );
		wp_enqueue_style( 'jquery-qtip2', AC()->get_url() . "external/qtip2/jquery.qtip.min.css", array(), AC()->get_version() );

		// Main
		wp_enqueue_script( 'ac-table', AC()->get_url() . "assets/js/table.js", array( 'jquery', 'jquery-qtip2' ), AC()->get_version() );
		wp_enqueue_style( 'ac-table', AC()->get_url() . "assets/css/table.css", array(), AC()->get_version() );

		wp_localize_script( 'ac-table', 'AC', array(
				'list_screen'  => $this->list_screen->get_key(),
				'layout'       => $this->list_screen->get_layout_id(),
				'column_types' => $this->get_column_types_mapping(),
				'ajax_nonce'   => wp_create_nonce( 'ac-ajax' ),
				'table_id'     => $this->list_screen->get_table_attr_id(),
				'screen'       => $this->get_current_screen_id(),
				'meta_type'    => $this->list_screen->get_meta_type(),
			)
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
		$types = array();
		foreach ( $this->list_screen->get_columns() as $column ) {
			$types[ $column->get_name() ] = $column->get_type();
		}

		return $types;
	}

	/**
	 * @deprecated 3.2.5
	 * @return ListScreen
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
				$css_column_width .= ".ac-" . esc_attr( $this->list_screen->get_key() ) . " .wrap table th.column-" . esc_attr( $column->get_name() ) . " { width: " . $width . " !important; }";
				$css_column_width .= "body.acp-overflow-table.ac-" . esc_attr( $this->list_screen->get_key() ) . " .wrap th.column-" . esc_attr( $column->get_name() ) . " { min-width: " . $width . " !important; }";
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
	 * @return string|false
	 */
	private function get_edit_link() {
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return false;
		}

		$button = new Settings\Admin\General\ShowEditButton();

		if ( ! $button->show_button() ) {
			return false;
		}

		return $this->list_screen->get_edit_link();
	}

	/**
	 * Admin header scripts
	 * @since 3.1.4
	 */
	public function admin_head_scripts() {
		$this->display_width_styles();

		/**
		 * Add header scripts that only apply to column screens.
		 * @since 3.1.4
		 *
		 * @param ListScreen
		 * @param self
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
		 * @since 2.3.5
		 *
		 * @param ListScreen
		 * @param self
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
			<legend>Admin Columns</legend>
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