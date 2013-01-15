<?php

/**
 * Upgrade
 *
 * @since 1.5
 */

class CPAC_Upgrade {

	/**
	 * Upgrade
	 *
	 * @since 1.5
	 */
	static function upgrade() {
		$version = get_option( 'cpac_version' );

		if ( version_compare( $version, '2.0', '<' ) ) {
			$options = (array) get_option( 'cpac_options' );

			// @todo make recursive
			if ( !empty($options['columns']) ) {
				foreach ( $options['columns'] as $k1 => $columns ) {
					if ( !empty( $columns ) ) {
						foreach ( $columns as $k2 => $column ) {
							if ( !empty( $column ) ) {
								foreach ( $column as $k3 => $value ) {
									if ( 'field_type' == $k3 && 'library_id' == $value ) {
										$options['columns'][$k1][$k2][$k3] = 'image';
									}
								}
							}
						}
					}
				}
			}

			update_option( 'cpac_options', $options );
		}

		update_option( 'cpac_version', CPAC_VERSION);
	}
}