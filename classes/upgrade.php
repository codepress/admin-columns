<?php

/**
 * Upgrade
 *
 * @since 2.0.0
 */
class CPAC_Upgrade {
	
	private $previous_version;
	private $current_version;
	
	function __construct() {		
		
		// get previous version
		$this->previous_version = get_option( 'cpac_version' );
		$this->current_version 	= CPAC_VERSION;
		
		// upgrades
		$this->flush_transients();
				
		// update version
		update_option( 'cpac_version', CPAC_VERSION );		
	}
	
	/**
	 * Flush transients
	 *
	 * @since 2.0.0
	 */
	private function flush_transients() {
				
		// flush this transient so new custom columns get's added.
		if ( $this->previous_version != $this->current_version ) {
			delete_transient( 'cpac_custom_columns' );
		}	
	}
}