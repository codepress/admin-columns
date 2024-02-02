<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\RecursiveFormatterTrait;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Settings\Column;

class Post extends Column implements Formatter
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

    public function __construct(string $post_format, SettingCollection $settings, Specification $conditionals = null)
    {
        parent::__construct(
            __('Display', 'codepress-admin-columns'),
            '',
            OptionFactory::create_select(
                'post',
                $this->get_display_options(),
                $post_format
            ),
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
        if ( ! is_numeric($value->get_value())) {
            return $value;
        }

        $id = (int)$value->get_value();

        switch ($this->post_format) {
            case self::PROPERTY_TITLE :
                $title = get_post_field('post_title', $id)
                    ?: sprintf(
                        '%s (%s)',
                        __('No title', 'codepress-admin-columns'),
                        $id
                    );

                return $value->with_value($title);
            case self::PROPERTY_FEATURED_IMAGE :
                return ( new Formatter\Post\FeaturedImage())->format($value);
            case self::PROPERTY_AUTHOR :
                return $value->with_value((int)get_post_field('post_author', $id));
            case self::PROPERTY_STATUS :
                return $value->with_value((string)get_post_field('post_status', $id));
            case self::PROPERTY_DATE :
                // TODO
                return $value->with_value((string)get_post_field('post_date_gmt', $id));
            case self::PROPERTY_ID :
            default:
                return $value;
        }
    }

    private function get_formatter():Formatter
    {
        switch ($this->post_format) {
            case self::PROPERTY_FEATURED_IMAGE :
                return new Formatter\Post\FeaturedImage();
        }

        return $value;
    }

    public function format(Value $value): Value
    {
        return $this->format_by_condition(
            $this->get_formatter()->format($value),
            $this->post_format
        );
    }

    public function get_children(): SettingCollection
    {
        return $this->settings;
    }

    // TODO
    //
    //    public function format(Value $value, Config $options): Value
    //    {
    //        $ids = $value->get_value();
    //
    //        if ( ! $ids) {
    //            return $value->with_value('');
    //        }
    //
    //        $option = $options->get($this->get_name());
    //
    //        switch ($option) {
    //            case self::PROPERTY_FEATURED_IMAGE:
    //                $value = $value->with_value(get_post_thumbnail_id($value->get_value()));
    //
    //                break;
    //            case self::PROPERTY_FEATURED_IMAGE:
    //                $value = $value->with_value(get_post_thumbnail_id($ids[0]));
    //
    //                break;
    //            case self::PROPERTY_AUTHOR :
    //                return $value->with_value(
    //                // TODO $ids[0]
    //                    ac_helper()->user->get_display_name($ids[0])
    //                        ?: sprintf(
    //                        '<em>%s</em> (%s)',
    //                        __('No author', 'codepress-admin-columns'),
    //                        $ids[0]
    //                    )
    //                );
    // TODO add formatter
    //        }
    //
    //        return parent::format($value, $options);
    //    }

    // TODO
    //
    //    /**
    //     * @var string
    //     */
    //    private $post_property;
    //
    //    protected function set_name()
    //    {
    //        $this->name = self::NAME;
    //    }
    //
    //    protected function define_options()
    //    {
    //        return [
    //            'post_property_display' => self::PROPERTY_TITLE,
    //        ];
    //    }
    //
    //    public function get_dependent_settings()
    //    {
    //        $settings = [];
    //
    //        switch ($this->get_post_property_display()) {
    //            case self::PROPERTY_FEATURED_IMAGE :
    //                $settings[] = new Settings\Column\Image($this->column);
    //                break;
    //            case self::PROPERTY_DATE :
    //                $settings[] = new Settings\Column\Date($this->column);
    //                break;
    //            case self::PROPERTY_TITLE :
    //                $setting = new Settings\Column\CharacterLimit($this->column);
    //                $setting->set_default(30);
    //
    //                $settings[] = $setting;
    //                break;
    //            case self::PROPERTY_STATUS :
    //                $settings[] = new StatusIcon($this->column);
    //        }
    //
    //        $settings[] = new Settings\Column\PostLink($this->column);
    //
    //        return $settings;
    //    }
    //
    //    /**
    //     * @param int   $id
    //     * @param mixed $original_value
    //     *
    //     * @return string|int
    //     */
    //    public function format($id, $original_value)
    //    {
    //        switch ($this->get_post_property_display()) {
    //            case self::PROPERTY_AUTHOR :
    //                return ac_helper()->user->get_display_name(
    //                    ac_helper()->post->get_raw_field('post_author', $id)
    //                ) ?: sprintf('%s (%s)', __('No author', 'codepress-admin-columns'), $id);
    //            case self::PROPERTY_FEATURED_IMAGE :
    //                return get_post_thumbnail_id($id);
    //            case self::PROPERTY_TITLE :
    //                $title = ac_helper()->post->get_title($id);
    //
    //                if (false !== $title) {
    //                    return $title
    //                        ?: sprintf(
    //                            '%s (%s)',
    //                            __('No title', 'codepress-admin-columns'),
    //                            $id
    //                        );
    //                }
    //
    //                return '';
    //            case self::PROPERTY_DATE :
    //                return ac_helper()->post->get_raw_field('post_date', $id);
    //            case self::PROPERTY_STATUS:
    //                return ac_helper()->post->get_raw_field('post_status', $id);
    //
    //            default :
    //                return $id;
    //        }
    //    }
    //
    //    public function create_view()
    //    {
    //        $select = $this->create_element('select')
    //                       ->set_attribute('data-refresh', 'column')
    //                       ->set_options($this->get_display_options());
    //
    //        $view = new View([
    //            'label'   => __('Display', 'codepress-admin-columns'),
    //            'setting' => $select,
    //        ]);
    //
    //        return $view;
    //    }
    //
    //
    //    /**
    //     * @return string
    //     */
    //    public function get_post_property_display()
    //    {
    //        return $this->post_property;
    //    }
    //
    //    /**
    //     * @param string $post_property
    //     *
    //     * @return bool
    //     */
    //    public function set_post_property_display($post_property)
    //    {
    //        $this->post_property = $post_property;
    //
    //        return true;
    //    }

}