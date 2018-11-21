<?php
namespace AC\Settings;

abstract class Admin {

	/** @var string */
	protected $name;

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * @return string HTML
	 */
	abstract public function render();

	protected function settings() {
		return new General();
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	protected function get_default_label( $string ) {
		return sprintf( __( "Default is %s.", 'codepress-admin-columns' ), '<code>' . $string . '</code>' );
	}

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