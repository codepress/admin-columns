<?php declare( strict_types=1 );

namespace AC\Asset\Script\Localize;

use InvalidArgumentException;

final class Translation {

	/**
	 * @var array
	 */
	private $data;

	public function __construct( array $data ) {
		$this->data = $data;
	}

	public function get_translation( string $key = null ): array {
		$data = $this->data;

		if ( $key ) {
			if ( ! isset( $data[ $key ] ) ) {
				throw new InvalidArgumentException( sprintf( 'Undefined key %s for translation.', $key ) );
			}

			$data = $data[ $key ];
		}

		return $data;
	}

	public function with_translation( Translation $translation ): self {
		$data = $this->get_translation() + $translation->get_translation();

		return new self( $data );
	}

}