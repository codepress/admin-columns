<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\VideoDisplay;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
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

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->video_display);
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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Formatter\Media\Video\ValidMimeType());
        $formatters->add(new Formatter\Media\AttachmentUrl());

        if ($config->get('video_display', '') === 'embed') {
            $formatters->add(new Formatter\Media\VideoEmbed());
        } else {
            $formatters->add(
                new Formatter\Media\Video\ModalEmbedLink(
                    new MediaPreview()
                )
            );
        }
    }

}