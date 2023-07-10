<?php

namespace AC\Service;

use AC\Asset\Location\Absolute;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Registerable;

class CommonAssets implements Registerable {

	/**
	 * @var Absolute
	 */
	private $location;

	/**
	 * @var Script\Localize\Translation
	 */
	private $translation;

	public function __construct( Absolute $location, Script\Localize\Translation $translation ) {
		$this->location = $location;
		$this->translation = $translation;
	}

	public function register(): void
    {
		add_action( 'admin_enqueue_scripts', [ $this, 'register_global_assets' ], 1 );
	}

	public function register_global_assets() {
		( new Script\GlobalTranslationFactory( $this->location, $this->translation ) )->create();
		( new Style( 'ac-utilities', $this->location->with_suffix( 'assets/css/utilities.css' ) ) )->register();
		( new Style( 'ac-ui', $this->location->with_suffix( 'assets/css/acui.css' ) ) )->register();
	}

}