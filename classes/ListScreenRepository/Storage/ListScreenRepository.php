<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Storage;

use AC;
use AC\ListScreenRepository\Rules;
use LogicException;

final class ListScreenRepository
{

    private $list_screen_repository;

    private $writable;

    private $rules;

    // TODO David ARE LSids unique? Can we enforce this?
    // TODO David think about refactor of this class: supply a factory instead of LS Repo to 'skip' the writable flag.
    public function __construct(
        AC\ListScreenRepository $list_screen_repository,
        bool $writable = null,
        Rules $rules = null
    ) {
        if (null === $writable) {
            $writable = false;
        }

        $this->list_screen_repository = $list_screen_repository;
        $this->writable = $writable && $this->list_screen_repository instanceof AC\ListScreenRepositoryWritable;
        $this->rules = $rules;
    }

    public function get_list_screen_repository(): AC\ListScreenRepository
    {
        return $this->list_screen_repository;
    }

    public function is_writable(): bool
    {
        return $this->writable;
    }

    public function with_writable(bool $writable): self
    {
        return new self(
            $this->list_screen_repository,
            $writable,
            $this->rules
        );
    }

    public function get_rules(): Rules
    {
        if ( ! $this->has_rules()) {
            throw new LogicException('No rules defined.');
        }

        return $this->rules;
    }

    public function has_rules(): bool
    {
        return $this->rules !== null;
    }

}