<?php

namespace AC\Controller;

use AC\Ajax;
use AC\Helper\Select;
use AC\Meta\Query;
use AC\Registerable;
use AC\Request;
use AC\Response;
use ACP\Helper\Select\Generic\GroupFormatter\BlogSite;
use ACP\Helper\Select\Generic\GroupFormatter\VisibilityType;
use ACP\Helper\Select\Generic\Groups;
use ACP\Helper\Select\Generic\Options;

class AjaxRequestCustomFieldKeys implements Registerable {

	public function register(): void
    {
		$this->get_ajax_handler()->register();
	}

	private function get_ajax_handler(): Ajax\Handler {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac_custom_field_options' )
			->set_callback( [ $this, 'ajax_get_custom_fields' ] );

		return $handler;
	}

	public function ajax_get_custom_fields(): void {
		$this->get_ajax_handler()->verify_request();

		$request = new Request();
		$response = new Response\Json();

		$post_type = $request->get( 'post_type' );

		$query = new Query( $request->get( 'meta_type' ) );

		$query->select( 'meta_key' )
		      ->distinct()
		      ->order_by( 'meta_key' );

		if ( $post_type ) {
			$query->where_post_type( $post_type );
		}

		$formatter = is_multisite()
			? new BlogSite()
			: new VisibilityType();

		$meta_keys = $query->get();

		$options = new Options( array_combine( $meta_keys, $meta_keys ) );
		$options = new Groups( $options, $formatter );

		$select = new Select\Response( $options );

		$response
			->set_parameters( $select() )
			->success();
	}

}