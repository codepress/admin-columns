<script lang="ts">
    import {IntegrationItem, toggleIntegrationStatus} from "../ajax/requests";
    import AcToggle from "ACUi/element/AcToggle.svelte";
    import AcButton from "ACUi/element/AcButton.svelte";
    import AcIcon from "ACUi/AcIcon.svelte";
    import {getAddonsConfig, getAddonsTranslation} from "../global";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";

    export let integration: IntegrationItem;
    export let isPro: boolean;

    let checked: boolean = integration.active;

    const handleStatusChange = () => {
        toggleIntegrationStatus({
            integration: integration.slug,
            status: checked
        }).then(response => {
            NotificationProgrammatic.open({
                type: "success",
                message: response.data.data
            });
        })
    }

    const addons = getAddonsConfig();
    const i18n = getAddonsTranslation();

</script>
<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[300px] acu-overflow-hidden">
	<div class="acu-bg-[#F1F5F9]">
		{#if integration.plugin_active}
			<div class="acu-pt-2 acu-pl-2">
				<span class="acu-inline-flex acu-items-center acu-gap-1 acu-bg-[#E6F9F1] acu-text-[#00875A] acu-text-[13px] acu-font-semibold acu-rounded-full acu-px-3 acu-py-1 acu-border acu-border-solid acu-border-[#B7E4CF]">
					<AcIcon icon="yes" pack="dashicons" customClass="acu-text-[#00875A]"/>
					{i18n.plugin_detected}
				</span>
			</div>
		{/if}
		<a href="{integration.external_link}" class="acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
			<img src="{addons.asset_location}/{integration.plugin_logo}" alt="{integration.title}"
				class="acu-max-h-[80px] acu-max-w-[80%]"/>
		</a>
	</div>
	<div class="acu-p-4">
		<h3>{integration.title}</h3>
		<div class="acu-h-[140px] acu-overflow-hidden">
			<p>{integration.description}</p>
		</div>
		<hr class="acu-mb-3"/>

		{#if isPro }
			{#if integration.plugin_active}
				<div class="acu-flex acu-pt-2">
					<strong class="acu-flex-grow ">{i18n.enable_integration}</strong>
					<span class="acu-justify-end">
				<AcToggle bind:checked={checked} on:input={handleStatusChange}/>
				</span>
				</div>
			{:else}
				<div class="acu-flex acu-pt-2">
					<strong class="acu-flex-grow ">{i18n.plugin_not_detected}</strong>
					<span class="acu-justify-end">
						<AcIcon icon="no-alt" pack="dashicons" customClass="acu-text-[#D63638]"/>
					</span>
				</div>
			{/if}
		{:else}
			{#if integration.plugin_active}
				<div class="acu-flex acu-flex-nowrap acu-gap-2 acu-py-2">
					<AcButton type="pink" label="{i18n.buy_now}"
						href={addons.buy_url} target="_blank" customClass="acu-text-center acu-whitespace-nowrap"/>
					<AcButton type="default" label="{i18n.learn_more}"
						href={integration.external_link} target="_blank" customClass="acu-text-center acu-whitespace-nowrap"/>
				</div>
			{:else}
				<div class="acu-flex acu-pt-2">
					<strong class="acu-flex-grow ">{i18n.plugin_not_detected}</strong>
					<span class="acu-justify-end">
						<AcIcon icon="no-alt" pack="dashicons" customClass="acu-text-[#D63638]"/>
					</span>
				</div>
			{/if}
		{/if}
	</div>
</div>