<?php

namespace AC;

use ACP\Storage\ListScreens\DecoderAggregate;
use Iterator;

final class EncodedListScreenData implements Iterator {

	/**
	 * @var DecoderAggregate
	 */
	private $decoder_aggregate;

	/**
	 * @var ListScreen[]
	 */
	private $data;

	public function __construct( DecoderAggregate $decoder_aggregate ) {
		$this->decoder_aggregate = $decoder_aggregate;
	}

	public function add( array $data ) {
		foreach ( $this->decoder_aggregate->decode( $data ) as $list_screen ) {
			$this->data[] = $list_screen;
		}
	}

	public function rewind() {
		reset( $this->data );
	}

	/**
	 * @return ListScreen
	 */
	public function current() {
		return current( $this->data );
	}

	public function key() {
		return key( $this->data );
	}

	public function next() {
		return next( $this->data );
	}

	public function valid() {
		$key = $this->key();

		return ( $key !== null && $key !== false );
	}

}