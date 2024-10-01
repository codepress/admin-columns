<?php

namespace AC\Table;

interface Renderable
{

    public function render($row_id): ?string;

}