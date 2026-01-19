<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class Links implements Formatter
{

    private ?string $filter_by;

    private ?array $internal_domains;

    public function __construct(?string $filter_by = null, array $internal_domains = null)
    {
        $this->filter_by = $filter_by;
        $this->internal_domains = $internal_domains;
    }

    public function format(Value $value): ValueCollection
    {
        $urls = ac_helper()->html->get_links(
            (string)$value
        );

        if ( ! $urls) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        switch ($this->filter_by) {
            case 'internal':
                $urls = $this->filter_by_internal($urls);
                break;
            case 'external':
                $urls = $this->filter_by_external($urls);
        }

        $values = [];

        foreach ($urls as $url) {
            $values[] = new Value($url);
        }

        return new ValueCollection($value->get_id(), $values);
    }

    private function filter_by_internal(array $urls): array
    {
        return array_filter($urls, [$this, 'is_internal_url']);
    }

    private function filter_by_external(array $urls): array
    {
        return array_filter($urls, [$this, 'is_external_url']);
    }

    private function is_external_url(string $url): bool
    {
        return ! $this->is_internal_url($url);
    }

    private function is_internal_url(string $url): bool
    {
        foreach ($this->internal_domains as $domain) {
            if (false !== strpos($url, $domain)) {
                return true;
            }
        }

        return false;
    }

}