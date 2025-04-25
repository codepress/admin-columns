<?php

namespace AC\Message;

use AC\Message;
use AC\View;

class InlineMessage extends Message
{

    /**
     * @var string|null
     */
    private $class;

    public function __construct($message, $type = null, $class = null)
    {
        parent::__construct($message, $type);

        $this->class = $class;
    }

    public function render(): string
    {
        $view = new View([
            'message' => $this->message,
            'class'   => trim($this->type . ' ' . $this->class),
        ]);
        $view->set_template('message/notice/inline');

        return $view->render();
    }

}