<?php

namespace AC\Storage;

use AC\Preferences\Site;
use AC\Type\ListScreenId;

class UserColumnOrder {

	/**
	 * @var Site
	 */
	private $user_preference;

	public function __construct() {
		$this->user_preference = new Site( 'column_order' );
	}

	/**
	 * @param array $column_names
	 */
	public function save( ListScreenId $id, array $column_names ) {
		$this->user_preference->set(
			$id->get_id(),
			$column_names
		);
	}

	/**
	 * @param ListScreenId $id
	 *
	 * @return bool
	 */
	public function exists( ListScreenId $id ) {
		return null !== $this->get( $id );
	}

	/**
	 * @param ListScreenId $id
	 *
	 * @return array
	 */
	public function get( ListScreenId $id ) {
		return $this->user_preference->get(
			$id->get_id()
		);
	}

	/**
	 * @param ListScreenId $id
	 *
	 * @return void
	 */
	public function delete( ListScreenId $id ) {
		$this->user_preference->delete(
			$id->get_id()
		);
	}

}