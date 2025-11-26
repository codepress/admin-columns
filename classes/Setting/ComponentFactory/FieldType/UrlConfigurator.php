<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldTypeBuilder;

class UrlConfigurator implements FieldTypeConfigurator
{

    public const TYPE = 'link';

    private ComponentFactory\LinkLabel $link_label;

    public function __construct(ComponentFactory\LinkLabel $link_label)
    {
        $this->link_label = $link_label;
    }

    public function configure(FieldTypeBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('URL', 'codepress-admin-columns'), 'basic')
            ->add_child_component(
                $this->link_label,
                StringComparisonSpecification::equal(self::TYPE)
            );
    }
}