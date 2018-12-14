<?php
namespace AC\Admin;

class Tooltip {

	/** @var string */
	private $name;

	/** @var string */
	private $description;

	public function __construct( $name, $description ) {
		$this->name = $name;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return sprintf( '<a class="ac-pointer instructions" rel="pointer-%s" data-pos="right">%s</a>', esc_attr( $this->name ), __( 'Instructions', 'codepress-admin-columns' ) );
	}

	/**
	 * @return string
	 */
	public function render() {
		ob_start();
		?>
		<div id="pointer-<?php echo $this->name; ?>" style="display:none;">
			<h3><?php _e( 'Notice', 'codepress-admin-columns' ); ?></h3>
			<?php echo $this->description; ?>
		</div>
		<?php

		return ob_get_clean();
	}

}