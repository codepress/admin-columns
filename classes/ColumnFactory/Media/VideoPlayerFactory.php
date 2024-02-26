<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\VideoDisplayFactory;

class VideoPlayerFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        VideoDisplayFactory $video_display_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($video_display_factory);
    }

    protected function get_group(): ?string
    {
        return 'media-video';
    }

    public function get_type(): string
    {
        return 'column-video_player';
    }

    protected function get_label(): string
    {
        return __('Video Player', 'codepress-admin-columns');
    }

    // TODO implement Modal value 
    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        $formatter_builder = $this->aggregate_formatter_builder_factory
            ->create()
            ->add(new Formatter\Media\Video\ValidMimeType())
            ->add(new Formatter\Media\AttachmentUrl());

        switch ($config->get('video_display')) {
            case 'embed':
                return $formatter_builder
                    ->add(new Formatter\Media\VideoEmbed());
            default:
                return $formatter_builder
                    ->add(new Formatter\Media\Video\ModalEmbedLink());
        }
    }

}