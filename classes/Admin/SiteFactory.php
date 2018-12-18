<?php
namespace AC\Admin;

use AC\Admin;

class SiteFactory {

	/** @var Admin */
	private $admin;

	/**
	 * @return Admin
	 */
	public function create() {
		if ( null === $this->admin ) {
			$this->admin = new Admin( 'options-general.php' );
			$this->admin->register_page_factory( new PageFactory() );
		}

		return $this->admin;
	}

	public function register() {
		add_action( 'admin_menu', array( $this->create(), 'settings_menu' ) );
	}

}