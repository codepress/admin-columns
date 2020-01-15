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

	/** @var ListScreenTypes */
	private $types;

	/** @var ListScreenRepository */
	private $repository;

	/** @var Preferences\Site */
	private $preference;

	public function __construct( Request $request, ListScreenTypes $types, ListScreenRepository $repository, Preferences\Site $preference ) {
		$this->request = $request;
		$this->types = $types;
		$this->repository = $repository;
		$this->preference = $preference;
		$this->preference = new Preferences\Site( 'settings' );
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

		if ( $list_key ) {
			/** @var ListScreen $list_screen */
			$list_screens = $this->repository->find_all( [ 'key' => $list_key ] );
			$list_screen = $list_screens->current();

			if ( $list_screen ) {
				$this->preference->set( 'list_id', $list_screen->get_layout_id() );
				$this->preference->set( 'list_key', $list_screen->get_key() );

				return $list_screen;
			}

			return $this->types->get_list_screen_by_key( $list_key );
		}

		// Last visited ID
		$list_id = $this->preference->get( 'list_id' );

		if ( $list_id && $this->repository->exists( $list_id ) ) {
			return $this->repository->find( $list_id );
		}

		// Last visited Key
		$list_key = $this->preference->get( 'list_key' );

		if ( $list_key && $this->types->get_list_screen_by_key( $list_key ) ) {
			return $this->types->get_list_screen_by_key( $list_key );
		}

		// Initialize new
		$types = $this->types->get_list_screens();

		$list_screen = $this->types->get_list_screen_by_key( current( $types )->get_key() );


		// todo: make sure we always get a list screen. That not the case atm. It could be `null`.

		return $list_screen;
	}

}