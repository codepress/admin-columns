<?php

namespace AC\Admin\ScreenOption;

use AC\Admin\Preference;
use AC\Admin\ScreenOption;

class ColumnId extends ScreenOption
{

    private const KEY = 'show_column_id';

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

		<label for="ac-column-id" data-ac-screen-option="<?= self::KEY; ?>">
			<input id="ac-column-id" type="checkbox" <?php
            checked($this->is_active()); ?>>
            <?= __('Column Name', 'codepress-admin-columns'); ?>
		</label>
        <?php
        return ob_get_clean();
    }

}