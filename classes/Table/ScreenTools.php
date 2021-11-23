<?php

namespace AC\Table;

use AC;
use AC\Registrable;

final class ScreenTools implements Registrable {

	public function register() {
		add_action( 'ac/table', function () {
			add_filter( 'screen_settings', [ $this, 'render' ] );
		} );
	}

	public function render( $html ) {
		ob_start();
		?>

		<fieldset id="acp-screen-option-tools">
			<legend><?= __( 'Tools', 'codepress-admin-columns' ); ?></legend>
			<div class="acp-tools-container">
			</div>
		</fieldset>

		<?php

		$html .= ob_get_clean();

		return $html;
	}

}