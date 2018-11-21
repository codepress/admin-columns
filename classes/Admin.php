<?php
namespace AC;

use AC\Admin\Page;
use AC\Admin\PageFactory;
use AC\Admin\Pages;

class Admin {

	const MENU_SLUG = 'codepress-admin-columns';

	/** @var Page */
	private $current_page;

	public function __construct() {
		$this->set_current_page();
	}

	/**
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );

		if ( $this->current_page ) {
			$this->current_page->register();
		}
	}

	/**
	 * @return void
	 */
	public function settings_menu() {
		$settings_page = add_submenu_page(
			'options-general.php',
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			self::MENU_SLUG,
			array( $this, 'render' )
		);

		add_action( "admin_print_scripts-" . $settings_page, array( $this, 'admin_scripts' ) );
	}

	private function set_current_page() {
		if ( self::MENU_SLUG !== filter_input( INPUT_GET, 'page' ) ) {
			return;
		}

		$page = PageFactory::create( filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) );

		if ( ! $page ) {
			$pages = Pages::get_pages();
			$page = current( $pages );
		}

		$this->current_page = $page;
	}

	/**
	 * @return Page
	 */
	public function get_current_page() {
		return $this->current_page;
	}

	/**
	 * Scripts
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_script( 'ac-admin-general', AC()->get_url() . "assets/js/admin-general.js", array( 'jquery', 'wp-pointer' ), AC()->get_version() );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'ac-admin', AC()->get_url() . "assets/css/admin-general.css", array(), AC()->get_version() );

		do_action( 'ac/admin_scripts', $this );
	}

	/**
	 * @return void
	 */
	public function render() {
		?>
		<div id="cpac" class="wrap">
			<h1 class="nav-tab-wrapper cpac-nav-tab-wrapper">
				<?php $this->menu( $this->current_page->get_slug() ); ?>
			</h1>

			<?php $this->current_page->display(); ?>
		</div>

		<?php
	}

	/**
	 * @param string $current_tab
	 *
	 * @return void
	 */
	private function menu( $current_tab ) {
		foreach ( Pages::get_pages() as $page ) {
			if ( $page->show_in_menu() ) {
				echo sprintf( '<a href="%s" class="nav-tab %s">%s</a>', ac_get_admin_url( $page->get_slug() ), $page->get_slug() === $current_tab ? 'nav-tab-active' : '', $page->get_label() );
			}
		}
	}

}