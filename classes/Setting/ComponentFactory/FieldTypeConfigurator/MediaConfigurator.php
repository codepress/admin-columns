<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Expression\StringComparisonSpecification;
use AC\Formatter;
use AC\FormatterCollection;
use AC\Setting;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;
use AC\Setting\Config;

class MediaConfigurator implements FieldTypeConfigurator
{
    public const TYPE = 'library_id';

    private ComponentFactory\FileDisplay $file_display;

    private ComponentFactory\ImageSize $image_size;

    private ComponentFactory\MediaLink $media_link;

    private ComponentFactory\NumberOfItems $number_of_items;

    public function __construct(
        ComponentFactory\FileDisplay $file_display,
        ComponentFactory\ImageSize $image_size,
        ComponentFactory\MediaLink $media_link,
        ComponentFactory\NumberOfItems $number_of_items
    ) {
        $this->file_display = $file_display;
        $this->image_size = $image_size;
        $this->media_link = $media_link;
        $this->number_of_items = $number_of_items;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Media', 'codepress-admin-columns'), 'relational')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, FormatterCollection $formatters): void {
                    if ($config->get('is_multiple', 'off') === 'on') {
                        $formatters->add(
                            $this->is_filename($config)
                                ? new Formatter\IdsToCollection()
                                : new Formatter\GroupedIdsToCollection()
                        );
                    }

                    if ($this->is_filename($config)) {
                        $formatters->add(new Formatter\FileLink((string)$config->get('file_link_to', '')));
                    }
                }
            )
            ->add_child_component_resolver(
                function (Config $config): ?Component {
                    if (! $this->is_selected($config)) {
                        return null;
                    }

                    // Columns created before the filename option existed have no file_display stored
                    // and must keep rendering as a preview.
                    return $this->file_display->create(
                        $config->has('file_display') ? $config : $config->with(['file_display' => 'preview']),
                        StringComparisonSpecification::equal(self::TYPE)
                    );
                }
            )
            ->add_child_component_resolver($this->preview_only($this->image_size))
            ->add_child_component_resolver($this->preview_only($this->number_of_items))
            ->add_child_component_resolver($this->preview_only($this->media_link))
            ->add_final_formatter(
                self::TYPE,
                function (Setting\Config $config, FormatterCollection $formatters): void {
                    $formatters->add(
                        new Formatter\Collection\Separator(
                            $this->is_filename($config) ? ', ' : '',
                            (int)$config->get('number_of_items', 0)
                        )
                    );
                }
            );
    }

    private function preview_only(Setting\ComponentFactory $factory): callable
    {
        return function (Config $config) use ($factory): ?Component {
            return $this->is_selected($config) && ! $this->is_filename($config)
                ? $factory->create($config, StringComparisonSpecification::equal(self::TYPE))
                : null;
        };
    }

    private function is_selected(Config $config): bool
    {
        return (string)$config->get('field_type', '') === self::TYPE;
    }

    private function is_filename(Config $config): bool
    {
        return $config->get('file_display') === '';
    }
}
