<?php

namespace AC\Plugin;

class Updater {

	/**
	 * @var UpdateCollection
	 */
	private $update_collection;

	/**
	 * @var VersionStorage
	 */
	private $version_storage;

	/**
	 * @var NewInstallCheck
	 */
	private $new_install_check;

	public function __construct( UpdateCollection $update_collection, VersionStorage $version_storage, NewInstallCheck $new_install_check ) {
		$this->update_collection = $update_collection;
		$this->version_storage = $version_storage;
		$this->new_install_check = $new_install_check;
	}

	public function apply_updates() {
		if ( $this->new_install_check->is_new_install() ) {
			return;
		}

		array_map( [ $this, 'apply_update' ], $this->update_collection->get_copy() );
	}

	private function apply_update( Update $update ) {
		if ( $update->needs_update( $this->version_storage->get() ) ) {
			$update->apply_update();

			$this->version_storage->save( $update->get_version() );
		}
	}

}