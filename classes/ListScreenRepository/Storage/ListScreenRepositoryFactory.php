<?php /** @noinspection PhpMethodParametersCountMismatchInspection */

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


	// TODO David consider adding a path e.g. with_path() instead of Directory

	/**
	 * @param Directory  $directory
	 * @param bool       $writable
	 * @param Rules|null $rules
	 *
	 * @return ListScreenRepository
	 */
	public function create( Directory $directory, $writable, Rules $rules = null ) {
		$file = $this->file_factory->create(
			SerializerTypes::PHP,
			$directory
		);

		return new ListScreenRepository( $file, $writable, $rules );
	}

}