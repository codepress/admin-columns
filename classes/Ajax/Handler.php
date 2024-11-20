<?php

namespace AC\Ajax;

use AC\Registerable;
use LogicException;

class Handler implements Registerable
{

    public const NONCE_ACTION = 'ac-ajax';

    protected array $params = [];

    /**
     * @var callable
     */
    protected $callback;

    protected bool $wp_ajax;

    protected int $priority = 10;

    public function __construct(bool $wp_ajax = null)
    {
        $this->wp_ajax = $wp_ajax === null;

        $this->set_nonce();
    }

    public function register(): void
    {
        if ( ! $this->get_action()) {
            throw new LogicException('Action parameter is missing.');
        }

        if ( ! $this->get_callback()) {
            throw new LogicException('Callback is missing.');
        }

        add_action($this->get_action(), $this->get_callback(), $this->priority);
    }

    public function deregister(): void
    {
        remove_action($this->get_action(), $this->get_callback(), $this->priority);
    }

    public function get_action(): string
    {
        $action = $this->get_param('action');

        if ($this->wp_ajax) {
            $action = 'wp_ajax_' . $action;
        }

        return $action;
    }

    public function set_action(string $action): self
    {
        $this->params['action'] = $action;

        return $this;
    }

    public function set_priority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function get_priority(): ?int
    {
        return $this->priority;
    }

    public function set_callback(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    public function get_callback(): callable
    {
        return $this->callback;
    }

    public function set_nonce(string $nonce = null): void
    {
        if (null === $nonce) {
            $nonce = wp_create_nonce(self::NONCE_ACTION);
        }

        $this->params['_ajax_nonce'] = $nonce;
    }

    public function verify_request(string $action = null): void
    {
        if (null === $action) {
            $action = self::NONCE_ACTION;
        }

        check_ajax_referer($action);
    }

    public function get_params(): array
    {
        return $this->params;
    }

    public function get_param(string $key)
    {
        if ( ! array_key_exists($key, $this->params)) {
            return null;
        }

        return $this->params[$key];
    }

    public function set_param(string $key, $value): void
    {
        switch ($key) {
            case 'action':
                $this->set_action($value);

                break;
            case 'nonce':
                $this->set_nonce($value);

                break;
            default:
                $this->params[$key] = $value;
        }
    }

}