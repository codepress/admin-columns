<?php

declare(strict_types=1);

namespace AC\ColumnIterator;

use AC\Column;
use AC\ColumnCollection;
use AC\ColumnIterator;
use AC\ColumnRepository;

class ProxyColumnIterator implements ColumnIterator
{

    private ColumnRepository $repository;

    private ?ColumnCollection $column_collection = null;

    public function __construct(ColumnRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function forward(): ColumnCollection
    {
        if (null === $this->column_collection) {
            $this->column_collection = $this->repository->find_all();
        }

        return $this->column_collection;
    }

    public function current(): Column
    {
        return $this->forward()->current();
    }

    public function next(): void
    {
        $this->forward()->next();
    }

    public function key(): int
    {
        return $this->forward()->key();
    }

    public function valid(): bool
    {
        return $this->forward()->valid();
    }

    public function rewind(): void
    {
        $this->forward()->rewind();
    }

    public function count(): int
    {
        return $this->forward()->count();
    }

    public function first(): ?Column
    {
        return $this->forward()->first();
    }

}