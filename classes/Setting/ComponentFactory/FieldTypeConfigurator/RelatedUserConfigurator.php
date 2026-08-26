<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\FormatterCollection;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;
use AC\Setting\ComponentFactory\NumberOfItems;

class RelatedUserConfigurator implements FieldTypeConfigurator
{
    private const TYPE = 'user_by_id';

    private Setting\ComponentFactory\UserProperty $user_property;

    private Setting\ComponentFactory\UserLink $user_link;

    private NumberOfItems $number_of_items;

    public function __construct(
        Setting\ComponentFactory\UserProperty $user_property,
        Setting\ComponentFactory\UserLink $user_link,
        NumberOfItems $number_of_items
    ) {
        $this->user_property = $user_property;
        $this->user_link = $user_link;
        $this->number_of_items = $number_of_items;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('User', 'codepress-admin-columns'), 'relational')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, FormatterCollection $formatters) {
                    $formatters->add(new AC\Formatter\IdsToCollection());
                }
            )->add_child_component(
                $this->user_property,
                StringComparisonSpecification::equal(self::TYPE)
            )->add_child_component(
                $this->user_link,
                StringComparisonSpecification::equal(self::TYPE)
            )->add_child_component(
                $this->number_of_items,
                StringComparisonSpecification::equal(self::TYPE)
            )->add_final_formatter(
                self::TYPE,
                function (Setting\Config $config, FormatterCollection $formatters) {
                    $formatters->add(
                        new AC\Formatter\Collection\Separator(', ', (int)$config->get('number_of_items', 0))
                    );
                }
            );
    }
}
