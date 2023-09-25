<?php declare( strict_types=1 );

namespace AC\Helper\Select;

final class ArrayMapper {

	public static function map( Options $options ): array {
		$items = [];

		foreach ( $options as $option ) {
			$item = [
				'text' => $option->get_label(),
			];

			if ( $option instanceof OptionGroup ) {
				$item['children'] = self::map( new Options( $option->get_options() ) );
			} else {
				$item['id'] = $option->get_value();
			}

			$items[] = $item;
		}

		return $items;
	}

}