<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Component\Type\Option;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;

class Image extends AC\Settings\Column implements AC\Setting\Recursive, AC\Setting\Formatter
{

    private $image_format;

    private $width;

    private $height;

    public function __construct(
        string $image_format = null,
        int $width = null,
        int $height = null,
        Specification $specification = null
    ) {
        parent::__construct(
            __('Image Size', 'codepress-admin-columns'),
            '',
            OptionFactory::create_select(
                'image_size',
                $this->get_grouped_image_sizes(),
                $image_format
            ),
            $specification
        );

        $this->image_format = $image_format ?? 'cpac-custom';
        $this->width = $width ?? 60;
        $this->height = $height ?? 60;
    }

    public function is_parent(): bool
    {
        return true;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            // TODO verify it's an `id`
            // TODO verify sizes
            ac_helper()->image->get_image(
                $value->get_value(),
                $this->get_size()
            )
        );
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new AC\Settings\Column(
                __('Width', 'codepress-admin-columns'),
                '',
                AC\Setting\Component\Input\Number::create_single_step('image_size_w', 0, null, $this->width),
                StringComparisonSpecification::equal('cpac-custom')
            ),
            new AC\Settings\Column(
                __('Height', 'codepress-admin-columns'),
                '',
                AC\Setting\Component\Input\Number::create_single_step('image_size_h', 0, null, $this->height),
                StringComparisonSpecification::equal('cpac-custom')
            ),
        ]);
    }

    protected function get_size(): array
    {
        if ('cpac-custom' === $this->image_format) {
            return [
                $this->width,
                $this->height,
            ];
        }

        // TODO custom sizes

        return [60, 60];
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