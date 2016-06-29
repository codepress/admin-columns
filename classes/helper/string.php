<?php

class AC_Helper_String {
	/**
	 * @since 1.3
	 */
	public function strip_trim( $string ) {
		return trim( strip_tags( $string ) );
	}

}