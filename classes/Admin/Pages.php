<?php

class AC_Admin_Pages {

	/**
	 * @var AC_Admin_Page[]
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
	 * @param AC_Admin_Page $tab
	 * @return AC_Admin_Pages
	 */
	public function register_page( AC_Admin_Page $tab ) {
		$this->pages[ $tab->get_slug() ] = $tab;

		if ( $tab->is_default() ) {
			$this->default_slug = $tab->get_slug();
		}

		return $this;
	}

	/**
	 * @param $slug
	 *
	 * @return AC_Admin_Page|false
	 */
	public function get_page( $slug ) {
		$tab = false;

		if ( isset( $this->pages[ $slug ] ) ) {
			$tab = $this->pages[ $slug ];
		}

		return $tab;
	}

	/**
	 * @return AC_Admin_Page|false
	 */
	public function get_current_tab() {
		$tab = $this->get_page( filter_input( INPUT_GET, 'tab' ) );

		if ( ! $tab ) {
		    $tab = $this->get_page( $this->default_slug );
        }

        return $tab;
	}

	public function display() { ?>
		<div id="cpac" class="wrap">
			<h1 class="nav-tab-wrapper cpac-nav-tab-wrapper">
				<?php

				$active_tab = $this->get_current_tab();

				foreach ( $this->pages as $slug => $tab ) {

				    // skip
				    if ( ! $tab->show_in_menu() ) {
				        continue;
                    }

					$active = $slug === $active_tab->get_slug() ? ' nav-tab-active' : '';

				    echo ac_helper()->html->link( AC()->admin()->get_link( $slug ), $tab->get_label(), array( 'class' => 'nav-tab ' . $active ) );
				}

				?>
			</h1>

			<?php

			do_action( 'cpac_messages' );

			do_action( 'cac/settings/after_menu' );

            $active_tab->display();

			?>
		</div>

		<?php
	}

}