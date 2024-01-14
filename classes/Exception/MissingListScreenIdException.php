<?php

namespace AC\Exception;

use LogicException;

class MissingListScreenIdException extends LogicException
{

    public static function from_saving_list_screen(): self
    {
        return new self('Cannot save a ListScreen without an identity.');
    }

}