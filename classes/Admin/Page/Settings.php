<?php

namespace AC\Admin\Page;

use AC\Admin;
use AC\Admin\RenderableHead;
use AC\Admin\Section;
use AC\Admin\SectionCollection;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Script\Localize\Translation;
use AC\Renderable;
use AC\View;

class Settings implements Enqueueables, Renderable, RenderableHead {

	const NAME = 'settings';

	/**
	 * @var Renderable
	 */
	private $head;

	/**
	 * @var SectionCollection
	 */
	protected $sections;

	/**
	 * @var Location\Absolute
	 */
	private $location;

	/**
	 * @var Translation
	 */
	private $global_translation;

	public function __construct( Renderable $head, Location\Absolute $location, Translation $global_translation, SectionCollection $sections = null ) {
		if ( null === $sections ) {
			$sections = new SectionCollection();
		}

		$this->head = $head;
		$this->location = $location;
		$this->sections = $sections;
		$this->global_translation = $global_translation;
	}

	public function render_head() {
		return $this->head;
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
	 * @param int     $prio
	 *
	 * @return $this
	 */
	public function add_section( Section $section, $prio = 10 ) {
		$this->sections->add( $section, $prio );

		return $this;
	}

	public function get_assets() {
		$factory = new Admin\Asset\Script\SettingsFactory(
			$this->location,
			$this->global_translation
		);
		$factory->create();


		$assets = new Assets( [
			$factory->create()
			//new Admin\Asset\Settings( 'ac-admin-page-settings', $this->location->with_suffix( 'assets/js/admin-page-settings.js' ) ),
		] );

		foreach ( $this->sections->all() as $section ) {
			if ( $section instanceof Enqueueables ) {
				$assets->add_collection( $section->get_assets() );
			}
		}

		return $assets;
	}

	public function render() {
		$view = new View( [
			'sections' => $this->sections->all(),
		] );

		return $view->set_template( 'admin/page/settings' )->render();
	}

}