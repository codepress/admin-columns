<?php

namespace AC;

interface RequestHandler
{

    public function handle(Request $request): void;

}