<?php

namespace AC\Table;

use AC;

final class TableFormView {

	/**
	 * @var AC\ListScreen
	 */
	private $list_screen;

	/**
	 * @var string
	 */
	private $html;

	public function __construct( AC\ListScreen $list_screen ) {
		$this->list_screen = $list_screen;
	}

	/**
	 * Register hooks
	 */
	public function render( $html, $priority = 10 ) {
		$this->html = $html;

		switch ( $this->list_screen->get_meta_type() ) {
			case 'post':
				add_action( 'restrict_manage_posts', [ $this, 'echo_html' ], $priority );

				break;
			case'user':
				add_action( 'restrict_manage_users', [ $this, 'echo_html' ], $priority );

				break;
			case 'comment':
				add_action( 'restrict_manage_comment', [ $this, 'echo_html' ], $priority );

				break;
		}
	}

	public function echo_html() {
		echo $this->html;
	}

}