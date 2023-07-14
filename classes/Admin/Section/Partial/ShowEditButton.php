<?php

namespace AC\Admin\Section\Partial;

use AC\Form\Element\Toggle;
use AC\Renderable;
use AC\Settings\Option\EditButton;
use AC\View;

class ShowEditButton implements Renderable
{

    private $option;

    public function __construct()
    {
        $this->option = new EditButton();
    }

    private function get_label(): string
    {
        return sprintf(
            __("Show %s button on table screen.", 'codepress-admin-columns'),
            sprintf('"%s"', __('Edit columns', 'codepress-admin-columns'))
        );
    }

    public function render(): string
    {
        $toggle = new Toggle($this->option->get_name(), $this->get_label(), $this->option->is_enabled());
        $toggle->set_value('1');
        $toggle->set_attribute('data-ajax-setting', $this->option->get_name());

        $view = new View(['setting' => $toggle->render()]);

        return $view->set_template('admin/settings/setting-row')->render();
    }

}