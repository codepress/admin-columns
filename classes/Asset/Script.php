<?php

namespace AC\Asset;

use AC\Asset\Script\Inline\Position;

class Script extends Enqueueable
{

    protected bool $in_footer;

    public function __construct(
        string $handle,
        Location $location = null,
        array $dependencies = [],
        bool $in_footer = false
    ) {
        parent::__construct($handle, $location, $dependencies);

        $this->in_footer = $in_footer;
    }

    protected function is_registered(): bool
    {
        return wp_script_is($this->get_handle(), 'registered');
    }

    public function is_in_footer(): bool
    {
        return $this->in_footer;
    }

    public function register(): void
    {
        if ( ! $this->location instanceof Location) {
            return;
        }

        wp_register_script(
            $this->get_handle(),
            $this->location->get_url(),
            $this->dependencies,
            $this->get_version(),
            $this->is_in_footer()
        );
    }

    public function enqueue(): void
    {
        if (wp_script_is($this->get_handle())) {
            return;
        }

        if ( ! $this->is_registered()) {
            $this->register();
        }

        wp_enqueue_script($this->get_handle());
    }

    public function localize(string $name, Script\Localize\Translation $translation): self
    {
        if ( ! $this->is_registered()) {
            $this->register();
        }

        wp_localize_script($this->handle, $name, $translation->get_translation());

        return $this;
    }

    public function add_inline(string $data, Position $position = null): self
    {
        if (null === $position) {
            $position = Position::after();
        }

        if ( ! $this->is_registered()) {
            $this->register();
        }

        wp_add_inline_script($this->handle, $data, (string)$position);

        return $this;
    }

    public function add_inline_variable(string $name, $data): self
    {
        return $this->add_inline(
            sprintf('var %s = %s;', $name, json_encode($data)),
            Position::before()
        );
    }

}
