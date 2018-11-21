<?php
namespace AC\Settings;

abstract class Admin {

	/** @var string */
	protected $name;

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * @return string HTML
	 */
	abstract public function render();

	protected function settings() {
		return new General();
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	protected function get_default_label( $string ) {
		return sprintf( __( "Default is %s.", 'codepress-admin-columns' ), '<code>' . $string . '</code>' );
	}

}