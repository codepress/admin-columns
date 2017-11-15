<?php

class AC_Admin_Addons {

	/**
	 * @var AC_Admin_Addon[]
	 */
	private $addons;

	/**
	 * @return AC_Admin_Addon[]
	 */
	public function get_addons() {
		if ( null === $this->addons ) {
			$this->set_addons();
		}

		return $this->addons;
	}

	/**
	 * @return AC_Admin_Addon[]
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
	 * @return AC_Admin_Addon[]
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
	 * @return AC_Admin_Addon[]
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
	 * @return AC_Admin_Addon|false Returns addon details if the add-on exists, false otherwise
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
	 * @param AC_Admin_Addon $addon
	 */
	public function register_addon( AC_Admin_Addon $addon ) {
		$this->addons[] = $addon;
	}

	/**
	 * Register addon
	 */
	private function set_addons() {
		$classes = AC()->autoloader()->get_class_names_from_dir( AC()->get_plugin_dir() . 'classes/Admin/Addon', 'AC_' );

		foreach ( $classes as $class ) {
			$this->register_addon( new $class );
		}
	}

}