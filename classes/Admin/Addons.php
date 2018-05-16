<?php

namespace AC\Admin;

use AC;

class Addons {

	/**
	 * @var Addon[]
	 */
	private $addons;

	/**
	 * @return Addon[]
	 */
	public function get_addons() {
		if ( null === $this->addons ) {
			$this->set_addons();
		}

		return $this->addons;
	}

	/**
	 * @return Addon[]
	 */
	public function get_active_promo() {
		$addons = $this->get_addons();

		foreach ( $addons as $k => $addon ) {
			if ( ! $addon->is_plugin_active() || $addon->is_active() ) {
				unset( $addons[ $k ] );
			}
		}

		return $addons;
	}

	/**
	 * All addons where 3d party is installed but integration is not installed
	 *
	 * @return Addon[]
	 */
	public function get_missing_addons() {
		$missing = array();

		foreach ( $this->get_addons() as $k => $addon ) {
			if ( $addon->is_plugin_active() && ! $addon->is_active() ) {
				$missing[] = $addon;
			}
		}

		return $missing;
	}

	/**
	 * @return Addon[]
	 */
	public function get_active_addons() {
		$addons = array();

		foreach ( $this->get_addons() as $addon ) {
			if ( $addon->is_active() ) {
				$addons[] = $addon;
			}
		}

		return $addons;
	}

	/**
	 * Get add-on details from the available add-ons list
	 *
	 * @since 2.2
	 *
	 * @param string $slug Addon slug
	 *
	 * @return Addon|false Returns addon details if the add-on exists, false otherwise
	 */
	public function get_addon( $slug ) {
		foreach ( $this->get_addons() as $addon ) {
			if ( $slug === $addon->get_slug() ) {
				return $addon;
			}
		}

		return false;
	}

	/**
	 * Register addon
	 */
	private function set_addons() {
		$this->addons = array();
		$classes = AC\Autoloader::instance()->get_class_names_from_dir( __NAMESPACE__ . '\Addon' );

		foreach ( $classes as $class ) {
			$this->addons[] = new $class;
		}
	}

}