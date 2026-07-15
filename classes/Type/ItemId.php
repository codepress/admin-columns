<?php

declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

/**
 * The identifier of a row in a list table. Usually an integer (a post, user, term or comment id),
 * but custom database tables can use a string primary key, such as a UUID or a slug.
 */
final class ItemId
{
    /**
     * @var int|string
     */
    private $id;

    /**
     * @param int|string $id
     */
    public function __construct($id)
    {
        if (! self::is_valid_id($id)) {
            throw new InvalidArgumentException('Invalid item identifier.');
        }

        $this->id = $id;
    }

    /**
     * @param mixed $id
     */
    public static function is_valid_id($id): bool
    {
        if (is_int($id)) {
            return true;
        }

        return is_string($id) && '' !== $id;
    }

    /**
     * Create an identifier from a raw (request) value. A string that is the canonical
     * representation of an integer becomes an integer, so that existing numeric identifiers keep
     * flowing as integers. Every other value is preserved exactly, so that identifiers like
     * "00123" or a UUID still match their row.
     */
    public static function from_string(string $id): self
    {
        return new self((string)(int)$id === $id ? (int)$id : $id);
    }

    /**
     * @return int|string
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * For storages backed by a WordPress object, whose identifier is always an integer.
     */
    public function to_int(): int
    {
        return (int)$this->id;
    }

    public function equals(ItemId $id): bool
    {
        return $this->id === $id->get_id();
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }

}
