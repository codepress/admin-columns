<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\Preference;
use AC\Admin\Section;
use AC\Asset\Location;
use AC\DefaultColumnsRepository;
use AC\ListScreenRepository\Storage;
use AC\Renderable;

class Columns implements PageFactoryInterface {

	/**
	 * @var Storage
	 */
	protected $storage;

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	/**
	 * @var Renderable
	 */
	protected $head;

	public function __construct( Storage $storage, Location\Absolute $location, Renderable $head ) {
		$this->storage = $storage;
		$this->location = $location;
		$this->head = $head;
	}

	public function create() {
		return new Page\Columns(
			$this->location,
			new DefaultColumnsRepository(),
			new Section\Partial\Menu(),
			$this->storage,
			$this->head,
			new Preference\ListScreen()
		);
	}

}