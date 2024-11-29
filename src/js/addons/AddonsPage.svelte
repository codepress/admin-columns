<script lang="ts">
    import AdminHeaderBar from "../components/AdminHeaderBar.svelte";
    import AcPanel from "ACUi/acui-panel/AcPanel.svelte";
    import AcPanelHeader from "ACUi/acui-panel/AcPanelHeader.svelte";
    import Integrations from "./component/Integrations.svelte";
    import {fetchIntegrations, IntegrationItem} from "./ajax/requests";
    import {getAddonsTranslation} from "./global";

    export let pro: boolean;
    let integrations: IntegrationItem[] = [];
    let recommended: IntegrationItem[] = [];
    let available: IntegrationItem[] = [];
    let enabled: IntegrationItem[] = [];


    fetchIntegrations().then(r => {
        integrations = r.data.data.integrations

        enabled = integrations.filter(i => i.plugin_active && i.active)
        recommended = integrations.filter(i => i.plugin_active && !i.active)
        available = integrations.filter(i => !i.plugin_active)
    })

    const i18n = getAddonsTranslation();

</script>

<AdminHeaderBar title="Integrations"/>

<div class="acu-mx-[50px] acu-pt-[70px]">

	<div class=" acu-hidden">
		<hr class="wp-header-end acu-hidden">
	</div>

	<main class="acu-flex acu-gap-4 acu-w-full">
		<AcPanel classNames={['acu-mb-3','acu-flex-grow', 'acu-max-w-[1520px]']}>
			<AcPanelHeader slot="header" title="Integrations" type="h2"
					subtitle="Available integrations with popular plugins."
					border/>
			<div class="acu-p-4 acu-mb-8" slot="body">
				{#if enabled.length > 0}
					<Integrations integrations={enabled} pro={pro} title="{i18n.title.enabled}"/>
				{/if}
				{#if recommended.length > 0}
					<Integrations integrations={recommended} pro={pro} title="{i18n.title.recommended}"/>
				{/if}
				{#if available.length > 0}
					<Integrations integrations={available} pro={pro} title="{i18n.title.available}"/>
				{/if}
			</div>

		</AcPanel>
	</main>

</div>