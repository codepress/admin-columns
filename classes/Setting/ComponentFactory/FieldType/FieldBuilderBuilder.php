<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Setting\ComponentFactory;

// TODO what to do here? Instead of make a copy of FieldBuilderFactory for fewer fields
class FieldBuilderBuilder
{

    private ComponentFactory\FieldTypeBuilder $builder;

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

        $this->builder = new ComponentFactory\FieldTypeBuilder();
    }

    public function with_text_field(): self
    {
        $this->builder->with($this->text_configurator);

        return $this;
    }

    public function with_color_field(): self
    {
        $this->builder->with($this->color_configurator);

        return $this;
    }

    public function with_date_field(): self
    {
        $this->builder->with($this->date_configurator);

        return $this;
    }

    public function with_html_field(): self
    {
        $this->builder->with($this->html_configurator);

        return $this;
    }

    public function with_image_field(): self
    {
        $this->builder->with($this->image_configurator);

        return $this;
    }

    public function with_url_field(): self
    {
        $this->builder->with($this->url_configurator);

        return $this;
    }

    public function with_numeric_field(): self
    {
        $this->builder->with($this->numeric_configurator);

        return $this;
    }

    public function with_has_content_field(): self
    {
        $this->builder->with($this->has_content_configurator);

        return $this;
    }

    public function with_boolean_field(): self
    {
        $this->builder->with($this->boolean_configurator);

        return $this;
    }

    public function with_select_field(): self
    {
        $this->builder->with($this->select_configurator);

        return $this;
    }

    public function with_media_field(): self
    {
        $this->builder->with($this->media_configurator);

        return $this;
    }

    public function with_post_field(): self
    {
        $this->builder->with($this->post_configurator);

        return $this;
    }

    public function with_user_field(): self
    {
        $this->builder->with($this->user_configurator);

        return $this;
    }

    public function with_count_field(): self
    {
        $this->builder->with($this->count_configurator);

        return $this;
    }

    public function with_serialized_field(): self
    {
        $this->builder->with($this->serialized_configurator);

        return $this;
    }

    public function with_basic_fields(): self
    {
        $this->with_color_field()
             ->with_date_field()
             ->with_text_field()
             ->with_html_field()
             ->with_image_field()
             ->with_url_field()
             ->with_numeric_field();

        return $this;
    }

    public function with_choices_fields(): self
    {
        $this->with_has_content_field()
             ->with_boolean_field()
             ->with_select_field();

        return $this;
    }

    public function with_relational_fields(): self
    {
        $this->with_post_field()
             ->with_user_field()
             ->with_post_field();

        return $this;
    }

    public function build(): ComponentFactory\FieldTypeBuilder
    {
        return $this->builder;
    }
}