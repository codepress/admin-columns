<?php

namespace AC\ListScreenRepository;

use AC\API;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenTypes;
use DateTime;
use LogicException;

class FilePHP implements ListScreenRepository {

	/** @var ListScreenTypes */
	private $list_screen_types;

	/** @var API */
	private $api;

	public function __construct( ListScreenTypes $list_screen_types, API $api ) {
		$this->list_screen_types = $list_screen_types;
		$this->api = $api;
	}

	/**
	 * @return ListScreenCollection
	 */
	private function get_list_screens() {
		$list_types = $this->api->get_data();

		$list_screens = new ListScreenCollection();

		if ( empty( $list_types ) ) {
			return $list_screens;
		}

		foreach ( $list_types as $list_type => $lists_data ) {
			foreach ( $lists_data as $list_data ) {
				if ( ! $this->list_screen_exists( $list_type ) ) {
					continue;
				}

				try {
					$list_screen = $this->create_list_screen( $list_type, $list_data );
				} catch ( LogicException $e ) {

					// todo: log exception
					continue;
				}

				$list_screens->push( $list_screen );
			}
		}

		return $list_screens;
	}

	/**
	 * @param string $key
	 * @param array  $data
	 *
	 * @return ListScreen
	 */
	private function create_list_screen( $key, array $data ) {
		$list_screen = $this->list_screen_types->get_list_screen_by_key( $key );

		$layout = $data['layout'];
		$columns = $data['columns'];

		if ( empty( $layout['id'] ) ) {
			throw new LogicException( 'Missing list screen ID.' );
		}

		$list_screen->set_title( ! empty( $layout['name'] ) ? $layout['name'] : ucfirst( $key ) )
		            ->set_read_only( true )
		            ->set_layout_id( $layout['id'] );

		if ( $columns ) {
			$list_screen->set_settings( $columns );
		}

		if ( isset( $layout['updated'] ) ) {
			$list_screen->set_updated( DateTime::createFromFormat( 'U', (int) $layout['updated'] ) );
		}

		$settings = [];
		if ( isset( $layout['roles'] ) ) {
			$settings['roles'] = $layout['roles'];
		}
		if ( isset( $layout['users'] ) ) {
			$settings['users'] = $layout['users'];
		}

		$list_screen->set_preferences( $settings );

		return $list_screen;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	private function list_screen_exists( $key ) {
		return null !== $this->list_screen_types->get_list_screen_by_key( $key );
	}

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [] ) {
		$list_screens = new ListScreenCollection();

		if ( ! isset( $args['key'] ) ) {
			return $list_screens;
		}

		$key = $args['key'];

		$api_data = $this->api->get_data();

		if ( ! isset( $api_data[ $key ] ) ) {
			return $list_screens;
		}

		$lists_data = $api_data[ $key ];

		foreach ( $lists_data as $list_data ) {
			$list_screens->push( $this->create_list_screen( $key, $list_data ) );
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