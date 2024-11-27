<?php

namespace AC\Admin\Page;

use AC;
use AC\Admin;
use AC\Admin\RenderableHead;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Style;
use AC\IntegrationRepository;
use AC\Renderable;

class Addons implements Enqueueables, Renderable, RenderableHead
{

    public const NAME = 'addons';

    protected Location\Absolute $location;

    protected IntegrationRepository $integrations;

    protected Renderable $head;

    public function __construct(Location\Absolute $location, IntegrationRepository $integrations, Renderable $head)
    {
        $this->location = $location;
        $this->integrations = $integrations;
        $this->head = $head;
    }

    public function render_head(): Renderable
    {
        return $this->head;
    }

    protected function is_pro(): bool
    {
        return false;
    }

    public function get_assets(): Assets
    {
        return new Assets([
            new Style('ac-admin-page-addons', $this->location->with_suffix('assets/css/admin-page-addons.css')),
            new Admin\Asset\Addons(
                'ac-admin-page-addons',
                new AC\Nonce\Ajax(),
                $this->location->with_suffix('assets/js/admin-page-addons.js'),
                $this->location,
                $this->is_pro()
            ),
        ]);
    }

    public function render(): string
    {
        return '';
    }

}