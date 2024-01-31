<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class CustomFieldTypeFactory implements SettingFactory
{

    private $string_limit_factory;

    private $number_format_factory;

    private $post_factory;

    private $user_factory;

    public function __construct(
        StringLimitFactory $string_limit_factory,
        NumberFormatFactory $number_format_factory,
        PostFactory $post_factory,
        UserFactory $user_factory
    ) {
        $this->string_limit_factory = $string_limit_factory;
        $this->number_format_factory = $number_format_factory;
        $this->post_factory = $post_factory;
        $this->user_factory = $user_factory;
    }

    public function create(Config $config, Specification $specification = null): Column
    {
        return new CustomFieldType(
            $config->get('field_type') ?: '',
            new SettingCollection([
                $this->string_limit_factory->create(
                    $config,
                    StringComparisonSpecification::equal(CustomFieldType::TYPE_TEXT)
                ),
                $this->number_format_factory->create(
                    $config,
                    StringComparisonSpecification::equal(CustomFieldType::TYPE_NUMERIC)
                ),
                $this->post_factory->create(
                    $config,
                    StringComparisonSpecification::equal(CustomFieldType::TYPE_POST)
                ),
                $this->user_factory->create(
                    $config,
                    StringComparisonSpecification::equal(CustomFieldType::TYPE_USER)
                ),
            ])
        );
    }

    //        return new SettingCollection([
    //            // TODO
    //            new Date(
    //                StringComparisonSpecification::equal(self::TYPE_DATE)
    //            ),
    //            new DateFormat(
    //                StringComparisonSpecification::equal(self::TYPE_DATE)
    //            ),
    //            new Image(
    //                new OrSpecification([
    //                    StringComparisonSpecification::equal(self::TYPE_IMAGE),
    //                    StringComparisonSpecification::equal(self::TYPE_MEDIA),
    //                ])
    //            ),
    //            new MediaLink(
    //                new OrSpecification([
    //                    StringComparisonSpecification::equal(self::TYPE_IMAGE),
    //                    StringComparisonSpecification::equal(self::TYPE_MEDIA),
    //                ])
    //            ),
    //            new LinkLabel(
    //                StringComparisonSpecification::equal(self::TYPE_URL)
    //            ),
    //        ]);

}