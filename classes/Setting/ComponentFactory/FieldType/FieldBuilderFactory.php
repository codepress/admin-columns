<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;

class FieldBuilderFactory implements ComponentFactory
{

    private TextConfigurator $text_configurator;

    private ColorConfigurator $color_configurator;

    private DateConfigurator $date_configurator;

    private HtmlConfigurator $html_configurator;

    private ImageConfigurator $image_configurator;

    private UrlConfigurator $url_configurator;

    private NumericConfigurator $numeric_configurator;

    private HasContentConfigurator $has_content_configurator;

    private BooleanConfigurator $boolean_configurator;

    private SelectConfigurator $select_configurator;

    private MediaConfigurator $media_configurator;

    private RelatedPostConfigurator $post_configurator;

    private RelatedUserConfigurator $user_configurator;

    private CountConfigurator $count_configurator;

    private SerializedConfigurator $serialized_configurator;

    // TODO Stefan: Make Configurtator Registry
    public function __construct(
        TextConfigurator $text_configurator,
        ColorConfigurator $color_configurator,
        DateConfigurator $date_configurator,
        HtmlConfigurator $html_configurator,
        ImageConfigurator $image_configurator,
        UrlConfigurator $url_configurator,
        NumericConfigurator $numeric_configurator,
        HasContentConfigurator $has_content_configurator,
        BooleanConfigurator $boolean_configurator,
        SelectConfigurator $select_configurator,
        MediaConfigurator $media_configurator,
        RelatedPostConfigurator $post_configurator,
        RelatedUserConfigurator $user_configurator,
        CountConfigurator $count_configurator,
        SerializedConfigurator $serialized_configurator
    ) {
        $this->text_configurator = $text_configurator;
        $this->color_configurator = $color_configurator;
        $this->date_configurator = $date_configurator;
        $this->html_configurator = $html_configurator;
        $this->image_configurator = $image_configurator;
        $this->url_configurator = $url_configurator;
        $this->numeric_configurator = $numeric_configurator;
        $this->has_content_configurator = $has_content_configurator;
        $this->boolean_configurator = $boolean_configurator;
        $this->select_configurator = $select_configurator;
        $this->media_configurator = $media_configurator;
        $this->post_configurator = $post_configurator;
        $this->user_configurator = $user_configurator;
        $this->count_configurator = $count_configurator;
        $this->serialized_configurator = $serialized_configurator;
    }

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $builder = (new ComponentFactory\FieldTypeBuilder());

        $this->color_configurator->configure($builder);
        $this->date_configurator->configure($builder);
        $this->html_configurator->configure($builder);
        $this->text_configurator->configure($builder);
        $this->image_configurator->configure($builder);
        $this->url_configurator->configure($builder);
        $this->numeric_configurator->configure($builder);
        $this->has_content_configurator->configure($builder);
        $this->boolean_configurator->configure($builder);
        $this->select_configurator->configure($builder);
        $this->media_configurator->configure($builder);
        $this->post_configurator->configure($builder);
        $this->user_configurator->configure($builder);
        $this->count_configurator->configure($builder);
        $this->serialized_configurator->configure($builder);

        return $builder->build()->create($config, $conditions);
    }
}