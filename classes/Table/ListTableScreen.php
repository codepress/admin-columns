<?php
declare( strict_types=1 );

namespace AC\Table;

class ListTableScreen {

	private $list_key;

	private $label;

	private $url;

	private $site_type;

	private $group;

	public function __construct( string $list_key, string $label, string $url, string $site_type, string $group ) {
		$this->list_key = $list_key;
		$this->label = $label;
		$this->url = $url;
		$this->site_type = $site_type;
		$this->group = $group;
	}

	public function get_list_key(): string {
		return $this->list_key;
	}

	public function get_label(): string {
		return $this->label;
	}

	public function get_url(): string {
		return $this->url;
	}

	public function get_site_type(): string {
		return $this->site_type;
	}

	public function get_group(): string {
		return $this->group;
	}

}