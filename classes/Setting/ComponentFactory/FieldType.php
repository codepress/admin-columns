<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\FormatterCollection;
use AC\Setting\AttributeCollection;
use AC\Setting\AttributeFactory;
use AC\Setting\Children;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Control\OptionCollectionFactory\ToggleOptionCollection;
use AC\Setting\Control\Type\Option;

class FieldType extends BaseComponentFactory
{

    public const TYPE_DEFAULT = '';
    public const TYPE_HTML = 'html';
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

    private StringLimit $string_limit;

    private NumberFormat $number_format;

    private LinkablePostProperty $post;

    private UserProperty $user;

    private DateFormat\Date $date;

    private DateSaveFormat $date_format;

    private LinkLabel $link_label;

    private ImageSize $image;

    private MediaLink $media_link;

    private SelectOptions $select_options;

    private SerializedDisplay $serialized_display;

    private UserLink $user_link;

    private ModalDisplay $modal_display;

    private NumberOfItems $number_of_items;

    private FileDisplay $file_display;

    public function __construct(
        StringLimit $string_limit,
        NumberFormat $number_format,
        LinkablePostProperty $post,
        UserProperty $user,
        UserLink $user_link,
        DateFormat\Date $date,
        DateSaveFormat $date_format,
        LinkLabel $link_label,
        ImageSize $image,
        MediaLink $media_link,
        SelectOptions $select_options,
        SerializedDisplay $serialized_display,
        ModalDisplay $modal_display,
        NumberOfItems $number_of_items,
        FileDisplay $file_display
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
        $this->serialized_display = $serialized_display;
        $this->user_link = $user_link;
        $this->modal_display = $modal_display;
        $this->number_of_items = $number_of_items;
        $this->file_display = $file_display;
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
            'field_type',
            $this->get_field_type_options(),
            $config->get('field_type', ''),
            __('Select the field type', 'codepress-admin-columns'),
            false,
            new AC\Setting\AttributeCollection([
                AttributeFactory::create_refresh(),
            ])
        );
    }

    protected function get_attributes(Config $config, AttributeCollection $attributes): AttributeCollection
    {
        $options = [];

        if ('array' === $config->get('field_type')) {
            $options[] = AttributeFactory::create_help_reference('doc-serialized');
        }

        return new AttributeCollection($options);
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
            new Option(__('Default', 'codepress-admin-columns'), '')
        );

        foreach ($this->get_field_types() as $group => $options) {
            foreach ($options as $value => $label) {
                $collection->add(
                    new Option(
                        (string)$label,
                        (string)$value,
                        $groups[$group] ?? $group
                    )
                );
            }
        }

        return $collection;
    }

    protected function get_field_types(): array
    {
        $options = [
            'basic'      => [
                self::TYPE_COLOR   => __('Color', 'codepress-admin-columns'),
                self::TYPE_DATE    => __('Date', 'codepress-admin-columns'),
                self::TYPE_TEXT    => __('Text', 'codepress-admin-columns'),
                self::TYPE_HTML    => __('HTML', 'codepress-admin-columns'),
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

        foreach ($options as $k => $fields) {
            natcasesort($options[$k]);
        }

        return $options;
    }

    protected function add_final_formatters(Config $config, FormatterCollection $formatters): void
    {
        switch ($config->get('field_type', self::TYPE_DEFAULT)) {
            case self::TYPE_IMAGE:
                $formatters->add(new AC\Formatter\Collection\Separator('', (int)$config->get('number_of_items', 0)));
                break;
            case self::TYPE_MEDIA:
                $separator = $config->get('file_display') === '' ? ', ' : '';
                $formatters->add(new AC\Formatter\Collection\Separator($separator, (int)$config->get('number_of_items', 0)));
                break;
            case self::TYPE_POST:
            case self::TYPE_USER:
                $formatters->add(new AC\Formatter\Collection\Separator(', ', (int)$config->get('number_of_items', 0)));
                break;
        }
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        switch ($config->get('field_type', self::TYPE_DEFAULT)) {
            case self::TYPE_BOOLEAN:
                $formatters->add(new AC\Formatter\YesNoIcon());
                break;
            case self::TYPE_SELECT:
                if ($config->get('is_multiple', 'off') === 'on') {
                    $formatters->add(new AC\Formatter\ArrayToCollection());
                }

                $formatters->add(new AC\Formatter\SelectOptionMapper($config));
                break;
            case self::TYPE_DATE:
                $source_format = $config->get('date_save_format', '');

                if ($source_format === DateSaveFormat::FORMAT_AUTO) {
                    $formatters->add(new AC\Formatter\Date\Timestamp());
                    break;
                }

                $date_formatter = $source_format
                    ? new AC\Formatter\Date\DateFormat('U', $source_format)
                    : new AC\Formatter\Date\Timestamp();

                $formatters->add($date_formatter);
                break;
            case self::TYPE_COLOR:
                $formatters->add(new AC\Formatter\Color());
                break;

            case self::TYPE_NON_EMPTY:
                $formatters->add(new AC\Formatter\HasValue());
                $formatters->add(new AC\Formatter\YesNoIcon());
                break;
            case self::TYPE_IMAGE:
                $formatters->add(new AC\Formatter\ImageToCollection());
                break;
            case self::TYPE_MEDIA:
                if ($config->get('is_multiple', 'off') === 'on') {
                    $is_preview = $config->get('file_display') !== '';
                    $formatters->add(
                        $is_preview
                            ? new AC\Formatter\GroupedIdsToCollection()
                            : new AC\Formatter\IdsToCollection()
                    );
                }

                if ($config->get('file_display') === '') {
                    $formatters->add(new AC\Formatter\FileLink((string)$config->get('file_link_to', '')));
                }
                break;
            case self::TYPE_USER:
            case self::TYPE_POST:
                $formatters->add(new AC\Formatter\IdsToCollection());
                break;
            case self::TYPE_HTML:
                if ($config->get($this->modal_display::TOGGLE) === ToggleOptionCollection::ON) {
                    $formatters->add(
                        new AC\Formatter\ExtendedValueLink(
                            new AC\Value\ExtendedValueLinkFactory(),
                            $config->get($this->modal_display::LABEL)
                        )
                    );
                }

                break;
        }
    }

    protected function get_children(Config $config): ?Children
    {
        // When TYPE_MEDIA is in filename mode, image/media settings do not apply.
        $media_is_filename = $config->get('file_display') === '';

        $image_spec = $media_is_filename
            ? StringComparisonSpecification::equal(self::TYPE_IMAGE)
            : new AC\Expression\OrSpecification([
                StringComparisonSpecification::equal(self::TYPE_IMAGE),
                StringComparisonSpecification::equal(self::TYPE_MEDIA),
            ]);

        $number_of_items_spec = $media_is_filename
            ? new AC\Expression\OrSpecification([
                StringComparisonSpecification::equal(self::TYPE_IMAGE),
                StringComparisonSpecification::equal(self::TYPE_POST),
                StringComparisonSpecification::equal(self::TYPE_USER),
            ])
            : new AC\Expression\OrSpecification([
                StringComparisonSpecification::equal(self::TYPE_IMAGE),
                StringComparisonSpecification::equal(self::TYPE_MEDIA),
                StringComparisonSpecification::equal(self::TYPE_POST),
                StringComparisonSpecification::equal(self::TYPE_USER),
            ]);

        $media_link_spec = $media_is_filename
            ? StringComparisonSpecification::equal(self::TYPE_IMAGE)
            : new AC\Expression\OrSpecification([
                StringComparisonSpecification::equal(self::TYPE_IMAGE),
                StringComparisonSpecification::equal(self::TYPE_MEDIA),
            ]);

        // Default the file_display to 'preview' for TYPE_MEDIA so existing columns
        // (which have no file_display saved) continue to show as images.
        $media_display_config = $config->has('file_display')
            ? $config
            : new Config(array_merge($config->all(), ['file_display' => 'preview']));

        return new Children(
            new AC\Setting\ComponentCollection([
                $this->string_limit->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_TEXT)
                ),
                $this->modal_display->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_HTML),
                ),
                $this->number_format->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_NUMERIC)
                ),
                $this->post->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_POST)
                ),
                $this->user->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_USER)
                ),
                $this->user_link->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_USER)
                ),
                $this->date_format->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_DATE)
                ),
                $this->date->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_DATE)
                ),
                $this->select_options->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_SELECT)
                ),
                $this->link_label->create(
                    $config,
                    StringComparisonSpecification::equal(self::TYPE_URL)
                ),
                $this->file_display->create(
                    $media_display_config,
                    StringComparisonSpecification::equal(self::TYPE_MEDIA)
                ),
                $this->image->create(
                    $config,
                    $image_spec
                ),
                $this->number_of_items->create(
                    $config,
                    $number_of_items_spec
                ),
                $this->serialized_display->create($config, StringComparisonSpecification::equal(self::TYPE_ARRAY)),
                $this->media_link->create(
                    $config,
                    $media_link_spec
                ),
            ])
        );
    }

}