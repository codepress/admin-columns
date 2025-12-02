<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

class RelatedPostConfigurator implements FieldTypeConfigurator
{

    private const TYPE = 'title_by_id';

    private Setting\ComponentFactory\LinkablePostProperty $post_property;

    public function __construct(Setting\ComponentFactory\LinkablePostProperty $post_property)
    {
        $this->post_property = $post_property;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Post', 'codepress-admin-columns'), 'relational')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                    $formatters->add(new AC\Value\Formatter\IdCollectionFromArrayOrString());
                }
            )->add_child_component(
                $this->post_property,
                StringComparisonSpecification::equal(self::TYPE)
            );
    }
}