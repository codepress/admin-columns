<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenTypes;
use DateTime;
use DomainException;
use Exception;
use LogicException;

// TODO David test this new implementation

final class Database implements Writable {

	const TABLE = 'admin_columns';

	/**
	 * @var ListScreenTypes
	 */
	private $list_screen_types;

	/**
	 * @var array
	 */
	private $cached_list_ids = [];

	public function __construct( ListScreenTypes $list_screen_types ) {
		$this->list_screen_types = $list_screen_types;
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	private function get_results( array $args = [] ) {
		global $wpdb;

		$args = array_merge( [
			'id'    => null,
			'limit' => null,
		], $args );

		$sql = '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE 1=1
		';

		if ( $args['id'] ) {
			$sql .= $wpdb->prepare( ' AND list_id = %s', $args['id'] ) . "\n";
		}

		if ( $args['limit'] ) {
			$sql .= 'LIMIT ' . absint( $args['limit'] ) . "\n";
		}

		return $wpdb->get_results( $sql );
	}

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [] ) {
		$list_screens = new ListScreenCollection();

		foreach ( $this->get_results( $args ) as $list_data ) {
			try {
				$list_screen = $this->create_list_screen( $list_data );
			} catch ( Exception $e ) {
				continue;
			}

			$list_screens->add( $list_screen );

		}

		return $list_screens;
	}

	/**
	 * @param string $list_id
	 *
	 * @return ListScreen
	 */
	public function find( $list_id ) {
		$list_screens = $this->find_all( [
			'id'    => $list_id,
			'limit' => 1,
		] );

		return $list_screens->current();
	}

	/**
	 * @param string $list_id
	 *
	 * @return int
	 */
	private function get_id( $list_id ) {
		if ( in_array( $list_id, $this->cached_list_ids, true ) ) {
			return $list_id;
		}

		$results = $this->get_results( [
			'id'    => $list_id,
			'limit' => 1,
		] );

		if ( ! count( $results ) ) {
			return null;
		}

		$id = (int) current( $results )->list_id;

		$this->cached_list_ids[] = $id;

		return $id;
	}

	public function exists( $list_id ) {
		return null !== $this->get_id( $list_id );
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return void
	 */
	public function save( ListScreen $list_screen ) {
		$id = $this->get_id( $list_screen->get_layout_id() );

		$id
			? $this->update( $id, $list_screen )
			: $this->create( $list_screen );
	}

	private function create( ListScreen $list_screen ) {
		global $wpdb;

		if ( empty( $list_screen->get_layout_id() ) ) {
			throw new LogicException( 'Invalid listscreen Id.' );
		}

		$wpdb->insert(
			$wpdb->prefix . self::TABLE,
			[
				'title'         => $list_screen->get_title(),
				'list_id'       => $list_screen->get_layout_id(),
				'list_key'      => $list_screen->get_key(),
				'columns'       => serialize( $list_screen->get_settings() ),
				'settings'      => serialize( $list_screen->get_preferences() ),
				'date_modified' => $list_screen->get_updated()->format( 'Y-m-d H:i:s' ),
				'date_created'  => $list_screen->get_updated()->format( 'Y-m-d H:i:s' ),
			],
			[
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			]
		);
	}

	private function update( $id, ListScreen $list_screen ) {
		// TODO David: Let update and save be a single call
		global $wpdb;

		if ( empty( $list_screen->get_layout_id() ) ) {
			throw new LogicException( 'Invalid listscreen Id.' );
		}

		$wpdb->update(
			$wpdb->prefix . self::TABLE,
			[
				'list_id'       => $list_screen->get_layout_id(),
				'list_key'      => $list_screen->get_key(),
				'title'         => $list_screen->get_title(),
				'columns'       => serialize( $list_screen->get_settings() ),
				'settings'      => serialize( $list_screen->get_preferences() ),
				'date_modified' => $list_screen->get_updated()->format( 'Y-m-d H:i:s' ),
			],
			[
				'id' => $id,
			],
			[
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			],
			[
				'%d',
			]
		);
	}

	public function delete( ListScreen $list_screen ) {
		global $wpdb;

		/**
		 * Fires before a column setup is removed from the database
		 * Primarily used when columns are deleted through the Admin Columns settings screen
		 *
		 * @param ListScreen $list_screen
		 *
		 * @deprecated 4.0
		 * @since      3.0.8
		 */
		do_action( 'ac/columns_delete', $list_screen );

		$wpdb->delete(
			$wpdb->prefix . self::TABLE,
			[
				'list_id' => $list_screen->get_layout_id(),
			],
			[
				'%s',
			]
		);
	}

	/**
	 * @param $data
	 *
	 * @return ListScreen
	 */
	private function create_list_screen( $data ) {
		$list_screen = $this->list_screen_types->get_list_screen_by_key( $data->list_key );

		if ( null === $list_screen ) {
			throw new DomainException( 'List screen not found.' );
		}

		$list_screen->set_title( $data->title )
		            ->set_layout_id( $data->list_id )
		            ->set_updated( DateTime::createFromFormat( 'Y-m-d H:i:s', $data->date_modified ) );

		if ( $data->settings ) {
			$list_screen->set_preferences( unserialize( $data->settings ) );
		}

		if ( $data->columns ) {
			$list_screen->set_settings( unserialize( $data->columns ) );
		}

		// TODO David need to decide on this
		$list_screen->set_source( $data->id );

		return $list_screen;
	}

}