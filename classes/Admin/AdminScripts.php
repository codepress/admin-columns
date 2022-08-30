<?php

namespace AC\Admin;

use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\Asset\Style;

class AdminScripts implements Enqueueables {

	/**
	 * @var Location\Absolute
	 */
	private $location;

	public function __construct( Location\Absolute $location ) {
		$this->location = $location;
	}

	public function get_assets() {
		return new Assets( [
			new Script( 'ac-admin-general', $this->location->with_suffix( 'assets/js/admin-general.js' ), [ 'jquery' ] ),
			new Style( 'ac-admin', $this->location->with_suffix( 'assets/css/admin-general.css' ) ),
		] );
	}

}