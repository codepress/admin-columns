<?php

namespace AC;

class IntegrationFactory {

	/**
	 * @var Integrations
	 */
	private static $integrations;

	private static function get_integrations() {
		if ( self::$integrations === null ) {
			self::$integrations = new Integrations();
		}

		return self::$integrations;
	}

	/**
	 * @param string $basename
	 *
	 * @return Integration|false
	 */
	public static function create( $basename ) {
		foreach ( self::get_integrations() as $integration ) {
			if ( $integration->get_basename() === $basename ) {
				return $integration;
			}
		}

		return false;
	}

	/**
	 * @param string $dirname
	 *
	 * @return Integration|false
	 */
	public static function create_by_dirname( $dirname ) {
		foreach ( self::get_integrations() as $integration ) {
			if ( $integration->get_slug() === $dirname ) {
				return $integration;
			}
		}

		return false;
	}

}