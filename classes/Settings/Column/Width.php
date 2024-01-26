<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use InvalidArgumentException;

final class Width extends Column implements AC\Setting\Recursive
{

    private $default;

    private $default_unit;

    public function __construct(int $default = null, string $default_unit = 'px')
    {
        parent::__construct(
            'width',
            __('Width', 'codepress-admin-columns'),
            '',
            new Input\Custom('width')
        );

        $this->default = $default;
        $this->default_unit = $default_unit;

        $this->validate();
    }

    private function validate(): void
    {
        if ( ! in_array($this->default_unit, ['%', 'px'], true)) {
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
            new Column(
                $this->name,
                '',
                '',
                Input\Number::create_single_step(0, null, $this->default)
            ),
            new Column(
                $this->name . '_unit',
                '',
                '',
                Input\Option\Single::create_radio(
                    OptionCollection::from_array([
                        '%',
                        'px',
                    ], false),
                    $this->default_unit
                )
            ),
        ];

        return new SettingCollection($settings);
    }

}