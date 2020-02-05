<?php

namespace AC\ListScreenRepository\Storage;

use AC\ListScreenRepository\Rules;
use ACP\ListScreenRepository\FileFactory;
use ACP\Storage\Directory;
use ACP\Storage\ListScreen\SerializerTypes;

final class ListScreenRepositoryFactory {

	/**
	 * @var FileFactory
	 */
	private $file_factory;

	public function __construct( FileFactory $file_factory ) {
		$this->file_factory = $file_factory;
	}

	/**
	 * @param string     $key
	 * @param Directory  $directory
	 * @param bool       $writable
	 * @param Rules|null $rules
	 *
	 * @return ListScreenRepository
	 */
	public function create( $key, Directory $directory, $writable, Rules $rules = null ) {
		$file = $this->file_factory->create(
			SerializerTypes::PHP,
			$directory
		);

		return new ListScreenRepository( $key, $file, $writable, $rules );
	}

}