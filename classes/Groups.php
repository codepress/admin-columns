<?php

namespace AC;

/**
 * Holds the groups to which columns can belong.
 */
final class Groups {

	const SORT_PRIORITY = 1;

	const SORT_SLUG = 2;

	const SORT_LABEL = 3;

	/**
	 * @var array
	 */
	private $groups = [];

	/**
	 * @return array
	 */
	public function get_groups() {
		return $this->groups;
	}

	/**
	 * Return the registered groups sorted by either label, slug or priority
	 *
	 * @param int $sort_by Default is self::SORT_PRIORITY
	 *
	 * @return array
	 */
	public function get_groups_sorted( $sort_by = null ) {
		switch ( $sort_by ) {
			case self::SORT_LABEL :
				$sorted = $this->sort_groups_by_string( $this->get_groups(), 'label' );

				break;
			case self::SORT_SLUG :
				$sorted = $this->sort_groups_by_string( $this->get_groups(), 'slug' );

				break;
			default :
				$sorted = $this->sort_groups_by_priority( $this->get_groups() );
		}

		return $sorted;
	}

	/**
	 * Sort the group by priority
	 * If there are more groups with the same priority it will those groups by label
	 *
	 * @param $groups
	 *
	 * @return array
	 */
	private function sort_groups_by_priority( array $groups ) {
		$aggregated = $sorted = [];

		foreach ( $groups as $group ) {
			$aggregated[ $group['priority'] ][] = $group;
		}

		ksort( $aggregated, SORT_NUMERIC );

		foreach ( $aggregated as $priority => $_groups ) {
			$sorted = array_merge( $sorted, $this->sort_groups_by_string( $_groups, 'label' ) );
		}

		return $sorted;
	}

	/**
	 * Sort the group by label or slug
	 *
	 * @param array  $groups
	 * @param string $key
	 *
	 * @return array
	 */
	private function sort_groups_by_string( array $groups, $key ) {
		$sorted = [];

		foreach ( $groups as $k => $group ) {
			$sorted[ $k ] = $group[ $key ];
		}

		natcasesort( $sorted );

		foreach ( array_keys( $sorted ) as $k ) {
			$sorted[ $k ] = $groups[ $k ];
		}

		return $sorted;
	}

	/**
	 * @param string $slug
	 *
	 * @return bool|mixed
	 */
	public function get_group( $slug ) {
		if ( ! $this->has_group( $slug ) ) {
			return false;
		}

		return $this->groups[ $slug ];
	}

	public function get_group_label( $slug ) {
		$group = $this->get_group( $slug );

		return $group ? $group['label'] : false;
	}

	/**
	 * @param string $slug
	 *
	 * @return bool
	 */
	public function has_group( $slug ) {
		return isset( $this->groups[ $slug ] );
	}

	/**
	 * Register a (column) group
	 *
	 * @param string $slug
	 * @param string $label Should be translatable
	 * @param int    $priority
	 *
	 * @return bool
	 */
	public function register_group( $slug, $label, $priority = 10 ) {
		if ( $this->has_group( $slug ) ) {
			return false;
		}

		$this->groups[ $slug ] = [
			'slug'     => $slug,
			'label'    => $label,
			'priority' => $priority,
		];

		return true;
	}

}