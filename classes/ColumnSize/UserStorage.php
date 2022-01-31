<?php

namespace AC\ColumnSize;

use AC\Type\ColumnWidth;
use AC\Type\ListScreenId;

class UserStorage {

	const OPTION_UNIT = 'unit';
	const OPTION_VALUE = 'value';

	/**
	 * @var UserPreference
	 */
	private $user_preference;

	public function __construct( UserPreference $user_preference ) {
		$this->user_preference = $user_preference;
	}

	/**
	 * @param ListScreenId $list_id
	 * @param string       $column_name
	 * @param ColumnWidth  $column_width
	 */
	public function save( ListScreenId $list_id, $column_name, ColumnWidth $column_width ) {
		$widths = $this->user_preference->get( $list_id->get_id() );

		if ( ! $widths ) {
			$widths = [];
		}

		$widths[ $column_name ] = [
			self::OPTION_UNIT  => $column_width->get_unit(),
			self::OPTION_VALUE => $column_width->get_value(),
		];

		$this->user_preference->set(
			$list_id->get_id(),
			$widths
		);
	}

	public function exists( ListScreenId $list_id ) {
		return null !== $this->user_preference->get( $list_id->get_id() );
	}

	/**
	 * @param ListScreenId $list_id
	 * @param string       $column_name
	 *
	 * @return ColumnWidth|null
	 */
	public function get( ListScreenId $list_id, $column_name ) {
		$widths = $this->user_preference->get(
			$list_id->get_id()
		);

		if ( ! isset( $widths[ $column_name ] ) ) {
			return null;
		}

		return new ColumnWidth(
			$widths[ $column_name ][ self::OPTION_UNIT ],
			$widths[ $column_name ][ self::OPTION_VALUE ]
		);
	}

	/**
	 * @param ListScreenId $list_id
	 *
	 * @return ColumnWidth[]
	 */
	public function all( ListScreenId $list_id ) {
		$widths = $this->user_preference->get(
			$list_id->get_id()
		);

		if ( ! $widths ) {
			return [];
		}

		$column_widths = [];

		foreach ( $widths as $column_name => $width ) {
			$column_widths[ $column_name ] = new ColumnWidth(
				$width[ self::OPTION_UNIT ],
				$width[ self::OPTION_VALUE ]
			);
		}

		return $column_widths;
	}

	/**
	 * @param ListScreenId $list_id
	 * @param string       $column_name
	 */
	public function delete( ListScreenId $list_id, $column_name ) {
		$widths = $this->user_preference->get(
			$list_id->get_id()
		);

		if ( ! $widths ) {
			return;
		}

		unset( $widths[ $column_name ] );

		$widths
			? $this->user_preference->set( $list_id->get_id(), $widths )
			: $this->delete_by_list_id( $list_id );
	}

	/**
	 * @param ListScreenId $list_id
	 */
	public function delete_by_list_id( ListScreenId $list_id ) {
		$this->user_preference->delete(
			$list_id->get_id()
		);
	}

}