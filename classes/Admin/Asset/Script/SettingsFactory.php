<?php

namespace AC\Admin\Asset\Script;

use AC\Asset\Location;
use AC\Asset\Script;
use AC\Asset\Script\Localize\Translation;
use AC\Asset\ScriptFactory;
use AC\Form\NonceFactory;

final class SettingsFactory implements ScriptFactory {

	public const HANDLE = 'ac-admin-page-settings';

	/**
	 * @var Location\Absolute
	 */
	private $location;

	public function __construct( Location\Absolute $location ) {
		$this->location = $location;
	}

	public function create(): Script {
		$translations = [
			'restore_settings' => __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ),
		];;

		$nonce = ( new NonceFactory )->createAjax();

		$script = new Script( self::HANDLE, $this->location->with_suffix( 'assets/js/admin-page-settings.js' ), [ Script\GlobalTranslationFactory::HANDLE ] );
		$script->localize( 'AC_I18N', Translation::create( $translations ) )
		       ->add_inline_variable( 'AC', [
			       $nonce->get_name() => $nonce->create(),
		       ] );

		return $script;
	}

}