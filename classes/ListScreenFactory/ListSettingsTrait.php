<?php

namespace AC\ListScreenFactory;

use AC\ListScreen;
use DateTime;

trait ListSettingsTrait {

	private function add_settings( ListScreen $list_screen, array $settings ): ListScreen {
		$date = $settings['date'] ?? null;
		$columns = $settings['columns'] ?? [];
		$preferences = $settings['preferences'] ?? [];

		$list_screen->set_title( $settings['title'] ?? '' );
		$list_screen->set_layout_id( $settings['list_id'] ?? '' );
		$list_screen->set_preferences( $preferences ?: [] );
		$list_screen->set_settings( $columns ?: [] );

		if ( $date ) {
			$list_screen->set_updated( DateTime::createFromFormat( 'Y-m-d H:i:s', $settings['date'] ) );
		}

		$list_screen->set_group( $settings['group'] ?? '' );

		return $list_screen;
	}

}