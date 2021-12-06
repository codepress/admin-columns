<?php

namespace AC\Admin\Notice;

use AC\ListScreen;
use AC\Message;
use AC\Registrable;

class ReadOnlyListScreen implements Registrable {

	public function register() {
		add_action( 'ac/settings/notice', [ $this, 'render_notice' ] );

	}

	public function render_notice( ListScreen $list_screen ) {
		if ( $list_screen->is_read_only() ) {
			$message = sprintf( __( 'The columns for %s are read only and can therefore not be edited.', 'codepress-admin-columns' ), '<strong>' . esc_html( $list_screen->get_title() ?: $list_screen->get_label() ) . '</strong>' );
			$message = sprintf( '<p>%s</p>', apply_filters( 'ac/read_only_message', $message, $list_screen ) );

			$notice = new Message\InlineMessage( $message );

			echo $notice->set_type( Message::INFO )
			            ->render();
		}
	}

}