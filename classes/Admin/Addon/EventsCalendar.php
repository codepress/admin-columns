<?php

namespace AC\Admin\Addon;

use AC\Admin\Addon;

class EventsCalendar extends Addon {

	public function __construct() {
		parent::__construct( 'ac-addon-events-calendar' );

		$this
			->set_title( __( 'The Events Calendar', 'codepress-admin-columns' ) )
			->set_logo( AC()->get_url() . 'assets/images/addons/events-calendar.png' )
			->set_icon( AC()->get_url() . 'assets/images/addons/events-calendar-icon.png' )
			->set_link( ac_get_site_utm_url( 'events-calendar', 'addon', 'events-calendar' ) )
			->set_description( __( 'Manage columns for your event, organizer or venue overviews.', 'codepress-admin-columns' ) )
			->add_plugin( 'events-calendar' );
	}

	public function display_promo() {
		echo $this->get_title() . ' ';
		$this->display_icon();
	}

	public function is_notice_screen() {
		global $pagenow;

		return 'edit.php' === $pagenow && in_array( filter_input( INPUT_GET, 'post_type' ), array( 'tribe_events', 'tribe_venue', 'tribe_organizer' ) );
	}

	public function is_plugin_active() {
		return class_exists( 'Tribe__Events__Main' );
	}

}