<?php

namespace AC\Asset;

use AC\Asset\Script\Inline\Position;

class Script extends Enqueueable
{

    protected bool $in_footer;

    protected array $templates = [];

    public function __construct(
        string $handle,
        ?Location $location = null,
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

        if ($this->templates) {
            // Allows JS frameworks to use PHP templates
            $this->add_inline(
                sprintf(
                    'window.addEventListener("DOMContentLoaded",function(){document.body.insertAdjacentHTML("beforeend", %s);});',
                    json_encode(implode('', $this->templates))
                )
            );
        }
    }

    public function localize(string $name, Script\Localize\Translation $translation): self
    {
        if ( ! $this->is_registered()) {
            $this->register();
        }

        wp_localize_script($this->handle, $name, $translation->get_translation());

        return $this;
    }

    public function add_inline(string $data, ?Position $position = null): self
    {
        if ( ! $this->is_registered()) {
            $this->register();
        }

        wp_add_inline_script($this->handle, $data, (string)($position ?? Position::after()));

        return $this;
    }

    public function add_inline_variable(string $name, $data): self
    {
        return $this->add_inline(
            sprintf('var %s = %s;', $name, json_encode($data)),
            Position::before()
        );
    }

    public function add_template(string $id, string $html): self
    {
        $this->templates[] = sprintf('<template id="%s">%s</template>', esc_attr($id), $html);

        return $this;
    }

}
