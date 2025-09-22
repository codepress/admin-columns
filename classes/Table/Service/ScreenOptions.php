<?php

declare(strict_types=1);

namespace AC\Table\Service;

use AC;
use AC\Form;
use AC\Renderable;

class ScreenOptions implements AC\Registerable
{

	/**
	 * @var Form\Element[]
	 */
	private static array $screen_options = [];

	public function register(): void
	{
		add_filter('screen_settings', [$this, 'screen_options']);
	}

	public static function add(Renderable $option): void
	{
		self::$screen_options[] = $option;
	}

	public function screen_options($html): string
	{
		if (empty($this->screen_options)) {
			return (string)$html;
		}

		ob_start();
		?>

		<fieldset class='acp-screen-option-prefs'>
			<legend><?= __('Admin Columns', 'codepress-admin-columns'); ?></legend>
			<div class="acp-so-container">
				<?php

				foreach (self::$screen_options as $option) {
					echo $option->render();
				}

				?>
			</div>
		</fieldset>

		<?php

		$html .= ob_get_clean();

		return $html;
	}

}