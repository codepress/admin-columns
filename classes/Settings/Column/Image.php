<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Option;
use AC\Setting\Type\Value;

class Image extends AC\Settings\Column implements AC\Setting\Recursive, AC\Setting\Formatter
{

    public function __construct(Specification $specification = null, string $default = 'cpac-custom')
    {
        parent::__construct(
            'image_size',
            __('Image Size', 'codepress-admin-columns'),
            '',
            Input\Option\Single::create_select(
                $this->get_grouped_image_sizes(),
                $default
            ),
            $specification
        );
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
            new AC\Settings\Column(
                'image_size_w',
                __('Width', 'codepress-admin-columns'),
                '',
                Input\Number::create_single_step(0, null, 60),
                StringComparisonSpecification::equal('cpac-custom')
            ),
            new AC\Settings\Column(
                'image_size_h',
                __('Height', 'codepress-admin-columns'),
                '',
                Input\Number::create_single_step(0, null, 60),
                StringComparisonSpecification::equal('cpac-custom')
            ),
        ]);
    }

    /**
     * @return int[]|string
     */
    protected function get_size(ArrayImmutable $options)
    {
        $size = $options->get($this->get_name());

        if ('cpac-custom' === $size) {
            $width = (int)$options->get('image_size_w');
            $height = (int)$options->get('image_size_h');

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
                'cpac-custom',
                __('Custom', 'codepress-admin-columns')
            )
        );

        return $this->append_dimensions($options);
    }

}