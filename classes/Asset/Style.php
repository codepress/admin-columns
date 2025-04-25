<?php

namespace AC\Asset;

class Style extends Enqueueable
{

    public function register(): void
    {
        if ( ! $this->location instanceof Location) {
            return;
        }

        wp_register_style(
            $this->get_handle(),
            $this->location->get_url(),
            $this->dependencies,
            $this->get_version()
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