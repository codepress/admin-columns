<?php

namespace AC\Type;

interface QueryAware extends Url
{

    public function add_one(string $key, string $value): void;

    public function add(array $params = []): void;

    public function remove(string $key): void;

}