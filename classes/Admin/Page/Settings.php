<?php

namespace AC\Admin\Page;

use AC\Admin\Asset\Script\SettingsFactory;
use AC\Admin\RenderableHead;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Renderable;

class Settings implements Enqueueables, Renderable, RenderableHead
{

    public const NAME = 'settings';

    private Renderable $head;

    private SettingsFactory $settings_factory;

    public function __construct(
        Renderable $head,
        SettingsFactory $settings_factory
    ) {
        $this->head = $head;
        $this->settings_factory = $settings_factory;
    }

    public function render_head(): Renderable
    {
        return $this->head;
    }

    public function get_assets(): Assets
    {
        return new Assets([
            $this->settings_factory->create(),
        ]);
    }

    public function render(): string
    {
        return '';
    }

}