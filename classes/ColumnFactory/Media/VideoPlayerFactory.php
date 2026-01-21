<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Media;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\VideoDisplay;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;
use AC\Value\Extended\MediaPreview;

class VideoPlayerFactory extends BaseColumnFactory
{

    private VideoDisplay $video_display;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        VideoDisplay $video_display
    ) {
        parent::__construct($default_settings_builder);

        $this->video_display = $video_display;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = new FormatterCollection([
            new AC\Formatter\Media\Video\ValidMimeType(),
            new AC\Formatter\Media\AttachmentUrl(),
        ]);

        if ($config->get('video_display', '') === 'embed') {
            $formatters->add(new AC\Formatter\Media\VideoEmbed());
        } else {
            $formatters->add(
                new AC\Formatter\Media\Video\ModalEmbedLink(
                    new MediaPreview()
                )
            );
        }

        return $formatters;
    }

}