<?php declare( strict_types=1 );

namespace AC\Admin\Colors\Storage;

use AC;
use InvalidArgumentException;

final class OptionFactory extends AC\Storage\OptionFactory {

	private const PREFIX = '_ac_colors_';

	public function create( $key ): AC\Storage\Option {
		if ( strpos( $key, self::PREFIX ) === 0 ) {
			throw new InvalidArgumentException( 'Prefix is managed storage.' );
		}

		return parent::create( self::PREFIX . $key );
	}

}