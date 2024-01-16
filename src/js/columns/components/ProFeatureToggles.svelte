<script lang="ts">

    import HeaderToggle from "./settings/HeaderToggle.svelte";
    import {afterUpdate} from "svelte";
    import {listScreenIsReadOnly} from "../store/read_only";

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

    // afterUpdate(() => {
    //     setTimeout(() => {
    //         proFeatures.forEach(feature => {
    //             console.log('S');
    //             if (typeof data[feature.feature] === 'undefined') {
    //                 data[feature.feature] = config.find(c => c.name === feature.feature)?.input?.default ?? 'off';
    //             }
    //         });
    //     }, 100)
    // })


</script>
<style>
	.ac-header-toggle.-skeleton {
		border-color: transparent;
		background: transparent;
	}

</style>
{#if config}
	<div style="display: flex; align-items: center; gap: 5px;">
		{#each proFeatures as feature}

			{#if config.find( c => c.name === feature.feature )}
				<HeaderToggle bind:value={data[feature.feature]} title={feature.title} disabled={listScreenIsReadOnly}>
					<span class="{feature.iconClass}"></span>
				</HeaderToggle>
			{:else}
				<div class="ac-header-toggle -skeleton"></div>
			{/if}


		{/each}
	</div>
{/if}