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
        OptionCollection $options,
        $default = null,
        string $placeholder = null,
        bool $multiple = null,
        AttributeCollection $attributes = null
    ): Option {
        return new Option(
            'select',
            $options,
            $default,
            $placeholder,
            $multiple,
            $attributes
        );
    }

    public static function create_select_remote(
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

        return self::create_select(
            new OptionCollection(),
            $default,
            $placeholder,
            $multiple,
            $attributes
        );
    }

    public static function create_radio(
        OptionCollection $options,
        string $default = null,
        AttributeCollection $attributes = null
    ): Option {
        return new Option(
            'radio',
            $options,
            $default,
            null,
            null,
            $attributes
        );
    }

    public static function create_toggle(
        OptionCollection $options = null,
        string $default = null,
        AttributeCollection $attributes = null
    ): Option {
        if (null === $options) {
            $options = (new ToggleOptionCollection())->create();
        }

        return new Option(
            'toggle',
            $options,
            $default,
            null,
            null,
            $attributes
        );
    }

}