<?php

namespace AC\Admin\Page;

use AC\Admin\RenderableHead;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Style;
use AC\Deprecated\Hooks;
use AC\Renderable;
use AC\Type\Url;
use AC\View;

class Help implements Enqueueables, Renderable, RenderableHead
{

    public const NAME = 'help';

    private Hooks $hooks;

    private Location\Absolute $location;

    private Renderable $head;

    public function __construct(Hooks $hooks, Location\Absolute $location, Renderable $head)
    {
        $this->hooks = $hooks;
        $this->location = $location;
        $this->head = $head;
    }

    public function render_head(): Renderable
    {
        return $this->head;
    }

    public function get_assets(): Assets
    {
        return new Assets([
            new Style('ac-admin-page-help-css', $this->location->with_suffix('assets/css/admin-page-help.css')),
        ]);
    }

    public function render(): string
    {
        // Force cache refresh
        $this->hooks->get_count(true);

        $view = new View([
            'documentation_url'  => (new Url\Documentation(Url\Documentation::ARTICLE_UPGRADE_V3_TO_V4))->get_url(),
            'deprecated_filters' => $this->hooks->get_deprecated_filters(),
            'deprecated_actions' => $this->hooks->get_deprecated_actions(),
        ]);
        $view->set_template('admin/page/help-v2');

        return $view->render();
    }

}