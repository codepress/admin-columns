<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository;
use AC\ListScreenTypes;
use AC\Type\ListScreenId;
use DateTime;
use DomainException;
use Exception;
use LogicException;

// TODO David needs some love
final class Database implements ListScreenRepository {

	const TABLE = 'admin_columns';

	/**
	 * @var ListScreenTypes
	 */
	private $list_screen_types;

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
			self::KEY => null,
		], $args );

		$sql = '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE 1=1
		';

		$limit = '';
		$where = [];

		if ( $args['_id'] ) {
			$where[] = $wpdb->prepare( 'AND list_id = %s', $args['_id'] );
			$limit = 'LIMIT 1';
		}

		if ( $args[ self::KEY ] ) {
			$where[] = $wpdb->prepare( 'AND list_key = %s', $args[ self::KEY ] );
		}

		$sql .= implode( "\n", $where );

		if ( $limit ) {
			$sql .= "\n" . $limit;
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
	 * @param ListScreenId $list_id
	 *
	 * @return ListScreen
	 */
	public function find( ListScreenId $list_id ) {
		$list_screens = $this->find_all( [
			'_id' => $list_id,
		] );

		return $list_screens->current();
	}

	/**
	 * @param ListScreenId $list_id
	 *
	 * @return int
	 */
	private function get_id( ListScreenId $list_id ) {
		static $cached_list_ids = [];

		if ( in_array( $list_id->get_id(), $cached_list_ids, true ) ) {
			return $list_id->get_id();
		}

		$results = $this->get_results( [
			'_id' => $list_id->get_id(),
		] );

		if ( ! count( $results ) ) {
			return null;
		}

		$id = (int) current( $results )->id;
		$cached_list_ids[] = $id;

		return $id;
	}

	public function exists( ListScreenId $list_id ) {
		return null !== $this->get_id( $list_id );
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return void
	 */
	public function save( ListScreen $list_screen ) {
		global $wpdb;

		if ( empty( $list_screen->get_layout_id() ) || ! ListScreenId::is_valid_id( $list_screen->get_layout_id() ) ) {
			throw new LogicException( 'Invalid listscreen Id.' );
		}

		$id = $this->get_id( new ListScreenId( $list_screen->get_layout_id() ) );
		$table = $wpdb->prefix . self::TABLE;

		$args = [
			'list_id'       => $list_screen->get_layout_id(),
			'list_key'      => $list_screen->get_key(),
			'title'         => $list_screen->get_title(),
			'columns'       => serialize( $list_screen->get_settings() ),
			'settings'      => serialize( $list_screen->get_preferences() ),
			'date_modified' => $list_screen->get_updated()->format( 'Y-m-d H:i:s' ),
		];

		$format = array_fill( 0, 6, '%s' );

		if ( $id ) {
			$wpdb->update(
				$table,
				$args,
				[
					'id' => $id,
				],
				$format,
				[
					'%d',
				]
			);
		} else {
			$args['date_created'] = $args['date_modified'];
			$format[] = '%s';

			$wpdb->insert(
				$table,
				$args,
				$format
			);
		}
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

		// TODO David needed?
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

		return $list_screen;
	}

}