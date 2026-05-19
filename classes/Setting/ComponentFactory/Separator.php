<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;

final class Separator implements ComponentFactory, InputNameAware
{

    public const DEFAULT = '';
    public const COMMA = 'comma';
    public const HORIZONTAL_RULE = 'horizontal_rule';
    public const NEW_LINE = 'newline';
    public const NONE = 'none';
    public const WHITE_SPACE = 'white_space';

    public function get_name(): string
    {
        return 'separator';
    }

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        return (new ComponentBuilder())
            ->set_label(__('Separator', 'codepress-admin-columns'))
            ->set_input(
                AC\Setting\Control\Input\OptionFactory::create_select(
                    $this->get_name(),
                    AC\Setting\Control\OptionCollection::from_array([
                        self::DEFAULT         => __('Default', 'codepress-admin-columns'),
                        self::COMMA           => __('Comma Separated', 'codepress-admin-columns'),
                        self::HORIZONTAL_RULE => __('Horizontal Rule', 'codepress-admin-columns'),
                        self::NEW_LINE        => __('New Line', 'codepress-admin-columns'),
                        self::NONE            => __('None', 'codepress-admin-columns'),
                        self::WHITE_SPACE     => __('Whitespace', 'codepress-admin-columns'),
                    ]),
                    $config->get($this->get_name(), self::DEFAULT)
                )
            )
            ->build();
    }

}