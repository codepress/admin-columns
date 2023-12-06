<?php

declare(strict_types=1);

namespace AC\ColumnIterator;

use AC\Column;
use AC\ColumnIterator;
use AC\ColumnRepository;

class ProxyColumnIterator implements ColumnIterator
{

    private $repository;

    private $columnCollection;

    public function __construct(ColumnRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function loaded(): bool
    {
        return null !== $this->columnCollection;
    }

    protected function forward(): ColumnIterator
    {
        if ( ! $this->loaded()) {
            $this->columnCollection = $this->repository->find_all();
        }

        return $this->columnCollection;
    }

    public function exists(string $name): bool
    {
        return $this->forward()->exists($name);
    }

    public function get(string $name): Column
    {
        return $this->forward()->get($name);
    }

    public function keys(): array
    {
        return $this->forward()->keys();
    }

    public function current()
    {
        return $this->forward()->current();
    }

    public function next()
    {
        return $this->forward()->next();
    }

    public function key()
    {
        return $this->forward()->key();
    }

    public function valid(): bool
    {
        return $this->forward()->valid();
    }

    public function rewind()
    {
        return $this->forward()->rewind();
    }

    public function count(): int
    {
        return $this->forward()->count();
    }

}