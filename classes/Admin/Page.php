<?php
namespace AC\Admin;

use AC\View;

abstract class Page {

	/** @var string */
	private $slug;

	/** @var string */
	private $label;

	public function __construct( $slug, $label ) {
		$this->slug = $slug;
		$this->label = $label;
	}

	/**
	 * @return void
	 */
	abstract protected function display();

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return void
	 */
	//	public function register() {
	//		// Run hooks
	//	}

	/**
	 * @return void
	 */
	public function register_ajax() {
		// Run ajax hooks
	}

	private function render_menu() {
		$items = array();

		foreach ( Pages::get_pages() as $page ) {
			$items[ $page->get_slug() ] = $page->get_label();
		}

		$menu = new View( array(
			'items'   => $items,
			'current' => $this->get_slug(),
		) );

		echo $menu->set_template( 'admin/edit-tabmenu' );
	}

	public function render() {
		?>
		<div id="cpac" class="wrap">
			<?php $this->render_menu(); ?>
			<?php $this->display(); ?>
		</div>

		<?php
	}

}