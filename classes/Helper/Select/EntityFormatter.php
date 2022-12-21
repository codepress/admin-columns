<?php

namespace AC\Helper\Select;

interface EntityFormatter {

	public function format_entity_value( $entity ): string;

}