<?php

namespace AC\Admin\Page;

use AC\Admin\Asset\Script\SettingsFactory;
use AC\Admin\RenderableHead;
use AC\Admin\Section;
use AC\Admin\SectionCollection;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Renderable;
use AC\View;

class Settings implements Enqueueables, Renderable, RenderableHead
{

    public const NAME = 'settings';

    private $head;

    protected $sections;

    private $settings_factory;

    public function __construct(
        Renderable $head,
        SettingsFactory $settings_factory,
        SectionCollection $sections = null
    ) {
        if (null === $sections) {
            $sections = new SectionCollection();
        }

        $this->head = $head;
        $this->sections = $sections;
        $this->settings_factory = $settings_factory;
    }

    public function render_head(): Renderable
    {
        return $this->head;
    }

    public function get_section(string $slug): ?Section
    {
        return $this->sections->get($slug);
    }

    public function add_section(Section $section, int $prio = 10): self
    {
        $this->sections->add($section, $prio);

        return $this;
    }

    public function get_assets(): Assets
    {
        $assets = new Assets([
            $this->settings_factory->create(),
        ]);

        foreach ($this->sections->all() as $section) {
            if ($section instanceof Enqueueables) {
                $assets->add_collection($section->get_assets());
            }
        }

        return $assets;
    }

    public function render(): string
    {
        $view = new View([
            'sections' => $this->sections->all(),
        ]);

        return $view->set_template('admin/page/settings')->render();
    }

}