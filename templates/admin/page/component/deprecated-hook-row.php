<?php

declare(strict_types=1);

/** @var \AC\Deprecated\Hook $hook */
$hook = $this->hook;
$callbacks = $hook->get_callbacks();

?>

<tr>
	<td class="acu-p-1 acu-px-3 acu-py-3 acu-align-top" colspan="1">
		<code><?= $hook->get_name() ?></code>
	</td>
	<td class="acu-p-1 acu-px-3 acu-py-3 acu-align-top" colspan="1">
        <?php
        if ($hook->has_replacement()) : ?>
			<code><?= $hook->get_replacement() ?></code>
        <?php
        else: ?>
			<code><?php
                __('Removed', 'codepress-admin-columns') ?></code>
        <?php
        endif ?>
	</td>
	<td class="acu-p-1 acu-px-3 acu-py-3 acu-align-top acu-leading-5" style="width:100%" colspan="1">
        <?php
        if ($hook->has_replacement()) : ?>
            <?php
            $translation = $this->type === 'action'
                ?
                __(
                    'The action %s used on this website is deprecated since %s and replaced by %s.',
                    'codepress-admin-columns'
                )
                :
                __(
                    'The filter %s used on this website is deprecated since %s and replaced by %s.',
                    'codepress-admin-columns'
                );

            printf(
                $translation,
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