<?php

namespace AC\Admin;

class SectionCollection {

	/**
	 * @var array
	 */
	private $items = [];

	public function add( Section $section, $priority = 10 ) {
		$this->items[ (int) $priority ][ $section->get_slug() ] = $section;

		return $this;
	}

	public function get( $slug ) {
		$all = $this->all();

		return isset( $all[ $slug ] )
			? $all[ $slug ]
			: null;
	}

	public function all() {
		ksort( $this->items );

		return array_merge( ...$this->items );
	}

}