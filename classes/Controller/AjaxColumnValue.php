<?php

namespace AC\Controller;

use AC\Ajax;
use AC\Column\AjaxValue;
use AC\ListScreenRepository;
use AC\Registrable;
use AC\Type\ListScreenId;

class AjaxColumnValue implements Registrable {

	/**
	 * @var ListScreenRepository
	 */
	private $repository;

	public function __construct( ListScreenRepository $repository ) {
		$this->repository = $repository;
	}

	public function register() {
		$this->get_ajax_handler()->register();
	}

	private function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac_get_column_value' )
			->set_callback( [ $this, 'get_value' ] );

		return $handler;
	}

	public function get_value() {
		check_ajax_referer( 'ac-ajax' );

		// Get ID of entry to edit
		$id = (int) filter_input( INPUT_POST, 'pk' );

		if ( ! $id ) {
			wp_die( __( 'Invalid item ID.', 'codepress-admin-columns' ), null, 400 );
		}

		$list_screen = $this->repository->find( new ListScreenId( filter_input( INPUT_POST, 'layout' ) ) );

		if ( ! $list_screen ) {
			wp_die( __( 'Invalid list screen.', 'codepress-admin-columns' ), null, 400 );
		}

		$column = $list_screen->get_column_by_name( filter_input( INPUT_POST, 'column' ) );

		if ( ! $column ) {
			wp_die( __( 'Invalid column.', 'codepress-admin-columns' ), null, 400 );
		}

		if ( ! $column instanceof AjaxValue ) {
			wp_die( __( 'Invalid method.', 'codepress-admin-columns' ), null, 400 );
		}

		// Trigger ajax callback
		echo $column->get_ajax_value( $id );
		exit;
	}

}