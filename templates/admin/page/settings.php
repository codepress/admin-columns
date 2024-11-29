<?php
/**
 * @var Renderable $section
 */

use AC\Renderable;

// TODO remove
?>

<h1 class="screen-reader-text"><?= __('Settings', 'codepress-admin-columns'); ?></h1>


<main class="acu-flex acu-gap-4 acu-w-full">
	<div class="acu-bg-[white] acu-border acu-border-solid acu-border-ui-border acu-mb-[15px] acu-shadow acu-rounded-[10px] acu-border acu-border-solid acu-border-ui-border acu-mb-3 acu-flex-grow acu-max-w-[1520px]">
		<div class="acu-px-[20px] acu-pt-[20px] acu-pb-[20px] acu-border-0 acu-border-b acu-border-solid acu-border-ui-border">
			<h2 class="acu-my-[0] acu-text-2xl acu-font-normal">Integrations</h2> <span class="acu-pt-1 acu-block">Available integrations with popular plugins.</span>
		</div>
		<div class="acu-p-4 acu-mb-8" slot="body"><h3 class="acu-text-xl acu-font-light">Recommended Integrations</h3>
			<div class="acu-flex acu-gap-8 acu-flex-wrap acu-mb-8">
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/acf-v2.png" alt="Advanced Custom Fields" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>Advanced Custom Fields</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>Integrates ACF with Admin Columns. Display,
								inline- and bulk-edit, export, smart filter and sort your ACF contents on any admin list
								table.</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/advanced-custom-fields/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Enable Integration</strong>
							<span class="acu-justify-end"><div class="ac-toggle-v2 svelte-1qts9gw" style=""><span class="ac-toggle-v2__toggle svelte-1qts9gw"><input class="ac-toggle-v2__toggle__input svelte-1qts9gw" type="checkbox" value="off" id="ac9484c882-3e4e-3896-e321"> <span class="ac-toggle-v2__toggle__track svelte-1qts9gw"></span> <span class="ac-toggle-v2__toggle__thumb"></span></span> <label class="ac-toggle-v2__label" for="ac9484c882-3e4e-3896-e321"></label></div></span>
						</div>
					</div>
				</div>
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/mla.png" alt="Media Library Assistant" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>Media Library Assistant</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>The Media Library Assistant plugin from David
								Lingren provides several enhancements for managing the Media Library.</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/add-ons/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Enable Integration</strong>
							<span class="acu-justify-end"><div class="ac-toggle-v2 svelte-1qts9gw" style=""><span class="ac-toggle-v2__toggle svelte-1qts9gw"><input class="ac-toggle-v2__toggle__input svelte-1qts9gw" type="checkbox" value="off" id="acf57e8b16-0da5-49b2-8ff8"> <span class="ac-toggle-v2__toggle__track svelte-1qts9gw"></span> <span class="ac-toggle-v2__toggle__thumb"></span></span> <label class="ac-toggle-v2__label" for="acf57e8b16-0da5-49b2-8ff8"></label></div></span>
						</div>
					</div>
				</div>
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/woocommerce-icon.png" alt="WooCommerce" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>WooCommerce</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>Integrates WooCommerce with Admin Columns.
								Display, inline- and bulk-edit, smart filter and sort your Products, Variations, Orders
								and Customers</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/woocommerce-columns/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Enable Integration</strong>
							<span class="acu-justify-end"><div class="ac-toggle-v2 svelte-1qts9gw" style=""><span class="ac-toggle-v2__toggle svelte-1qts9gw"><input class="ac-toggle-v2__toggle__input svelte-1qts9gw" type="checkbox" value="off" id="acc0c9efb4-ec90-4f7f-a320"> <span class="ac-toggle-v2__toggle__track svelte-1qts9gw"></span> <span class="ac-toggle-v2__toggle__thumb"></span></span> <label class="ac-toggle-v2__label" for="acc0c9efb4-ec90-4f7f-a320"></label></div></span>
						</div>
					</div>
				</div>
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/yoast-seo.png" alt="Yoast SEO" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>Yoast SEO</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>Integrates Yoast SEO with Admin Columns.
								Display, inline- and bulk-edit, export, smart filter and sort your Yoast SEO contents on
								any admin list table.</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/yoast-seo/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Enable Integration</strong>
							<span class="acu-justify-end"><div class="ac-toggle-v2 svelte-1qts9gw" style=""><span class="ac-toggle-v2__toggle svelte-1qts9gw"><input class="ac-toggle-v2__toggle__input svelte-1qts9gw" type="checkbox" value="off" id="accb9544b0-103e-1d8d-a2b8"> <span class="ac-toggle-v2__toggle__track svelte-1qts9gw"></span> <span class="ac-toggle-v2__toggle__thumb"></span></span> <label class="ac-toggle-v2__label" for="accb9544b0-103e-1d8d-a2b8"></label></div></span>
						</div>
					</div>
				</div>
			</div>
			<h3 class="acu-text-xl acu-font-light">Available Integrations</h3>
			<div class="acu-flex acu-gap-8 acu-flex-wrap">
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/buddypress.png" alt="BuddyPress" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>BuddyPress</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>Integrates BuddyPress with Admin Columns.
								Display, inline- and bulk-edit, export, smart filter and sort your BuddyPress data
								fields on the Users page.</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/buddypress/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Plugin not detected</strong>
							<span class="acu-justify-end"><span class="acui-icon acui-icon--md acu-text-[#D63638] svelte-1e1ls94"><span class="dashicons dashicons-no-alt svelte-1e1ls94"></span></span></span>
						</div>
					</div>
				</div>
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/events-calendar.png" alt="Events Calendar" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>Events Calendar</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>Integrates Events Calendar with Admin Columns.
								Display, inline- and bulk-edit, export, smart filter and sort your Events, Organizers
								and Venues.</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/events-calendar/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Plugin not detected</strong>
							<span class="acu-justify-end"><span class="acui-icon acui-icon--md acu-text-[#D63638] svelte-1e1ls94"><span class="dashicons dashicons-no-alt svelte-1e1ls94"></span></span></span>
						</div>
					</div>
				</div>
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/gravityforms.svg" alt="Gravity Forms" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>Gravity Forms</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>Integrates Gravity Forms with Admin Columns.
								Display, inline- and bulk-edit, export, smart filter and sort your Gravity Forms
								Entries.</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/gravity-forms/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Plugin not detected</strong>
							<span class="acu-justify-end"><span class="acui-icon acui-icon--md acu-text-[#D63638] svelte-1e1ls94"><span class="dashicons dashicons-no-alt svelte-1e1ls94"></span></span></span>
						</div>
					</div>
				</div>
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/jetengine.svg?v3" alt="JetEngine" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>JetEngine</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>Integrates JetEngine with Admin Columns.
								Display, inline- and bulk-edit, export, smart filter and sort your JetEngine contents on
								any admin list table.</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/jetengine/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Plugin not detected</strong>
							<span class="acu-justify-end"><span class="acui-icon acui-icon--md acu-text-[#D63638] svelte-1e1ls94"><span class="dashicons dashicons-no-alt svelte-1e1ls94"></span></span></span>
						</div>
					</div>
				</div>
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/pods.png" alt="Pods" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>Pods</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>Integrates Pods with Admin Columns. Display,
								inline- and bulk-edit, export, smart filter and sort your Pods contents on any admin
								list table.</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/pods/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Plugin not detected</strong>
							<span class="acu-justify-end"><span class="acui-icon acui-icon--md acu-text-[#D63638] svelte-1e1ls94"><span class="dashicons dashicons-no-alt svelte-1e1ls94"></span></span></span>
						</div>
					</div>
				</div>
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/toolset-types.png" alt="Toolset Types" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>Toolset Types</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>Integrates Toolset Types with Admin Columns.
								Display, inline- and bulk-edit, export, smart filter and sort your Toolset Types
								contents on any admin list table.</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/toolset-types/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Plugin not detected</strong>
							<span class="acu-justify-end"><span class="acui-icon acui-icon--md acu-text-[#D63638] svelte-1e1ls94"><span class="dashicons dashicons-no-alt svelte-1e1ls94"></span></span></span>
						</div>
					</div>
				</div>
				<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
					<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
						<img src="http://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/addons/metabox.svg" alt="Meta Box" class="acu-max-h-[80px] acu-max-w-[80%]">
					</div>
					<div class="acu-p-4"><h3>Meta Box</h3>
						<div class="acu-h-[120px] acu-overflow-hidden"><p>Integrates Meta Box with Admin Columns.
								Display, inline- and bulk-edit, export, smart filter and sort your Meta Box contents on
								any admin list table.</p></div>
						<div class="acu-mb-4">
							<a href="https://www.admincolumns.com/meta-box-integration/" target="_blank" class="acu-no-underline">Learn
								more
								»</a></div>
						<hr class="acu-mb-3">
						<div class="acu-flex acu-pt-2"><strong class="acu-flex-grow ">Plugin not detected</strong>
							<span class="acu-justify-end"><span class="acui-icon acui-icon--md acu-text-[#D63638] svelte-1e1ls94"><span class="dashicons dashicons-no-alt svelte-1e1ls94"></span></span></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>


<div class="ac-settings">
    <?php
    foreach ($this->sections as $section) : ?>
        <?= $section->render(); ?>
    <?php
    endforeach; ?>
</div>