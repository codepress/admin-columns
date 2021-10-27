<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\Section;
use AC\Asset\Location;
use AC\Renderable;

class Settings implements PageFactoryInterface {

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	/**
	 * @var Renderable
	 */
	protected $head;

	public function __construct( Location\Absolute $location, Renderable $head ) {
		$this->location = $location;
		$this->head = $head;
	}

	public function create() {
		$page = new Page\Settings(
			$this->head,
			$this->location
		);

		$page->add_section( new Section\General( [ new Section\Partial\ShowEditButton() ] ) )
		     ->add_section( new Section\Restore(), 40 );

		if ( ! ac_is_pro_active() ) {
			$page->add_section( new Section\ProCta(), 50 );
		}

		return $page;
	}

}