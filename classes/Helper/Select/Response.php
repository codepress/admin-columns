<?php

namespace AC\Helper\Select;

final class Response {

	/**
	 * @var Options
	 */
	private $options;

	/**
	 * @var bool
	 */
	private $more;

	/**
	 * @param Options $options
	 * @param bool    $more
	 */
	public function __construct( Options $options, $more = false ) {
		$this->options = $options;
		$this->more = (bool) $more;
	}

	/**
	 * @param array $options
	 *
	 * @return array
	 */
	private function parse_options( array $options ) {
		$results = [];

		foreach ( $options as $option ) {
			switch ( true ) {
				case $option instanceof OptionGroup:
					$results[] = [
						'text'     => $option->get_label(),
						'children' => $this->parse_options( $option->get_options() ),
					];

					break;
				case $option instanceof Option:
					$results[] = [
						'id'   => $option->get_value(),
						'text' => $option->get_label(),
					];

					break;
			}
		}

		return $results;
	}

	/**
	 * @inheritDoc
	 */
	public function __invoke() {
		return [
			'results'    => $this->parse_options( $this->options->get_copy() ),
			'pagination' => [
				'more' => $this->more,
			],
		];
	}

}