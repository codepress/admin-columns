<?php

namespace AC\Admin\ScreenOption;

use AC\Admin\Preference;
use AC\Admin\ScreenOption;
use AC\Preferences;

class ListScreenType extends ScreenOption {

	const KEY = 'show_list_screen_type';

	/**
	 * @var Preferences\User
	 */
	private $preference;

	public function __construct( Preference\ScreenOptions $preference ) {
		$this->preference = $preference;
	}

	public function is_active() {
		return 1 === $this->preference->get( self::KEY );
	}

	public function render() {
		ob_start();
		?>

		<label for="ac-list-screen-type" data-ac-screen-option="<?= self::KEY; ?>">
			<input id="ac-list-screen-type" type="checkbox" <?php checked( $this->is_active() ); ?>>
			<?= __( 'List Screen Key', 'codepress-admin-columns' ); ?>
		</label>
		<?php
		return ob_get_clean();
	}

}