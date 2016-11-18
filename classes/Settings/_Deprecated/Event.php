<?php

final class AC_Settings_Form_Event {

	/**
	 * Type of event like change or focus
	 *
	 * @var string
	 */
	public $event;

	/**
	 * A jQuery compatible selector in the DOM
	 *
	 * @var string
	 */
	public $target;

	/**
	 * @var string
	 */
	public $action;

	public function __construct( $event, $target, $action ) {
		$this->event = $event;
		$this->target = $target;
		$this->action = $action;
	}

	/**
	 * Create a toggle event on change
	 *
	 * @param $target
	 *
	 * @return AC_Settings_Form_Event
	 */
	public static function toggle( $target ) {
		return new self( 'change', $target, 'toggle' );
	}

}