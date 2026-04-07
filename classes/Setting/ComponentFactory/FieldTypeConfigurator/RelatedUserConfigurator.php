<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

class RelatedUserConfigurator implements FieldTypeConfigurator
{

    private const TYPE = 'user_by_id';

    private Setting\ComponentFactory\UserProperty $user_property;

    private Setting\ComponentFactory\UserLink $user_link;

    public function __construct(
        Setting\ComponentFactory\UserProperty $user_property,
        Setting\ComponentFactory\UserLink $user_link
    ) {
        $this->user_property = $user_property;
        $this->user_link = $user_link;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('User', 'codepress-admin-columns'), 'relational')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                    $formatters->add(new AC\Value\Formatter\IdCollectionFromArrayOrString());
                }
            )->add_child_component(
                $this->user_property,
                StringComparisonSpecification::equal(self::TYPE)
            )->add_child_component(
                $this->user_link,
                StringComparisonSpecification::equal(self::TYPE)
            );
    }
}