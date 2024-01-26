<?php

declare(strict_types=1);

namespace AC\Settings\Column;

<<<<<<< HEAD
use AC\Column;
use AC\Setting\Base;
use AC\Setting\Component\OptionCollection;
=======
use AC;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use InvalidArgumentException;

final class Width extends Column implements AC\Setting\Recursive
{

    private $default;

<<<<<<< HEAD
    public function __construct(Column $column, int $default = null)
    {
        $this->name = 'width';
        $this->label = __('Width', 'codepress-admin-columns');
        $this->input = new Input\Element\Custom('width');
        $this->default = $default;
=======
    private $default_unit;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2

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
                Input\Element\Number::create_single_step(0, null, $this->default)
            ),
            new Column(
                $this->name . '_unit',
                '',
                '',
                Input\Element\Single::create_radio(
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