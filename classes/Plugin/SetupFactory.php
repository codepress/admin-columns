<?php

namespace AC\Plugin;

use AC\Plugin;
use AC\Storage\Option;
use AC\Storage\SiteOption;

final class SetupFactory {

	/**
	 * @var string
	 */
	private $version_key;

	/**
	 * @var Version
	 */
	private $version;

	public function __construct( $version_key, Version $version ) {
		$this->version_key = $version_key;
		$this->version = $version;
	}

	/**
	 * @param $is_network_admin
	 *
	 * @return Setup
	 */
	public function create( $is_network_admin ) {
		$install_collection = new InstallCollection( [
			new Plugin\Install\Capabilities(),
			new Plugin\Install\Database(),
		] );

		if ( $is_network_admin ) {
			return new Plugin\Setup\Network(
				new SiteOption( $this->version_key ),
				$this->version,
				$install_collection,
				new UpdateCollection()
			);
		}

		return new Plugin\Setup\Site(
			new Option( $this->version_key ),
			$this->version,
			$install_collection,
			new UpdateCollection( [
				new Update\V3005(),
				new Update\V3007(),
				new Update\V3201(),
				new Update\V4000(),
			] )
		);
	}

}