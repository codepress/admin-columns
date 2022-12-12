<?php

namespace AC;

use AC\Type\ListScreenId;
use WP_User;

interface ListScreenRepository {

	public function find( ListScreenId $id ): ?ListScreen;

	public function find_by_user( ListScreenId $id, WP_User $user ): ?ListScreen;

	public function exists( ListScreenId $id ): bool;

	public function find_all( string $order_by = null ): ListScreenCollection;

	public function find_all_by_key( string $key, string $order_by = null ): ListScreenCollection;

	public function find_all_by_user( string $key, WP_User $user, string $order_by = null ): ListScreenCollection;

}