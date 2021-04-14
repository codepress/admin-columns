<?php

namespace AC\Message;

use AC\Message;
use AC\View;

class InlineMessage extends Message {

	/**
	 * @var string|null
	 */
	private $class;

	public function __construct( $message, $class = null ) {
		parent::__construct( $message );

		$this->class = $class;
	}

	public function render() {
		$view = new View( [
			'message' => $this->message,
			'class'   => trim( $this->type . ' ' . $this->class ),
		] );
		$view->set_template( 'message/notice/inline' );

		return $view->render();
	}

}