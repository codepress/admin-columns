<?php

namespace AC\Admin;

use AC\Asset\Enqueueable;
use AC\Asset\Enqueueables;
use AC\Registrable;
use AC\Renderable;

class Page implements Registrable {

	/**
	 * @var Renderable
	 */
	private $main;

	/**
	 * @var Renderable
	 */
	private $head;

	public function __construct( Renderable $main, Renderable $head ) {
		$this->main = $main;
		$this->head = $head;
	}

	public function get_main() {
		return $this->main;
	}

	public function get_head() {
		return $this->head;
	}

	public function register() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		if ( $this->main instanceof Registrable ) {
			$this->register();
		}
		if ( $this->main instanceof Helpable ) {
			$this->help_tabs( $this->main, $screen );
		}
		if ( $this->main instanceof Enqueueables ) {
			array_map( [ $this, 'enqueue' ], $this->main->get_assets()->all() );
		}

		do_action( 'ac/admin_scripts', $this->main );

		add_filter( 'screen_settings', [ $this, 'screen_options' ] );
	}

	protected function help_tabs( Helpable $page, \WP_Screen $screen ) {
		foreach ( $page->get_help_tabs() as $help ) {
			$screen->add_help_tab( [
				'id'      => $help->get_id(),
				'title'   => $help->get_title(),
				'content' => $help->get_content(),
			] );
		}
	}

	public function screen_options( $settings ) {
		if ( $this->main instanceof ScreenOptions ) {
			$settings .= sprintf( '<legend>%s</legend>', __( 'Display', 'codepress-admin-columns' ) );

			foreach ( $this->main->get_screen_options() as $screen_option ) {
				$settings .= $screen_option->render();
			}
		}

		return $settings;
	}

	protected function enqueue( Enqueueable $asset ) {
		$asset->enqueue();
	}

}