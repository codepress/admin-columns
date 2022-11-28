<?php declare( strict_types=1 );

namespace AC\Form;

class NonceFactory {

	public function createAjax(): Nonce {
		return new Nonce( '_ajax_nonce', 'ac-ajax' );
	}

}