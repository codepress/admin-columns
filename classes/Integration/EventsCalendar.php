<?php

namespace AC\Integration;

use AC\Integration;
use AC\ListScreen;
use AC\ListScreenPost;
use AC\Screen;

class EventsCalendar extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-events-calendar/ac-addon-events-calendar.php',
			__( 'Events Calendar', 'codepress-admin-columns' ),
			'assets/images/addons/events-calendar.png',
			null,
			'events-calendar'
		);
	}

	public function is_plugin_active() {
		return class_exists( 'Tribe__Events__Main' );
	}

	public function get_description() {
		return __( 'Manage columns for your event, organizer or venue overviews.', 'codepress-admin-columns' );
	}

	private function get_post_types() {
		return array(
			'tribe_events',
			'tribe_organizer',
			'tribe_venue',
		);
	}

	public function show_notice( Screen $screen ) {
		return 'edit' === $screen->get_base()
		       && in_array( $screen->get_post_type(), $this->get_post_types() );
	}

	public function show_placeholder( ListScreen $list_screen ) {
		return $list_screen instanceof ListScreenPost
		       && in_array( $list_screen->get_post_type(), $this->get_post_types() );
	}

}