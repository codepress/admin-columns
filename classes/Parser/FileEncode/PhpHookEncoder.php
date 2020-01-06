<?php

namespace AC\Parser\FileEncode;

use AC\ListScreenCollection;
use AC\Parser\FileEncode;

class PhpHookEncoder extends FileEncode {

	public function format( ListScreenCollection $listScreens ) {
		return sprintf( "add_action( 'ac/ready', function() { \n  ac_load_columns( %s );\n});", $this->var_export_php54( $this->encode->encode( $listScreens ) ) );
	}

	/**
	 * Outputs `var_export` to use valid php5.4 array syntax.
	 *
	 * @param mixed  $var
	 * @param string $indent
	 *
	 * @return string|null
	 */
	private function var_export_php54( $var, $indent = "" ) {
		switch ( gettype( $var ) ) {
			case "string":
				return '"' . addcslashes( $var, "\\\$\"\r\n\t\v\f" ) . '"';
			case "array":
				$indexed = array_keys( $var ) === range( 0, count( $var ) - 1 );

				$r = [];
				foreach ( $var as $key => $value ) {
					$r[] = "$indent    "
					       . ( $indexed ? "" : $this->var_export_php54( $key ) . " => " )
					       . $this->var_export_php54( $value, "$indent    " );
				}

				return $r
					? "[\n" . implode( ",\n", $r ) . "\n" . $indent . "]"
					: "[]";
			case "boolean":
				return $var
					? "TRUE"
					: "FALSE";
			default:
				return var_export( $var, true );
		}
	}

	public function get_file_type() {
		return 'php';
	}

}