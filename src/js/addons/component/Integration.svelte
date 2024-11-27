<script lang="ts">
    import {IntegrationItem, toggleIntegrationStatus} from "../ajax/requests";
    import AcToggle from "ACUi/element/AcToggle.svelte";
    import AcButton from "ACUi/element/AcButton.svelte";

    export let integration: IntegrationItem;
    export let isPro: boolean;

    let checked: boolean = integration.active;

    const handleStatusChange = () => {
        toggleIntegrationStatus({
            integration: integration.slug,
            status: checked
        })
    }


</script>
<div class="acu-rounded-[10px] acu-border-solid acu-border-ui-border acu-w-full acu-max-w-[260px] acu-overflow-hidden">
	<div class="acu-bg-[#F1F5F9] acu-p-4 acu-text-center acu-flex acu-h-[100px] acu-items-center acu-justify-center">
		<img src="{AC_ADDONS.asset_location}/{integration.plugin_logo}" alt="{integration.title}"
			class="acu-max-h-[80px] acu-max-w-[80%]"/>

	</div>
	<div class="acu-p-4">
		<h3>{integration.title}</h3>
		<div class="acu-h-[120px] acu-overflow-hidden">
			<p>{integration.description}</p>
		</div>
		<div class="acu-mb-4">
			<a href="{integration.external_link}" target="_blank"
				class="acu-no-underline">Learn more
				&raquo;
			</a>
		</div>
		<hr class="acu-mb-3"/>

		{#if isPro }
			<div class="acu-flex acu-pt-2">
				<strong class="acu-flex-grow ">Enable Integration</strong>
				<span class="acu-justify-end">
				<AcToggle bind:checked={checked} on:input={handleStatusChange}/>
				</span>
			</div>
		{:else}
			<div>
				<AcButton iconLeft="lock" iconLeftPack="dashicons" type="pink" label="Buy Admin Columns Pro"
					customClass="acu-block acu-w-full" href={AC_ADDONS.buy_url} target="_blank"/>
			</div>
		{/if}
	</div>
</div>