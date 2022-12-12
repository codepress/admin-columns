<?php

namespace AC;

class ListScreenTypes {

	public const ARG_NETWORK = 'network_only';
	public const ARG_SITE = 'site_only';

	/**
	 * @var ListScreenTypes
	 */
	private static $instance;

	/**
	 * @var ListScreen[]
	 */
	private $list_screens = [];

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function register_list_screen( ListScreen $list_screen ): self {
		$this->list_screens[ $list_screen->get_key() ] = $list_screen;

		return $this;
	}

	public function get_list_screens( array $args = [] ) {
		$list_screens = $this->list_screens;

		if ( isset( $args[ self::ARG_NETWORK ] ) && true === $args[ self::ARG_NETWORK ] ) {
			$list_screens = $this->filter_by_network( $list_screens );
		}
		if ( isset( $args[ self::ARG_SITE ] ) && true === $args[ self::ARG_SITE ] ) {
			$list_screens = $this->filter_by_non_network( $list_screens );
		}

		return $list_screens;
	}

	/**
	 * @param ListScreen[] $list_screens
	 *
	 * @return ListScreen[]
	 */
	private function filter_by_network( array $list_screens ): array {
		foreach ( $list_screens as $k => $list_screen ) {
			if ( ! $list_screen->is_network_only() ) {
				unset( $list_screens[ $k ] );
			}
		}

		return $list_screens;
	}

	/**
	 * @param ListScreen[] $list_screens
	 *
	 * @return ListScreen[]
	 */
	private function filter_by_non_network( array $list_screens ): array {
		foreach ( $list_screens as $k => $list_screen ) {
			if ( $list_screen->is_network_only() ) {
				unset( $list_screens[ $k ] );
			}
		}

		return $list_screens;
	}

	public function get_list_screen_by_key( string $key, bool $network_only = null ): ?ListScreen {
		if ( true === $network_only ) {
			$list_screens = $this->filter_by_network( $this->get_list_screens() );
		} else if ( false === $network_only ) {
			$list_screens = $this->filter_by_non_network( $this->get_list_screens() );
		} else {
			$list_screens = $this->get_list_screens();
		}

		foreach ( $list_screens as $list_screen ) {
			if ( $key === $list_screen->get_key() ) {
				return clone $list_screen;
			}
		}

		return null;
	}

}