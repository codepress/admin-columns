<?php
namespace AC\Parser\FileEncode;

use AC\ListScreenCollection;
use AC\Parser\FileEncode;

class PhpHookEncoder extends FileEncode {

	public function format( ListScreenCollection $listScreens ) {
		return sprintf( "add_action( 'ac/ready', function() { \n  ac_load_columns( %s );\n});", var_export( $this->encode->encode( $listScreens ), true ) );
	}

	public function get_file_type() {
		return 'php';
	}

}