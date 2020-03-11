<?php

namespace AC\Message;

use AC\Message;
use AC\View;

class InlineMessage extends Message {

	public function render() {
		$view = new View( [
			'message' => $this->message,
			'type'    => $this->type,
		] );
		$view->set_template( 'message/notice/inline' );

		return $view->render();
	}

}