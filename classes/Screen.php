<?php

namespace AC;

use AC\Admin\Page\Columns;
use AC\Admin\RequestHandlerInterface;
use WP_Screen;

class Screen implements Registerable {

	private $list_screen_factory;

	/**
	 * @var WP_Screen
	 */
	protected $screen;

	public function __construct( ListScreenFactory $list_screen_factory ) {
		$this->list_screen_factory = $list_screen_factory;
	}

	public function register() {
		add_action( 'current_screen', [ $this, 'init' ] );
	}

	public function init( WP_Screen $screen ): void {
		$this->set_screen( $screen );

		do_action( 'ac/screen', $this, $this->screen->id );
	}

	public function is_screen( string $id ): bool {
		return $this->has_screen() && $this->get_screen()->id === $id;
	}

	public function set_screen( WP_Screen $screen ): self {
		$this->screen = $screen;

		return $this;
	}

	public function get_screen(): WP_Screen {
		return $this->screen;
	}

	public function has_screen(): bool {
		return $this->screen instanceof WP_Screen;
	}

	public function get_id(): string {
		return $this->screen->id;
	}

	public function get_base(): string {
		return $this->screen->base;
	}

	public function get_post_type(): string {
		return $this->screen->post_type;
	}

	private function is_admin_network(): bool {
		return $this->screen->in_admin( 'network' );
	}

	public function is_list_screen(): bool {
		return null !== $this->list_screen_factory->create_by_wp_screen( $this->screen );
	}

	public function is_admin_screen( string $slug = null ): bool {
		if ( null !== $slug ) {
			$tabs = [ $slug ];

			// When the column page is requested from the setting menu the 'tab' querystring is not set.
			if ( Columns::NAME === $slug ) {
				$tabs[] = null;
			}

			return $this->is_main_admin_screen() && in_array( filter_input( INPUT_GET, RequestHandlerInterface::PARAM_TAB ), $tabs, true );
		}

		return $this->is_main_admin_screen();
	}

	private function is_main_admin_screen(): bool {
		$id = 'settings_page_' . Admin\Admin::NAME;

		if ( $this->is_admin_network() ) {
			$id .= '-network';
		}

		return $this->is_screen( $id );
	}

}