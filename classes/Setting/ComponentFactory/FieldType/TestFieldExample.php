<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;

class TestFieldExample implements ComponentFactory
{

    private TextConfigurator $text_configurator;

    private ColorConfigurator $color_configurator;

    private DateConfigurator $date_configurator;

    private HtmlConfigurator $html_configurator;

    private ImageConfigurator $image_configurator;

    private UrlConfigurator $url_configurator;

    private NumericConfigurator $numeric_configurator;

    public function __construct(
        TextConfigurator $text_configurator,
        ColorConfigurator $color_configurator,
        DateConfigurator $date_configurator,
        HtmlConfigurator $html_configurator,
        ImageConfigurator $image_configurator,
        UrlConfigurator $url_configurator,
        NumericConfigurator $numeric_configurator
    ) {
        $this->text_configurator = $text_configurator;
        $this->color_configurator = $color_configurator;
        $this->date_configurator = $date_configurator;
        $this->html_configurator = $html_configurator;
        $this->image_configurator = $image_configurator;
        $this->url_configurator = $url_configurator;
        $this->numeric_configurator = $numeric_configurator;
    }

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $builder = (new ComponentFactory\FieldTypeBuilder())
            ->with($this->color_configurator)
            ->with($this->date_configurator)
            ->with($this->html_configurator)
            ->with($this->text_configurator)
            ->with($this->image_configurator)
            ->with($this->url_configurator)
            ->with($this->numeric_configurator);

        return $builder->build()->create($config, $conditions);
    }
}