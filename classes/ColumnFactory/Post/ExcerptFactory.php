<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\SettingCollection;
use AC\Settings;
use AC\Vendor\DI\Container;

// TODO POC for a column factory
class ExcerptFactory implements ColumnFactory
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function can_create(string $key): bool
    {
        return 'column-excerpt' === $key;
    }

    protected function get_settings(Config $config):SettingCollection
    {
        return new SettingCollection([
            $this->container->get(Settings\Column\LabelFactory::class)->create($config),
            $this->container->get(Settings\Column\WidthFactory::class)->create($config),
            $this->container->get(Settings\Column\StringLimitFactory::class)->create($config),
            $this->container->get(Settings\Column\BeforeAfterFactory::class)->create($config),
        ]);
    }

    public function create(Config $config): Column
    {
        $settings = $this->get_settings($config);

        return new Column(
            'column-excerpt',
            __('Excerpt', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new Formatter\Post\Excerpt()),
            $settings
        );
    }
}