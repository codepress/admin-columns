<?php

namespace AC\Admin\Page;

use AC;
use AC\Admin\Page;

class Settings extends Page {

	/**
	 * @var AC\Admin\Section[]
	 */
	private static $sections;

	/**
	 * @param AC\Admin\Section $section
	 */
	public static function register_section( AC\Admin\Section $section ) {
		self::$sections[] = $section;
	}

	/**
	 * @return AC\Admin\Section[]
	 */
	public static function get_sections() {
		return self::$sections;
	}

	public function __construct() {
		parent::__construct( 'settings', __( 'Settings', 'codepress-admin-columns' ) );
	}

	/**
	 * Register Hooks
	 */
	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		foreach ( $this->get_sections() as $section ) {
			$section->register();
		}
	}

	public function admin_scripts() {
		wp_enqueue_style( 'ac-admin-page-settings', AC()->get_url() . 'assets/css/admin-page-settings.css', array(), AC()->get_version() );
	}


	public function display() { ?>
		<table class="form-table ac-form-table settings">
			<tbody>

			<?php foreach ( self::get_sections() as $section ) : ?>

				<tr class="<?php echo esc_attr( $section->get_id() ); ?>">
					<th scope="row">
						<h2><?php echo $section->get_title(); ?></h2>
						<p><?php echo $section->get_description(); ?></p>
					</th>
					<td>
						<?php $section->render(); ?>
					</td>
				</tr>

			<?php endforeach; ?>

			</tbody>
		</table>

		<?php
	}

}