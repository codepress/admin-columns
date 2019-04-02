<?php

namespace AC\Settings;

use AC\Ajax;
use AC\Registrable;
use ACP\Helper;

class CustomField
	implements Registrable {

	public function register() {
		$this->get_ajax_handler()->register();
	}

	/**
	 * @return Ajax\Handler
	 */
	protected function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac_get_custom_fields_options' )
			->set_callback( array( $this, 'ajax_get_custom_fields' ) );

		return $handler;
	}

	public function ajax_get_custom_fields() {

		$entities = new Helper\Select\Entities\User( array() );

		$options = new Helper\Select\Options\Paginated(
			$entities,
			new Helper\Select\Formatter\UserName( $entities )
		);

		$select = new Helper\Select\Response( $options, ! $options->is_last_page() );
		echo '<pre>'; print_r( $select() ); echo '</pre>';
	}

}