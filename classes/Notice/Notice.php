<?php

declare(strict_types=1);

namespace AC\Notice;

use AC\Expression\NullSpecification;
use AC\Expression\Specification;

final class Notice
{

    public const SUCCESS = 'success';
    public const ERROR = 'error';
    public const WARNING = 'warning';
    public const INFO = 'info';

    private $message;

    private $type;

    private $specification;

    public function __construct(
        string $message,
        string $type = null,
        Specification $specification = null
    ) {
        if ($specification === null) {
            $specification = new NullSpecification();
        }

        if ($type === null) {
            $type = self::SUCCESS;
        }

        $this->message = $message;
        $this->type = $type;
        $this->specification = $specification;
    }

    public function get_message(): string
    {
        return $this->message;
    }

    public function get_type(): ?string
    {
        return $this->type;
    }

    public function get_conditions(): Specification
    {
        return $this->specification;
    }

}