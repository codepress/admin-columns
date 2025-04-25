<?php

namespace AC\Helper\Select;

final class Response
{

    /**
     * @var Options
     */
    private $options;

    /**
     * @var bool
     */
    private $more;

    public function __construct(Options $options, bool $more = false)
    {
        $this->options = $options;
        $this->more = $more;
    }

    public function __invoke()
    {
        return [
            'results'    => ArrayMapper::map($this->options),
            'pagination' => [
                'more' => $this->more,
            ],
        ];
    }

}