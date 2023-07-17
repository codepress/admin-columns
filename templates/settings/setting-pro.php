<table class="ac-column-setting ac-column-setting--pro <?php
echo $this->name ? esc_attr(' ac-column-setting--' . $this->name) : ''; ?>" data-setting="<?php
echo esc_attr($this->name); ?>">
	<tr>
		<td class="col-label">
			<label for="<?php
            echo esc_attr($this->for); ?>">
				<span class="label <?php
                echo esc_attr($this->tooltip ? 'tooltip' : ''); ?>">
					<?php
                    echo $this->label; ?>
				</span>

                <?php
                if ($this->tooltip) : ?>
					<div class="tooltip">
                        <?php
                        echo $this->tooltip; ?>
					</div>
                <?php
                endif; ?>

                <?php
                if ($this->instructions): ?>
                    <?php
                    $id = $this->name . uniqid(); ?>
					<a class="ac-pointer ac-column-setting__instructions" rel="p-instruction-<?= $id; ?>" data-pos="right" data-width="300">
						<img src="<?= esc_url(ac_get_url('assets/images/question.svg')) ?>" alt="?">
					</a>
					<template id="p-instruction-<?= $id; ?>">
                        <?= $this->instructions; ?>
					</template>
                <?php
                endif; ?>

			</label>
		</td>
		<td class="col-input">
            <?php
            if ($this->setting) : ?>
				<div class="ac-setting-input">
                    <?php
                    echo $this->setting; ?>
					<button class="acp-button" data-ac-modal="pro">
						PRO
					</button>
				</div>

            <?php
            endif; ?>
		</td>
	</tr>
</table>