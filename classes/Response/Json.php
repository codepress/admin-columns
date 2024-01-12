<?php

declare(strict_types=1);

namespace AC\Response;

use LogicException;

class Json
{

    public const MESSAGE = 'message';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var int
     */
    protected $status_code = 200;

    public function __construct()
    {
        $this->set_header('Content-Type', 'application/json');
    }

    public function send(): void
    {
        if (empty($this->parameters)) {
            throw new LogicException('Missing response body.');
        }

        $this->send_response($this->parameters);
        wp_send_json($this->parameters, $this->status_code);
    }

    private function send_response($data): void
    {
        status_header($this->status_code);

        foreach ($this->headers as $header) {
            header($header);
        }

        echo json_encode($data);
        exit;
    }

    public function error(): void
    {
        $this->send_response([
            'success' => false,
            'data'    => $this->parameters,
        ]);
    }

    public function success(): void
    {
        $this->send_response([
            'success' => true,
            'data'    => $this->parameters,
        ]);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set_parameter($key, $value): self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function set_parameters(array $values): self
    {
        foreach ($values as $key => $value) {
            $this->set_parameter($key, $value);
        }

        return $this;
    }

    public function set_header(string $name, string $value): self
    {
        $this->headers[] = sprintf('%s: %s', $name, $value);

        return $this;
    }

    public function set_message(string $message): self
    {
        $this->set_parameter(self::MESSAGE, $message);

        return $this;
    }

    public function set_status_code(int $code): self
    {
        $this->status_code = $code;

        return $this;
    }

}