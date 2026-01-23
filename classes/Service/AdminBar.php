<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Registerable;
use AC\Type\Uri;
use WP_Admin_Bar;

class AdminBar implements Registerable
{

    private Uri $url;

    private string $title;

    private string $id;

    public function __construct(Uri $url, string $title, string $id)
    {
        $this->url = $url;
        $this->title = $title;
        $this->id = $id;
    }

    public function register(): void
    {
        add_action('admin_bar_menu', [$this, 'add_edit_columns'], 900);
    }

    public function add_edit_columns(WP_Admin_Bar $wp_admin_bar): void
    {
        $wp_admin_bar->add_node([
            'id'    => $this->id,
            'title' => $this->title,
            'href'  => $this->url->get_url(),
        ]);
    }

}