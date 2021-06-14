<?php

namespace AC;

class IntegrationRepository {

	/**
	 * @return Integration[]
	 */
	private function all() {
		return [
			new Integration\ACF(),
			new Integration\BuddyPress(),
			new Integration\EventsCalendar(),
			new Integration\GravityForms(),
			new Integration\NinjaForms(),
			new Integration\Pods(),
			new Integration\Types(),
			new Integration\MetaBox(),
			new Integration\WooCommerce(),
			new Integration\YoastSeo(),
		];
	}

	/**
	 * @param string $basename
	 *
	 * @return Integration|null
	 */
	public function find_by_basename( $basename ) {
		foreach ( $this->all() as $integration ) {
			if ( $integration->get_basename() === $basename ) {
				return $integration;
			}
		}

		return null;
	}

	/**
	 * @param array $args
	 *
	 * @return Integrations
	 */
	public function find_all( array $args = [] ) {
		$integrations = new Integrations( $this->all() );

		$args = array_merge( [
			'filter' => [],
		], $args );

		foreach ( $args['filter'] as $filter ) {
			if ( $filter instanceof Integration\Filter ) {
				$integrations = $filter->filter( $integrations );
			}
		}

		return $integrations;
	}

}