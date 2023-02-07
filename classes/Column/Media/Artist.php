<?php

namespace AC\Column\Media;

/**
 * @since 4.3.2
 */
class Artist extends Meta {

	public function __construct() {
		$this->set_type( 'column-meta_artist' )
		     ->set_group( 'media-audio' )
		     ->set_label( __( 'Artist', 'codepress-admin-columns' ) );
	}

	protected function get_sub_key() {
		return 'artist';
	}

	public function get_value( $id ) {
		$meta = $this->get_raw_value( $id );

		return empty( $meta[ $this->get_sub_key() ] )
			? $this->get_empty_char()
			: $meta[ $this->get_sub_key() ];
	}

}