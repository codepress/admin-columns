<?php

namespace AC\Admin\ScreenOption;

use AC\Admin\Preference;
use AC\Admin\ScreenOption;
use AC\Preferences;

class ColumnId extends ScreenOption {

	const KEY = 'show_column_id';

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

	public function render(): string
    {
		ob_start();
		?>

		<label for="ac-column-id" data-ac-screen-option="<?= self::KEY; ?>">
			<input id="ac-column-id" type="checkbox" <?php checked( $this->is_active() ); ?>>
			<?= __( 'Column Name', 'codepress-admin-columns' ); ?>
		</label>
		<?php
		return ob_get_clean();
	}

}