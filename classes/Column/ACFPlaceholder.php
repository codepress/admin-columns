<?php
defined( 'ABSPATH' ) or die();

/**
 * ACF Placeholder column, holding a CTA for Admin Columns Pro.
 *
 * @since 2.2
 */
class AC_Column_ACFPlaceholder extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-acf_placeholder' );
		$this->set_label( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) );
		$this->set_group( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) );

		// TODO: add placeholder message
	}

	public function get_value( $id ) {
		return false;
	}

	public function register_settings() {
		//$this->add_setting( new AC_Settings_Setting_Placeholder( $this ) );

		//$this->placeholder( array( 'label' => $this->get_label(), 'type' => $this->get_type(), 'url' => ac_get_site_url( 'advanced-custom-fields-columns' ) ) );
	}

	function placeholder( $args = array() ) {
		$defaults = array(
			'label' => '',
			'url'   => '',
			'type'  => '',
		);

		$data = (object) wp_parse_args( $args, $defaults );

		if ( ! $data->label ) {
			return;
		}
		?>
		<div class="is-disabled">
			<p>
				<strong><?php printf( __( "The %s column is only available in Admin Columns Pro - Business or Developer.", 'codepress-admin-columns' ), $data->label ); ?></strong>
			</p>

			<p>
				<?php printf( __( "If you have a business or developer licence please download & install your %s add-on from the <a href='%s'>add-ons tab</a>.", 'codepress-admin-columns' ), $data->label, admin_url( 'options-general.php?page=codepress-admin-columns&tab=addons' ) ); ?>
			</p>

			<p>
				<?php printf( __( "Admin Columns Pro offers full %s integration, allowing you to easily display and edit %s fields from within your overview.", 'codepress-admin-columns' ), $data->label, $data->label ); ?>
			</p>
			<a href="<?php echo add_query_arg( array(
				'utm_source'   => 'plugin-installation',
				'utm_medium'   => $data->type,
				'utm_campaign' => 'plugin-installation',
			), $data->url ); ?>" class="button button-primary"><?php _e( 'Find out more', 'codepress-admin-columns' ); ?></a>
		</div>
		<?php
	}

}