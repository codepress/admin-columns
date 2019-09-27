<?php
namespace AC;

class PostTypes implements Registrable {

	const LIST_SCREEN_DATA = 'ac-listscreen-data';

	public function register() {
		add_action( 'init', [ $this, 'register_post_types' ] );
	}

	public function register_post_types() {
		register_post_type( self::LIST_SCREEN_DATA, [
			'public' => false,
		] );
	}

}