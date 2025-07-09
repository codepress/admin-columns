<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Registerable;
use AC\View;

class Tooltips implements Registerable
{

    public function register(): void
    {
        add_filter('ac/page/columns/render', [$this, 'render'], 10, 2);
    }

    private function create_view(string $slug): string
    {
        return (new View())->set_template('tooltip/' . $slug)->render();
    }

    public function render(string $html): string
    {
        $tooltips = array_map(
            [$this, 'create_view'],
            [
                'custom-field',
                'serialized',
            ]
        );

        return $html . implode($tooltips);
    }

}