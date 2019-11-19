<?php

namespace AC\Check;

use AC\Admin\Promo;
use AC\Registrable;

final class Promotion
	implements Registrable {

	/** @var Promo */
	private $promo;

	public function __construct( Promo $promo ) {
		$this->promo = $promo;
	}

	public function register() {
		// todo: see AddonAvailable
	}

}