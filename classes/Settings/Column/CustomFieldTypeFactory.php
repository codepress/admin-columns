<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\OrSpecification;
use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Component;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

class CustomFieldTypeFactory implements SettingFactory
{

    private $string_limit_factory;

    private $number_format_factory;

    private $post_factory;

    private $user_factory;

    private $date_factory;

    private $date_format_factory;

    private $link_label_factory;

    private $image_factory;

    private $media_link_factory;

    public function __construct(
        StringLimitFactory $string_limit_factory,
        NumberFormatFactory $number_format_factory,
        PostFactory $post_factory,
        UserFactory $user_factory,
        DateFactory $date_factory,
        DateFormatFactory $date_format_factory,
        LinkLabelFactory $link_label_factory,
        ImageFactory $image_factory,
        MediaLinkFactory $media_link_factory
    ) {
        $this->string_limit_factory = $string_limit_factory;
        $this->number_format_factory = $number_format_factory;
        $this->post_factory = $post_factory;
        $this->user_factory = $user_factory;
        $this->date_factory = $date_factory;
        $this->date_format_factory = $date_format_factory;
        $this->link_label_factory = $link_label_factory;
        $this->image_factory = $image_factory;
        $this->media_link_factory = $media_link_factory;
    }

    public function create(Config $config, Specification $specification = null): Component
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
                $this->date_factory->create(
                    $config,
                    StringComparisonSpecification::equal(CustomFieldType::TYPE_DATE)
                ),
                $this->date_format_factory->create(
                    $config,
                    StringComparisonSpecification::equal(CustomFieldType::TYPE_DATE)
                ),
                $this->link_label_factory->create(
                    $config,
                    StringComparisonSpecification::equal(CustomFieldType::TYPE_URL)
                ),
                $this->image_factory->create(
                    $config,
                    new OrSpecification([
                        StringComparisonSpecification::equal(CustomFieldType::TYPE_IMAGE),
                        StringComparisonSpecification::equal(CustomFieldType::TYPE_MEDIA),
                    ])
                ),
                $this->media_link_factory->create(
                    $config,
                    new OrSpecification([
                        StringComparisonSpecification::equal(CustomFieldType::TYPE_IMAGE),
                        StringComparisonSpecification::equal(CustomFieldType::TYPE_MEDIA),
                    ])
                ),
            ])
        );
    }

}