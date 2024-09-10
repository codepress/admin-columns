<?php

declare(strict_types=1);

namespace AC\Notice;

class NoticeRepository
{

    public function save(Notice $notice): void
    {
        $rules = $notice->get_conditions()->get_rules();
    }

}