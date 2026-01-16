<?php

declare(strict_types=1);

namespace AC\Formatter\User;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use InvalidArgumentException;

class Property implements Formatter
{

    private string $property;

    public function __construct(string $property)
    {
        $this->property = $property;

        $this->validate();
    }

    public function validate(): void
    {
        $properties = [
            'id',
            'ID',
            'deleted',
            'description',
            'display_name',
            'first_name',
            'last_name',
            'locale',
            'nickname',
            'rich_editing',
            'spam',
            'use_ssl',
            'user_activation_key',
            'user_description',
            'user_email',
            'user_firstname',
            'user_lastname',
            'user_login',
            'user_pass',
            'user_nicename',
            'user_status',
            'user_registered',
            'user_url',
        ];

        if ( ! in_array($this->property, $properties, true)) {
            throw new InvalidArgumentException('Invalid property');
        }
    }

    public function format(Value $value): Value
    {
        $user = get_userdata($value->get_id());

        if ( ! $user) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $field = $user->{$this->property} ?? null;

        if (null === $field) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $field
        );
    }

}