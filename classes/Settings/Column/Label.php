<?php

declare(strict_types=1);

namespace AC\Settings\Column;

// TODO
use AC\Setting;
use AC\Setting\Component\Input\Custom;
use AC\Setting\Config;
use AC\Settings\Column;

class Label extends Column
{

    private $custom_label;


    public function __construct(string $custom_label)
    {
        parent::__construct(
            'label',
            __('Label', 'codepress-admin-columns'),
            __('This is the name which will appear as the column header.', 'codepress-admin-columns'),
            new Custom('label')
        );

        $this->custom_label = $custom_label;
    }

    // TODO create factory
    public static function from_config(Config $config):self
    {
        return new self( $config->get('label') ?: '');
    }

    public function get_custom_label(): string
    {
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