<?php
namespace AC;

use AC\Admin\Helpable;
use AC\Admin\PageFactory;
use AC\Admin\Pages;

class Admin {

	const MENU_SLUG = 'codepress-admin-columns';

	/** @var string */
	private $tab;

	public function __construct( $tab ) {
		$this->tab = $tab;
	}

	/**
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
	}

	/**
	 * @return void
	 */
	public function settings_menu() {
		$hook_suffix = add_submenu_page(
			'options-general.php',
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			self::MENU_SLUG,
			array( $this, 'render' )
		);

		add_action( "admin_print_scripts-" . $hook_suffix, array( $this, 'admin_scripts' ) );
		add_action( "load-" . $hook_suffix, array( $this, 'register_page' ) );
		add_action( "load-" . $hook_suffix, array( $this, 'register_help' ) );
	}

	/**
	 * Show help screen options
	 */
	public function register_help() {
		$page = PageFactory::create( $this->tab );

		if ( ! $page instanceof Helpable ) {
			return;
		}

		foreach ( $page->get_help_tabs() as $help ) {
			get_current_screen()->add_help_tab( array(
				'id'      => $help->get_id(),
				'content' => $help->get_content(),
				'title'   => $help->get_title(),
			) );
		}
	}

	public function register_page() {
		$page = PageFactory::create( $this->tab );
		$page->register();
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
		$page = PageFactory::create( $this->tab );

		?>
		<div id="cpac" class="wrap">
			<h1 class="nav-tab-wrapper cpac-nav-tab-wrapper">
				<?php $this->menu( $page->get_slug() ); ?>
			</h1>

			<?php $page->display(); ?>
		</div>

		<?php
	}

	/**
	 * @param string $current_tab
	 *
	 * @return void
	 */
	private function menu( $current_tab ) {

		// todo: make register for menu items
		foreach ( Pages::get_pages() as $page ) {
			echo sprintf( '<a href="%s" class="nav-tab %s">%s</a>', ac_get_admin_url( $page->get_slug() ), $page->get_slug() === $current_tab ? 'nav-tab-active' : '', $page->get_label() );
		}
	}

}