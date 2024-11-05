<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Type\TableScreenContext;

class CustomField extends Builder
{

    private const NAME = 'field';

    private TableScreenContext $table_screen_context;

    public function __construct(TableScreenContext $table_screen_context)
    {
        $this->table_screen_context = $table_screen_context;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Field', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('Custom field key', 'codepress-admin-columns');
    }

    private function use_text_field(): bool
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
                'meta_type' => (string)$this->table_screen_context->get_meta_type(),
                'post_type' => $this->table_screen_context->has_post_type()
                    ? (string)$this->table_screen_context->get_post_type()
                    : '',
                'taxonomy'  => $this->table_screen_context->has_taxonomy()
                    ? (string)$this->table_screen_context->get_taxonomy()
                    : '',
            ],
            __('Select', 'codepress-admin-columns')
        );
    }

}