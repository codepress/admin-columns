<?php

namespace AC\Integration;

use AC\Integration;
use AC\ListScreen;
use AC\ListScreenPost;
use AC\Screen;
use AC\Type\Url\Site;

final class NinjaForms extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-ninjaforms/ac-addon-ninjaforms.php',
			__( 'Ninja Forms', 'codepress-admin-columns' ),
			'assets/images/addons/ninja-forms.png',
			sprintf(
				'%s %s',
				sprintf( __( 'Integrates %s with Admin Columns.', 'codepress-admin-columns' ), __( 'Ninja Forms', 'codepress-admin-columns' ) ),
				sprintf( __( 'Display, inline- and bulk-edit, export, smart filter and sort your %s Submissions.', 'codepress-admin-columns' ), __( 'Ninja Forms', 'codepress-admin-columns' ) )
			),
			null,
			new Site( Site::PAGE_ADDON_NINJA_FORMS )
		);
	}

	public function is_plugin_active() {
		return class_exists( 'Ninja_Forms' );
	}

	private function get_post_types() {
		return [ 'nf_sub' ];
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