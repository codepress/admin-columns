<?php

namespace AC\Column\Media;

/**
 * @since 4.3.2
 */
class Album extends Meta {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-meta_album' )
		     ->set_group( 'media-audio' )
		     ->set_label( __( 'Album', 'codepress-admin-columns' ) );
	}

	protected function get_sub_key() {
		return 'album';
	}

	public function get_value( $id ) {
		$meta = $this->get_raw_value( $id );

		return empty( $meta[ $this->get_sub_key() ] )
			? $this->get_empty_char()
			: $meta[ $this->get_sub_key() ];
	}

}