<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting;
use AC\Setting\ArrayImmutable;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;

class Post extends Recursive
{

    public const NAME = 'post';

    public const PROPERTY_AUTHOR = 'author';
    public const PROPERTY_FEATURED_IMAGE = 'thumbnail';
    public const PROPERTY_ID = 'id';
    public const PROPERTY_TITLE = 'title';
    public const PROPERTY_DATE = 'date';
    public const PROPERTY_STATUS = 'status';

    public function __construct(Specification $conditionals = null)
    {
        parent::__construct(
            'post',
            __('Display', 'codepress-admin-columns'),
            '',
            Setting\Input\Option\Single::create_select(
                Setting\OptionCollection::from_array($this->get_display_options())
            ),
            $conditionals
        );
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $ids = $value->get_value();

        if ( ! $ids) {
            return $value->with_value('');
        }

        $option = $options->get($this->get_name());

        switch ($option) {
            case self::PROPERTY_FEATURED_IMAGE:
                $value = $value->with_value(get_post_thumbnail_id($ids[0]));

                break;
            case self::PROPERTY_AUTHOR :
                return $value->with_value(
                // TODO $ids[0]
                    ac_helper()->user->get_display_name($ids[0])
                        ?: sprintf(
                        '<em>%s</em> (%s)',
                        __('No author', 'codepress-admin-columns'),
                        $ids[0]
                    )
                );
            // TODO add formatter
        }

        return parent::format($value, $options);
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Image(
                StringComparisonSpecification::equal(self::PROPERTY_FEATURED_IMAGE)
            ),
            new Date(
                StringComparisonSpecification::equal(self::PROPERTY_DATE)
            ),
            new CharacterLimit(
                StringComparisonSpecification::equal(self::PROPERTY_TITLE)
            ),
            new StatusIcon(
                StringComparisonSpecification::equal(self::PROPERTY_STATUS)
            ),
        ]);
    }

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
    protected function get_display_options(): array
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

        return $options;
    }
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