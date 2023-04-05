<?php

namespace AC;

use LogicException;

final class MetaType {

	public const POST = 'post';
	public const USER = 'user';
	public const COMMENT = 'comment';
	public const TERM = 'term';
	public const SITE = 'site';

	private $meta_type;

	public function __construct( string $meta_type ) {
		$this->meta_type = $meta_type;

		$this->validate();
	}

	public function get(): string {
		return $this->meta_type;
	}

	/**
	 * @throws LogicException
	 */
	private function validate(): void {
		$types = [
			self::POST,
			self::USER,
			self::COMMENT,
			self::TERM,
			self::SITE,
		];

		if ( ! in_array( $this->meta_type, $types ) ) {
			throw new LogicException( 'Invalid meta type.' );
		}
	}

	public function __toString(): string {
		return $this->meta_type;
	}

}