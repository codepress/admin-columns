<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Asset\Location;
use AC\Deprecated\Hooks;
use AC\Renderable;

class Help implements PageFactoryInterface {

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
		return new Page\Help(
			new Hooks(),
			$this->location,
			$this->head
		);
	}

}