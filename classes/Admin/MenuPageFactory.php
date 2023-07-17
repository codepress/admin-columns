<?php

namespace AC\Admin;

interface MenuPageFactory
{

    public function create(array $args = []): string;

}