<?php
namespace AC\Admin\Request\Column;

use AC\Admin\Request\Handler;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenRepository;
use AC\Request;
use AC\Storage;

class Save extends Handler {

	/** @var ListScreenFactory */
	private $list_screen_factory;

	/** @var ListScreenRepository */
	private $list_screen_repository;

	public function __construct() {
		parent::__construct( 'save' );

		$this->list_screen_factory = new ListScreenFactory();
		$this->list_screen_repository = new ListScreenRepository\PostType();
	}

	public function request( Request $request ) {
		parse_str( $request->get( 'data' ), $formdata );

		if ( ! isset( $formdata['columns'] ) ) {
			wp_send_json_error( array(
					'type'    => 'error',
					'message' => __( 'You need at least one column', 'codepress-admin-columns' ),
				)
			);
		}

		// todo
		$list_id = $formdata['list_screen_id'];
		$type = $formdata['list_screen'];

		$list_screen = $this->list_screen_repository->find_by_id( $list_id );

		if ( ! $list_screen ) {
			$list_screen = $this->list_screen_factory->create( $type, new Storage\DataObject( [
				'title'    => isset( $formdata['title'] ) ? $formdata['title'] : __( 'Original', 'codepress-admin-columns' ),
				'columns'  => $formdata['columns'],
				'list_id'  => uniqid( 'ac' ),
			] ) );
		}

		// todo
		$this->list_screen_repository->save( $list_screen );

		do_action( 'ac/columns_stored', $list_screen );

		// Current storage
		// $result = $list_screen->store( $formdata['columns'] );

		$view_link = ac_helper()->html->link( $list_screen->get_screen_link(), sprintf( __( 'View %s screen', 'codepress-admin-columns' ), $list_screen->get_label() ) );

		// todo: do object hash compare to see if there were any changes
		$result = true;

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

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return string $label
	 */
	private function get_list_screen_message_label( ListScreen $list_screen ) {
		return apply_filters( 'ac/settings/list_screen_message_label', $list_screen->get_label(), $list_screen );
	}

}