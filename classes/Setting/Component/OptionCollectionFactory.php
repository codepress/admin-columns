<?php

declare(strict_types=1);

namespace AC\Setting\Component;

interface OptionCollectionFactory
{

    public function create(): OptionCollection;

}