<?php

declare(strict_types=1);

/** @var \AC\Deprecated\Hook $hook */
$hook = $this->hook;
$callbacks = $hook->get_callbacks();
?>
<div class="acu-mb-3">
    <?=
    sprintf(
        __('The action %s used on this website is deprecated since %s.', 'codepress-admin-columns'),
        '<code>' . $hook->get_name() . '</code>',
        '<strong>' . $hook->get_version() . '</strong>'
    )
    ?>

    <?php
    if ( ! empty($callbacks)) : ?>
		<br>
		<span class="acu-pl-4">

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
		</span>

    <?php
    endif; ?>

    <?= sprintf(
        '<a href="%s" target="_blank">%s &raquo;</a>',
        $this->documentation_url,
        __('View documentation', 'codepress-admin-columns')
    )
    ?>
</div>
