<?php

namespace AC\Storage;

interface KeyValuePair
{

    public function get(array $args = []);

    public function save($value): void;

    public function delete(): void;

}