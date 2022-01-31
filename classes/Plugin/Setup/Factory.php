<?php declare( strict_types=1 );

namespace AC\Plugin\Setup;

use AC\Plugin;

class Factory {

	/**
	 * @var string
	 */
	protected $version_key;

	/**
	 * @var Plugin\Version
	 */
	protected $version;

	/**
	 * @var Plugin\InstallCollection
	 */
	protected $installers;

	/**
	 * @var Plugin\UpdateCollection
	 */
	protected $updates;

	public function __construct(

	)

	public function create( $version_key, Plugin\Version $version ) {


		is_network_admin();
	}

}