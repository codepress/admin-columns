<?php

declare(strict_types=1);

namespace AC\Type;

use AC\Setting\Collection;

final class NoticeCollection extends Collection
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(Notice $item): void
    {
        $this->data[] = $item;
    }

    public function current(): Notice
    {
        return current($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

}