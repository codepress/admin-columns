<?php

namespace AC\ListScreenRepository;

interface Rule
{

    public const TYPE = 'type';
    public const ID = 'id';
    public const GROUP = 'group';

    public function match(array $args): bool;

}