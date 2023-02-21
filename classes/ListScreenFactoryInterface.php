<?php

namespace AC;

interface ListScreenFactoryInterface {

	public function create( string $key, array $settings ): ?ListScreen;

}