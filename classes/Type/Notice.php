<?php

namespace AC\Type;

use DateTime;
use LogicException;

class Notice
{

    public const SUCCESS = 'updated'; // green
    public const ERROR = 'notice-error'; // red
    public const WARNING = 'notice-warning'; // yellow
    public const INFO = 'notice-info'; // blue

    private $id;

    private $content;

    private $type;

    private $screens;

    private $user;

    private $date;

    public function __construct(
        string $id,
        string $content,
        array $screens,
        string $type = null,
        int $user = null,
        DateTime $date = null

    ) {
        if (null === $type) {
            $type = self::SUCCESS;
        }

        $this->id = $id;
        $this->type = $type;
        $this->content = $content;
        $this->screens = $screens;
        $this->user = $user;
        $this->date = $date;

        $this->validate();
    }

    private function validate(): void
    {
        if ('' === $this->content) {
            throw new LogicException('Content cannot be empty');
        }
        if ( ! in_array($this->type, [self::SUCCESS, self::ERROR, self::INFO, self::WARNING], true)) {
            throw new LogicException('Invalid notice type');
        }
    }

    public function get_content(): string
    {
        return $this->content;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_id(): string
    {
        return $this->id;
    }

    public function get_screens(): array
    {
        return $this->screens;
    }

    public function get_user(): int
    {
        return $this->user;
    }

    public function has_user(): bool
    {
        return null !== $this->user;
    }

    public function get_date(): DateTime
    {
        return $this->date;
    }

    public function has_date(): bool
    {
        return null !== $this->date;
    }

}