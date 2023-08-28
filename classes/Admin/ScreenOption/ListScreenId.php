<?php

namespace AC\Admin\ScreenOption;

use AC\Admin\Preference;
use AC\Admin\ScreenOption;

class ListScreenId extends ScreenOption
{

    private const KEY = 'show_tools_list_screen_id';

    private $preference;

    public function __construct(Preference\ScreenOptions $preference)
    {
        $this->preference = $preference;
    }

    public function is_active(): bool
    {
        return 1 === $this->preference->get(self::KEY);
    }

    public function render(): string
    {
        ob_start();
        ?>

		<label for="ac-list-screen-id" data-ac-screen-option="<?= self::KEY ?>">
			<input id="ac-list-screen-id" type="checkbox" <?php
            checked($this->is_active()); ?>>
            <?= __('List Screen ID', 'codepress-admin-columns') ?>
		</label>
        <?php
        return ob_get_clean();
    }

}