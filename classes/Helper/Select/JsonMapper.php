<?php declare( strict_types=1 );

namespace AC\Helper\Select;

final class JsonMapper {

	public function map( Options $options ): array {
		$mapping = [];

		foreach ( $options as $option ) {
			switch ( true ) {
				case $option instanceof OptionGroup:
					$mapping[] = [
						'text'     => $option->get_label(),
						'children' => self::map( $option->get_options() ),
					];

					break;
				case $option instanceof Option:
					$mapping[] = [
						'id'   => $option->get_value(),
						'text' => $option->get_label(),
					];

					break;
			}
		}

		return $mapping;
	}

}