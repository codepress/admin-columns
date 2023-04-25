<?php

declare( strict_types=1 );

namespace AC\ListScreenRepository;

use AC\Exception\MissingListScreenIdException;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenFactoryInterface;
use AC\ListScreenRepositoryWritable;
use AC\Type\ListScreenId;
use LogicException;
use stdClass;
use WP_User;

final class Database implements ListScreenRepositoryWritable {

	use ListScreenPermissionTrait;

	private const TABLE = 'admin_columns';

	private $list_screen_factory;

	public function __construct( ListScreenFactoryInterface $list_screen_factory ) {
		$this->list_screen_factory = $list_screen_factory;
	}

	private function find_from_database( ListScreenId $id ): ?stdClass {
		global $wpdb;
		$sql = $wpdb->prepare( '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE list_id = %s
		',
			(string) $id
		);

		$data = $wpdb->get_row( $sql );

		return $data instanceof stdClass
			? $data
			: null;
	}

	private function find_all_from_database(): array {
		global $wpdb;

		return $wpdb->get_results( '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
		' );
	}

	private function find_all_by_key_from_database( string $key ): array {
		global $wpdb;
		$sql = $wpdb->prepare( '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE list_key = %s
		',
			$key
		);

		return $wpdb->get_results( $sql );
	}

	public function find_by_user( ListScreenId $id, WP_User $user ): ?ListScreen {
		$list_screen = $this->find( $id );

		return $list_screen && $this->user_can_view_list_screen( $list_screen, $user )
			? $list_screen
			: null;
	}

	public function find_all_by_key( string $key, Sort $sort = null ): ListScreenCollection {
		$list_screens = $this->create_list_screens(
			$this->find_all_by_key_from_database( $key )
		);

		return $sort
			? $sort->sort( $list_screens )
			: $list_screens;
	}

	public function find_all_by_user( string $key, WP_User $user, Sort $sort = null ): ListScreenCollection {
		$list_screens = $this->find_all_by_key( $key, $sort );

		return ( new Filter\User( $user ) )->filter( $list_screens );
	}

	public function find_all( Sort $sort = null ): ListScreenCollection {
		$list_screens = $this->create_list_screens( $this->find_all_from_database() );

		return $sort
			? $sort->sort( $list_screens )
			: $list_screens;
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
				'list_id' => (string) $list_screen->get_id(),
			],
			[
				'%s',
			]
		);
	}

	private function create_list_screen( object $data ): ?ListScreen {
		if ( ! $this->list_screen_factory->can_create( $data->list_key ) ) {
			return null;
		}

		return $this->list_screen_factory->create(
			$data->list_key,
			[
				'title'       => $data->title,
				'list_id'     => $data->list_id,
				'date'        => $data->date_modified,
				'preferences' => $data->settings ? unserialize( $data->settings, [ 'allowed_classes' => false ] ) : [],
				'columns'     => $data->columns ? unserialize( $data->columns, [ 'allowed_classes' => false ] ) : [],
				'group'       => null,
			]
		);
	}

}