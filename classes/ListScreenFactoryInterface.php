<?php

namespace AC;

use WP_Screen;

interface ListScreenFactoryInterface {

	public function can_create( string $key ): bool;

	public function create( string $key, array $settings = [] ): ListScreen;

	public function can_create_by_wp_screen( WP_Screen $screen ): bool;

	public function create_by_wp_screen( WP_Screen $screen, array $settings = [] ): ListScreen;

}