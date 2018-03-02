<?php

final class AC_IntegrationPromoNotice extends AC_DismissableNotice {

	/**
	 * @return string
	 */
	public function get_name() {
		return 'integration-promo';
	}

	public function display() {
		$titles = array();

		foreach ( AC()->addons()->get_addons() as $addon ) {
			if ( ! $this->is_notice_screen( $addon ) ) {
				continue;
			}

			if ( $addon->is_plugin_active() && ! $addon->is_active() ) {
				$addons[] = '<strong>' . $addon->get_title() . '</strong>';
			}
		}

		if ( ! $titles ) {
			return;
		}

		$message = sprintf( __( "Did you know Admin Columns Pro has an integration addon for %s? With the proper Admin Columns Pro license, you can download them from %s!", 'codepress-admin-columns' ), ac_helper()->string->enumeration_list( $titles, 'and' ), ac_helper()->html->link( AC()->admin()->get_link( 'addons' ), __( 'the addons page', 'codepress-admin-columns' ) ) );

		$notice = ac_notice( $message, AC_Notice::WARNING );
		$notice->set_dismissible( true, 'integration' );
	}

	/**
	 * @param AC_Admin_Addon $addon
	 *
	 * @return bool
	 */
	private function is_notice_screen( AC_Admin_Addon $addon ) {
		return AC()->admin()->is_admin_screen() || AC()->table_screen()->get_current_list_screen() || $addon->is_notice_screen();
	}

}