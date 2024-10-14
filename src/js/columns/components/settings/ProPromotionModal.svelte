<script lang="ts">
    import AcModal from "ACUi/AcModal.svelte";
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../../utils/global";
    import AcButton from "ACUi/element/AcButton.svelte";

    export let title: string = "";

    const config = getColumnSettingsConfig();
    const i18n = getColumnSettingsTranslation().pro.modal;
    console.log( config.urls )
</script>


<AcModal visible className="acui2 -promotion" appendToBody on:close>
	<span slot="header">
		<h2>{title}</h2>
	</span>
	<div slot="content">
		<p class="acu-pt-[0] acu-font-bold">{i18n.subtitle}</p>
		<ul>
			<li>{i18n.sort_filter}</li>
			<li>{i18n.search}</li>
			<li>{i18n.bulk_edit}</li>
			<li>{i18n.inline_edit}</li>
			<li>{i18n.export}</li>
			<li>{i18n.list_tables}</li>
			<li>{i18n.addon}</li>
		</ul>
	</div>
	<div slot="footer" class="acu-flex acu-items-center">
		<div class="acu-flex-grow">
			<a href="{config.urls.upgrade}" target="_blank" class="button button-primary">
				{i18n.upgrade}
			</a>
		</div>
	</div>
</AcModal>
