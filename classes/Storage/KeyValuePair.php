<?php

namespace AC\Storage;

interface KeyValuePair
{

    public function get();

    public function exists(): bool;

    public function save($value): bool;

    public function delete(): bool;

}