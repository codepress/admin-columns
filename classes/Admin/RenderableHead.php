<?php

namespace AC\Admin;

use AC\Renderable;

interface RenderableHead {

	/**
	 * @return Renderable
	 */
	public function render_head();

}