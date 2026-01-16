<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\FormatterCollection;
use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

class PostProperty extends BaseComponentFactory
{

    public const NAME = 'post';
    public const PROPERTY_AUTHOR = 'author';
    public const PROPERTY_FEATURED_IMAGE = 'thumbnail';
    public const PROPERTY_ID = 'id';
    public const PROPERTY_TITLE = 'title';
    public const PROPERTY_DATE = 'date';
    public const PROPERTY_STATUS = 'status';

    protected StringLimit $string_limit;

    protected ImageSize $image_size;

    protected UserProperty $user_property;

    protected PostStatusIcon $post_status_icon;

    protected Date $date;

    public function __construct(
        StringLimit $string_limit,
        ImageSize $image_size,
        UserProperty $user_property,
        PostStatusIcon $post_status_icon,
        Date $date
    ) {
        $this->string_limit = $string_limit;
        $this->image_size = $image_size;
        $this->user_property = $user_property;
        $this->post_status_icon = $post_status_icon;
        $this->date = $date;
    }

    protected function get_label(Config $config): string
    {
        return __('Post Display', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): Input
    {
        return OptionFactory::create_select(
            self::NAME,
            $this->get_display_options(),
            $config->get(self::NAME, self::PROPERTY_TITLE)
        );
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
                $this->string_limit->create(
                    $config,
                    StringComparisonSpecification::equal(self::PROPERTY_TITLE)
                ),
                $this->image_size->create(
                    $config,
                    StringComparisonSpecification::equal(self::PROPERTY_FEATURED_IMAGE)
                ),
                $this->user_property->create(
                    $config,
                    StringComparisonSpecification::equal(self::PROPERTY_AUTHOR)
                ),
                $this->post_status_icon->create(
                    $config,
                    StringComparisonSpecification::equal(self::PROPERTY_STATUS)
                ),
                $this->date->create(
                    $config,
                    StringComparisonSpecification::equal(self::PROPERTY_DATE)
                ),
            ])
        );
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        switch ($this->get_input($config)->get_value()) {
            case self::PROPERTY_TITLE:
                $formatters->add(new AC\Formatter\Post\PostTitle());
                break;
            case self::PROPERTY_FEATURED_IMAGE:
                $formatters->add(new AC\Formatter\Post\FeaturedImage());
                break;
            case self::PROPERTY_AUTHOR:
                $formatters->add(new AC\Formatter\Post\Author());
                break;
            case self::PROPERTY_STATUS:
                $formatters->add(new AC\Formatter\Post\PostStatus());
                break;
            case self::PROPERTY_DATE:
                $formatters->add(new AC\Formatter\Post\GmtDate());
                $formatters->add(new AC\Formatter\Date\WordPressDateFormat('U', 'Y-m-d H:i:s'));
                break;
        }
    }

    protected function get_display_options(): OptionCollection
    {
        $options = [
            self::PROPERTY_TITLE          => __('Title'),
            self::PROPERTY_ID             => __('ID'),
            self::PROPERTY_AUTHOR         => __('Author'),
            self::PROPERTY_FEATURED_IMAGE => _x('Featured Image', 'post'),
            self::PROPERTY_DATE           => __('Date'),
            self::PROPERTY_STATUS         => __('Status'),
        ];

        natcasesort($options);

        return OptionCollection::from_array($options);
    }

}