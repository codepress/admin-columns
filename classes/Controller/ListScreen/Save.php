<?php

namespace AC\Controller\ListScreen;

use AC\Column\LabelEncoder;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\Request;
use AC\Type\ListScreenId;

class Save {

	private $storage;

	private $list_screen_factory;

	public function __construct( Storage $storage, ListScreenFactory $list_screen_factory ) {
		$this->storage = $storage;
		$this->list_screen_factory = $list_screen_factory;
	}

	public function request( Request $request ): void {
		$data = json_decode( $request->get( 'data' ), true );

		if ( ! isset( $data['columns'] ) ) {
			wp_send_json_error( [ 'message' => __( 'You need at least one column', 'codepress-admin-columns' ) ] );
		}

		$list_key = (string) ( $data['list_screen'] ?? '' );
		$list_id = $data['list_screen_id'] ?? '';

		if ( ! $this->list_screen_factory->can_create( $list_key ) ) {
			wp_send_json_error( [ 'message' => 'List screen not found' ] );
		}

		$list_id = ListScreenId::is_valid_id( $list_id )
			? new ListScreenId( $list_id )
			: ListScreenId::generate();

		$data = ( new Sanitize\FormData() )->sanitize( $data );

		$list_screen = $this->list_screen_factory->create(
			$list_key,
			[
				'list_id'     => $list_id->get_id(),
				'columns'     => $this->maybe_encode_urls( $data['columns'] ),
				'preferences' => $data['settings'] ?? [],
				'title'       => $data['title'] ?? '',
			]
		);

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
		] );
	}

	private function maybe_encode_urls( array $columndata ): array {
		foreach ( $columndata as $name => $data ) {
			if ( isset( $data['label'] ) ) {
				$columndata[ $name ]['label'] = ( new LabelEncoder() )->encode( $data['label'] );
			}
		}

		return $columndata;
	}

}