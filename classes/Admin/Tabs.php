<?php

class AC_Admin_Tabs {

	/**
	 * @var AC_Admin_Tab[]
	 */
	private $tabs;

	/**
	 * Reference that points to default tab
	 *
	 * @var string
	 */
	private $default_slug;

	public function __construct() {
		$this->tabs = array();
	}

	/**
	 * @param AC_Admin_Tab $tab
	 * @return AC_Admin_Tabs
	 */
	public function register_tab( AC_Admin_Tab $tab ) {
		$this->tabs[ $tab->get_slug() ] = $tab;

		if ( $tab->is_default() ) {
			$this->default_slug = $tab->get_slug();
		}

		return $this;
	}

	/**
	 * @param $slug
	 *
	 * @return AC_Admin_Tab|false
	 */
	public function get_tab( $slug ) {
		$tab = false;

		if ( isset( $this->tabs[ $slug ] ) ) {
			$tab = $this->tabs[ $slug ];
		}

		return $tab;
	}

	/**
	 * @return string
	 */
	public function get_current_slug() {
		$slug = filter_input( INPUT_GET, 'tab' );

		if ( ! $slug && $this->default_slug ) {
			$slug = $this->default_slug;
		}

		return $slug;
	}

	/**
	 * @return AC_Admin_Tab|false
	 */
	public function get_current_tab() {
		return $this->get_tab( $this->get_current_slug() );
	}

	public function display() { ?>
		<div id="cpac" class="wrap">
			<h1 class="nav-tab-wrapper cpac-nav-tab-wrapper">
				<?php

				$active_slug = $this->get_current_slug();

				foreach ( $this->tabs as $slug => $tab ) {

				    // skip hidden tabs
				    if ( $tab->is_hidden() ) {
				        continue;
                    }

					$active = $slug == $active_slug ? ' nav-tab-active' : '';

				    echo ac_helper()->html->link( AC()->admin()->get_link( $slug ), $tab->get_label(), array( 'class' => 'nav-tab ' . $active ) );
				}

				?>
			</h1>

			<?php

			do_action( 'cpac_messages' );

			do_action( 'cac/settings/after_menu' );

			if ( $tab = $this->get_tab( $active_slug ) ) {
				$tab->display();
			}

			?>
		</div>

		<?php
	}

}