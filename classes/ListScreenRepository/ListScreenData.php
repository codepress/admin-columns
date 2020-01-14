<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenApiData;
use AC\ListScreenCollection;
use AC\ListScreenRepository;
use AC\Parser\DecodeFactory;
use RuntimeException;

class ListScreenData implements ListScreenRepository {

	/** @var DecodeFactory */
	private $decoder;

	/** @var ListScreenApiData */
	private $apiData;

	public function __construct( DecodeFactory $decodeFactory, ListScreenApiData $apiData ) {
		$this->decoder = $decodeFactory;
		$this->apiData = $apiData;
	}

	/**
	 * @return ListScreenCollection
	 */
	private function get_list_screens() {
		$list_screens = new ListScreenCollection();

		foreach ( $this->apiData->get() as $list_data ) {
			try {
				$_list_screens = $this->decoder->create( $list_data );
			} catch ( RuntimeException $e ) {
				continue;
			}

			$list_screens->add_collection( $_list_screens );
		}

		$this->set_read_only( $list_screens );

		return $list_screens;
	}

	private function set_read_only( ListScreenCollection $list_screens ) {
		/** @var ListScreen $list_screen */
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
		$list_screens = $this->get_list_screens();

		if ( isset( $args['key'] ) ) {
			$list_screens = ( new FilterStrategy\ByKey( $args['key'] ) )->filter( $list_screens );
		}

		return $list_screens;
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