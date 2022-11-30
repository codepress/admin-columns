<?php declare( strict_types=1 );

namespace AC\Admin\Colors;

use AC\Admin\Colors\Storage\OptionFactory;
use AC\Admin\Colors\Type\Color;
use AC\Storage\Option;

final class ColorRepository implements ColorReader {

	private $storage;

	private $colors;

	public function __construct( OptionFactory $optionFactory ) {
		$this->storage = $optionFactory->create( 'colors' );
	}

	public function save( Color $color_to_add ): void {
		$colors = $this->find_all();
		$colors->add( $color_to_add );

		$data = [];

		foreach ( $colors as $color ) {
			$data[ $color->get_name() ] = $color->get_color();
		}

		$this->storage->save( $data );
	}

	public function find_all(): ColorCollection {
		if ( null === $this->colors ) {
			$this->colors = new ColorCollection();

			$data = $this->storage->get( [
				Option::OPTION_DEFAULT => [],
			] );

			foreach ( $data as $name => $color ) {
				$this->colors->add( new Color( $color, $name ) );
			}
		}

		return $this->colors;
	}

	public function find_with_name( string $name ): ?Color {
		foreach ( $this->find_all() as $color ) {
			if ( $color->get_name() === $name ) {
				return $color;
			}
		}

		return null;
	}

}