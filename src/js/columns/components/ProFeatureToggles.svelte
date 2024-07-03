<script lang="ts">

    import HeaderToggle from "./settings/HeaderToggle.svelte";
    import {listScreenIsReadOnly} from "../store/read_only";
    import {onMount} from "svelte";

    export let data: any = {};
    export let config: AC.Column.Settings.ColumnSettingCollection = [];

    let proFeatures = [
        {feature: 'export', title: 'Enable Export', iconClass: 'cpacicon cpacicon-download'},
        {feature: 'sort', title: 'Enable Sorting', iconClass: 'dashicons dashicons-sort'},
        {feature: 'edit', title: 'Enable Editing', iconClass: 'dashicons dashicons-edit'},
        {feature: 'bulk_edit', title: 'Enable Bulk Editing', iconClass: 'cpacicon-bulk-edit'},
        {feature: 'search', title: 'Enable Smart Filter', iconClass: 'cpacicon-smart-filter'},
        {feature: 'filter', title: 'Enable Filtering', iconClass: 'dashicons dashicons-filter'},
    ];

    onMount(() => {

    })

</script>
{#if config}
	<div class="acu-flex acu-items-center acu-gap-1">
		{#each proFeatures as feature}
			{#if config.find( c => c.input && c.input.name === feature.feature )}
				<HeaderToggle bind:value={data[feature.feature]} title={feature.title} disabled={$listScreenIsReadOnly}>
					<span class="{feature.iconClass}"></span>
				</HeaderToggle>
			{:else}
				<div class="ac-header-toggle acu-invisible -skeleton acu-border-[transparent] acu-bg-none acu-cursor-default"></div>
			{/if}

		{/each}
	</div>
{/if}