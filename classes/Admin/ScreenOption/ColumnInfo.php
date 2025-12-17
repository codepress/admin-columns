<?php

namespace AC\Admin\ScreenOption;

use AC\Admin\Preference\ScreenOptions;
use AC\Admin\ScreenOption;

class ColumnInfo extends ScreenOption
{

    private $preference;

    public function __construct(ScreenOptions $preference)
    {
        $this->preference = $preference;
    }

    public function is_active(): bool
    {
        return $this->preference->is_active('show_column_info');
    }

    public function render(): string
    {
        ob_start();
        ?>

		<label for="ac-column-info" data-ac-screen-option="show_column_info">
			<input id="ac-column-info" type="checkbox" <?php
            checked($this->is_active()); ?>>
            <?= __('Column Info', 'codepress-admin-columns'); ?>
		</label>
        <?php
        return ob_get_clean();
    }

}