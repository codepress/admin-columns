<?php

namespace AC\Storage\Parser;

use AC;

class JsonListScreen {

	const TITLE_KEY = 'title';
	const TYPE_KEY = 'type';
	const LAYOUT_KEY = 'id';
	const UPDATED_KEY = 'date_modified';
	const COLUMNS_KEY = 'columns';
	const SETTINGS_KEY = 'settings';

	/**
	 * @param AC\ListScreen $listScreen
	 *
	 * @return array
	 */
	public function fromListScreen( AC\ListScreen $listScreen ) {
		return [
			self::TITLE_KEY    => $listScreen->get_title(),
			self::TYPE_KEY     => $listScreen->get_key(),
			self::LAYOUT_KEY   => $listScreen->get_layout_id(),
			self::UPDATED_KEY  => $listScreen->get_updated()->getTimestamp(),
			self::COLUMNS_KEY  => $listScreen->get_settings(),
			self::SETTINGS_KEY => $listScreen->get_preferences(),
		];
	}

	/**
	 * @param $path
	 *
	 * @return array|null
	 */
	public function fromPath( $path ) {
		$contents = file_get_contents( $path );
		$data = json_decode( $contents, true );

		if ( empty( $data ) ) {
			return null;
		}

		return [
			self::TITLE_KEY    => $data[ self::TITLE_KEY ],
			self::TYPE_KEY     => $data[ self::TYPE_KEY ],
			self::LAYOUT_KEY   => $data[ self::LAYOUT_KEY ],
			self::UPDATED_KEY  => $data[ self::UPDATED_KEY ],
			self::COLUMNS_KEY  => $data[ self::COLUMNS_KEY ],
			self::SETTINGS_KEY => $data[ self::SETTINGS_KEY ],
		];
	}

}