<?php

declare(strict_types=1);

namespace AC\Settings\Column;

// TODO
class Label extends Single
{

    public function __construct()
    {
        parent::__construct(
            'label',
            __('Label', 'codepress-admin-columns'),
            __('This is the name which will appear as the column header.', 'codepress-admin-columns')
        );
    }

    // TODO where to do the KSEs for label?

    //
    //    /**
    //     * @param string $label
    //     */
    //    public function set_label($label)
    //    {
    //        $sanitize = new Kses();
    //
    //        $this->label = (string)apply_filters('ac/column/label', $sanitize->sanitize((string)$label), $label);
    //    }
    //

}