<?php

declare(strict_types=1);

namespace AC\Asset;

interface Location
{

    public function get_url(): string;

    public function get_path(): string;

    public function with_suffix(string $suffix): self;

}
