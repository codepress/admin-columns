<?php

class AC_Admin_Addon_EventsCalendar extends AC_Admin_Addon {

	public function __construct() {
		parent::__construct( 'ac-addon-events-calendar' );

		$this
			->set_title( __( 'The Events Calendar', 'codepress-admin-columns' ) )
			->set_logo( AC()->get_plugin_url() . 'assets/images/addons/events-calendar.png' )
			->set_icon( AC()->get_plugin_url() . 'assets/images/addons/events-calendar-icon.png' )
			->set_link( ac_get_site_utm_url( 'events-calendar', 'addon', 'events-calendar' ) )
			->set_description( "Manage columns for your event, organizer or venue overviews." )
			->add_plugin( 'the-events-calendar' );
	}

	public function display_promo() {
		echo $this->get_title() . ' ';
		$this->display_icon();
	}

	public function show_missing_notice_on_current_page() {
		global $pagenow;

		$is_page = 'edit.php' === $pagenow && in_array( filter_input( INPUT_GET, 'post_type' ), array( 'tribe_events', 'tribe_venue', 'tribe_organizer' ) );

		return parent::show_missing_notice_on_current_page() || $is_page;
	}

	public function is_plugin_active() {
		return class_exists( 'Tribe__Events__Main' );
	}

}