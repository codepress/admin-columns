<?php

namespace AC\Storage;

interface KeyValuePair
{

    public function get();

    public function exists(): bool;

    public function save($value): void;

    public function delete(): void;

}