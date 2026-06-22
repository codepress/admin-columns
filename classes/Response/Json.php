<?php

declare(strict_types=1);

namespace AC\Response;

use DomainException;

class Json
{

    public const MESSAGE = 'message';

    protected array $parameters = [];

    protected array $headers = [];

    protected int $status_code = 200;

    public function __construct(array $parameters = [])
    {
        $this->set_header('Content-Type', 'application/json');

        $this->parameters = $parameters;
    }

    /**
     * @return never
     */
    public function send()
    {
        if (empty($this->parameters)) {
            throw new DomainException('Missing response body.');
        }

        $this->send_response($this->parameters);
    }

    /**
     * @return never
     */
    private function send_response($data)
    {
        status_header($this->status_code);

        foreach ($this->headers as $header) {
            header($header);
        }

        echo json_encode($data);
        exit;
    }

    /**
     * @return never
     */
    public function error()
    {
        $this->send_response([
            'success' => false,
            'data'    => $this->parameters,
        ]);
    }

    /**
     * @return never
     */
    public function success()
    {
        $this->send_response([
            'success' => true,
            'data'    => $this->parameters,
        ]);
    }

    public function set_parameter($key, $value): self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function with_parameter($key, $value): self
    {
        $clone = clone $this;
        $clone->parameters[$key] = $value;

        return $clone;
    }

    public function set_parameters(array $values): self
    {
        foreach ($values as $key => $value) {
            $this->set_parameter($key, $value);
        }

        return $this;
    }

    public function with_parameters(array $values): self
    {
        $clone = $this;

        foreach ($values as $key => $value) {
            $clone = $clone->with_parameter($key, $value);
        }

        return $clone;
    }

    public function set_header(string $name, string $value): self
    {
        $this->headers[] = sprintf('%s: %s', $name, $value);

        return $this;
    }

    public function with_header(string $name, string $value): self
    {
        $clone = clone $this;
        $clone->headers[] = sprintf('%s: %s', $name, $value);

        return $clone;
    }

    public function set_message(string $message): self
    {
        $this->set_parameter(self::MESSAGE, $message);

        return $this;
    }

    public function with_message(string $message): self
    {
        return $this->with_parameter(self::MESSAGE, $message);
    }

    public function set_status_code(int $code): self
    {
        $this->status_code = $code;

        return $this;
    }

    public function with_status_code(int $code): self
    {
        $clone = clone $this;
        $clone->status_code = $code;

        return $clone;
    }

}
