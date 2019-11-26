<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\Parser\DecodeFactory;
use RuntimeException;

class FilePhp implements ListScreenRepository {

	/** @var DecodeFactory */
	private $decoder;

	public function __construct( DecodeFactory $decodeFactory ) {
		$this->decoder = $decodeFactory;
	}

	/**
	 * @return ListScreenCollection
	 */
	private function get_list_screens() {
		$list_screens = new ListScreenCollection();

		foreach ( FilePhpData::get() as $data ) {
			try {
				$_list_screens = $this->decoder->create( $data );
			} catch ( RuntimeException $e ) {
				continue;
			}

			$list_screens->add_collection( $_list_screens );
		}

		$this->set_read_only( $list_screens );

		return $list_screens;
	}

	private function set_read_only( ListScreenCollection $list_screens ) {
		foreach ( $list_screens as $list_screen ) {
			$list_screen->set_read_only( true );
		}
	}

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [] ) {
		$listScreens = $this->get_list_screens();

		if ( ! isset( $args['key'] ) ) {
			return $listScreens;
		}

		$filteredListScreens = new ListScreenCollection();

		/** @var ListScreen $list_screen */
		foreach ( $listScreens as $listScreen ) {
			if ( $listScreen->get_key() === $args['key'] ) {
				$filteredListScreens->push( $listScreen );
			}
		}

		return $filteredListScreens;
	}

	/**
	 * @param string $id
	 *
	 * @return ListScreen|null
	 */
	public function find( $id ) {
		foreach ( $this->get_list_screens() as $list_screen ) {
			if ( $list_screen->get_layout_id() == $id ) {
				return $list_screen;
			}
		}

		return null;
	}

	public function exists( $id ) {
		return null !== $this->find( $id );
	}

}