<?php

namespace AC\Storage;

class NetworkOption implements KeyValuePair {

	/**
	 * @var bool
	 */
	protected $network_active;

	/**
	 * @var Option
	 */
	private $option;

	/**
	 * @var SiteOption
	 */
	private $site_option;

	public function __construct( $key, $network_active ) {
		$this->network_active = (bool) $network_active;
		$this->option = new Option( (string) $key );
		$this->site_option = new SiteOption( (string) $key );
	}

	public function get( array $args = [] ) {
		return $this->network_active
			? $this->site_option->get( $args )
			: $this->option->get( $args );
	}

	public function save( $data ) {
		$this->network_active
			? $this->site_option->save( $data )
			: $this->option->save( $data );
	}

	public function delete() {
		$this->network_active
			? $this->site_option->delete()
			: $this->option->delete();
	}

}