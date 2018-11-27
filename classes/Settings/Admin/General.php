<?php

namespace AC\Settings\Admin;

use AC;
use AC\Settings\Admin;

abstract class General extends Admin {

	/** @var AC\Settings\General */
	protected $settings;

	public function __construct( $name ) {
		$this->settings = new AC\Settings\General();

		parent::__construct( $name );
	}

	protected function get_value() {
		return $this->settings->get_option( $this->name );
	}



	// todo: move tooltip to its own class

	/**
	 * @return string
	 */
	protected function get_tooltip_label() {
		ob_start();
		?>
		<a class="ac-pointer instructions" rel="pointer-<?php echo $this->name; ?>" data-pos="right">
			<?php _e( 'Instructions', 'codepress-admin-columns' ); ?>
		</a>
		<?php

		return ob_get_clean();
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	protected function get_tooltip( $text ) {
		ob_start();
		?>
		<div id="pointer-<?php echo $this->name; ?>" style="display:none;">
			<h3><?php _e( 'Notice', 'codepress-admin-columns' ); ?></h3>
			<?php echo $text; ?>
		</div>
		<?php

		return ob_get_clean();
	}

}