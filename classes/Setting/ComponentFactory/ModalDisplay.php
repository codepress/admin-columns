<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollectionFactory\ToggleOptionCollection;

class ModalDisplay extends Builder
{

    public const TOGGLE = 'show_modal_link';
    public const LABEL = 'show_modal_link_label';

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_toggle(
            self::TOGGLE,
            (new ToggleOptionCollection())->create(),
            $config->get(self::TOGGLE, ToggleOptionCollection::OFF)
        );
    }

    protected function get_label(Config $config): ?string
    {
        return 'Display in a Modal';
    }

    protected function get_description(Config $config): ?string
    {
        return __('Displays content in a modal instead of the table cell.', 'codepress-admin-columns');
    }

    protected function get_children(Config $config): ?Children
    {
        $value = $config->get(self::LABEL);

        if ($value === '') {
            $value = __('Display', 'codepress-admin-columns');
        }

        $input = new Input\Open(
            self::LABEL,
            'text',
            $value
        );

        $component = new Component(
            __('Link Label', 'codepress-admin-columns'),
            __('Label of the modal link.', 'codepress-admin-columns'),
            $input,
            StringComparisonSpecification::equal(ToggleOptionCollection::ON)
        );

        return new Children(new ComponentCollection([
            $component,
        ]));
    }

}