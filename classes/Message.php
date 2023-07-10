<?php

namespace AC;

use Exception;
use LogicException;

abstract class Message
{

    public const SUCCESS = 'updated';
    public const ERROR = 'notice-error';
    public const WARNING = 'notice-warning';
    public const INFO = 'notice-info';

    protected $message;

    protected $type;

    protected $id = '';

    public function __construct(string $message, string $type = null)
    {
        if (null === $type) {
            $type = self::SUCCESS;
        }

        $this->type = $type;
        $this->message = trim($message);

        $this->validate();
    }

    protected function validate(): void
    {
        if (empty($this->message)) {
            throw new LogicException('Message cannot be empty');
        }
    }

    abstract public function render(): string;

    /**
     * Display self::render to the screen
     * @throws Exception
     */
    public function display(): void
    {
        echo $this->render();
    }

    public function get_message(): string
    {
        return $this->message;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function set_type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function get_id(): string
    {
        return $this->id;
    }

    public function set_id(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}