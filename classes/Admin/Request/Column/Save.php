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

	public function __construct( ListScreenFactory $list_screen_factory, ListScreenRepository $list_screen_repository ) {
		parent::__construct( 'save' );

		$this->list_screen_factory = $list_screen_factory;
		$this->list_screen_repository = $list_screen_repository;
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

		//$list_id = $request->get( 'list_screen_id' );
		$list_id = $formdata['list_screen_id'];
		//$type = $request->get( 'list_screen' );
		$type = $formdata['list_screen'];

		if ( ! $this->list_screen_repository->exists( $list_id ) ) {
			$list_id = uniqid( 'ac' );
		}

		$data = new Storage\DataObject( [
			'title'   => ! empty( $formdata['title'] ) ? $formdata['title'] : __( 'Original', 'codepress-admin-columns' ),
			'columns' => $formdata['columns'],
			'list_id' => $list_id,
		] );

		$list_screen = $this->list_screen_factory->create( $type, $data );

		$this->list_screen_repository->save( $list_screen );

		do_action( 'ac/columns_stored', $list_screen );

		$view_link = ac_helper()->html->link( $list_screen->get_screen_link(), sprintf( __( 'View %s screen', 'codepress-admin-columns' ), $list_screen->get_label() ) );

		wp_send_json_success(
			sprintf(
				__( 'Settings for %s updated successfully.', 'codepress-admin-columns' ),
				"<strong>" . esc_html( $this->get_list_screen_message_label( $list_screen ) ) . "</strong>"
			) . ' ' . $view_link
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