<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\FormatterCollection;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;
use AC\Setting\ComponentFactory\NumberOfItems;

class RelatedPostConfigurator implements FieldTypeConfigurator
{
    public const TYPE = 'title_by_id';

    private Setting\ComponentFactory\LinkablePostProperty $post_property;

    private NumberOfItems $number_of_items;

    public function __construct(
        Setting\ComponentFactory\LinkablePostProperty $post_property,
        NumberOfItems $number_of_items
    ) {
        $this->post_property = $post_property;
        $this->number_of_items = $number_of_items;
    }

    public function get_type(): string
    {
        return self::TYPE;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Post', 'codepress-admin-columns'), 'relational')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, FormatterCollection $formatters) {
                    $formatters->add(new AC\Formatter\IdsToCollection());
                }
            )->add_child_component(
                $this->post_property,
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
