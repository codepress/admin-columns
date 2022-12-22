<?php

namespace AC\Helper\Select;

// TODO remove. use Select\Post\LabelFormatter
interface UnqiueValueFormatter {

	public function format_value_unique( $value ): string;

}