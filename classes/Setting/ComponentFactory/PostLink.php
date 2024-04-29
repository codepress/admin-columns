<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Relation;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class PostLink extends Builder
{

    protected const NAME = 'post_link_to';

    private $relation;

    public function __construct(Relation $relation = null)
    {
        $this->relation = $relation;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Link to', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            self::NAME,
            OptionCollection::from_array($this->get_display_options()),
            (string)$config->get(self::NAME, '')
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        $formatters->add(new Formatter\Post\PostLink((string)$config->get(self::NAME)));

        return parent::get_formatters($config, $formatters);
    }

    protected function get_display_options(): array
    {
        // Default options
        $options = [
            ''            => __('None'),
            'edit_post'   => __('Edit Post'),
            'view_post'   => __('View Post'),
            'edit_author' => __('Edit Post Author', 'codepress-admin-columns'),
            'view_author' => __('View Public Post Author Page', 'codepress-admin-columns'),
        ];

        if ($this->relation) {
            $relation_options = [
                'edit_post'   => _x('Edit %s', 'post'),
                'view_post'   => _x('View %s', 'post'),
                'edit_author' => _x('Edit %s Author', 'post', 'codepress-admin-columns'),
                'view_author' => _x('View Public %s Author Page', 'post', 'codepress-admin-columns'),
            ];

            $label = $this->relation->get_labels()->singular_name;

            foreach ($relation_options as $k => $option) {
                $options[$k] = sprintf($option, $label);
            }
        }

        return $options;
    }

}