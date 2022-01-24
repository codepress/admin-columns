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

	public function __construct( UpdateCollection $update_collection, VersionStorage $version_storage ) {
		$this->update_collection = $update_collection;
		$this->version_storage = $version_storage;
	}

	public function apply_updates() {
		array_map( [ $this, 'apply_update' ], $this->update_collection->get_copy() );
	}

	private function apply_update( Update $update ) {
		if ( $update->needs_update( $this->version_storage->get() ) ) {
			$update->apply_update();

			$this->version_storage->save( $update->get_version() );
		}
	}

}