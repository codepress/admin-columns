<?php

namespace AC;

// TODO David get a feel for this how and what. Is this a repositoru of some sort?
class ListScreenApiData {

	static $data = [];

	static function push( array $data ) {
		self::$data[] = $data;
	}

	static function get() {
		return self::$data;
	}

}

// TODO Implement here
//$decoders = [
//	new Decoder\Version510( new Storage\ListScreen\DecoderFactory( $this->types ) ),
//	new Decoder\Version400( $this->types ),
//	new Decoder\Version384( $this->types ),
//	new Decoder\Version332( $this->types ),
//];
//
//foreach ( $decoders as $decoder ) {
//	if ( $decoder->can_decode( $data ) ) {
//		$list_screens->add_collection( $decoder->decode( $data ) );
//	}
//}