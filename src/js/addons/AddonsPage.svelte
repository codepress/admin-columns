<script lang="ts">
    import AdminHeaderBar from "../components/AdminHeaderBar.svelte";
    import AcPanel from "ACUi/acui-panel/AcPanel.svelte";
    import AcPanelHeader from "ACUi/acui-panel/AcPanelHeader.svelte";
    import Integration from "./component/Integration.svelte";
    import {fetchIntegrations, IntegrationItem} from "./ajax/requests";

    export let pro: boolean;
    let integrations: IntegrationItem[] = [];
    let recommended: IntegrationItem[] = [];
    let available: IntegrationItem[] = [];


    fetchIntegrations().then(r => {
        integrations = r.data.data.integrations

        recommended = integrations.filter(i => i.plugin_active)
        available = integrations.filter(i => !i.plugin_active)
    })

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
				{#if recommended.length > 0}
					<h3 class="acu-text-xl acu-font-light">Recommended Integrations</h3>
					<div class="acu-flex acu-gap-8 acu-flex-wrap acu-mb-8">
						{#each recommended as integration }
							<Integration {integration} isPro={pro}/>
						{/each}
					</div>
				{/if}

				{#if available.length > 0}
					<h3 class="acu-text-xl acu-font-light">Available Integrations</h3>
					<div class="acu-flex acu-gap-8 acu-flex-wrap">
						{#each available as integration }
							<Integration {integration} isPro={pro}/>
						{/each}
					</div>
				{/if}
			</div>

		</AcPanel>
	</main>

</div>