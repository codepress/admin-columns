<?php declare( strict_types=1 );

namespace AC\Asset\Script\Localize;

use InvalidArgumentException;

final class Translation {

	/**
	 * @var array
	 */
	private $translations;

	public function __construct( array $translations ) {
		$this->translations = $translations;
	}

	public static function create( array $translations ): self {
		return new self( $translations );
	}

	public function with_translation( array $translations ): self {
		return self::create( array_merge( $this->translations, $translations ) );
	}

	public function get_translation( string $component = null ): array {
		$translations = $this->translations;

		if ( $component ) {
			if ( ! isset( $translations[ $component ] ) ) {
				throw new InvalidArgumentException( sprintf( 'Undefined component %s for translation.', $component ) );
			}

			$translations = [
				$component => $translations[ $component ],
			];
		}

		return $translations;
	}

}