<?php

namespace AC\Deprecated;

class Hook
{

    private string $name;

    private string $version;

    private ?string $replacement;

    public function __construct(string $name, string $version, ?string $replacement = null)
    {
        $this->name = $name;
        $this->version = $version;
        $this->replacement = $replacement;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_version(): string
    {
        return $this->version;
    }

    public function get_replacement(): string
    {
        return $this->replacement;
    }

    public function has_replacement(): bool
    {
        return null !== $this->replacement;
    }

    public function has_hook(): bool
    {
        return has_filter($this->name);
    }

    public function usage_count(): int
    {
        return count($this->get_callbacks());
    }

    private function get_filter_callbacks(): array
    {
        global $wp_filter;

        return $wp_filter[$this->name]->callbacks ?? [];
    }

    public function get_callbacks(): ?array
    {
        $messages = [];

        foreach ($this->get_filter_callbacks() as $callback) {
            foreach ($callback as $cb) {
                $function = $cb['function'];

                // Function
                if (is_scalar($function)) {
                    $messages[] = $function;
                    continue;
                }

                // Method
                if (is_array($function)) {
                    $messages[] = get_class($function[0]) . '::' . $function[1];
                    continue;
                }
                
                // Anonymous function
                $messages[] = __('Anonymous Function', 'codepress-admin-columns');
            }
        }

        if ( ! $messages) {
            return null;
        }

        return $messages;
    }

}