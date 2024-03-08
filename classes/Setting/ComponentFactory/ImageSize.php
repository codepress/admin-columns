<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\Number;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Control\Type\Option;

final class ImageSize implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        $width = $config->has('image_size_w') ? (int)$config->get('image_size_w') : 60;
        $height = $config->has('image_size_h') ? (int)$config->get('image_size_h') : 60;

        $builder = (new ComponentBuilder())
            ->set_label(__('Image Size', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'image_size',
                    $this->get_grouped_image_sizes(),
                    $config->get('image_size') ?: 'cpac-custom'
                )
            )
            ->set_children(
                new Children(
                    new ComponentCollection([
                        new Component(
                            __('Width', 'codepress-admin-columns'),
                            null,
                            Number::create_single_step(
                                'image_size_w',
                                0,
                                null,
                                $width
                            ),
                            StringComparisonSpecification::equal('cpac-custom')
                        ),
                        new Component(
                            __('Height', 'codepress-admin-columns'),
                            null,
                            Number::create_single_step(
                                'image_size_h',
                                0,
                                null,
                                $height
                            ),
                            StringComparisonSpecification::equal('cpac-custom')
                        ),
                    ])
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
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

}