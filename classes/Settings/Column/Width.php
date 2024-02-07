<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Component\Input\Number;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Settings\Setting;
use InvalidArgumentException;

final class Width extends AC\Settings\Component implements AC\Setting\Recursive
{

    private $width;

    private $width_unit;

    public function __construct(int $width = null, string $width_unit = null)
    {
        parent::__construct(
            'row_width',
            __('Width', 'codepress-admin-columns')
        );

        $this->width = $width;
        $this->width_unit = $width_unit ?? 'px';

        $this->validate();
    }

    private function validate(): void
    {
        if ( ! in_array($this->width_unit, ['%', 'px'], true)) {
            throw new InvalidArgumentException('Invalid width unit');
        }
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function get_children(): SettingCollection
    {
        $settings = [
            new Setting(
                Number::create_single_step(
                    'width',
                    0,
                    null,
                    $this->width
                ),
                ''
            ),
            new Setting(
                OptionFactory::create_radio(
                    'width_unit',
                    OptionCollection::from_array(
                        [
                            '%',
                            'px',
                        ],
                        false
                    ),
                    $this->width_unit
                ),
                ''
            ),
        ];

        return new SettingCollection($settings);
    }

}