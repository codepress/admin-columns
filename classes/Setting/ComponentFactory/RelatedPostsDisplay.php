<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\FormatterCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class RelatedPostsDisplay extends BaseComponentFactory
{
    private const NAME = 'related_posts_display';

    private const OPTION_COUNT = 'count';

    private const OPTION_TITLES = 'titles';

    protected function get_label(Config $config): ?string
    {
        return __('Display', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            self::NAME,
            OptionCollection::from_array([
                self::OPTION_TITLES => __('Titles', 'codepress-admin-columns'),
                self::OPTION_COUNT  => __('Count', 'codepress-admin-columns'),
            ]),
            (string)$config->get(self::NAME, self::OPTION_TITLES)
        );
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        $meta_key = (string)$config->get('related_meta_key', '');
        $post_type = RelatedPostType::get_post_type($config);

        if ('' === $meta_key || '' === $post_type) {
            return;
        }

        $formatters->add(
            new AC\Formatter\Post\PostReferences($meta_key, new AC\Type\PostTypeSlug($post_type))
        );

        if (self::OPTION_COUNT === $config->get(self::NAME, self::OPTION_TITLES)) {
            $formatters->add(new AC\Formatter\Post\RelatedPostsCount($meta_key, $post_type));

            return;
        }

        $formatters->add(new AC\Formatter\Post\PostTitle());
    }

    protected function add_final_formatters(Config $config, FormatterCollection $formatters): void
    {
        // An unconfigured column (no meta key selected) must stay without formatters, so the cell falls back to default rendering
        if ($formatters->count() < 1) {
            return;
        }

        $formatters->add(new AC\Formatter\Collection\Separator(', '));
    }

}
