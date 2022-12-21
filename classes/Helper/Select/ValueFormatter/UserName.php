<?php
declare( strict_types=1 );

namespace AC\Helper\Select\ValueFormatter;

use AC\Helper\Select\ValueFormatter;
use WP_User;

class UserName implements ValueFormatter {

	private $properties;

	public function __construct( array $properties = [] ) {
		$this->properties = array_merge( [
			'first_name',
			'last_name',
		], $properties );
	}

	public function format_value( $entity ): string {
		if ( is_numeric( $entity ) ) {
			$entity = get_userdata( $entity );
		}

		if ( ! $entity instanceof WP_User ) {
			return '';
		}

		return $this->get_label_user( $entity );
	}

	private function get_label_user( WP_User $user ): string {
		$name_parts = [];

		foreach ( $this->properties as $key ) {
			if ( $user->{$key} ) {
				$name_parts[] = $user->{$key};
			}
		}

		$label = implode( ' ', $name_parts );

		if ( ! $label ) {
			$label = $user->user_login;
		}

		$suffix = $user->user_email ?: $user->user_login;

		$label .= sprintf( ' (%s)', $suffix );

		return (string) apply_filters( 'acp/select/formatter/user_name', $label, $user );
	}

}