<?php

namespace AC;

class ViewCollection implements Renderable {

	/**
	 * @var View[]
	 */
	private $views;

	public function __construct( array $views ) {
		$this->views = $views;
	}

	public function render(): string
    {
		$html = '';

		foreach ( $this->views as $view ) {
			$html .= $view->render();
		}

		return $html;
	}

}