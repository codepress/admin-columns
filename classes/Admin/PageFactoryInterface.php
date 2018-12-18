<?php
namespace AC\Admin;

interface PageFactoryInterface {

	/**
	 * @param string $slug
	 *
	 * @return Page|false
	 */
	public function get( $slug );

	/**
	 * @return Page[]
	 */
	public function get_pages();


}