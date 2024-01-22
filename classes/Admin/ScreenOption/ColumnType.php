<?php

namespace AC\Admin\ScreenOption;

use AC\Admin\Preference;
use AC\Admin\ScreenOption;

class ColumnType extends ScreenOption
{

    private const KEY = 'show_column_type';

    private $preference;

    public function __construct(Preference\ScreenOptions $preference)
    {
        $this->preference = $preference;
    }

    public function is_active(): bool
    {
        return $this->preference->is_active(self::KEY);
    }

    public function render(): string
    {
        ob_start();
        ?>

		<label for="ac-column-type" data-ac-screen-option="<?= self::KEY; ?>">
			<input id="ac-column-type" type="checkbox" <?php
            checked($this->is_active()); ?>>
            <?= __('Column Type', 'codepress-admin-columns'); ?>
		</label>
        <?php
        return ob_get_clean();
    }

}