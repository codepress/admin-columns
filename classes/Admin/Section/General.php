<?php

namespace AC\Admin\Section;

use AC\Admin\Section;
use AC\Settings;

class General extends Section {

	/** @var Settings\Admin\General[] */
	private $settings;

	public function __construct() {
		parent::__construct( 'general', __( 'General Settings', 'codepress-admin-columns' ), __( 'Customize your Admin Columns settings.', 'codepress-admin-columns' ) );
	}

	/**
	 * @param Settings\Admin\General $setting
	 *
	 * @return $this
	 */
	public function register_setting( Settings\Admin\General $setting ) {
		$this->settings[] = $setting;

		return $this;
	}

	/**
	 * @return void
	 */
	protected function display_fields() {
		?>
		<form method="post" action="options.php">

			<?php settings_fields( Settings\General::SETTINGS_GROUP ); ?>

			<?php
			foreach ( $this->settings as $setting ) {
				echo sprintf( '<p>%s</p>', $setting->render() );
			}
			?>

			<p>
				<input type="submit" class="button" value="<?php echo esc_attr( __( 'Save' ) ); ?>"/>
			</p>
		</form>
		<?php
	}

}