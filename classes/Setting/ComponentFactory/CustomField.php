<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Type\ListKey;

class CustomField extends Builder
{

    private const NAME = 'field';

    private $list_key;

    public function __construct(ListKey $list_key)
    {
        $this->list_key = $list_key;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Field', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('Custom field key', 'codepress-admin-columns');
    }

    private function use_text_field()
    {
        return (bool)apply_filters('ac/column/custom_field/use_text_input', false);
    }

    protected function get_input(Config $config): ?Input
    {
        if ($this->use_text_field()) {
            return new Input\Open(
                self::NAME,
                'text',
                (string)$config->get('field', ''),
                __('Custom field key', 'codepress-admin-columns')
            );
        }

        return OptionFactory::create_select_remote(
            self::NAME,
            'ac-custom-field-keys',
            $config->get('field', ''),
            [
                'list_key' => (string)$this->list_key,
            ],
            __('Select', 'codepress-admin-columns')
        );
    }

}