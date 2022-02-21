<?php

namespace AC\Plugin;

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
		$this->version_key = (string) $version_key;
		$this->version = $version;
	}

	/**
	 * @return Setup
	 */
	public function create() {
		$installers = [
			new Install\Capabilities(),
			new Install\Database(),
		];

		$updates = [
			new Update\V3005(),
			new Update\V3007(),
			new Update\V3201(),
			new Update\V4000(),
		];

		$setup_builder = new SetupBuilder( $this->version_key, $this->version );

		return $setup_builder
			->set_installers( $installers )
			->set_updates( $updates )
			->build();
	}

}