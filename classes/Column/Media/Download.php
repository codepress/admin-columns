<?php

namespace AC\Column\Media;

use AC\Column;

class Download extends Column {

	public function __construct() {
		$this->set_type( 'column-download' )
		     ->set_label( __( 'Download', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $id ) {
		return wp_get_attachment_url( $id );
	}

	public function get_value( $id ) {
		$url = $this->get_raw_value( $id );

		if ( ! $url ) {
			return $this->get_empty_char();
		}

		return sprintf(
			'<a class="ac-download cpacicon-download" href="%s" title="%s" download></a>',
			$url,
			esc_attr( $this->get_label() )
		);
	}

}