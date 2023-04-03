<?php

namespace AC;

final class Groups {

	private $groups;

	public const SORT_PRIORITY = 1;
	public const SORT_SLUG = 2;
	public const SORT_LABEL = 3;

	public function __construct( array $groups = [] ) {
		$this->groups = $groups;
	}

	public function get_all( $sort_by = null ): array {
		switch ( $sort_by ) {
			case self::SORT_LABEL :
				return $this->sort_groups_by_string( $this->groups, 'label' );
			case self::SORT_SLUG :
				return $this->sort_groups_by_string( $this->groups, 'slug' );
			default :
				return $this->sort_groups_by_priority( $this->groups );
		}
	}

	private function sort_groups_by_priority( array $groups ): array {
		$aggregated = $sorted = [];

		foreach ( $groups as $group ) {
			$aggregated[ $group['priority'] ][] = $group;
		}

		ksort( $aggregated, SORT_NUMERIC );

		foreach ( $aggregated as $_groups ) {
			// TODO
			$sorted = array_merge( $sorted, $this->sort_groups_by_string( $_groups, 'label' ) );
		}

		return $sorted;
	}

	private function sort_groups_by_string( array $groups, string $key ): array {
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

	public function add( string $slug, string $label, int $priority = 10 ): bool {
		if ( isset( $this->groups[ $slug ] ) ) {
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