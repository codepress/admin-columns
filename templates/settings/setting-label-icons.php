<table class="ac-column-setting<?php
echo $this->name ? esc_attr(' ac-column-setting--' . $this->name) : ''; ?>" data-setting="<?php
echo esc_attr($this->name); ?>">
	<tr>
		<td class="col-label">
			<label for="<?php
            echo esc_attr($this->for); ?>">

				<span class="label<?php
                echo esc_attr($this->tooltip ? ' tooltip' : ''); ?>">
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

			</label>
		</td>
		<td class="col-input">
			<div class="ac-setting-input ac-setting-input-label">
				<div class="ac-modal -setting -iconpicker">
					<div class="ac-modal__dialog">
						<div class="ac-modal__dialog__header">

                            <?php
                            _e('Select Icon', 'codepress-admin-columns'); ?>
							<button class="ac-modal__dialog__close" data-dismiss="modal">
								<span class="dashicons dashicons-no-alt"></span>
							</button>

						</div>

						<div class="ac-modal__dialog__content">
                            <?php
                            $groups = [
                                __('Admin Menu', 'codepress-admin-columns') => [
                                    'menu',
                                    'menu-alt',
                                    'menu-alt2',
                                    'menu-alt3',
                                    'admin-site',
                                    'admin-site-alt',
                                    'admin-site-alt2',
                                    'admin-site-alt3',
                                    'dashboard',
                                    'admin-post',
                                    'admin-media',
                                    'admin-links',
                                    'admin-page',
                                    'admin-comments',
                                    'admin-appearance',
                                    'admin-plugins',
                                    'plugins-checked',
                                    'admin-users',
                                    'admin-tools',
                                    'admin-settings',
                                    'admin-network',
                                    'admin-home',
                                    'admin-generic',
                                    'admin-collapse',
                                    'filter',
                                    'admin-customizer',
                                    'admin-multisite',
                                ],
                                __('Welcome Screen', 'codepress-admin-columns') => [
                                    'welcome-write-blog',
                                    'welcome-add-page',
                                    'welcome-view-site',
                                    'welcome-widgets-menus',
                                    'welcome-comments',
                                    'welcome-learn-more',
                                ],
                                __('Post Formats', 'codepress-admin-columns') => [
                                    'format-aside',
                                    'format-image',
                                    'format-gallery',
                                    'format-video',
                                    'format-status',
                                    'format-quote',
                                    'format-chat',
                                    'format-audio',
                                    'camera',
                                    'camera-alt',
                                    'images-alt',
                                    'images-alt2',
                                    'video-alt',
                                    'video-alt2',
                                    'video-alt3',
                                ],
                                __('Media', 'codepress-admin-columns') => [
                                    'media-archive',
                                    'media-audio',
                                    'media-code',
                                    'media-default',
                                    'media-document',
                                    'media-interactive',
                                    'media-spreadsheet',
                                    'media-text',
                                    'media-video',
                                    'playlist-audio',
                                    'playlist-video',
                                    'controls-play',
                                    'controls-pause',
                                    'controls-forward',
                                    'controls-skipforward',
                                    'controls-back',
                                    'controls-skipback',
                                    'controls-repeat',
                                    'controls-volumeon',
                                    'controls-volumeoff',
                                ],
                                __('Image Editing', 'codepress-admin-columns') => [
                                    'image-crop',
                                    'image-rotate',
                                    'image-rotate-left',
                                    'image-rotate-right',
                                    'image-flip-vertical',
                                    'image-flip-horizontal',
                                    'image-filter',
                                    'undo',
                                    'redo',
                                ],
                                __('Databases', 'codepress-admin-columns') => [
                                    'database-add',
                                    'database',
                                    'database-export',
                                    'database-import',
                                    'database-remove',
                                    'database-view',
                                ],
                                __('Block Editor', 'codepress-admin-columns') => [
                                    'align-full-width',
                                    'align-pull-left',
                                    'align-pull-right',
                                    'align-wide',
                                    'block-default',
                                    'button',
                                    'cloud-saved',
                                    'cloud-upload',
                                    'columns',
                                    'cover-image',
                                    'ellipsis',
                                    'embed-audio',
                                    'embed-generic',
                                    'embed-photo',
                                    'embed-post',
                                    'embed-video',
                                    'exit',
                                    'heading',
                                    'html',
                                    'info-outline',
                                    'insert',
                                    'insert-after',
                                    'insert-before',
                                    'remove',
                                    'saved',
                                    'shortcode',
                                    'table-col-after',
                                    'table-col-before',
                                    'table-col-delete',
                                    'table-row-after',
                                    'table-row-before',
                                    'table-row-delete',
                                ],
                                __('TinyMCE', 'codepress-admin-columns') => [
                                    'editor-bold',
                                    'editor-italic',
                                    'editor-ul',
                                    'editor-ol',
                                    'editor-ol-rtl',
                                    'editor-quote',
                                    'editor-alignleft',
                                    'editor-aligncenter',
                                    'editor-alignright',
                                    'editor-insertmore',
                                    'editor-spellcheck',
                                    'editor-expand',
                                    'editor-contract',
                                    'editor-kitchensink',
                                    'editor-underline',
                                    'editor-justify',
                                    'editor-textcolor',
                                    'editor-paste-word',
                                    'editor-paste-text',
                                    'editor-removeformatting',
                                    'editor-video',
                                    'editor-customchar',
                                    'editor-outdent',
                                    'editor-indent',
                                    'editor-help',
                                    'editor-strikethrough',
                                    'editor-unlink',
                                    'editor-rtl',
                                    'editor-ltr',
                                    'editor-break',
                                    'editor-code',
                                    'editor-paragraph',
                                    'editor-table',
                                ],
                                __('Posts Screen', 'codepress-admin-columns') => [
                                    'align-left',
                                    'align-right',
                                    'align-center',
                                    'align-none',
                                    'lock',
                                    'unlock',
                                    'calendar',
                                    'calendar-alt',
                                    'visibility',
                                    'hidden',
                                    'post-status',
                                    'edit',
                                    'trash',
                                    'sticky',
                                ],
                                __('Sorting', 'codepress-admin-columns') => [
                                    'external',
                                    'arrow-up',
                                    'arrow-down',
                                    'arrow-right',
                                    'arrow-left',
                                    'arrow-up-alt',
                                    'arrow-down-alt',
                                    'arrow-right-alt',
                                    'arrow-left-alt',
                                    'arrow-up-alt2',
                                    'arrow-down-alt2',
                                    'arrow-right-alt2',
                                    'arrow-left-alt2',
                                    'sort',
                                    'leftright',
                                    'randomize',
                                    'list-view',
                                    'excerpt-view',
                                    'grid-view',
                                    'move',
                                ],
                                __('Social', 'codepress-admin-columns') => [
                                    'share',
                                    'share-alt',
                                    'share-alt2',
                                    'rss',
                                    'email',
                                    'email-alt',
                                    'email-alt2',
                                    'networking',
                                    'amazon',
                                    'facebook',
                                    'facebook-alt',
                                    'google',
                                    'instagram',
                                    'linkedin',
                                    'pinterest',
                                    'podio',
                                    'reddit',
                                    'spotify',
                                    'twitch',
                                    'twitter',
                                    'twitter-alt',
                                    'whatsapp',
                                    'xing',
                                    'youtube',
                                ],
                                __('WordPress.org', 'codepress-admin-columns') => [
                                    'hammer',
                                    'art',
                                    'migrate',
                                    'performance',
                                    'universal-access',
                                    'universal-access-alt',
                                    'tickets',
                                    'nametag',
                                    'clipboard',
                                    'heart',
                                    'megaphone',
                                    'schedule',
                                    'tide',
                                    'rest-api',
                                    'code-standards',
                                ],
                                __('Buddicons', 'codepress-admin-columns') => [
                                    'buddicons-activity',
                                    'buddicons-bbpress-logo',
                                    'buddicons-buddypress-logo',
                                    'buddicons-community',
                                    'buddicons-forums',
                                    'buddicons-friends',
                                    'buddicons-groups',
                                    'buddicons-pm',
                                    'buddicons-replies',
                                    'buddicons-topics',
                                    'buddicons-tracking',
                                ],
                                __('Products', 'codepress-admin-columns') => [
                                    'wordpress',
                                    'wordpress-alt',
                                    'pressthis',
                                    'update',
                                    'update-alt',
                                    'screenoptions',
                                    'info',
                                    'cart',
                                    'feedback',
                                    'cloud',
                                    'translation',
                                ],
                                __('Taxonomies', 'codepress-admin-columns') => ['tag', 'category',],
                                __('Widgets', 'codepress-admin-columns') => ['archive', 'tagcloud', 'text',],
                                __('Notifications', 'codepress-admin-columns') => [
                                    'bell',
                                    'yes',
                                    'yes-alt',
                                    'no',
                                    'no-alt',
                                    'plus',
                                    'plus-alt',
                                    'plus-alt2',
                                    'minus',
                                    'dismiss',
                                    'marker',
                                    'star-filled',
                                    'star-half',
                                    'star-empty',
                                    'flag',
                                    'warning',
                                ],
                                __('Miscellaneous', 'codepress-admin-columns') => [
                                    'location',
                                    'location-alt',
                                    'vault',
                                    'shield',
                                    'shield-alt',
                                    'sos',
                                    'search',
                                    'slides',
                                    'text-page',
                                    'analytics',
                                    'chart-pie',
                                    'chart-bar',
                                    'chart-line',
                                    'chart-area',
                                    'groups',
                                    'businessman',
                                    'businesswoman',
                                    'businessperson',
                                    'id',
                                    'id-alt',
                                    'products',
                                    'awards',
                                    'forms',
                                    'testimonial',
                                    'portfolio',
                                    'book',
                                    'book-alt',
                                    'download',
                                    'upload',
                                    'backup',
                                    'clock',
                                    'lightbulb',
                                    'microphone',
                                    'desktop',
                                    'laptop',
                                    'tablet',
                                    'smartphone',
                                    'phone',
                                    'index-card',
                                    'carrot',
                                    'building',
                                    'store',
                                    'album',
                                    'palmtree',
                                    'tickets-alt',
                                    'money',
                                    'money-alt',
                                    'smiley',
                                    'thumbs-up',
                                    'thumbs-down',
                                    'layout',
                                    'paperclip',
                                    'color-picker',
                                    'edit-large',
                                    'edit-page',
                                    'airplane',
                                    'bank',
                                    'beer',
                                    'calculator',
                                    'car',
                                    'coffee',
                                    'drumstick',
                                    'food',
                                    'fullscreen-alt',
                                    'fullscreen-exit-alt',
                                    'games',
                                    'hourglass',
                                    'open-folder',
                                    'pdf',
                                    'pets',
                                    'printer',
                                    'privacy',
                                    'superhero',
                                    'superhero-alt',
                                ],
                            ];
                            ?>

							<div class="ac-ipicker__icons">
                                <?php
                                foreach ($groups as $label => $icons): ?>
									<div class="ac-ipicker__group">
										<h3 class="ac-ipicker__icons__group__title"><?php
                                            echo $label; ?></h3>

                                        <?php
                                        foreach ($icons as $icon): ?>
											<div class="ac-ipicker__icon" data-dashicon="<?php
                                            echo $icon; ?>" tabindex="0">
												<span class="dashicons dashicons-<?php
                                                echo $icon; ?>"></span>
											</div>
                                        <?php
                                        endforeach; ?>
									</div>
                                <?php
                                endforeach; ?>
							</div>

						</div>

						<div class="ac-modal__dialog__footer">
							<input type="search" placeholder="Search"/>
							<div class="ac-ipicker__selection"></div>
							<button class="button button-primary" data-action="submit"><?php
                                _e('Select'); ?></button>
						</div>

					</div>

				</div>

				<div class="ac-setting-input__container">
                    <?php
                    echo $this->setting; ?>
					<button class="ac-setting-label-icon" title="Choose icon">
						<span class="dashicons dashicons-format-image"></span></button>
				</div>

			</div>
            <?php

            if (is_array($this->sections)) {
                foreach ($this->sections as $section) {
                    echo $section;
                }
            }

            ?>
		</td>
	</tr>
</table>