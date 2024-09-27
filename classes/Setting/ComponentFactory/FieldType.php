<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\AttributeFactory;
use AC\Setting\Children;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\FormatterCollection;

// TODO huge performance issue see usages RelatedUserMetaField and CustomFieldFactory
class FieldType extends Builder
{

    private const NAME = 'field_type';

    public const TYPE_DEFAULT = '';
    public const TYPE_ARRAY = 'array';
    public const TYPE_BOOLEAN = 'checkmark';
    public const TYPE_COLOR = 'color';
    public const TYPE_COUNT = 'count';
    public const TYPE_DATE = 'date';
    public const TYPE_IMAGE = 'image';
    public const TYPE_MEDIA = 'library_id';
    public const TYPE_NON_EMPTY = 'has_content';
    public const TYPE_NUMERIC = 'numeric';
    public const TYPE_POST = 'title_by_id';
    public const TYPE_TEXT = 'excerpt';
    public const TYPE_URL = 'link';
    public const TYPE_USER = 'user_by_id';

    public const TYPE_SELECT = 'select';

    private $string_limit;

    private $number_format;

    private $post;

    private $user;

    private $date;

    private $date_format;

    private $link_label;

    private $image;

    private $media_link;

    private SelectOptions $select_options;

    public function __construct(
        StringLimit $string_limit,
        NumberFormat $number_format,
        PostProperty $post,
        UserProperty $user,
        DateFormat\Date $date,
        DateSaveFormat $date_format,
        LinkLabel $link_label,
        ImageSize $image,
        MediaLink $media_link,
        SelectOptions $select_options
    ) {
        $this->string_limit = $string_limit;
        $this->number_format = $number_format;
        $this->post = $post;
        $this->user = $user;
        $this->date = $date;
        $this->date_format = $date_format;
        $this->link_label = $link_label;
        $this->image = $image;
        $this->media_link = $media_link;
        $this->select_options = $select_options;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Field Type', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('This will determine how the value will be displayed.', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            self::NAME,
            $this->get_field_type_options(),
            $config->get(self::NAME, ''),
            __('Select the field type', 'codepress-admin-columns'),
            false,
            new AC\Setting\AttributeCollection([
                AttributeFactory::create_refresh(),
            ])
        );
    }

    protected function get_field_type_options(): OptionCollection
    {
        $groups = [
            'basic'      => __('Basic', 'codepress-admin-columns'),
            'relational' => __('Relational', 'codepress-admin-columns'),
            'choice'     => __('Choice', 'codepress-admin-columns'),
            'multiple'   => __('Multiple', 'codepress-admin-columns'),
            'custom'     => __('Custom', 'codepress-admin-columns'),
        ];

        $collection = new OptionCollection();
        $collection->add(
            new AC\Setting\Control\Type\Option(__('Default', 'codepress-admin-columns'), '')
        );

        foreach ($this->get_field_types() as $group => $options) {
            foreach ($options as $value => $label) {
                $collection->add(
                    new AC\Setting\Control\Type\Option(
                        $label,
                        $value,
                        $groups[$group] ?? $group
                    )
                );
            }
        }

        return $collection;
    }

    protected function get_field_types(): array
    {
        $grouped_types = [
            'basic'      => [
                self::TYPE_COLOR   => __('Color', 'codepress-admin-columns'),
                self::TYPE_DATE    => __('Date', 'codepress-admin-columns'),
                self::TYPE_TEXT    => __('Text', 'codepress-admin-columns'),
                self::TYPE_IMAGE   => __('Image', 'codepress-admin-columns'),
                self::TYPE_URL     => __('URL', 'codepress-admin-columns'),
                self::TYPE_NUMERIC => __('Number', 'codepress-admin-columns'),
            ],
            'choice'     => [
                self::TYPE_NON_EMPTY => __('Has Content', 'codepress-admin-columns'),
                self::TYPE_BOOLEAN   => __('True / False', 'codepress-admin-columns'),
                self::TYPE_SELECT    => __('Select', 'codepress-admin-columns'),
            ],
            'relational' => [
                self::TYPE_MEDIA => __('Media', 'codepress-admin-columns'),
                self::TYPE_POST  => __('Post', 'codepress-admin-columns'),
                self::TYPE_USER  => __('User', 'codepress-admin-columns'),
            ],
            'multiple'   => [
                self::TYPE_COUNT => __('Number of Fields', 'codepress-admin-columns'),
                self::TYPE_ARRAY => sprintf(
                    '%s / %s',
                    __('Multiple Values', 'codepress-admin-columns'),
                    __('Serialized', 'codepress-admin-columns')
                ),
            ],
        ];

        /**
         * Filter the available custom field types for the meta (custom field) field
         *
         * @param array $field_types Available custom field types ([type] => [label])
         *
         * @since 3.0
         */
        //TODO remove?
        $grouped_types['custom'] = apply_filters('ac/column/custom_field/field_types', []);

        foreach ($grouped_types as $k => $fields) {
            natcasesort($grouped_types[$k]);
        }

        return $grouped_types;
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        $field_type = $config->get(self::NAME, self::TYPE_DEFAULT);

        switch ($field_type) {
            case self::TYPE_COLOR:
                $formatters->add(new AC\Value\Formatter\Color());
                break;
            case self::TYPE_NUMERIC:
                $formatters->add(
                    new AC\Value\Formatter\NumberFormat(
                        (int)$config->get('number_decimals', 0),
                        $config->get('number_decimal_point', '.'),
                        $config->get('number_thousands_separator', ',')
                    )
                );
                break;
            case self::TYPE_NON_EMPTY:
                $formatters->add(new AC\Value\Formatter\YesNoIcon());
                break;
            case self::TYPE_USER:
            case self::TYPE_MEDIA: // TODO check for string also
            case self::TYPE_IMAGE: // TODO check for string also
            case self::TYPE_POST:
                $formatters->add(new AC\Value\Formatter\ForeignId());
                break;
        }
    }

    protected function get_children(Config $config): ?Children
    {
        // TODO huge performance issue
        // test with
        //        return null;

        return new Children(
            new AC\Setting\ComponentCollection([
                $this->string_limit->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_TEXT)
                ),
                $this->number_format->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_NUMERIC)
                ),
                // TODO huge performance issue
                $this->post->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_POST)
                ),
                // TODO huge performance issue
                $this->user->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_USER)
                ),
                $this->date->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_DATE)
                ),
                $this->select_options->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_SELECT)
                ),
                $this->date_format->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_DATE)
                ),
                $this->link_label->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_URL)
                ),
                $this->image->create(
                    $config,
                    new AC\Expression\OrSpecification([
                        StringComparisonSpecification::equal(self::TYPE_IMAGE),
                        StringComparisonSpecification::equal(self::TYPE_MEDIA),
                    ])
                ),
                $this->media_link->create(
                    $config,
                    new AC\Expression\OrSpecification([
                        StringComparisonSpecification::equal(self::TYPE_IMAGE),
                        StringComparisonSpecification::equal(self::TYPE_MEDIA),
                    ])
                ),
            ])
        );
    }

}