<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\AttributeFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

class SerializedConfigurator implements FieldTypeConfigurator
{
    public const TYPE = 'array';

    private ComponentFactory\SerializedDisplay $serialized_display;

    public function __construct(ComponentFactory\SerializedDisplay $serialized_display)
    {
        $this->serialized_display = $serialized_display;
    }

    public function get_type(): string
    {
        return self::TYPE;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(
                self::TYPE,
                sprintf(
                    '%s / %s',
                    __('Multiple Values', 'codepress-admin-columns'),
                    __('Serialized', 'codepress-admin-columns')
                ),
                'multiple'
            )->add_attribute(
                self::TYPE,
                AttributeFactory::create_help_reference('doc-serialized')
            )->add_child_component(
                $this->serialized_display,
                StringComparisonSpecification::equal(self::TYPE)
            );
    }
}
