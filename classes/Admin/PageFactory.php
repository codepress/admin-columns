<?php
namespace AC\Admin;

use AC\Admin;
use AC\Settings\Admin\General\ShowEditButton;

class PageFactory {

	public static function create( $slug ) {
		switch ( $slug ) {

			case 'help':
				return new Admin\Page\Help();

			case 'settings' :
				$general = new Admin\Section\General();
				$general->register_setting( new ShowEditButton );

				// todo: should we use actions here?
				do_action( 'ac/settings/general', $general );

				$settings = new Page\Settings();
				$settings->register_section( $general )
				         ->register_section( new Admin\Section\Restore );

				// todo: should we use actions here?
				do_action( 'ac/settings/sections', $settings );

				return $settings;

			default :
				return new Admin\Page\Columns();
		}
	}

}