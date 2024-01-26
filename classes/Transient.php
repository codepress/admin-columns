<?php

namespace AC;

class Transient implements Expirable
{

    /**
     * @var Storage\Option
     */
    protected $option;

    /**
     * @var Storage\Timestamp
     */
    protected $timestamp;

    public function __construct(string $key, bool $network_only = false)
    {
        $option_factory = $network_only
            ? new Storage\SiteOptionFactory()
            : new Storage\OptionFactory();

        $this->option = $option_factory->create($key);
        $this->timestamp = new Storage\Timestamp(
            $option_factory->create($key . '_timestamp')
        );
    }

    public function is_expired(int $value = null): bool
    {
        return $this->timestamp->is_expired($value);
    }

    public function has_expiration_time(): bool
    {
        return false !== $this->timestamp->get();
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->option->get();
    }

    public function delete(): void
    {
        $this->option->delete();
        $this->timestamp->delete();
    }

    public function save($data, int $expiration): void
    {
        // Always store timestamp before option data.
        $this->timestamp->save(time() + $expiration);

        $this->option->save($data);
    }

}