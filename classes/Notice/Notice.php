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

    private string $id;

    private string $message;

    private string $type;

    private Specification $specification;

    public function __construct(
        string $id,
        string $message,
        string $type = null,
        Specification $specification = null
    ) {
        $this->id = $id;
        $this->message = $message;
        $this->type = $type ?? self::SUCCESS;
        $this->specification = $specification ?? new NullSpecification();
    }

    public function get_id(): string
    {
        return $this->id;
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