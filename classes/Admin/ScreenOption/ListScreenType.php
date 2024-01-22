<?php

namespace AC\Admin\ScreenOption;

use AC\Admin\Preference;
use AC\Admin\ScreenOption;

class ListScreenType extends ScreenOption
{

    private const KEY = 'show_list_screen_type';

    private $preference;

    public function __construct(Preference\ScreenOptions $preference)
    {
        $this->preference = $preference;
    }

    public function is_active(): bool
    {
        return 1 === $this->preference->find(self::KEY);
    }

    public function render(): string
    {
        ob_start();
        ?>

		<label for="ac-list-screen-type" data-ac-screen-option="<?= self::KEY ?>">
			<input id="ac-list-screen-type" type="checkbox" <?php
            checked($this->is_active()); ?>>
            <?= __('List Screen Key', 'codepress-admin-columns') ?>
		</label>
        <?php
        return ob_get_clean();
    }

}