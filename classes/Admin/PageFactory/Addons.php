<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Asset\Location;
use AC\IntegrationRepository;
use AC\Renderable;

class Addons implements PageFactoryInterface {

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	/**
	 * @var IntegrationRepository
	 */
	protected $integrations;

	/**
	 * @var Renderable
	 */
	protected $head;

	public function __construct( Location\Absolute $location, IntegrationRepository $integrations, Renderable $head ) {
		$this->location = $location;
		$this->integrations = $integrations;
		$this->head = $head;
	}

	public function create() {
		return new Page\Addons(
			$this->location,
			$this->integrations,
			$this->head
		);
	}

}