<?php
namespace AC\Admin;

interface PageFactoryInterface {

	/**
	 * @param string $slug
	 *
	 * @return Page|false
	 */
	public function create( $slug = false );

	/**
	 * @return array
	 */
	public function get_slugs();

}