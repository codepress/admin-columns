<script lang="ts">
    import AcModal from "ACUi/AcModal.svelte";
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../../utils/global";
    import AcButton from "ACUi/element/AcButton.svelte";

    export let title: string = "";

    const config = getColumnSettingsConfig();
    const i18n = getColumnSettingsTranslation().pro.modal;

</script>


<AcModal visible className="acui2 -promotion" appendToBody on:close>
	<div slot="header">
		<h3 class="acu-my-[0]">{title}</h3>
	</div>
	<div slot="content">
		<p class="acu-pt-[0] acu-font-bold">{i18n.subtitle}</p>
		<ul>
			<li><span class="dashicons dashicons-yes"></span> {i18n.sort_filter}</li>
			<li class="acu-text-pink"><span class="dashicons dashicons-yes"></span> {i18n.search}</li>
			<li><span class="dashicons dashicons-yes"></span> {i18n.bulk_edit}</li>
			<li class="acu-text-pink"><span class="dashicons dashicons-yes"></span> {i18n.inline_edit}</li>
			<li><span class="dashicons dashicons-yes"></span> {i18n.export}</li>
			<li class="acu-text-pink"><span class="dashicons dashicons-yes"></span> {i18n.list_tables}</li>
			<li><span class="dashicons dashicons-yes"></span> {i18n.addons}</li>
		</ul>
	</div>
	<div slot="footer">
		<svg class="acu-absolute acu-right-[20px] acu-bottom-[20px] acu-w-[120px] acu-h-[200px]">
			<use xlink:href="{config.assets}/images/symbols.svg#zebra-thumbs-up"></use>
		</svg>

		<div class="acu-flex">
			<a href="{config.urls.upgrade}" target="_blank" class="button button-primary">
				{i18n.upgrade}
			</a>
		</div>
	</div>
</AcModal>
