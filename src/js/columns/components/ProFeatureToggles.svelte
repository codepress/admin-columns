<script lang="ts">

    import HeaderToggle from "./settings/HeaderToggle.svelte";
    import {onMount} from "svelte";

    export let data: any = {};
    export let config: AC.Column.Settings.ColumnSettingCollection = [];
    export let disabled: boolean = false;

    type Feature = {
        feature: string
        title: string
        iconClass: string
    }

    let proFeatures: Feature[] = [
        {feature: 'export', title: 'Enable Export', iconClass: 'cpacicon cpacicon-download'},
        {feature: 'sort', title: 'Enable Sorting', iconClass: 'dashicons dashicons-sort'},
        {feature: 'edit', title: 'Enable Editing', iconClass: 'dashicons dashicons-edit'},
        {feature: 'bulk_edit', title: 'Enable Bulk Editing', iconClass: 'cpacicon-bulk-edit'},
        {feature: 'search', title: 'Enable Smart Filter', iconClass: 'cpacicon-smart-filter'},
        {feature: 'filter', title: 'Enable Filtering', iconClass: 'dashicons dashicons-filter'},
    ];

    const getConfig = (feature: Feature) => {
        return config.find(c => c.input && c.input.name === feature.feature);
    }


    onMount(() => {

    })

</script>
{#if config}
	<div class="acu-flex acu-items-center acu-gap-1">
		{#each proFeatures as feature}
			{#if getConfig( feature ) }
				<HeaderToggle
					defaultValue={getConfig( feature ).input.default}
					bind:value={data[feature.feature]}
					title={feature.title}
					disabled={disabled}>

					<span class="{feature.iconClass}"></span>

				</HeaderToggle>
			{:else}
				<div class="ac-header-toggle acu-invisible -skeleton acu-border-[transparent] acu-bg-none acu-cursor-default"></div>
			{/if}

		{/each}
	</div>
{/if}