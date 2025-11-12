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

    protected function forward(): ColumnIterator
    {
        if (null === $this->column_collection) {
            $this->column_collection = $this->repository->find_all();
        }

        return $this->column_collection;
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->forward()->current();
    }

    public function next(): void
    {
        $this->forward()->next();
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->forward()->key();
    }

    public function valid(): bool
    {
        return $this->forward()->valid();
    }

    #[\ReturnTypeWillChange]
    public function rewind()
    {
        return $this->forward()->rewind();
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