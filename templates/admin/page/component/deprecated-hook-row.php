<?php

declare(strict_types=1);

/** @var \AC\Deprecated\Hook $hook */
$hook = $this->hook;
$callbacks = $hook->get_callbacks();
$count = count($callbacks);

?>

<tr>
	<td class="acu-p-1 acu-px-3 acu-py-3 acu-align-top" colspan="1">
		<code class="acu-text-[12px]"><?= $hook->get_name() ?></code>
	</td>

	<td class="acu-p-1 acu-px-3 acu-py-3 acu-align-top acu-leading-5" style="width:100%" colspan="1">
		<?php
		if ($hook->has_replacement()) : ?>
			<?php
			$translation = $this->type === 'action'
					?
					__(
							'The action %s is replaced by %s since %s.',
							'codepress-admin-columns'
					)
					:
					__(
							'The filter %s is replaced by %s since %s.',
							'codepress-admin-columns'
					);

			printf(
					$translation,
					'<code class="acu-text-[12px]">' . $hook->get_name() . '</code>',
					'<code class="acu-text-[12px]">' . $hook->get_replacement() . '</code>',
					'<strong>' . $hook->get_version() . '</strong>'
			);

		else:

			echo $this->type === 'action'
					? sprintf(
							__(
									'The action %s has been removed since %s.',
									'codepress-admin-columns'
							),
							'<code class="acu-text-[12px]">' . $hook->get_name() . '</code>',
							'<strong>' . $hook->get_version() . '</strong>'
					)
					: sprintf(
							__(
									'The filter %s has been removed since %s.',
									'codepress-admin-columns'
							),
							'<code class="acu-text-[12px]">' . $hook->get_name() . '</code>',
							'<strong>' . $hook->get_version() . '</strong>'
					);

		endif; ?>

		<?php
		if ($count > 0) : ?>
			<div class="acu-bg-[#F1F5F9] acu-p-3 acu-rounded-lg acu-mt-2">
				<?php

				$count_string = sprintf(
						_n(
								'%d usage',
								'%d usages',
								$count,
								'codepress-admin-columns'
						),
						$count
				);

				if (1 === $count) {
					printf(
							_x(
									'This deprecated hook has %s on this site with this callback: %s',
									'callback usages',
									'codepress-admin-columns'
							),
							'<strong>' . $count_string . '</strong>',
							'<code class="acu-bg-[transparent] acu-text-[12px]">' . $callbacks[0] . '</code>'
					);
				} else {
					printf(
							_x(
									'This deprecated hook has %s on this site with these callbacks:',
									'callback usages',
									'codepress-admin-columns'
							),
							'<strong>' . $count_string . '</strong>'
					);
					?>
					<br/>
					<code class="acu-bg-[transparent] acu-text-[12px]">
						<?= implode(
								'</code><br/><code class="acu-bg-[transparent] acu-text-[12px]">',
								$callbacks
						); ?>
					</code>
					<?php
				}
				?>
			</div>
		<?php
		endif; ?>

	</td>
	<td class="acu-p-1 acu-px-3 acu-py-3 acu-text-nowrap acu-align-top" colspan="1">
		<?= sprintf(
				'<a href="%s" target="_blank">%s &raquo;</a>',
				$this->documentation_url,
				__('View documentation', 'codepress-admin-columns')
		)
		?>
	</td>
</tr>