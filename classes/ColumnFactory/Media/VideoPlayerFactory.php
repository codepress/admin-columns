<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\VideoDisplay;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Extended\MediaPreview;
use AC\Value\Formatter;

class VideoPlayerFactory extends BaseColumnFactory
{

    private $video_display;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        VideoDisplay $video_display
    ) {
        parent::__construct($component_factory_registry);

        $this->video_display = $video_display;
    }

    protected function add_component_factories(Config $config): void
    {
        $this->add_component_factory($this->video_display);

        parent::add_component_factories($config);
    }

    protected function get_group(): ?string
    {
        return 'media-video';
    }

    public function get_column_type(): string
    {
        return 'column-video_player';
    }

    public function get_label(): string
    {
        return __('Video Player', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Media\Video\ValidMimeType());
        $formatters->add(new Formatter\Media\AttachmentUrl());

        switch ($config->get('video_display')) {
            case 'embed':
                $formatters->add(new Formatter\Media\VideoEmbed());
                break;
            default:
                $formatters->add(
                    new Formatter\Media\Video\ModalEmbedLink(
                        new MediaPreview()
                    )
                );
        }

        return parent::get_formatters($components, $config, $formatters);
    }
}