<?php

namespace AC\Admin\Page;

use AC;
use AC\Admin\Page;
use AC\Admin\Section;

class Settings extends Page
	implements AC\Registrable {

	const NAME = 'settings';

	/**
	 * @var Section[]
	 */
	private $sections = array();

	public function __construct() {
		parent::__construct( self::NAME, __( 'Settings', 'codepress-admin-columns' ) );
	}

	/**
	 * @param Section $section
	 *
	 * @return $this
	 */
	public function register_section( Section $section ) {
		$this->sections[] = $section;

		return $this;
	}

	/**
	 * @return Section[]
	 */
	public function get_sections() {
		return $this->sections;
	}

	/**
	 * Register Hooks
	 */
	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		foreach ( $this->sections as $section ) {
			if ( $section instanceof AC\Registrable ) {
				$section->register();
			}
		}
	}

	public function admin_scripts() {
		wp_enqueue_style( 'ac-admin-page-settings', AC()->get_url() . 'assets/css/admin-page-settings.css', array(), AC()->get_version() );
	}

	public function render() { ?>
		<table class="form-table ac-form-table settings">
			<tbody>

			<?php foreach ( $this->sections as $section ) {
				$section->render();
			}
			?>

			</tbody>
		</table>

		<?php
	}

}