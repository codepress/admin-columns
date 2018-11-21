<?php

namespace AC\Admin\Section;

use AC\Settings;
use AC\Admin\Section;

class General extends Section {

	public function __construct() {
		parent::__construct( 'general', __( 'General Settings', 'codepress-admin-columns' ), __( 'Customize your Admin Columns settings.', 'codepress-admin-columns' ) );
	}

	public function render() {
		?>
		<form method="post" action="options.php">

			<?php settings_fields( Settings\General::SETTINGS_GROUP ); ?>

			<?php
			foreach ( Settings::get_settings() as $setting ) {
				echo sprintf( '<p>%s</p>', $setting->render() );
			}
			?>

			<p>
				<input type="submit" class="button" value="<?php _e( 'Save' ); ?>"/>
			</p>
		</form>
		<?php
	}

}