<?php

namespace AC\Service;

use AC\Asset\Location\Absolute;
use AC\Asset\Script;
use AC\Registerable;

class Scripts implements Registerable {

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

	public function register() {
		( new Script\GlobalTranslationFactory( $this->location, $this->translation ) )->create();
	}

}