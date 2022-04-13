<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class VideoDisplay extends Settings\Column {

	private $video_display;

	protected function define_options() {
		return [
			'video_display' => 'embed',
		];
	}

	public function create_view() {
		$setting = $this->create_element( 'select' )
		                ->set_options( [
			                'embed' => __( 'Embed', 'codepress-admin-columns' ),
			                'modal' => __( 'Pop Up', 'codepress-admin-columns' ),
		                ] );

		return new View( [
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $setting,
		] );
	}

	/**
	 * @return int
	 */
	public function get_video_display() {
		return $this->video_display;
	}

	/**
	 * @param int $video_display
	 *
	 * @return bool
	 */
	public function set_video_display( $video_display ) {
		$this->video_display = $video_display;

		return true;
	}

}