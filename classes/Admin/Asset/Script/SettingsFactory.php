<?php

namespace AC\Admin\Asset\Script;

use AC\Asset\Location;
use AC\Asset\Script;
use AC\Asset\Script\Inline\Data\Variable;
use AC\Asset\Script\Inline\Position;
use AC\Asset\Script\Localize\Translation;
use AC\Asset\ScriptFactory;
use AC\Nonce;

class SettingsFactory implements ScriptFactory {

	public const HANDLE = 'ac-admin-page-settings';

	/**
	 * @var Location\Absolute
	 */
	private $location;

	/**
	 * @var Translation
	 */
	private $global_translations;

	public function __construct( Location\Absolute $location, Translation $global_translations ) {
		$this->location = $location;
		$this->global_translations = $global_translations;
	}

	public function create(): Script {
		$script = new Script(
			self::HANDLE,
			$this->location->with_suffix( 'assets/js/admin-page-settings.js' )
		);

		$nonce = new Nonce\Ajax();

		$data = [
			'restore_settings' => __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ),
		];

		$translation = Translation::create( $data )
		                          ->with_translation( $this->global_translations->get_translation( 'confirmation' ) );

		return $script->localize( 'AC_I18N', $translation )
		              ->add_inline( new Variable( 'AC', [
			              Nonce\Ajax::NAME => $nonce->create(),
		              ] ), Position::before() );
	}

}