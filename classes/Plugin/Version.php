<?php

namespace AC\Plugin;

class Version
{

    private string $value;

    public function __construct(string $version)
    {
        $this->value = $version;
    }

    public function get_value(): string
    {
        return $this->value;
    }

    public function is_valid(): bool
    {
        return ! empty($this->value);
    }

    /**
     * Greater than
     */
    public function is_gt(Version $version): bool
    {
        return version_compare($this->value, $version->get_value(), '>');
    }

    /**
     * Lesser than
     */
    public function is_lt(Version $version): bool
    {
        return version_compare($this->value, $version->get_value(), '<');
    }

    /**
     * Greater than or Equal
     */
    public function is_gte(Version $version): bool
    {
        return version_compare($this->value, $version->get_value(), '>=');
    }

    /**
     * Lesser than or Equal
     */
    public function is_lte(Version $version): bool
    {
        return version_compare($this->value, $version->get_value(), '<=');
    }

    public function is_equal(Version $version): bool
    {
        return 0 === version_compare($this->value, $version->get_value());
    }

    public function is_not_equal(Version $version): bool
    {
        return ! $this->is_equal($version);
    }

    public function is_beta(): bool
    {
        return false !== strpos($this->value, 'beta');
    }

    public function __toString(): string
    {
        return $this->value;
    }

}