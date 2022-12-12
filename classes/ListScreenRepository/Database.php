<?php

namespace AC\ListScreenRepository;

use AC\Exception\MissingListScreenIdException;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepositoryWritable;
use AC\ListScreenTypes;
use AC\Type\ListScreenId;
use DateTime;
use LogicException;
use stdClass;
use WP_User;

final class Database implements ListScreenRepositoryWritable {

	use ListScreenPermissionTrait;

	private const TABLE = 'admin_columns';

	private $list_screen_types;

	public function __construct( ListScreenTypes $list_screen_types ) {
		$this->list_screen_types = $list_screen_types;
	}

	private function find_all_from_database( array $args = [] ): array {
		global $wpdb;

		$args = array_merge( [
			'key' => null,
			'id'  => null,
		], $args );

		$sql = '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE 1=1
		';

		$where = [];

		if ( $args[ self::KEY ] ) {
			$where[] = $wpdb->prepare( 'AND list_key = %s', (string) $args[ self::KEY ] );
		}

		if ( $args[ self::ID ] instanceof ListScreenId ) {
			$where[] = $wpdb->prepare( 'AND list_id = %s', (string) $args[ self::ID ] );
		}

		$sql .= implode( "\n", $where );

		return $wpdb->get_results( $sql );
	}

	public function find_by_user( ListScreenId $id, WP_User $user ): ?ListScreen {
		$list_screen = $this->find( $id );

		return $list_screen && $this->user_can_view_list_screen( $list_screen, $user )
			? $list_screen
			: null;
	}

	public function find_all_by_key( string $key, string $order_by = null ): ListScreenCollection {
		$list_screens = new ListScreenCollection();

		foreach ( $this->find_all_from_database( [ self::KEY => $key ] ) as $list_data ) {
			$list_screen = $this->create_list_screen( $list_data );

			if ( ! $list_screen instanceof ListScreen ) {
				continue;
			}

			$list_screens->add( $list_screen );
		}

		return $this->order_list_screens( $list_screens, $order_by );
	}

	private function order_list_screens(
		ListScreenCollection $list_screens,
		string $order_by = null
	): ListScreenCollection {
		return ( new OrderByFactory() )->create( $order_by )
		                               ->sort( $list_screens );
	}

	public function find_all_by_user( string $key, WP_User $user, string $order_by = null ): ListScreenCollection {
		$list_screens = $this->find_all_by_key( $key, $order_by );

		// TODO test
		$list_screens = array_filter( (array) $list_screens, [ $this, 'user_can_view_list_screen' ] );

		return $this->order_list_screens(
			new ListScreenCollection( $list_screens ),
			$order_by
		);
	}

	public function find_all( string $order_by = null ): ListScreenCollection {
		$list_screens = new ListScreenCollection();

		foreach ( $this->find_all_from_database() as $list_data ) {
			$list_screen = $this->create_list_screen( $list_data );

			if ( ! $list_screen instanceof ListScreen ) {
				continue;
			}

			$list_screens->add( $list_screen );
		}

		return $this->order_list_screens(
			$list_screens,
			$order_by
		);
	}

	private function find_from_database( ListScreenId $id ): ?stdClass {
		$rows = $this->find_all_from_database( [ 'id' => $id ] );

		// TODO Test
		return $rows
			? $rows[0]
			: null;
	}

	private function create_list_screens( array $rows ): ListScreenCollection {
		$list_screens = array_filter( array_map( [ $this, 'create_list_screen' ], $rows ) );

		return new ListScreenCollection( $list_screens );
	}

	public function find( ListScreenId $id ): ?ListScreen {
		$row = $this->find_from_database( $id );

		return $row
			? $this->create_list_screen( $row )
			: null;
	}

	public function exists( ListScreenId $id ): bool {
		return null !== $this->find( $id );
	}

	public function save( ListScreen $list_screen ): void {
		global $wpdb;

		if ( ! $list_screen->has_id() ) {
			throw MissingListScreenIdException::from_saving_list_screen();
		}

		$args = [
			'list_id'       => $list_screen->get_layout_id(),
			'list_key'      => $list_screen->get_key(),
			'title'         => $list_screen->get_title(),
			'columns'       => $list_screen->get_settings() ? serialize( $list_screen->get_settings() ) : null,
			'settings'      => $list_screen->get_preferences() ? serialize( $list_screen->get_preferences() ) : null,
			'date_modified' => $list_screen->get_updated()->format( 'Y-m-d H:i:s' ),
		];

		$table = $wpdb->prefix . self::TABLE;
		$stored = $this->find_from_database( $list_screen->get_id() );

		if ( $stored ) {
			$wpdb->update(
				$table,
				$args,
				[
					'id' => $stored->id,
				],
				array_fill( 0, 6, '%s' ),
				[
					'%d',
				]
			);
		} else {
			$args['date_created'] = $args['date_modified'];

			$wpdb->insert(
				$table,
				$args,
				array_fill( 0, 7, '%s' )
			);
		}
	}

	public function delete( ListScreen $list_screen ): void {
		global $wpdb;

		if ( ! $list_screen->has_id() ) {
			throw new LogicException( 'Cannot delete a ListScreen without an identity.' );
		}

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
				'list_id' => $list_screen->get_id()->get_id(),
			],
			[
				'%s',
			]
		);
	}

	private function create_list_screen( object $data ): ?ListScreen {
		$list_screen = $this->list_screen_types->get_list_screen_by_key( $data->list_key );

		if ( $list_screen ) {
			$list_screen->set_title( $data->title )
			            ->set_layout_id( $data->list_id )
			            ->set_updated( DateTime::createFromFormat( 'Y-m-d H:i:s', $data->date_modified ) );

			if ( $data->settings ) {
				$list_screen->set_preferences( unserialize( $data->settings, [ 'allowed_classes' => false ] ) ?: [] );
			}

			if ( $data->columns ) {
				$list_screen->set_settings( unserialize( $data->columns, [ 'allowed_classes' => false ] ) ?: [] );
			}
		}

		return $list_screen;
	}

}