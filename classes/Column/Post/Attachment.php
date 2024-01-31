<?php

declare(strict_types=1);

namespace AC\Column\Post;

use AC\Column;
use AC\Column\Renderable;
use AC\Setting\SettingCollection;
use AC\Settings\Column\AttachmentDisplay;

class Attachment extends Column implements Column\Value
{

    public function __construct()
    {
        parent::__construct(
            'column-attachment',
            __('Attachments', 'codepress-admin-columns'),
            new SettingCollection([
                new AttachmentDisplay(),
            ])
        );
    }

    public function renderable(): Renderable
    {
        return new Column\Post\Renderable\Attachment(
            new Renderable\ValueFormatter($this->settings)
        );
    }

}