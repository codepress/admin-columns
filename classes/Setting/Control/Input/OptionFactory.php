<?php

declare(strict_types=1);

namespace AC\Setting\Control\Input;

use AC\Setting\AttributeCollection;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Control\OptionCollectionFactory\ToggleOptionCollection;
use AC\Setting\Type\Attribute;

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
        array $data = [],
        string $placeholder = null,
        bool $multiple = null,
        AttributeCollection $attributes = null
    ): Option {
        if (null === $attributes) {
            $attributes = new AttributeCollection();
        }

        $attributes->add(
            new Attribute(
                'data-handler',
                $handler
            )
        );

        $attributes->add(
            new Attribute(
                'data-params',
                $data ? json_encode($data) : '{}'
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