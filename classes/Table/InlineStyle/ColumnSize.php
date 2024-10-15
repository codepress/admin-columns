<?php

namespace AC\Table\InlineStyle;

use AC\ColumnSize\ListStorage;
use AC\ColumnSize\UserStorage;
use AC\ListScreen;
use AC\Renderable;
use AC\Type\ColumnWidth;

class ColumnSize implements Renderable
{

    private ListScreen $list_screen;

    private ListStorage $list_storage;

    private UserStorage $user_storage;

    public function __construct(ListScreen $list_screen, ListStorage $list_storage, UserStorage $user_storage)
    {
        $this->list_screen = $list_screen;
        $this->list_storage = $list_storage;
        $this->user_storage = $user_storage;
    }

    private function render_style($column_name, ColumnWidth $column_width, $type)
    {
        $css_width = $column_width->get_value() . $column_width->get_unit();

        $css = sprintf(
            '.ac-%1$s .wrap table th.column-%2$s, .ac-%1$s .wrap table td.column-%2$s   { width: %3$s !important; }',
            esc_attr((string)$this->list_screen->get_key()),
            esc_attr($column_name),
            $css_width
        );

        $css .= sprintf(
            'body.acp-overflow-table.ac-%1$s .wrap th.column-%2$s, body.acp-overflow-table.ac-%1$s .wrap td.column-%2$s { min-width: %3$s !important; max-width: %3$s !important }',
            esc_attr((string)$this->list_screen->get_key()),
            esc_attr($column_name),
            $css_width
        );

        $id = sprintf(
            'ac-column-size-%s-%s',
            $type,
            $column_name
        );

        ob_start();
        ?>
		<style id="<?= $id ?>">
			@media screen and (min-width: 783px) {
			<?= $css ?>
			}
		</style>
        <?php
        return ob_get_clean();
    }

    public function render(): string
    {
        $html = '';

        foreach ($this->list_screen->get_columns() as $column) {
            $width = $this->list_storage->get($this->list_screen, (string)$column->get_id());
            if ($width) {
                $html .= $this->render_style((string)$column->get_id(), $width, 'list');
            }

            $width = $this->user_storage->get($this->list_screen->get_id(), (string)$column->get_id());
            if ($width) {
                $html .= $this->render_style((string)$column->get_id(), $width, 'user');
            }
        }

        return $html;
    }

}