<?php

namespace AC;

class View implements Renderable
{

    private array $data = [];

    private ?string $template;

    public function __construct(array $data = [])
    {
        $this->set_data($data);
    }

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    public function set(string $key, $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function get_data(): array
    {
        return $this->data;
    }

    public function set_data(array $data): self
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Will try to resolve the current template to a file
     */
    public function resolve_template(): bool
    {
        /**
         * Returns the available template paths for column settings
         *
         * @param array  $paths    Template paths
         * @param string $template Current template path
         */
        $paths = apply_filters(
            'ac/view/templates',
            [
                Container::get_location()->with_suffix('templates')->get_path(),
            ],
            $this->template
        );

        foreach ($paths as $path) {
            $file = $path . '/' . $this->template . '.php';

            if (is_readable($file)) {
                include $file;

                return true;
            }
        }

        return false;
    }

    public function render(): string
    {
        ob_start();

        $this->resolve_template();

        return ob_get_clean();
    }

    public function get_template(): ?string
    {
        return $this->template;
    }

    public function set_template(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Should call self::render when treated as a string
     */
    public function __toString(): string
    {
        return $this->render();
    }

}