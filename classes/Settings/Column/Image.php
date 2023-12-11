<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Column;
use AC\Setting\ArrayImmutable;
use AC\Setting\Base;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Option;
use AC\Setting\Type\Value;
use ACP\Expression\Specification;
use ACP\Expression\StringComparisonSpecification;

class Image extends AC\Settings\Column implements AC\Setting\Recursive, AC\Setting\Formatter
{

    private const SETTING_WIDTH = 'image_size_w';
    private const SETTING_HEIGHT = 'image_size_h';

    private const SIZE_CUSTOM = 'cpac-custom';

    public function __construct(Column $column, Specification $specification = null)
    {
        $this->name = 'image_size';
        $this->label = __('Image Size', 'codepress-admin-columns');
        $this->input = Input\Option\Single::create_select(
            $this->get_grouped_image_sizes(),
            self::SIZE_CUSTOM
        );

        parent::__construct($column, $specification);
    }

    public function is_parent(): bool
    {
        return true;
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $size = $this->get_size($options);

        return $value->with_value(
            ac_helper()->image->get_image($value->get_value(), $size)
        );
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Base\Setting(
                self::SETTING_WIDTH,
                __('Width', 'codepress-admin-columns'),
                '',
                Input\Number::create_single_step(0, null, 60),
                StringComparisonSpecification::equal(self::SIZE_CUSTOM)
            ),
            new Base\Setting(
                self::SETTING_HEIGHT,
                __('Height', 'codepress-admin-columns'),
                '',
                Input\Number::create_single_step(0, null, 60),
                StringComparisonSpecification::equal(self::SIZE_CUSTOM)
            ),
        ]);
    }

    /**
     * @return int[]|string
     */
    protected function get_size(ArrayImmutable $options)
    {
        $size = $options->get($this->get_name());

        if (self::SIZE_CUSTOM === $size) {
            $width = (int)$options->get(self::SETTING_WIDTH);
            $height = (int)$options->get(self::SETTING_HEIGHT);

            if ($width && $height) {
                $size = [$width, $height];
            }
        }

        // Default
        if (null === $size) {
            $size = [60, 60];
        }

        return $size;
    }

    protected function get_dimension_for_size(string $size): ?array
    {
        global $_wp_additional_image_sizes;

        $width = $_wp_additional_image_sizes[$size]['width'] ?? get_option("{$size}_size_w");
        $height = $_wp_additional_image_sizes[$size]['height'] ?? get_option("{$size}_size_h");

        return $width && $height ? [$width, $height] : null;
    }

    private function append_dimensions(OptionCollection $options): OptionCollection
    {
        $labeled_options = new OptionCollection();

        foreach ($options as $option) {
            $size = $this->get_dimension_for_size($option->get_value());

            if (is_array($size)) {
                $size = sprintf(' (%s x %s)', $size[0], $size[1]);
            }

            $labeled_options->add(
                new Option(
                    $option->get_label() . $size,
                    $option->get_value(),
                    $option->get_group()
                )
            );
        }

        return $labeled_options;
    }

    private function get_grouped_image_sizes(): OptionCollection
    {
        $default_group = __('Default', 'codepress-admin-columns');

        $options = new OptionCollection([
            new Option(__('Thumbnail', 'codepress-admin-columns'), 'thumbnail', $default_group),
            new Option(__('Medium', 'codepress-admin-columns'), 'medium', $default_group),
            new Option(__('Large', 'codepress-admin-columns'), 'large', $default_group),
            new Option(__('Full Size', 'codepress-admin-columns'), 'full', $default_group),
        ]);

        $custom_group = __('Others', 'codepress-admin-columns');

        foreach (get_intermediate_image_sizes() as $size) {
            if ('medium_large' === $size || isset($sizes['default']['options'][$size])) {
                continue;
            }

            $options->add(
                new Option(
                    ucwords(str_replace('-', ' ', $size)),
                    $size,
                    $custom_group
                )
            );
        }

        $options->add(
            new Option(
                __('Custom Size', 'codepress-admin-columns'),
                self::SIZE_CUSTOM,
                __('Custom', 'codepress-admin-columns')
            )
        );

        return $this->append_dimensions($options);
    }

}