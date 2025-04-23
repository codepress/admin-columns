<?php

namespace AC\Admin\Section\Partial;

use AC\Form\Element\Toggle;
use AC\Renderable;
use AC\Storage;
use AC\View;

class ShowEditButton implements Renderable
{

    private $option;

    public function __construct()
    {
        $this->option = new Storage\Repository\EditButton();
    }

    //TODO Remove
    private function get_label(): string
    {
        return sprintf(
            __("Show %s button on table screen.", 'codepress-admin-columns'),
            sprintf('"%s"', __('Edit columns', 'codepress-admin-columns'))
        );
    }

    public function render(): string
    {
        $toggle = new Toggle('show_edit_button', $this->get_label(), $this->option->is_active());
        $toggle->set_value('1');
        $toggle->set_attribute('data-ajax-setting', 'show_edit_button');

        $view = new View(['setting' => $toggle->render()]);

        return $view->set_template('admin/settings/setting-row')->render();
    }

}