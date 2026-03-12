<?php

declare(strict_types=1);

namespace AC\Admin;

use AC\Renderable;

interface RenderableHead
{

    public function render_head(): Renderable;

}