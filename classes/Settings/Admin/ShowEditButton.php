<?php

namespace AC\Settings\Admin;

use AC\Form\Element\Checkbox;
use AC\Settings\Admin;
use AC\Settings\General;

class ShowEditButton extends Admin {

	public function __construct() {
		parent::__construct( 'show_edit_button' );
	}

	// todo: check if stored value is 'yes' or '1'
	private function get_value() {
		return $this->settings()->is_empty() ? 'yes' : $this->settings()->get_option( $this->name );
	}

	private function get_label() {
		return sprintf( __( "Show %s button on table screen.", 'codepress-admin-columns' ), '"' . __( 'Edit columns', 'codepress-admin-columns' ) . '"' ) . ' ' . $this->get_default_label( __( 'on', 'codepress-admin-columns' ) );
	}

	public function show_button() {
		return 'yes' === $this->get_value();
	}

	/**
	 * @return string
	 */
	public function render() {
		$name = sprintf( '%s[%s]', General::SETTINGS_NAME, $this->name );

		$checkbox = new Checkbox( $name );

		$checkbox->set_options( array( 'yes' => $this->get_label() ) )
		         ->set_value( $this->get_value() );

		return $checkbox->render();
	}

	// todo
	private function render_instructions( $instructions ) {
		?>
		<a class="ac-pointer instructions" rel="pointer-<?php echo $this->name; ?>" data-pos="right">
			<?php _e( 'Instructions', 'codepress-admin-columns' ); ?>
		</a>
		<div id="pointer-<?php echo $this->name; ?>" style="display:none;">
			<h3><?php _e( 'Notice', 'codepress-admin-columns' ); ?></h3>
			<?php echo $instructions; ?>
		</div>
		<?php
	}

}