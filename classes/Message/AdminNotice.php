<?php

namespace AC\Message;

use AC\View;

class AdminNotice extends Notice
{

    public function render(): string
    {
        $data = [
            'message' => $this->message,
            'type'    => $this->type,
            'id'      => $this->id,
        ];

        $view = new View($data);
        $view->set_template('message/notice/admin');

        return $view->render();
    }

}