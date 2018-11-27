<?php

namespace AC\Admin\Controller;

use AC\Capabilities;
use AC\Column;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\Registrable;
use AC\View;

class Columns implements Registrable {

	public function register() {
		// separate ajax handlers
		add_action( 'wp_ajax_ac_column_select', array( $this, 'ajax_column_select' ) );
		add_action( 'wp_ajax_ac_column_refresh', array( $this, 'ajax_column_refresh' ) );
		add_action( 'wp_ajax_ac_columns_save', array( $this, 'ajax_columns_save' ) );
	}

	/**
	 * @param string $action
	 *
	 * @return bool
	 */
	private function verify_nonce( $action ) {
		return wp_verify_nonce( filter_input( INPUT_POST, '_ac_nonce' ), $action );
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
	 * Handle request
	 */
	public function handle_request() {
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		// Handle requests
		switch ( filter_input( INPUT_POST, 'cpac_action' ) ) {

			case 'restore_by_type' :
				if ( $this->verify_nonce( 'restore-type' ) ) {

					$list_screen = ListScreenFactory::create( filter_input( INPUT_POST, 'list_screen' ), filter_input( INPUT_POST, 'layout' ) );
					$list_screen->delete();

					// call column page for notice
					// todo: use Notice object
					//$this->notice( sprintf( __( 'Settings for %s restored successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $this->get_list_screen_message_label( $list_screen ) ) . "</strong>" ), 'updated' );
				}
				break;
		}

		do_action( 'ac/settings/handle_request', $this );
	}

	/**
	 * Display HTML markup for column type
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
	 * @since 2.0
	 *
	 * @param Column $column
	 */
	private function display_column( Column $column ) {
		$view = new View( array(
			'column' => $column,
		) );

		$view->set_template( 'admin/column' );

		echo $view;
	}

}