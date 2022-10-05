<?php

namespace AC\Storage;

class UserMetaFactory {

	public function create( string $key, int $user_id ): UserMeta {
		return new UserMeta( $key, $user_id );
	}

	public function create_for_current_user( string $key ): UserMeta {
		return new UserMeta( $key, get_current_user_id() );
	}

}