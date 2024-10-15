<?php

namespace AC\Type;

interface QueryAware extends Url
{

    public function add(string $key, string $value): void;

    public function remove(string $key): void;

}