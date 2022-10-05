<?php
declare( strict_types=1 );

namespace AC\Translation;

class Confirmation implements Config {

	public static function get(): array {
		return [
			'confirmation' => [
				'ok'     => __( 'Ok', 'codepress-admin-columns' ),
				'cancel' => __( 'Cancel', 'codepress-admin-columns' ),
			],
		];
	}

}