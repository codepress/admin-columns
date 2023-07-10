<?php

namespace AC\Table;

use AC;
use AC\Registerable;

final class TableFormView implements Registerable {

	const PARAM_ACTION = 'ac-actions-form';

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $html;

	/**
	 * @var int
	 */
	private $priority;

	public function __construct( $type, $html, $priority = null ) {
		if ( null === $priority ) {
			$priority = 10;
		}

		$this->type = (string) $type;
		$this->html = (string) $html;
		$this->priority = (int) $priority;
	}

	public function register(): void
    {

		switch ( $this->type ) {
			case 'post':
				add_action( 'restrict_manage_posts', [ $this, 'render' ], $this->priority );

				break;
			case'user':
				add_action( 'restrict_manage_users', [ $this, 'render' ], $this->priority );

				break;
			case 'comment':
				add_action( 'restrict_manage_comment', [ $this, 'render' ], $this->priority );

				break;
		}
	}

	public function render() {
		echo $this->html;
	}

}