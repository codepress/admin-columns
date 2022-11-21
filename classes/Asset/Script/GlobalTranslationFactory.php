<?php

namespace AC\Asset\Script;

use AC\Asset\Location\Absolute;
use AC\Asset\Script;
use AC\Asset\ScriptFactory;

class GlobalTranslationFactory implements ScriptFactory {

	public const HANDLE = 'ac-global-translations';

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

	public function create(): Script {
		$script = new Script( self::HANDLE, $this->location->with_suffix( 'assets/js/global-translations.js' ) );
		$script->localize( 'ac_global_translations', $this->translation );

		return $script;
	}

}