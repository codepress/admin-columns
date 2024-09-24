<?php

declare(strict_types=1);

namespace AC\Storage;

interface KeyValue
{

    public function get();

    public function save($value): void;

    public function delete(): void;

}