<?php

namespace AC;

use AC\Type\ListScreenId;
use WP_User;

interface ListScreenRepository {

	public const KEY = 'key';
	public const ID = 'id';
	public const REQUIRE_USER = 'require_user';

	public function find( ListScreenId $id ): ?ListScreen;

	public function find_by_user( ListScreenId $id, WP_User $user ): ?ListScreen;

	// TODO remove
	//	public function exists( ListScreenId $id ): bool;

	public function find_all( string $order_by = null ): ListScreenCollection;

	public function find_all_by_key( string $key, string $order_by = null ): ListScreenCollection;

	public function find_all_by_user( string $key, WP_User $user, string $order_by = null ): ListScreenCollection;

	public function find_all_by_network( string $order_by = null ): ListScreenCollection;

	// TODO Tobias
	//public function find_by_list_key_using_permission( string $list_key ): ListScreenCollection;

}