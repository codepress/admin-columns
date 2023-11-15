<?php

declare(strict_types=1);

namespace AC\Setting\Column;

use AC\Setting\Multiple;
use AC\Setting\OptionCollection;
use AC\Setting\Recursive;
use AC\Setting\RecursiveTrait;
use AC\Setting\SettingCollection;
use AC\Setting\SettingTrait;
use AC\Setting\Single;
use AC\Setting\Type\Option;

final class Width implements Recursive
{

    use SettingTrait;
    use RecursiveTrait;

    public function __construct()
    {
        $this->name = 'width';
        $this->label = __('Width', 'codepress-admin-columns');
    }

    public function get_children(): SettingCollection
    {
        $settings = [
            Single::create('width'),
            Multiple::create(
                'width_unit',
                new OptionCollection([
                    Option::from_value('%'),
                    Option::from_value('px'),
                ])
            ),
        ];

        return new SettingCollection($settings);
    }

}