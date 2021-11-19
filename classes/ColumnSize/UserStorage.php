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
		$this->user_preference->set(
			$list_id->get_id() . $column_name,
			[
				self::OPTION_UNIT  => $column_width->get_unit(),
				self::OPTION_VALUE => $column_width->get_value(),
			]
		);
	}

	/**
	 * @param ListScreenId $list_id
	 * @param string       $column_name
	 *
	 * @return ColumnWidth|null
	 */
	public function get( ListScreenId $list_id, $column_name ) {
		$data = $this->user_preference->get(
			$list_id->get_id() . $column_name
		);

		if ( ! $data ) {
			return null;
		}

		return new ColumnWidth(
			$data[ self::OPTION_UNIT ],
			$data[ self::OPTION_VALUE ]
		);
	}

}