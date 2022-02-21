<?php

namespace AC\Table\InlineStyle;

use AC\ColumnSize\ListStorage;
use AC\ColumnSize\UserStorage;
use AC\ListScreen;
use AC\Renderable;
use AC\Type\ColumnWidth;

class ColumnSize implements Renderable {

	/**
	 * @var ListScreen
	 */
	private $list_screen;

	/**
	 * @var ListStorage
	 */
	private $list_storage;

	/**
	 * @var UserStorage
	 */
	private $user_storage;

	public function __construct( ListScreen $list_screen, ListStorage $list_storage, UserStorage $user_storage ) {
		$this->list_screen = $list_screen;
		$this->list_storage = $list_storage;
		$this->user_storage = $user_storage;
	}

	private function render_style( $column_name, ColumnWidth $column_width, $type ) {
		$css_width = $column_width->get_value() . $column_width->get_unit();

		$css = sprintf(
			'.ac-%s .wrap table th.column-%s { width: %s !important; }',
			esc_attr( $this->list_screen->get_key() ),
			esc_attr( $column_name ),
			$css_width
		);

		$css .= sprintf(
			'body.acp-overflow-table.ac-%s .wrap th.column-%s { min-width: %s !important; }',
			esc_attr( $this->list_screen->get_key() ),
			esc_attr( $column_name ),
			$css_width
		);

		$id = sprintf(
			'ac-column-size-%s-%s',
			$type,
			$column_name
		);

		ob_start();
		?>
		<style id="<?= $id ?>">
			@media screen and (min-width: 783px) {
			<?= $css ?>
			}
		</style>
		<?php
		return ob_get_clean();
	}

	public function render() {
		if ( ! $this->list_screen->get_settings() ) {
			return '';
		}

		$html = '';

		foreach ( $this->list_screen->get_columns() as $column ) {
			$width = $this->list_storage->get( $this->list_screen, $column->get_name() );
			if ( $width ) {
				$html .= $this->render_style( $column->get_name(), $width, 'list' );
			}

			$width = $this->user_storage->get( $this->list_screen->get_id(), $column->get_name() );
			if ( $width ) {
				$html .= $this->render_style( $column->get_name(), $width, 'user' );
			}
		}

		return $html;
	}

}