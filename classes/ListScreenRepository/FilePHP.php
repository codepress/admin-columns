<?php

namespace AC\ListScreenRepository;

use AC\API;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenFactory;
use AC\Storage\DataObject;

class FilePHP implements Read {

	/** @var ListScreenFactory */
	private $factory;

	/** @var API */
	private $api;

	public function __construct( ListScreenFactory $list_screen_factory, API $api ) {
		$this->factory = $list_screen_factory;
		$this->api = $api;
	}

	private function get_data_objects() {
		$data = [];
		$list_types = $this->api->get_data();

		if ( empty( $list_types ) ) {
			return $data;
		}

		foreach ( $this->api->get_data() as $list_type => $lists_data ) {
			foreach ( $lists_data as $list_data ) {

				$columns = $list_data['columns'];
				$layout = $list_data['layout'];

				$data[] = new DataObject( [
					'title'         => ! empty( $layout['name'] ) ? $layout['name'] : ucfirst( $list_type ),
					'type'          => $list_type,
					'columns'       => $columns,
					'list_id'       => $layout['id'],
					'date_modified' => isset( $layout['updated'] ) ? $layout['updated'] : false,
					'read_only'     => true,

					// todo: roles, users, other preferences
					'settings'      => [
						'roles' => $layout['roles'],
						'users' => $layout['users'],
					],
				] );
			}
		}

		return $data;
	}

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function query( array $args ) {
		if ( ! isset( $args['type'] ) ) {
			return new ListScreenCollection();
		}

		$type = $args['type'];

		$list_screens = [];

		foreach ( $this->get_data_objects() as $data_object ) {
			if ( $type === $data_object->type ) {
				$list_screens[] = $this->factory->create( $data_object->type, $data_object );
			}
		}

		return new ListScreenCollection( $list_screens );
	}

	/**
	 * @param string $id
	 *
	 * @return ListScreen|null
	 */
	public function find_by_id( $id ) {
		foreach ( $this->get_data_objects() as $data_object ) {
			if ( $id === $data_object->list_id ) {
				return $this->factory->create( $data_object->type, $data_object );
			}
		}

		return null;
	}

	public function exists( $id ) {
		return null !== $this->find_by_id( $id );
	}

}