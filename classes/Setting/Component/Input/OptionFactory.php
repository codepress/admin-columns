<?php

declare(strict_types=1);

namespace AC\Setting\Component\Input;

use AC\Setting\Component\AttributeCollection;
use AC\Setting\Component\AttributeFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Component\OptionCollectionFactory\ToggleOptionCollection;

final class OptionFactory
{

    public static function create_select(
        string $name,
        OptionCollection $options,
        $default = null,
        string $placeholder = null,
        bool $multiple = null,
        AttributeCollection $attributes = null
    ): Option {
        return new Option(
            $name,
            'select',
            $options,
            $default,
            $placeholder,
            $multiple,
            $attributes
        );
    }

    public static function create_select_remote(
        string $name,
        string $handler,
        $default = null,
        string $placeholder = null,
        bool $multiple = null,
        AttributeCollection $attributes = null
    ): Option {
        if (null === $attributes) {
            $attributes = new AttributeCollection();
        }

        $attributes->add(
            AttributeFactory::create_data(
                'handler',
                $handler
            )
        );

        return new Option(
            $name,
            'select_remote',
            new OptionCollection(),
            $default,
            $placeholder,
            $multiple,
            $attributes
        );
    }

    public static function create_radio(
        string $name,
        OptionCollection $options,
        string $default = null,
        AttributeCollection $attributes = null
    ): Option {
        return new Option(
            $name,
            'radio',
            $options,
            $default,
            null,
            null,
            $attributes
        );
    }

    public static function create_toggle(
        string $name,
        OptionCollection $options = null,
        string $default = null,
        AttributeCollection $attributes = null
    ): Option {
        if (null === $options) {
            $options = (new ToggleOptionCollection())->create();
        }

        return new Option(
            $name,
            'toggle',
            $options,
            $default,
            null,
            null,
            $attributes
        );
    }

}