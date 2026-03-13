<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Control\Type\Option;

abstract class ImageSizeBase extends BaseComponentFactory
{

    abstract protected function get_size_name(): string;

    abstract protected function get_width_name(): string;

    abstract protected function get_height_name(): string;

    protected function get_default_size(): string
    {
        return 'cpac-custom';
    }

    protected function get_default_width(): int
    {
        return 60;
    }

    protected function get_default_height(): int
    {
        return 60;
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            $this->get_size_name(),
            $this->get_grouped_image_sizes(),
            $config->get($this->get_size_name()) ?: $this->get_default_size()
        );
    }

    protected function get_children(Config $config): ?Children
    {
        $width = $config->has($this->get_width_name())
            ? (int)$config->get($this->get_width_name())
            : $this->get_default_width();

        $height = $config->has($this->get_height_name())
            ? (int)$config->get($this->get_height_name())
            : $this->get_default_height();

        return new Children(
            new ComponentCollection([
                new Component(
                    __('Width', 'codepress-admin-columns'),
                    null,
                    Number::create_single_step($this->get_width_name(), 0, null, $width),
                    StringComparisonSpecification::equal('cpac-custom')
                ),
                new Component(
                    __('Height', 'codepress-admin-columns'),
                    null,
                    Number::create_single_step($this->get_height_name(), 0, null, $height),
                    StringComparisonSpecification::equal('cpac-custom')
                ),
            ]),
            true
        );
    }

    /**
     * @return string|int[]
     */
    protected function get_size(Config $config)
    {
        $size = $config->get($this->get_size_name()) ?: $this->get_default_size();

        if ($size === 'cpac-custom') {
            return [
                $config->has($this->get_width_name())
                    ? (int)$config->get($this->get_width_name())
                    : $this->get_default_width(),
                $config->has($this->get_height_name())
                    ? (int)$config->get($this->get_height_name())
                    : $this->get_default_height(),
            ];
        }

        return $size;
    }

    protected function get_dimension_for_size(string $size): ?array
    {
        global $_wp_additional_image_sizes;

        $width = $_wp_additional_image_sizes[$size]['width'] ?? get_option("{$size}_size_w");
        $height = $_wp_additional_image_sizes[$size]['height'] ?? get_option("{$size}_size_h");

        return $width && $height
            ? [(int)$width, (int)$height]
            : null;
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

        $options->add(
            new Option(
                __('Custom Size', 'codepress-admin-columns'),
                'cpac-custom',
                __('Custom', 'codepress-admin-columns')
            )
        );

        $custom_group = __('Others', 'codepress-admin-columns');

        foreach (get_intermediate_image_sizes() as $size) {
            if ('medium_large' === $size || isset($sizes['default']['options'][$size])) {
                continue;
            }

            if ( ! is_string($size)) {
                continue;
            }

            $options->add(
                new Option(
                    ucwords(str_replace(['-', '_'], ' ', $size)),
                    $size,
                    $custom_group
                )
            );
        }

        return $this->append_dimensions($options);
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

}
