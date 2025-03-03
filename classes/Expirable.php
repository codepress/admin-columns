<?php

namespace AC;

interface Expirable
{

    public function is_expired(int $timestamp = null): bool;

}