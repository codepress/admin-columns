<?php

namespace AC;

use WP_Screen;

interface ListScreenFactoryInterface {

	public function create( string $key, array $settings = [] ): ?ListScreen;

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ?ListScreen;

}