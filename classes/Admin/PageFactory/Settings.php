<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\Section;
use AC\Asset\Location;

class Settings implements PageFactoryInterface {

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	/**
	 * @var MenuFactoryInterface
	 */
	protected $menu_factory;

	/**
	 * @var bool
	 */
	private $is_acp_active;

	/**
	 * @var AC\Asset\Script\Localize\Translation
	 */
	private $global_translation;



	public function __construct(
		Location\Absolute $location,
		MenuFactoryInterface $menu_factory,
		bool $is_acp_active,
		AC\Asset\Script\Localize\Translation $global_translation
	) {
		$this->location = $location;
		$this->menu_factory = $menu_factory;
		$this->is_acp_active = $is_acp_active;
		$this->global_translation = $global_translation;
	}

	public function create() {
		$page = new Page\Settings(
			new AC\Admin\View\Menu( $this->menu_factory->create( 'settings' ) ),
			$this->location,
			$this->global_translation
		);

		$page->add_section( new Section\General( [ new Section\Partial\ShowEditButton() ] ) )
		     ->add_section( new Section\Restore(), 40 );

		if ( ! $this->is_acp_active ) {
			$page->add_section( new Section\ProCta(), 50 );
		}

		return $page;
	}

}