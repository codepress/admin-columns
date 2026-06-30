<?php

declare(strict_types=1);

namespace AC\Service;

use AC\AdminColumns;
use AC\Registerable;
use AC\View;

class Tooltips implements Registerable
{
    private string $url;

    public function __construct(AdminColumns $plugin)
    {
        $this->url = rtrim($plugin->get_url(), '/');
    }

    public function register(): void
    {
        add_filter('ac/page/columns/render', [$this, 'render'], 10, 2);
    }

    private function create_view(string $slug): string
    {
        return (new View(['url' => $this->url]))->set_template('tooltip/' . $slug)->render();
    }

    public function render(string $html): string
    {
        $tooltips = array_map(
            [
                $this,
                'create_view',
            ],
            [
                'custom-field',
                'serialized',
            ]
        );

        return $html . implode($tooltips);
    }

}
