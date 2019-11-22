<?php
namespace AC\Parser;

use AC\ListScreenCollection;
use RuntimeException;

class DecodeFactory {

	/**
	 * @param array $data
	 *
	 * @return ListScreenCollection
	 */
	public function create( array $data ) {
		switch ( $this->get_version( $data ) ) {

			case Version332::VERSION :
				$parser = new Version332();

				return $parser->decode( $data );
			case Version384::VERSION :
				$parser = new Version384();

				return $parser->decode( $data );
			case Version480::VERSION :
				$parser = new Version480();

				return $parser->decode( $data );
			default :

				throw new RuntimeException( 'Invalid format.' );
		}
	}

	private function get_version( array $data ) {
		if ( isset( $data['version'] ) ) {
			return $data['version'];
		}

		if ( $this->is_version384( $data ) ) {
			return Version384::VERSION;
		}

		if ( $this->is_version332( $data ) ) {
			return Version332::VERSION;
		}

		return false;
	}

	private function is_version384( array $data ) {
		foreach ( $data as $key => $list_screens_data ) {
			if ( is_array( $list_screens_data ) ) {
				foreach ( $list_screens_data as $list_screen_data ) {
					if ( array_key_exists( 'columns', $list_screen_data ) && array_key_exists( 'layout', $list_screen_data ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	private function is_version332( array $data ) {
		foreach ( $data as $key => $columns ) {
			if ( is_array( $columns ) ) {
				foreach ( $columns as $column_settings ) {
					if ( array_key_exists( 'column-name', $column_settings ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

}