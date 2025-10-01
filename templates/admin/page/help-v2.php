<?php

use AC\View; ?>
<div class="acu-mx-[50px] acu-pt-[70px]">
	<div class="acu-hidden">
		<hr class="wp-header-end acu-hidden">
	</div>
	<main class="acu-flex acu-gap-4 acu-w-full">
		<div class="acu-bg-[white] acu-border acu-border-solid acu-border-ui-border acu-mb-[15px] acu-shadow acu-rounded-[10px] acu-flex-grow acu-max-w-[1200px]">
			<div class="acu-px-[20px] acu-pt-[20px] acu-pb-[20px] acu-border-0 acu-border-b acu-border-solid acu-border-ui-border">
				<h2 class="acu-my-[0] acu-text-2xl acu-font-normal">
                    <?= __('Help', 'codepress-admin-columns') ?>
				</h2>
				<span class="acu-pt-1 acu-block">
                    <?php
                    printf(
                        __(
                            'This site is using some actions or filters that have changed. Please read %s to resolve them.',
                            'codepress-admin-columns'
                        ),
                        sprintf(
                            '<a href="%s" target="_blank">%s</a>',
                            $this->documentation_url,
                            __('our documentation', 'codepress-admin-columns')
                        )
                    );
                    ?>

                </span>
			</div>
			<div>
				<table class="acui-table">
					<thead>
					<tr>
						<th class="acu-p-1 acu-px-3 acu-py-3 acu-text-left">Deprecated Hook</th>
						<th class="acu-p-1 acu-px-3 acu-py-3 acu-text-left">Replacement Hook</th>
						<th class="acu-p-1 acu-px-3 acu-py-3 acu-text-left">Description</th>
						<th class="acu-p-1 acu-px-3 acu-py-3 acu-text-left">Documentation</th>
					</tr>
					</thead>
					<tbody>
                    <?php
                    foreach ($this->deprecated_filters as $hook) {
                        $view = new View([
                            'hook'              => $hook,
                            'documentation_url' => $this->documentation_url,
                        ]);
                        $view->set_template('admin/page/component/deprecated-hook-row');
                        echo $view->render();
                    }
                    ?>

                    <?php
                    foreach ($this->deprecated_actions as $hook) {
                        $view = new View([
                            'hook'              => $hook,
                            'documentation_url' => $this->documentation_url,
                        ]);
                        $view->set_template('admin/page/component/deprecated-hook-row');
                        echo $view->render();
                    }
                    ?>


					</tbody>
				</table>
			</div>
		</div>
	</main>
</div>