<?php

declare(strict_types=1);

namespace AC\Setting;

interface OptionCollectionFactory
{

    public function create(): OptionCollection;

}