<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Expression\StringComparisonSpecification;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;
use AC\Setting\ComponentFactory\ModalDisplay;
use AC\Setting\Control\OptionCollectionFactory\ToggleOptionCollection;
use AC\Value;

class HtmlConfigurator implements FieldTypeConfigurator
{

    private ModalDisplay $modal_display;

    public function __construct(ModalDisplay $modal_display)
    {
        $this->modal_display = $modal_display;
    }

    public const TYPE = 'html';

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('HTML', 'codepress-admin-columns'), 'basic')
            ->add_child_component(
                $this->modal_display,
                StringComparisonSpecification::equal(self::TYPE)
            )
            ->add_formatter(self::TYPE, function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                if ($config->get($this->modal_display::TOGGLE) === ToggleOptionCollection::ON) {
                    $formatters->add(
                        new Value\Formatter\ExtendedValueLink(
                            new Value\ExtendedValueLinkFactory(),
                            $config->get($this->modal_display::LABEL)
                        )
                    );
                }
            });
    }
}