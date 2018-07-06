<?php

namespace AC\Table;

/**
 * Holds the buttons used on the overview pages
 *
 * @since NEWVERSION
 */
final class Buttons {

	/**
	 * @var array
	 */
	private $buttons = array();

	/**
	 * @return Button[]
	 */
	public function get_buttons() {

		$buttons = array();

		foreach ( $this->buttons as $button ) {
			$buttons = array_merge( $buttons, $button );
		}

		return $buttons;
	}

	public function register_button( Button $button, $priority = 10 ) {

		$this->buttons[ $priority ][] = $button;

		ksort( $this->buttons, SORT_NUMERIC );

		return true;
	}

}