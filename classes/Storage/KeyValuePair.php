<?php

namespace AC\Storage;

interface KeyValuePair
{

    // TODO arguments needed?
    public function get(array $args = []);

    public function save($value): void;

    public function delete(): void;

}