<?php

namespace AC;

interface Middleware
{

    public function handle(Request $request): void;

}