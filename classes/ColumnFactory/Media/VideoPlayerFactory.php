<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\VideoDisplay;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Extended\MediaPreview;
use AC\Value\Formatter;

class VideoPlayerFactory extends BaseColumnFactory
{

    private $video_display;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        VideoDisplay $video_display
    ) {
        parent::__construct($base_settings_builder);

        $this->video_display = $video_display;
    }

    protected function get_settings(Config $config): \AC\Setting\ComponentCollection
    {
        return new \AC\Setting\ComponentCollection([
            $this->video_display->create($config),
        ]);
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