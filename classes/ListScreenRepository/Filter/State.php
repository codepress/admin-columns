<?php

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use AC\ListscreenStateRepository;

class State implements Filter {

	/**
	 * @var ListscreenStateRepository
	 */
	private $list_screen_state_repository;

	public function __construct( ListscreenStateRepository $list_screen_state_repository ) {
		$this->list_screen_state_repository = $list_screen_state_repository;
	}

	public function filter( ListScreenCollection $list_screens ) {
		foreach ( clone $list_screens as $list_screen ) {
			if ( $this->list_screen_state_repository->is_disabled( $list_screen->get_id() ) ) {
				$list_screens->remove( $list_screen );
			}
		}

		return $list_screens;
	}

}