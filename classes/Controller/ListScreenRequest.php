<?php

namespace AC\Controller;

use AC\ListScreen;
use AC\ListScreenRepository\ListScreenRepository;
use AC\ListScreenTypes;
use AC\Preferences;
use AC\Request;

class ListScreenRequest {

	/** @var Request */
	private $request;

	/** @var ListScreenRepository */
	private $repository;

	/** @var Preferences */
	private $preference;

	/** @var bool */
	private $is_network;

	public function __construct( Request $request, ListScreenRepository $repository, Preferences $preference, $is_network = false ) {
		$this->request = $request;
		$this->repository = $repository;
		$this->preference = $preference;
		$this->is_network = (bool) $is_network;
	}

	/**
	 * @param string $list_key
	 *
	 * @return bool
	 */
	private function exists_list_screen( $list_key ) {
		$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $list_key );

		if ( $this->is_network && $list_screen && ! $list_screen->is_network_only() ) {
			$list_screen = null;
		}

		return null !== $list_screen;
	}

	/**
	 * @param string $list_key
	 *
	 * @return ListScreen|null
	 */
	private function get_first_available_list_screen( $list_key ) {
		$list_screens = $this->repository->find_all( [ 'key' => $list_key ] );

		if ( $list_screens->count() < 1 ) {
			return null;
		}

		$list_screen = $list_screens->current();

		if ( $this->is_network && $list_screen && ! $list_screen->is_network_only() ) {
			$list_screen = null;
		}

		return $list_screen;
	}

	/**
	 * @return ListScreen
	 */
	public function get_list_screen() {

		// Requested list ID
		$list_id = filter_input( INPUT_GET, 'layout_id' );

		if ( $list_id && $this->repository->exists( $list_id ) ) {
			$list_screen = $this->repository->find( $list_id );

			$this->preference->set( 'list_id', $list_screen->get_layout_id() );
			$this->preference->set( 'list_key', $list_screen->get_key() );

			return $list_screen;
		}

		// Requested list type
		$list_key = filter_input( INPUT_GET, 'list_screen' );

		if ( $list_key && $this->exists_list_screen( $list_key ) ) {
			$this->preference->set( 'list_key', $list_key );

			$list_screen = $this->get_first_available_list_screen( $list_key );

			if ( $list_screen ) {
				$this->preference->set( 'list_id', $list_screen->get_layout_id() );

				return $list_screen;
			}

			// Initialize new
			return ListScreenTypes::instance()->get_list_screen_by_key( $list_key );
		}

		// Last visited ID
		$list_id = $this->preference->get( 'list_id' );

		if ( $list_id && $this->repository->exists( $list_id ) ) {
			return $this->repository->find( $list_id );
		}

		// Last visited Key
		$list_key = $this->preference->get( 'list_key' );

		// Load first available ID
		if ( $list_key && $this->exists_list_screen( $list_key ) ) {
			$this->preference->set( 'list_key', $list_key );

			$list_screen = $this->get_first_available_list_screen( $list_key );

			if ( $list_screen ) {
				$this->preference->set( 'list_id', $list_screen->get_layout_id() );

				return $list_screen;
			}

			// Initialize new
			return ListScreenTypes::instance()->get_list_screen_by_key( $list_key );
		}

		// First visit. Fetch key then first layout ID.
		$list_key = $this->get_first_available_list_screen_key();

		$this->preference->set( 'list_key', $list_key );

		$list_screen = $this->get_first_available_list_screen( $list_key );

		if ( $list_screen ) {
			$this->preference->set( 'list_id', $list_screen->get_layout_id() );

			return $list_screen;
		}

		// Initialize new
		return ListScreenTypes::instance()->get_list_screen_by_key( $list_key );
	}

	/**
	 * @return string
	 */
	private function get_first_available_list_screen_key() {
		$list_screens = ListScreenTypes::instance()->get_list_screens( [ 'network_only' => $this->is_network ] );

		return current( $list_screens )->get_key();
	}

}