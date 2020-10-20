<?php

namespace AC\Column;

use AC\Column;
use AC\Integration;

/**
 * ACF Placeholder column, holding a CTA for Admin Columns Pro.
 * @since 2.2
 */
class Placeholder extends Column {

	/**
	 * @var Integration
	 */
	private $integration;

	/**
	 * @param Integration $integration
	 *
	 * @return $this
	 */
	public function set_integration( Integration $integration ) {
		$this->set_type( 'placeholder-' . $integration->get_slug() )
		     ->set_group( $integration->get_slug() )
		     ->set_label( $integration->get_title() );

		$this->integration = $integration;

		return $this;
	}

	public function get_message() {
		ob_start();
		?>

		<p><strong><?php printf( __( "The %s column is only available if you have installed the add-on.", 'codepress-admin-columns' ), $this->get_label() ); ?></strong></p>
		<p>
			<?php printf( __( "Download & install the %s add-on from the <a href='%s'>add-ons tab</a>.", 'codepress-admin-columns' ), $this->get_label(), ac_get_admin_url( 'addons' ) ); ?>
		</p>
		<p>
			<?php printf( __( "Admin Columns Pro offers full %s integration, allowing you to easily display and edit %s fields from within your overview.", 'codepress-admin-columns' ), $this->get_label(), $this->get_label() ); ?>
		</p>

		<a target="_blank" href="<?php echo $this->integration->get_link(); ?>" class="button button-primary">
			<?php _e( 'Find out more', 'codepress-admin-columns' ); ?>
		</a>
		<?php

		return ob_get_clean();
	}

}