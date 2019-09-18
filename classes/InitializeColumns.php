<?php
namespace AC;

class InitializeColumns implements Registrable {

	/**
	 * @var DefaultColumns
	 */
	private $default_columns;

	public function __construct( DefaultColumns $default_columns ) {
		$this->default_columns = $default_columns;
	}

	public function register() {

		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
	}

	public function scripts() {





		if ( $this->default_columns->)
	}

}