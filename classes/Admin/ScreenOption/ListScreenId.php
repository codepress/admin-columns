<?php

namespace AC\Admin\ScreenOption;

use AC\Admin\Preference;
use AC\Admin\ScreenOption;
use AC\Preferences;

class ListScreenId extends ScreenOption {

	const KEY = 'show_list_screen_id';

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

		<label for="ac-list-screen-id" data-ac-screen-option="<?= self::KEY; ?>">
			<input id="ac-list-screen-id" type="checkbox" <?php checked( $this->is_active() ); ?>>
			<?= __( 'List Screen ID', 'codepress-admin-columns' ); ?>
		</label>
		<?php
		return ob_get_clean();
	}

}