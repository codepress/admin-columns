<?php

namespace AC\Admin\Asset\Script;

use AC\Asset\Location;
use AC\Asset\Script;
use AC\Asset\Script\Inline\Data\Variable;
use AC\Asset\Script\Inline\Position;
use AC\Asset\ScriptFactory;
use AC\Nonce;

class SettingsFactory implements ScriptFactory {

	const HANDLE = 'ac-admin-page-settings';

	/**
	 * @var Location\Absolute
	 */
	private $location;

	/**
	 * @var Script\Localize\Translation
	 */
	private $global_translation;

	public function __construct( Location\Absolute $location, Script\Localize\Translation $global_translation ) {
		$this->location = $location;
		$this->global_translation = $global_translation;
	}

	public function create(): Script {
		$script = new Script(
			self::HANDLE,
			$this->location->with_suffix( 'assets/js/admin-page-settings.js' )
		);

		$nonce = new Nonce\Ajax();

		$translation = new Script\Localize\Translation( $this->global_translation->get_translation( 'settings' ) );
		$translation = $translation->with_translation( new Script\Localize\Translation( [ 'confirmation' => $this->global_translation->get_translation( 'confirmation' ) ] ) );

		return $script->localize( 'AC_I18N', $translation )
		              ->add_inline( new Variable( 'AC', [
			              Nonce\Ajax::NAME => $nonce->create(),
		              ] ), Position::before() );
	}

}