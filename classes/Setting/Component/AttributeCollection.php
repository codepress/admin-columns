<?php

declare(strict_types=1);

namespace AC\Setting\Component;

use AC\Setting\Collection;
use AC\Setting\Component\Type\Attribute;

final class AttributeCollection extends Collection
{

    public function __construct(array $options = [])
    {
        array_map([$this, 'add'], $options);
    }

    public static function from_array(array $attributes): self
    {
        $collection = new self();

        foreach ($attributes as $name => $value) {
            $collection->add(new Attribute($name, $value));
        }

        return $collection;
    }

    public function add(Attribute $item): void
    {
        $this->data[] = $item;
    }

    public function get( string $key ) : string
    {
        foreach( $this->data as $attribute )
    }

    public function current(): Attribute
    {
        return parent::current();
    }

}