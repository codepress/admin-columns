<?php

namespace AC\Admin\Section;

use AC\Admin\Section;
use AC\Renderable;
use AC\View;

class General extends Section {

	const NAME = 'general';

	/**
	 * @var Renderable[]
	 */
	private $options;

	public function __construct( array $options ) {
		parent::__construct( self::NAME );

		array_map( [ $this, 'add_option' ], $options );
	}

	public function add_option( Renderable $option ) {
		$this->options[] = $option;
	}

	public function render(): string
    {
		$form = new View( [
			'options' => $this->options,
		] );

		$form->set_template( 'admin/page/settings-section-general' );

		$view = new View( [
			'title'   => __( 'General Settings', 'codepress-admin-columns' ),
			'content' => $form->render(),
			'class'   => '-general',
		] );

		$view->set_template( 'admin/page/settings-section' );

		return $view->render();
	}

}