<?php

declare(strict_types=1);

namespace AC\Setting;

trait OptionTrait
{

    /**
     * @var OptionCollection;
     */
    protected $options;

    public function get_options(): OptionCollection
    {
        return $this->options;
    }

}