<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\RecursiveFormatterTrait;
use AC\Setting\Type\Value;
use AC\Settings;

class Post extends Settings\Control implements Formatter, Setting\Children
{

    use RecursiveFormatterTrait;

    public const PROPERTY_AUTHOR = 'author';
    public const PROPERTY_FEATURED_IMAGE = 'thumbnail';
    public const PROPERTY_ID = 'id';
    public const PROPERTY_TITLE = 'title';
    public const PROPERTY_DATE = 'date';
    public const PROPERTY_STATUS = 'status';

    private $settings;

    private $post_format;

    public function __construct(string $post_format, ComponentCollection $settings, Specification $conditionals = null)
    {
        parent::__construct(
            OptionFactory::create_select(
                'post',
                $this->get_display_options(),
                $post_format
            ),
            __('Display', 'codepress-admin-columns'),
            null,
            $conditionals
        );

        $this->settings = $settings;
        $this->post_format = $post_format;
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

        asort($options);

        return OptionCollection::from_array($options);
    }

    public function pre_format_value(Value $value): Value
    {
        if ( ! $value->get_id()) {
            return $value->with_value(false);
        }

        switch ($this->post_format) {
            case self::PROPERTY_TITLE :
                $title = get_post_field('post_title', (int)$value->get_id())
                    ?: sprintf(
                        '%s (%s)',
                        __('No title', 'codepress-admin-columns'),
                        $value->get_id()
                    );

                return $value->with_value($title);
            case self::PROPERTY_FEATURED_IMAGE :
                return $value->with_value((string)get_post_thumbnail_id((int)$value->get_id()));
            case self::PROPERTY_AUTHOR :
                // TODO test
                return new Value((int)get_post_field('post_author', (int)$value->get_id()));
            case self::PROPERTY_STATUS :
                return $value->with_value((string)get_post_field('post_status', (int)$value->get_id()));
            case self::PROPERTY_DATE :
                // TODO
                return $value->with_value((string)get_post_field('post_date_gmt', (int)$value->get_id()));
            case self::PROPERTY_ID :
            default:
                return $value;
        }
    }

    public function format(Value $value): Value
    {
        return $this->get_recursive_formatter($this->post_format)
                    ->format(
                        $this->pre_format_value($value)
                    );
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function get_iterator(): ComponentCollection
    {
        return $this->settings;
    }

}