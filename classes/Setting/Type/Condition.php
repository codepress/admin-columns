<?php

declare(strict_types=1);

namespace AC\Setting\Type;

final class Condition
{

    public const EQUALS = '===';

    private $setting;

    private $value;

    private $operator;

    public function __construct(string $setting, string $value, string $operator)
    {
        $this->setting = $setting;
        $this->value = $value;
        $this->operator = $operator;
    }

    public function get_setting(): string
    {
        return $this->setting;
    }

    public function get_value(): string
    {
        return $this->value;
    }

    public function get_operator(): string
    {
        return $this->operator;
    }

}