<?php

namespace AC\Admin\Main;

use AC\Admin\Section;
use AC\Admin\SectionCollection;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Renderable;
use AC\View;

class Settings implements Enqueueables, Renderable {

	const NAME = 'settings';

	/**
	 * @var SectionCollection
	 */
	protected $sections;

	public function __construct( SectionCollection $sections = null ) {
		if ( null === $sections ) {
			$sections = new SectionCollection();
		}

		$this->sections = $sections;
	}

	/**
	 * @param string $slug
	 *
	 * @return Section|null
	 */
	public function get_section( $slug ) {
		return $this->sections->get( $slug );
	}

	/**
	 * @param Section $section
	 *
	 * @return $this
	 */
	public function add_section( Section $section ) {
		$this->sections->add( $section );

		return $this;
	}

	public function get_assets() {
		$assets = new Assets();

		foreach ( $this->sections as $section ) {
			if ( $section instanceof Enqueueables ) {
				$assets->add_collection( $section->get_assets() );
			}
		}

		return $assets;
	}

	public function render() {
		$view = new View( [
			'sections' => $this->sections,
		] );

		return $view->set_template( 'admin/page/settings' )->render();
	}

}