<?php

namespace AC\Helper\Select;

interface ValueFormatter {

	public function format_value( $entity ): string;

}