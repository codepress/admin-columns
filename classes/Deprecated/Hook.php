<?php

namespace AC\Deprecated;

class Hook
{

    private string $name;

    private string $version;

    private ?string $slug;

    public function __construct(string $name, string $version, ?string $slug = null)
    {
        $this->name = $name;
        $this->version = $version;
        $this->slug = $slug;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_version(): string
    {
        return $this->version;
    }

    public function get_slug(): ?string
    {
        return $this->slug;
    }

    public function has_hook(): bool
    {
        return has_filter($this->name);
    }

    public function get_callbacks(): ?array
    {
        global $wp_filter;

        $callbacks = $wp_filter[$this->name]->callbacks ?? null;

        if ( ! $callbacks) {
            return null;
        }

        $messages = [];

        foreach ($callbacks as $callback) {
            foreach ($callback as $cb) {
                // Function
                if (is_scalar($cb['function'])) {
                    $messages[] = $cb['function'];
                    continue;
                }

                // Method
                if (is_array($cb['function'])) {
                    $messages[] = get_class($cb['function'][0]) . '::' . $cb['function'][1];
                }
            }
        }

        if ( ! $messages) {
            return null;
        }

        return $messages;
    }

}