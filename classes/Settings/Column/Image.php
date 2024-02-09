<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Component\Input\Number;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Component\Type\Option;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;

class Image extends AC\Settings\Setting implements AC\Setting\Recursive, AC\Setting\Formatter
{

    protected $image_format;

    protected $width;

    protected $height;

    public function __construct(
        string $image_format = null,
        int $width = null,
        int $height = null,
        Specification $specification = null
    ) {
        parent::__construct(
            OptionFactory::create_select(
                'image_size',
                $this->get_grouped_image_sizes(),
                $image_format
            ),
            __('Image Size', 'codepress-admin-columns'),
            null,
            $specification
        );

        $this->image_format = $image_format;
        $this->width = $width ?? 60;
        $this->height = $height ?? 60;
    }

    public function is_parent(): bool
    {
        return true;
    }

    public function format(Value $value): Value
    {
        $image = $value->get_value();

        if ( ! $image) {
            return $value;
        }

        $size = 'cpac-custom' === $this->image_format
            ? [$this->width, $this->height]
            : $this->image_format;

        if (is_numeric($image)) {
            return $value->with_value(
                ac_helper()->image->render_image_by_id(
                    (int)$image,
                    $size
                )
            );
        }

        if (is_string($image) && ac_helper()->string->is_valid_url($image)) {
            return $value->with_value(
                ac_helper()->image->render_image_by_url(
                    $image,
                    $size
                )
            );
        }

        return $value;
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new AC\Settings\Setting(
                Number::create_single_step('image_size_w', 0, null, $this->width),
                __('Width', 'codepress-admin-columns'),
                '',
                StringComparisonSpecification::equal('cpac-custom')
            ),
            new AC\Settings\Setting(
                Number::create_single_step('image_size_h', 0, null, $this->height),
                __('Height', 'codepress-admin-columns'),
                '',
                StringComparisonSpecification::equal('cpac-custom')
            ),
        ]);
    }

    protected function get_dimension_for_size(string $size): ?array
    {
        global $_wp_additional_image_sizes;

        $width = $_wp_additional_image_sizes[$size]['width'] ?? get_option("{$size}_size_w");
        $height = $_wp_additional_image_sizes[$size]['height'] ?? get_option("{$size}_size_h");

        return $width && $height
            ? [$width, $height]
            : null;
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

        // TODO
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