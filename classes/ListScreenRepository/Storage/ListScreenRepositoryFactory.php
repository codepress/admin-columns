<?php

namespace AC\ListScreenRepository\Storage;

use AC\ListScreenRepository\Rules;
use ACP\ListScreenRepository\FileFactory;
use ACP\Storage\Directory;
use ACP\Storage\ListScreen\SerializerTypes;
use LogicException;

final class ListScreenRepositoryFactory {

	/**
	 * @var FileFactory
	 */
	private $file_factory;

	public function __construct( FileFactory $file_factory ) {
		$this->file_factory = $file_factory;
	}

	/**
	 * @param string     $path
	 * @param bool       $writable
	 * @param Rules|null $rules
	 *
	 * @return ListScreenRepository
	 */
	public function create( $path, $writable, Rules $rules = null ) {
		if ( ! is_string( $path ) || $path === '' ) {
			throw new LogicException( 'Expected string as path.' );
		}

		$file = $this->file_factory->create(
			SerializerTypes::PHP,
			new Directory( $path )
		);

		return new ListScreenRepository( $file, $writable, $rules );
	}

}