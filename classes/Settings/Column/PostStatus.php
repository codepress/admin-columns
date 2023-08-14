<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class PostStatus extends Settings\Column {

	const NAME = 'post_status';

	/**
	 * @var array
	 */
	private $post_status;

	protected function define_options() {
		return [ self::NAME => [ 'publish', 'private' ] ];
	}

	public function create_view() {
		$options = [];

		foreach ( get_post_stati( [ 'exclude_from_search' => false ] ) as $name ) {
			$options[ $name ] = $this->get_post_status_label( $name );
		}

		$setting = $this->create_element( 'multi-select' )
		                ->set_options( $options );

		return new View( [
			'label'   => __( 'Post Status', 'codepress-admin-columns' ),
			'setting' => $setting,
		] );
	}

	private function get_post_status_label( $key ) {
		$status = get_post_status_object( $key );

		return $status ? $status->label : $key;
	}

	/**
	 * @return array
	 */
	public function get_post_status() {
		return $this->post_status;
	}

	/**
	 * @param array $post_status
	 *
	 * @return true
	 */
	public function set_post_status( $post_status ) {
		$this->post_status = $post_status;

		return true;
	}

}