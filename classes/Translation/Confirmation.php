<?php
declare( strict_types=1 );

namespace AC\Translation;

class Confirmation implements Config, Translation {

	public static function get(): array {
		return [
			'confirmation' => [

			],
		];
	}

	public function get_translation(): array {
		return [
			'confirmation' => [
				'ok'     => __( 'Ok', 'codepress-admin-columns' ),
				'cancel' => __( 'Cancel', 'codepress-admin-columns' ),
			]
		];
	}

}