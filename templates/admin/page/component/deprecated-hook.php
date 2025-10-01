<?php

declare(strict_types=1);

/** @var \AC\Deprecated\Hook $hook */
$hook = $this->hook;
$callbacks = $hook->get_callbacks();
?>
<div class="acu-mb-3">
	<p>
		<?php
		if ($hook->has_replacement()) : ?>
			<?php
			printf(
					__(
							'The action %s used on this website is deprecated since %s and replaced by %s.',
							'codepress-admin-columns'
					),
					'<code>' . $hook->get_name() . '</code>',
					'<strong>' . $hook->get_version() . '</strong>',
					'<code>' . $hook->get_replacement() . '</code>'
			);

		else:
			printf(
					__('The action %s used on this website is deprecated since %s.', 'codepress-admin-columns'),
					'<code>' . $hook->get_name() . '</code>',
					'<strong>' . $hook->get_version() . '</strong>'
			);

		endif; ?>
	</p>
	<?php
	if ( ! empty($callbacks)) : ?>
		<p>
			<?= sprintf(
					_n(
							'The callback used is %s.',
							'The callbacks used are %s.',
							count($callbacks),
							'codepress-admin-columns'
					),
					'<code>' . implode('</code>, <code>', $callbacks) . '</code>'
			);
			?>
		</p>

	<?php
	endif; ?>

	<?= sprintf(
			'<a href="%s" target="_blank">%s &raquo;</a>',
			$this->documentation_url,
			__('View documentation', 'codepress-admin-columns')
	)
	?>
</div>
