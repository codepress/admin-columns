<?php

namespace AC\Controller\ListScreen;

use AC\ListScreenRepository\Storage;
use AC\ListScreenTypes;
use AC\Request;
use AC\Type\ListScreenId;

class Save {

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @var Sanitize\FormData
	 */
	private $sanitizer;

	public function __construct( Storage $storage ) {
		$this->storage = $storage;
		$this->sanitizer = new Sanitize\FormData();
	}

	public function request( Request $request ) {
		$data = json_decode( $request->get( 'data' ), true );

		if ( ! isset( $data['columns'] ) ) {
			wp_send_json_error( [ 'message' => __( 'You need at least one column', 'codepress-admin-columns' ) ] );
		}

		$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $data['list_screen'] );

		if ( ! $list_screen ) {
			wp_send_json_error( [ 'message' => 'List screen not found' ] );
		}

		$list_id = isset( $data['list_screen_id'] ) && ListScreenId::is_valid_id( $data['list_screen_id'] )
			? new ListScreenId( $data['list_screen_id'] )
			: ListScreenId::generate();

		$data = $this->sanitizer->sanitize( $data );

		$list_screen->set_title( ! empty( $data['title'] ) ? $data['title'] : $list_screen->get_label() )
		            ->set_settings( isset( $data['columns'] ) ? $this->maybe_encode_urls( $data['columns'] ) : [] )
		            ->set_layout_id( $list_id->get_id() )
		            ->set_preferences( ! empty( $data['settings'] ) ? $data['settings'] : [] );

		$this->storage->save( $list_screen );

		do_action( 'ac/columns_stored', $list_screen );

		wp_send_json_success( [
				'message' => sprintf(
					'%s %s',
					sprintf(
						__( 'Settings for %s updated successfully.', 'codepress-admin-columns' ),
						sprintf( '<strong>%s</strong>', esc_html( $list_screen->get_title() ) )
					),
					ac_helper()->html->link( $list_screen->get_screen_link(), sprintf( __( 'View %s screen', 'codepress-admin-columns' ), $list_screen->get_label() ) )
				),
				'list_id' => $list_id->get_id(),
			]
		);
	}

	private function maybe_encode_urls( array $columndata ) {
		foreach ( $columndata as $name => $data ) {
			if ( isset( $data['label'] ) ) {
				$columndata[ $name ]['label'] = ac_convert_site_url( $data['label'] );
			}
		}

		return $columndata;
	}

}