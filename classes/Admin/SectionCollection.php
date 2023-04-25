<?php

namespace AC\Admin;

class SectionCollection {

	private $items = [];

	public function add( Section $section, $priority = 10 ): self {
		$this->items[ (int) $priority ][ $section->get_slug() ] = $section;

		return $this;
	}

	public function get( $slug ) {
		$all = $this->all();

		return $all[ $slug ] ?? null;
	}

	public function all(): array {
		ksort( $this->items );

		return array_merge( ...$this->items );
	}

}