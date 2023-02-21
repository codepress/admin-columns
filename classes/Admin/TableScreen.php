<?php
declare( strict_types=1 );

namespace AC\Admin;

class TableScreen {

	private $key;

	private $label;

	private $url;

	public function __construct( string $key, string $label, string $url ) {
		$this->key = $key;
		$this->label = $label;
		$this->url = $url;
	}

	public function get_key(): string {
		return $this->key;
	}

	public function get_label(): string {
		return $this->label;
	}

	public function get_url(): string {
		return $this->url;
	}

}