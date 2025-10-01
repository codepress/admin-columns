<?php

declare(strict_types=1);

/** @var \AC\Deprecated\Hook $hook */
$hook = $this->hook;
$callbacks = $hook->get_callbacks();

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
                '<code class="acu-text-[12px]">' . $hook->get_name() . '</code>',
                '<strong>' . $hook->get_version() . '</strong>',
                '<code class="acu-text-[12px]">' . $hook->get_replacement() . '</code>'
            );

        else:
            printf(
                __('The action %s used on this website is deprecated since %s.', 'codepress-admin-columns'),
                '<code class="acu-text-[12px]">' . $hook->get_name() . '</code>',
                '<strong>' . $hook->get_version() . '</strong>'
            );

        endif; ?>

        <?php
        if ( ! empty($callbacks)) : ?>
			<div class="acu-bg-[#F1F5F9] acu-p-3 acu-rounded-lg acu-mt-2">
                <?php

                $deprecated_string = _x(
                    'This deprecated hook has %s with these callbacks:',
                    'callback usages',
                    'codepress-admin-columns'
                );
                $count_string = sprintf(
                    _n(
                        '%d usage',
                        '%d usages',
                        count($callbacks),
                        'codepress-admin-columns'
                    ),
                    count($callbacks)
                );
                $count_string = sprintf('<strong>%s</strong>', $count_string)
                ?>

                <?= sprintf($deprecated_string, $count_string); ?>
                <?= '<br/><code class="acu-bg-[transparent] acu-text-[12px]">' . implode(
                    '</code><br/><code class="acu-bg-[transparent] acu-text-[12px]">',
                    $callbacks
                ) . '</code>'; ?>

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