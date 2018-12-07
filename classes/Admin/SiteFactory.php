<?php
namespace AC\Admin;

use AC\Admin;
use AC\Deprecated;

class SiteFactory {

	/** @var Admin */
	private $admin;

	/**
	 * @return Admin
	 */
	public function create() {
		if ( null === $this->admin ) {
			$this->admin = new Admin( 'options-general.php' );
		}

		return $this->admin;
	}

	public function register() {
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
	}

	public function settings_menu() {
		$admin = $this->create()
		              ->add_menu_item( Page\Columns::NAME )
		              ->add_menu_item( Page\Settings::NAME )
		              ->add_menu_item( Page\Addons::NAME );

		$counter = new Deprecated\Counter();

		if ( $counter->get() > 0 ) {
			$admin->add_menu_item( Page\Help::NAME );
		}

		$admin->settings_menu();
	}

}