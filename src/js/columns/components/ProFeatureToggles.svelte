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
        content: string
    }

    let proFeatures: Feature[] = [
        {feature: 'export', title: 'Enable Export', iconClass: 'ac-material-symbols', content: 'download'},
        {feature: 'sort', title: 'Enable Sorting', iconClass: 'ac-material-symbols', content: 'swap_vert'},
        {feature: 'edit', title: 'Enable Editing', iconClass: 'ac-material-symbols', content: 'edit'},
        {feature: 'bulk_edit', title: 'Enable Bulk Editing', iconClass: 'ac-material-symbols', content: 'stacks'},
        {feature: 'search', title: 'Enable Smart Filter', iconClass: 'ac-material-symbols', content: 'filter_list'},
        {feature: 'filter', title: 'Enable Filtering', iconClass: 'ac-material-symbols', content: 'filter_alt'},
    ];

    const getConfig = (feature: Feature) => {
        return config.find(c => c.input && c.input.name === feature.feature);
    }


    onMount(() => {

    })

</script>
{#if config}
	<div class="acu-flex acu-items-center acu-gap-1" on:click|stopPropagation role="none">
		{#each proFeatures as feature}
			{#if getConfig( feature ) }
				<HeaderToggle
					defaultValue={getConfig( feature ).input.default}
					bind:value={data[feature.feature]}
					title={feature.title}
					disabled={disabled}>

					<span class="{feature.iconClass} !acu-text-[20px]">{@html feature.content}</span>

				</HeaderToggle>
			{:else}
				<div class="ac-header-toggle acu-invisible -skeleton acu-border-[transparent] acu-bg-none acu-cursor-default"></div>
			{/if}

		{/each}
	</div>
{/if}