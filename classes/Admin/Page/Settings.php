<?php

namespace AC\Admin\Page;

use AC\Admin\Page;
use AC\Admin\Section;
use AC\Admin\SectionCollection;
use AC\View;

class Settings extends Page {

	const SLUG = 'settings';

	/**
	 * @var SectionCollection
	 */
	private $sections;

	public function __construct( SectionCollection $sections ) {
		parent::__construct( self::SLUG, __( 'Settings', 'codepress-admin-columns' ) );

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

	public function add_section( Section $section ) {
		$this->sections->put( $section->get_slug(), $section );
	}

	public function render() {
		$view = new View( [
			'sections' => $this->sections,
		] );
		$view->set_template( 'admin/page/settings' );

		return $view->render();
	}

}