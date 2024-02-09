<?php

declare(strict_types=1);

namespace AC\Settings\Column;

// TODO
use AC\Setting\Component\Input;
use AC\Settings\Setting;

class Label extends Setting
{

    private $custom_label;

    public function __construct(string $label)
    {
        parent::__construct(
            new Input\Custom('label', 'label', [], $label),
            __('Label', 'codepress-admin-columns'),
            __('This is the name which will appear as the column header.', 'codepress-admin-columns')
        );

        $this->custom_label = $label;
    }

    public function get_custom_label(): string
    {
        // TODO apply kses
        return $this->custom_label;
    }


    // TODO where to do the KSEs for label?

    //
    //    /**
    //     * @param string $custom_label
    //     */
    //    public function set_label($label)
    //    {
    //        $sanitize = new Kses();
    //
    //        $this->label = (string)apply_filters('ac/column/label', $sanitize->sanitize((string)$label), $label);
    //    }
    //

}