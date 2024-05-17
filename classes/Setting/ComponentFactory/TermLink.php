<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;
use AC\Type\PostTypeSlug;

final class TermLink extends Builder
{

    private $post_type;

    public function __construct(PostTypeSlug $post_type)
    {
        $this->post_type = $post_type;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Link To', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            'term_link_to',
            OptionCollection::from_array($this->get_input_options()),
            (string)$config->get('term_link_to')
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        $formatters->add(new Formatter\Term\TermLink((string)$config->get('term_link_to'), $this->post_type));

        return parent::get_formatters($config, $formatters);
    }

    protected function get_input_options(): array
    {
        return [
            ''       => __('None'),
            'filter' => __('Filter by Term', 'codepress-admin-columns'),
            'edit'   => __('Edit Term', 'codepress-admin-columns'),
        ];
    }

}