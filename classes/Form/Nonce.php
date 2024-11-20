<?php

declare(strict_types=1);

namespace AC\Form;

use AC\Request;

class Nonce
{

    private string $action;

    private string $name;

    public function __construct(string $action, string $name)
    {
        $this->action = $action;
        $this->name = $name;
    }

    public function get_action(): string
    {
        return $this->action;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function create(): ?string
    {
        return wp_create_nonce($this->action) ?: null;
    }

    public function create_field(): string
    {
        return wp_nonce_field($this->action, $this->name, true, false);
    }

    public function verify_nonce(string $nonce): bool
    {
        return (bool)wp_verify_nonce($nonce, $this->action);
    }

    public function verify(Request $request): bool
    {
        return $this->verify_nonce((string)$request->get($this->name));
    }

}