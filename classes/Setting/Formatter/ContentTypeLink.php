<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

abstract class ContentTypeLink implements Formatter
{

    public const VIEW = 'view';
    public const EDIT = 'edit';

    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function format(Value $value): Value
    {
        $url = $this->get_url($value);

        if ($url) {
            $value = $value->with_value(
                $this->create_link($url)
            );
        }

        return $value;
    }

    protected function get_url(Value $value): ?string
    {
        switch ($this->type) {
            case self::VIEW:
                return $this->get_view_link($value);
            case self::EDIT:
                return $this->get_edit_link($value);
        }

        return null;
    }

    protected function create_link(string $url): string
    {
        return ac_helper()->html->link($url, $url);
    }

    abstract protected function get_edit_link(Value $value): ?string;

    abstract protected function get_view_link(Value $value): ?string;

}