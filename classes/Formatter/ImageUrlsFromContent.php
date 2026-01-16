<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC;
use AC\Type\Value;
use AC\Type\ValueCollection;

class ImageUrlsFromContent implements AC\Formatter
{

    private AC\Column\Context $context;

    public function __construct(AC\Column\Context $context)
    {
        $this->context = $context;
    }

    public function format(Value $value)
    {
        $string = (string)apply_filters(
            'ac/column/images/content',
            (string)$value,
            $value->get_id(),
            $this->context
        );

        $urls = array_unique(ac_helper()->image->get_image_urls_from_string($value->get_value()));

        if (empty($urls)) {
            throw AC\Exception\ValueNotFoundException::from_id($value->get_id());
        }

        $collection = new ValueCollection($value->get_id());

        foreach ($urls as $url) {
            $collection->add(new Value($url));
        }

        return $collection;
    }

}