<?php

namespace AC\Column;

use AC\Admin\Addon;
use AC\Column;

/**
 * ACF Placeholder column, holding a CTA for Admin Columns Pro.
 *
 * @since 2.2
 */
class Placeholder extends Column {

	/**
	 * @var Addon
	 */
	private $addon;

	/**
	 * @param Addon $addon
	 */
	public function set_addon( Addon $addon ) {
		$this->addon = $addon;

		$this->set_type( 'placeholder-' . $addon->get_slug() );
		$this->set_group( $addon->get_slug() );
		$this->set_label( $addon->get_title() );
	}

	public function get_message() {
		ob_start();
		?>

		<p>
			<strong><?php printf( __( "The %s column is only available in Admin Columns Pro - Business or Developer.", 'codepress-admin-columns' ), $this->get_label() ); ?></strong>
		</p>

		<p>
			<?php printf( __( "If you have a business or developer licence please download & install your %s add-on from the <a href='%s'>add-ons tab</a>.", 'codepress-admin-columns' ), $this->get_label(), AC()->admin()->get_link( 'addons' ) ); ?>
		</p>

		<p>
			<?php printf( __( "Admin Columns Pro offers full %s integration, allowing you to easily display and edit %s fields from within your overview.", 'codepress-admin-columns' ), $this->get_label(), $this->get_label() ); ?>
		</p>
		<a target="_blank" href="<?php echo $this->addon->get_link(); ?>" class="button button-primary"><?php _e( 'Find out more', 'codepress-admin-columns' ); ?></a>

		<?php

		return ob_get_clean();
	}

}