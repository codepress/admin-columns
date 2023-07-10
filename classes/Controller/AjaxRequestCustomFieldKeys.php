<?php

namespace AC\Controller;

use AC\Ajax;
use AC\Helper\Select;
use AC\Registerable;
use AC\Request;
use AC\Response;

class AjaxRequestCustomFieldKeys implements Registerable {

	public function register(): void
    {
		$this->get_ajax_handler()->register();
	}

	private function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac_custom_field_options' )
			->set_callback( [ $this, 'ajax_get_custom_fields' ] );

		return $handler;
	}

	public function ajax_get_custom_fields() {
		$this->get_ajax_handler()->verify_request();

		$request = new Request();
		$response = new Response\Json();

		$args = [
			'meta_type' => $request->get( 'meta_type' ),
		];

		if ( $request->get( 'post_type' ) ) {
			$args['post_type'] = $request->get( 'post_type' );
		}

		$entities = new Select\Entities\CustomFields( $args );

		if ( is_multisite() ) {
			$formatter = new Select\Group\CustomField\MultiSite(
				new Select\Formatter\NullFormatter( $entities )
			);
		} else {
			$formatter = new Select\Group\CustomField(
				new Select\Formatter\NullFormatter( $entities )
			);
		}

		$options = new Select\Options\Paginated( $entities, $formatter );
		$select = new Select\Response( $options );

		$response
			->set_parameters( $select() )
			->success();
	}

}