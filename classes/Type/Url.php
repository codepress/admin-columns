<?php

declare(strict_types=1);

namespace AC\Type;

interface Url
{

    public function get_url(): string;

    public function __toString(): string;

}