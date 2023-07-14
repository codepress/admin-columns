<?php

namespace AC\Admin\Page;

use AC\Admin;
use AC\Admin\RenderableHead;
use AC\Admin\Section;
use AC\Admin\SectionCollection;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Renderable;
use AC\View;

class Settings implements Enqueueables, Renderable, RenderableHead
{

    public const NAME = 'settings';

    private $head;

    private $location;

    protected $sections;

    public function __construct(Renderable $head, Location\Absolute $location, SectionCollection $sections = null)
    {
        if (null === $sections) {
            $sections = new SectionCollection();
        }

        $this->head = $head;
        $this->location = $location;
        $this->sections = $sections;
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
        $factory = new Admin\Asset\Script\SettingsFactory(
            $this->location
        );

        $assets = new Assets([
            $factory->create(),
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