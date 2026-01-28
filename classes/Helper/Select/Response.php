<?php

namespace AC\Helper\Select;

final class Response
{

    private Options $options;

    private bool $more;

    public function __construct(Options $options, bool $more = false)
    {
        $this->options = $options;
        $this->more = $more;
    }

    public function __invoke(): array
    {
        return [
            'results'    => ArrayMapper::map($this->options),
            'pagination' => [
                'more' => $this->more,
            ],
        ];
    }

}