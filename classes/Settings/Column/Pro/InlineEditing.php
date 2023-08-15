<?php

namespace AC\Settings\Column\Pro;

use AC\Settings;
use AC\View;

class InlineEditing extends Settings\Column\Pro
{

    protected function get_label()
    {
        return __('Inline Editing', 'codepress-admin-columns');
    }

    protected function get_instructions()
    {
        $view = new View([
            'object_type' => $this->column->get_list_singular_label(),
        ]);

        return $view->set_template('tooltip/inline-editing');
    }

    protected function define_options()
    {
        return ['inline-edit'];
    }

}