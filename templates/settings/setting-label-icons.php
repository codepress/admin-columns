<table class="ac-column-setting<?php echo $this->name ? esc_attr( ' ac-column-setting--' . $this->name ) : ''; ?>" data-setting="<?php echo esc_attr( $this->name ); ?>">
	<tr>
		<td class="col-label">
			<label for="<?php echo esc_attr( $this->for ); ?>">

				<span class="label<?php echo esc_attr( $this->tooltip ? ' tooltip' : '' ); ?>">
					<?php echo $this->label; ?>
				</span>

				<?php if ( $this->tooltip ) : ?>
					<div class="tooltip">
						<?php echo $this->tooltip; ?>
					</div>
				<?php endif; ?>

			</label>
		</td>
		<td class="col-input">
			<div class="ac-setting-input ac-setting-input-label">
				<div class="ac-modal -setting -iconpicker">
					<div class="ac-modal__dialog">
						<div class="ac-modal__dialog__header">

							<?php _e( 'Select Icon', 'codepress-admin-columns' ); ?>
							<button class="ac-modal__dialog__close" data-dismiss="modal">
								<span class="dashicons dashicons-no"></span>
							</button>

						</div>

						<div class="ac-modal__dialog__content">
							<?php
							$groups = array(
								'Admin Menu'             => array( "menu", "admin-site", "dashboard", "admin-post", "admin-media", "admin-links", "admin-page", "admin-comments", "admin-appearance", "admin-plugins", "admin-users", "admin-tools", "admin-settings", "admin-network", "admin-home", "admin-generic", "admin-collapse", "filter", "admin-customizer", "admin-multisite" ),
								'Welcome Screen'         => array( "welcome-write-blog", "welcome-add-page", "welcome-view-site", "welcome-widgets-menus", "welcome-comments", "welcome-learn-more" ),
								'Post Formats'           => array( "format-aside", "format-image", "format-gallery", "format-video", "format-status", "format-quote", "format-chat", "format-audio", "camera", "images-alt", "images-alt2", "video-alt", "video-alt2", "video-alt3" ),
								'Media'                  => array( "media-archive", "media-audio", "media-code", "media-default", "media-document", "media-interactive", "media-spreadsheet", "media-text", "media-video", "playlist-audio", "playlist-video", "controls-play", "controls-pause", "controls-forward", "controls-skipforward", "controls-back", "controls-skipback", "controls-repeat", "controls-volumeon", "controls-volumeoff" ),
								'Image Editing'          => array( "image-crop", "image-rotate", "image-rotate-left", "image-rotate-right", "image-flip-vertical", "image-flip-horizontal", "image-filter", "undo", "redo" ),
								'TinyMCE'                => array( "editor-bold", "editor-italic", "editor-ul", "editor-ol", "editor-quote", "editor-alignleft", "editor-aligncenter", "editor-alignright", "editor-insertmore", "editor-spellcheck", "editor-expand", "editor-contract", "editor-kitchensink", "editor-underline", "editor-justify", "editor-textcolor", "editor-paste-word", "editor-paste-text", "editor-removeformatting", "editor-video", "editor-customchar", "editor-outdent", "editor-indent", "editor-help", "editor-strikethrough", "editor-unlink", "editor-rtl", "editor-break", "editor-code", "editor-paragraph", "editor-table" ),
								'Posts Screen'           => array( "align-left", "align-right", "align-center", "align-none", "lock", "unlock", "calendar", "calendar-alt", "visibility", "hidden", "post-status", "edit", "trash", "sticky" ),
								'Sorting'                => array( "external", "arrow-up", "arrow-down", "arrow-right", "arrow-left", "arrow-up-alt", "arrow-down-alt", "arrow-right-alt", "arrow-left-alt", "arrow-up-alt2", "arrow-down-alt2", "arrow-right-alt2", "arrow-left-alt2", "sort", "leftright", "randomize", "list-view", "exerpt-view", "grid-view", "move" ),
								'Social'                 => array( "share", "share-alt", "share-alt2", "twitter", "rss", "email", "email-alt", "facebook", "facebook-alt", "googleplus", "networking" ),
								'WordPress.org Specific' => array( "hammer", "art", "migrate", "performance", "universal-access", "universal-access-alt", "tickets", "nametag", "clipboard", "heart", "megaphone", "schedule" ),
								'Products'               => array( "wordpress", "wordpress-alt", "pressthis", "update", "screenoptions", "info", "cart", "feedback", "cloud", "translation" ),
								'Taxonomies'             => array( "tag", "category" ),
								'Widgets'                => array( "archive", "tagcloud", "text" ),
								'Notification'           => array( "yes", "no", "no-alt", "plus", "plus-alt", "minus", "dismiss", "marker", "star-filled", "star-half", "star-empty", "flag", "warning" ),
								'Misc'                   => array( "location", "location-alt", "vault", "shield", "shield-alt", "sos", "search", "slides", "analytics", "chart-pie", "chart-bar", "chart-line", "chart-area", "groups", "businessman", "id", "id-alt", "products", "awards", "forms", "testimonial", "portfolio", "book", "book-alt", "download", "upload", "backup", "clock", "lightbulb", "microphone", "desktop", "laptop", "tablet", "smartphone", "phone", "index-card", "carrot", "building", "store", "album", "palmtree", "tickets-alt", "money", "smiley", "thumbs-up", "thumbs-down", "layout", "paperclip" ),
							);
							?>

							<div class="ac-ipicker__icons">
								<?php foreach ( $groups as $label => $icons ): ?>
									<h3 class="ac-ipicker__icons__group"><?php echo $label; ?></h3>

									<?php foreach ( $icons as $icon ): ?>
										<div class="ac-ipicker__icon" data-dashicon="<?php echo $icon; ?>" tabindex="0">
											<span class="dashicons dashicons-<?php echo $icon; ?>"></span>
										</div>
									<?php endforeach; ?>
								
								<?php endforeach; ?>
							</div>

						</div>

						<div class="ac-modal__dialog__footer">
							<div class="ac-ipicker__selection"></div>
							<button class="button button-primary" data-action="submit"><?php _e( 'Select' ); ?></button>
						</div>

					</div>

				</div>

				<div class="ac-setting-input__container">
					<?php echo $this->setting; ?>
					<button class="ac-setting-label-icon" title="Choose icon"><span class="dashicons dashicons-format-image"></span></button>
				</div>

			</div>
			<?php

			if ( is_array( $this->sections ) ) {
				foreach ( $this->sections as $section ) {
					echo $section;
				}
			}

			?>
		</td>
	</tr>
</table>