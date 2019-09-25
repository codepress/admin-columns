<?php
namespace AC;

class PostTypes implements Registrable {

	const LIST_SCREEN_DATA = 'ac-listscreen-data';
	const COLUMN_DATA = 'ac-column-data';

	public function register() {
		add_action( 'init', [ $this, 'register_post_types' ] );
	}

	public function register_post_types() {
		register_post_type( self::LIST_SCREEN_DATA, [
			'public' => false,
		] );
		register_post_type( self::COLUMN_DATA, [
			'public' => false,
		] );
	}

}