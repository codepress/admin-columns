<?php

declare(strict_types=1);

namespace AC\Admin\Colors;

final class StyleInjector
{

	private $color_reader;

	public function __construct(ColorReader $color_reader)
	{
		$this->color_reader = $color_reader;
	}

	public function inject_style(): void
	{
		$colors = [];

		foreach ($this->color_reader->find_all() as $color) {
			$colors[] = sprintf('--ac-color-%s: %s;', $color->get_name(), $color->get_color());
		}

		sort($colors, SORT_NATURAL);

		?>

		<!-- Admin Columns color variables for custom and shipped WordPress colors -->
		<style>
			:root {
			<?= "\t" . implode( "\n" . str_repeat( "\t", 4 ), $colors ) . "\n" ?>
			}
		</style>

		<?php
	}

}