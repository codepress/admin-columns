<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\ListScreen;
use AC\Type\ListScreenId;

class EditorNetworkColumns extends EditorNetwork
{

    public function __construct(string $list_key, ListScreenId $list_id = null)
    {
        parent::__construct('columns');

        $this->add([
            'list_screen' => $list_key,
            'layout_id'   => (string)$list_id,
        ]);
    }

    public static function create_by_list(ListScreen $list_screen): self
    {
        return new self(
            $list_screen->get_key(),
            $list_screen->has_id() ? $list_screen->get_id() : null
        );
    }

}