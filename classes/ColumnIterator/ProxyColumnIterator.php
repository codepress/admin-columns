<?php

declare(strict_types=1);

namespace AC\ColumnIterator;

use AC\Column;
use AC\ColumnIterator;
use AC\ColumnRepository;
use ReturnTypeWillChange;

class ProxyColumnIterator implements ColumnIterator
{

    private $repository;

    private $column_collection;

    public function __construct(ColumnRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function loaded(): bool
    {
        return null !== $this->column_collection;
    }

    protected function forward(): ColumnIterator
    {
        if ( ! $this->loaded()) {
            $this->column_collection = $this->repository->find_all();
        }

        return $this->column_collection;
    }

    #[ReturnTypeWillChange]
    public function current()
    {
        return $this->forward()->current();
    }

    #[ReturnTypeWillChange]
    public function next()
    {
        return $this->forward()->next();
    }

    #[ReturnTypeWillChange]
    public function key()
    {
        return $this->forward()->key();
    }

    public function valid(): bool
    {
        return $this->forward()->valid();
    }

    #[ReturnTypeWillChange]
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