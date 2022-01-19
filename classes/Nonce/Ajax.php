<?php

namespace AC\Nonce;

use AC\Form\Nonce;

class Ajax extends Nonce {

	const NAME = '_ajax_nonce';
	const ACTION = 'ac-ajax';

	public function __construct() {
		parent::__construct( self::ACTION, self::NAME );
	}

}