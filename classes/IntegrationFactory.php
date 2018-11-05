<?php

namespace AC;

class IntegrationFactory {

	public static function create( $basename ) {
		foreach ( Integrations::get() as $integration ) {
			if ( $integration->get_basename() === $basename ) {
				return $integration;
			}
		}

		return false;
	}

	public static function create_by_dirname( $dirname ) {
		foreach ( Integrations::get() as $integration ) {
			if ( $integration->get_slug() === $dirname ) {
				return $integration;
			}
		}

		return false;
	}

}