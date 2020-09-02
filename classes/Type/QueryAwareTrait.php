<?php

namespace AC\Type;

trait QueryAwareTrait {

	/**
	 * @var string
	 */
	protected $url;

	public function add_one( $key, $value ) {
		$this->url = add_query_arg( $key, $value, $this->url );
	}

	public function add( array $params = [] ) {
		foreach ( $params as $key => $value ) {
			$this->add_one( $key, $value );
		}
	}

	public function remove( $key ) {
		$this->url = remove_query_arg( $key, $this->url );
	}

	public function get_url() {
		return $this->url;
	}

}