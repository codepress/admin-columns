<?php

namespace AC\Admin\Request\Column;

use AC\Admin\Request\Handler;
use AC\ListScreenRepository;
use AC\ListScreenTypes;
use AC\Request;

class Save extends Handler {

	/** @var ListScreenRepository\Aggregate */
	private $list_screen_repository;

	public function __construct( ListScreenRepository\Aggregate $list_screen_repository ) {
		parent::__construct( 'save' );

		$this->list_screen_repository = $list_screen_repository;
	}

	public function request( Request $request ) {
		parse_str( $request->get( 'data' ), $formdata );

		if ( ! isset( $formdata['columns'] ) ) {
			wp_send_json_error( [ 'message' => __( 'You need at least one column', 'codepress-admin-columns' ) ] );
		}

		$list_id = $formdata['list_screen_id'];
		$type = $formdata['list_screen'];

		if ( ! $this->list_screen_repository->exists( $list_id ) ) {
			$list_id = uniqid();
		}

		$formdata['columns'] = $this->maybe_encode_urls( $formdata['columns'] );

		$column_data = [];

		foreach ( $formdata['columns'] as $column_name => $settings ) {
			if ( 0 === strpos( $column_name, '_new_column_' ) ) {
				$column_data[ uniqid() ] = $settings;
			} else {
				$column_data[ $column_name ] = $settings;
			}
		}

		$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $type );

		if ( ! $list_screen ) {
			wp_send_json_error( [ 'message' => 'Failed: List screen not found.' ] );
		}

		$list_screen->set_title( ! empty( $formdata['title'] ) ? $formdata['title'] : $list_screen->get_label() )
		            ->set_settings( $column_data )
		            ->set_layout_id( $list_id )
		            ->set_preferences( ! empty( $formdata['settings'] ) ? $formdata['settings'] : [] );

		$this->list_screen_repository->save( $list_screen );

		do_action( 'ac/columns_stored', $list_screen );

		$view_link = ac_helper()->html->link( $list_screen->get_screen_link(), sprintf( __( 'View %s screen', 'codepress-admin-columns' ), $list_screen->get_label() ) );

		wp_send_json_success(
			sprintf(
				__( 'Settings for %s updated successfully.', 'codepress-admin-columns' ),
				"<strong>" . esc_html( $list_screen->get_title() ) . "</strong>"
			) . ' ' . $view_link
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