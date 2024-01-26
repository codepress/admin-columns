<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Component\AttributeCollection;

// TODO David interface component
interface Component
{

//    protected $type;
//
//    protected $attributes;
//
//    public function __construct(
//        string $type,
//        AttributeCollection $attributes = null
//    ) {
//        if (null === $attributes) {
//            $attributes = new AttributeCollection();
//        }
//
//        $this->type = $type;
//        $this->attributes = $attributes;
//    }

    public function get_type(): string;

    public function get_attributes(): AttributeCollection;

}