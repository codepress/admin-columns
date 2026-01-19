<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\FormatterCollection;
use AC\Setting\Children;
use AC\Setting\ComponentFactory\DateFormat\Date;
use AC\Setting\ComponentFactory\FieldTypeConfigurator\FieldComponentDirectorFactory;
use AC\Setting\Config;
use AC\Setting\Control\OptionCollection;
use AC\Type\TableScreenContext;

class PostExtendedProperty extends PostProperty
{

    public const PROPERTY_CUSTOM_FIELD = 'custom_field';

    private FieldComponentDirectorFactory $field_type;

    private CustomFieldFactory $custom_field;

    public function __construct(
        StringLimit $string_limit,
        ImageSize $image_size,
        UserProperty $user_property,
        PostStatusIcon $post_status_icon,
        Date $date,
        CustomFieldFactory $custom_field,
        FieldComponentDirectorFactory $field_type
    ) {
        parent::__construct($string_limit, $image_size, $user_property, $post_status_icon, $date);

        $this->custom_field = $custom_field;
        $this->field_type = $field_type;
    }

    protected function get_display_options(): OptionCollection
    {
        $options = parent::get_display_options();

        $options->add(
            new AC\Setting\Control\Type\Option(
                __('Custom Field', 'codepress-admin-columns'),
                self::PROPERTY_CUSTOM_FIELD,
                __('Other', 'codepress-admin-columns')
            )
        );

        return $options;
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        if ($this->get_input($config)->get_value() === self::PROPERTY_CUSTOM_FIELD) {
            $formatters->add(new AC\Formatter\Post\Meta($config->get('field', '')));

            return;
        }

        parent::add_formatters($config, $formatters);
    }

    protected function get_children(Config $config): ?Children
    {
        $components = parent::get_children($config)->get_iterator();

        $table_screen_context = new TableScreenContext(
            AC\MetaType::create_post_meta(),
        );

        $components->add(
            $this->custom_field->create($table_screen_context)->create(
                $config,
                StringComparisonSpecification::equal('custom_field')
            )
        );

        $components->add(
            $this->field_type->add_basic()->create(
                $config,
                StringComparisonSpecification::equal('custom_field')
            )
        );

        return new Children($components);
    }

}