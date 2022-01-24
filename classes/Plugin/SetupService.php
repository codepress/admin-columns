<?php

namespace AC\Plugin;

use AC\Plugin;
use AC\Registrable;

class SetupService implements Registrable {

	/**
	 * @var Plugin\SetupFactory\Site
	 */
	private $site_setup_factory;

	/**
	 * @var Plugin\SetupFactory\Network
	 */
	private $network_setup_factory;

	public function __construct( SetupFactory\Site $site_setup_factory, SetupFactory\Network $network_setup_factory ) {
		$this->site_setup_factory = $site_setup_factory;
		$this->network_setup_factory = $network_setup_factory;
	}

	public function register() {
		$this->create( is_network_admin() )->register();
	}

	/**
	 * @return Setup
	 */
	private function create( $is_network_admin ) {
		return $is_network_admin
			? $this->network_setup_factory->create( null, $this->get_install_collection() )
			: $this->site_setup_factory->create( $this->get_site_update_collection(), $this->get_install_collection() );
	}

	private function get_site_update_collection() {
		return new UpdateCollection(
			[
				new Update\V3005(),
				new Update\V3007(),
				new Update\V3201(),
				new Update\V4000(),
			]
		);
	}

	private function get_install_collection() {
		return new InstallCollection(
			[
				new Plugin\Install\Capabilities(),
				new Plugin\Install\Database(),
			]
		);
	}

}