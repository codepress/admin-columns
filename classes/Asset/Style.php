<?php

namespace AC\Asset;

class Style extends Enqueueable
{

    public function register(): void
    {
        if ( ! $this->location instanceof Location) {
            return;
        }

        $version = $this->get_version();

        wp_register_style(
            $this->get_handle(),
            $this->location->get_url(),
            $this->dependencies,
            $version !== null
                ? (string)$version
                : null
        );
    }

    public function enqueue(): void
    {
        if (wp_style_is($this->get_handle())) {
            return;
        }

        if ( ! wp_style_is($this->get_handle(), 'registered')) {
            $this->register();
        }

        wp_enqueue_style($this->get_handle());
    }

}