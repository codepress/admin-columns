<?php

declare(strict_types=1);

namespace AC\Type;

use DateTime;

class NoticeConditions
{

    private $screens;

    private $user;

    private $date;

    public function __construct(array $screens = [], int $user = null, DateTime $date = null)
    {
        $this->screens = $screens;
        $this->user = $user;
        $this->date = $date;
    }

    public function get_screens(): array
    {
        return $this->screens;
    }

    public function has_screens(): bool
    {
        return null !== $this->screens;
    }

    public function set_screens(array $screens): self
    {
        $this->screens = $screens;

        return $this;
    }

    public function get_user(): int
    {
        return $this->user;
    }

    public function has_user(): bool
    {
        return null !== $this->user;
    }

    public function set_user(int $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function get_date(): DateTime
    {
        return $this->date;
    }

    public function set_date(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function has_date(): bool
    {
        return null !== $this->date;
    }

}