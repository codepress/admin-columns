<?php
namespace AC\Admin\Section;

use AC\Admin\Section;
use AC\Capabilities;
use AC\Registrable;

abstract class Custom extends Section
	implements Registrable {

	/**
	 * Validate request
	 * @return bool
	 */
	protected function validate_request() {
		return $this->verify_nonce() && $this->verify_action() && current_user_can( Capabilities::MANAGE );
	}

	/**
	 * Verify nonce field based on the id
	 * @return bool
	 */
	protected function verify_nonce() {
		return wp_verify_nonce( filter_input( INPUT_POST, '_ac_nonce' ), $this->id );
	}

	/**
	 * Display nonce field based on the id
	 */
	protected function nonce_field() {
		wp_nonce_field( $this->id, '_ac_nonce', false );
	}

	/**
	 * Verify action field based on the id
	 * @return bool
	 */
	protected function verify_action() {
		return $this->id === filter_input( INPUT_POST, 'ac_action' );
	}

	/**
	 * Set an action field based on the id
	 * Nonce Field
	 */
	protected function action_field() {
		?>

		<input type="hidden" name="ac_action" value="<?php echo $this->id; ?>">

		<?php
	}

}