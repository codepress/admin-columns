<?php

namespace AC\Admin;

final class Pages {

	/**
	 * @var Page[]
	 */
	private $pages;

	/**
	 * Reference that points to default tab
	 *
	 * @var string
	 */
	private $default_slug;

	public function __construct() {
		$this->pages = array();
	}

	/**
	 * @param Page $page
	 *
	 * @return Pages
	 */
	public function register_page( Page $page ) {
		$this->pages[ $page->get_slug() ] = $page;

		if ( $page->is_default() ) {
			$this->default_slug = $page->get_slug();
		}

		return $this;
	}

	/**
	 * @param $slug
	 *
	 * @return Page|false
	 */
	public function get_page( $slug ) {
		$page = false;

		if ( isset( $this->pages[ $slug ] ) ) {
			$page = $this->pages[ $slug ];
		}

		return $page;
	}

	/**
	 * @return Page|false
	 */
	public function get_current_page() {
		$page = $this->get_page( filter_input( INPUT_GET, 'tab' ) );

		if ( ! $page ) {
			$page = $this->get_page( $this->default_slug );
		}

		return $page;
	}

	/**
	 * Register page hooks
	 */
	public function register() {
		foreach ( $this->pages as $page ) {
			$page->register();
		}
	}

	public function display() { ?>
		<div id="cpac" class="wrap">
			<h1 class="nav-tab-wrapper cpac-nav-tab-wrapper">
				<?php

				$active_page = $this->get_current_page();

				foreach ( $this->pages as $slug => $page ) {
					if ( $page->show_in_menu() ) {
						$active = $slug === $active_page->get_slug() ? ' nav-tab-active' : '';

						echo ac_helper()->html->link( AC()->admin()->get_link( $slug ), $page->get_label(), array( 'class' => 'nav-tab ' . $active ) );
					}
				}

				?>
			</h1>

			<?php

			do_action( 'ac/settings/after_menu' );

			$active_page->display();

			?>
		</div>

		<?php
	}

}