<?php

declare(strict_types=1);

namespace AC\Setting\Control;

interface OptionCollectionFactory
{

    public function create(): OptionCollection;

}