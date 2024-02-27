<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Settings;

final class Separator extends Settings\Control
{

    public const DEFAULT = '';
    public const COMMA = 'comma';
    public const HORIZONTAL_RULE = 'horizontal_rule';
    public const NEW_LINE = 'newline';
    public const NONE = 'none';
    public const WHITE_SPACE = 'white_space';

    public function __construct(string $separator, Specification $specification = null)
    {
        parent::__construct(
            AC\Setting\Component\Input\OptionFactory::create_select(
                'separator',
                AC\Setting\Component\OptionCollection::from_array([
                    self::DEFAULT         => __('Default', 'codepress-admin-columns'),
                    self::COMMA           => __('Comma Separated', 'codepress-admin-columns'),
                    self::HORIZONTAL_RULE => __('Horizontal Rule', 'codepress-admin-columns'),
                    self::NEW_LINE        => __('New Line', 'codepress-admin-columns'),
                    self::NONE            => __('None', 'codepress-admin-columns'),
                    self::WHITE_SPACE     => __('Whitespace', 'codepress-admin-columns'),
                ]),
                $separator
            ),
            __('Separator', 'codepress-admin-columns'),
            $specification
        );
    }

}