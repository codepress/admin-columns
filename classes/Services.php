<?php
declare( strict_types=1 );

namespace AC;

class Services {

	private $services;

	public function __construct( array $services = [] ) {
		$this->services = $services;
	}

	public function add( Registerable $service ): void {
		$this->services[] = $service;
	}

	public function register(): void {
		array_map( [ $this, 'register_service' ], $this->services );
	}

	private function register_service( Registerable $service ): void {
		$service->register();
	}

}