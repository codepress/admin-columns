<?php

namespace AC;

use LogicException;

final class MetaType
{

    public const POST = 'post';
    public const USER = 'user';
    public const COMMENT = 'comment';
    public const TERM = 'term';
    public const SITE = 'site';

    private string $meta_type;

    public function __construct(string $meta_type)
    {
        $this->meta_type = $meta_type;

        $this->validate();
    }

    public function get(): string
    {
        return $this->meta_type;
    }

    public static function create_post_type(): self
    {
        return new self(self::POST);
    }

    public static function create_user_type(): self
    {
        return new self(self::USER);
    }

    public static function create_comment_type(): self
    {
        return new self(self::COMMENT);
    }

    /**
     * @throws LogicException
     */
    private function validate(): void
    {
        $types = [
            self::POST,
            self::USER,
            self::COMMENT,
            self::TERM,
            self::SITE,
        ];

        if ( ! in_array($this->meta_type, $types)) {
            throw new LogicException('Invalid meta type ' . $this->meta_type);
        }
    }

    public function equals(MetaType $meta_type): bool
    {
        return $this->meta_type === (string)$meta_type;
    }

    public function __toString(): string
    {
        return $this->meta_type;
    }

}