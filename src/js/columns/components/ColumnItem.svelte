<script lang="ts">

    import {getSettingComponent} from "../helper";
    import {openedColumnsStore} from "../store/opened-columns";
    import {slide} from 'svelte/transition';
    import {createEventDispatcher, onMount} from "svelte";
    import {ColumnTypesUtils} from "../utils/column-types";
    import HeaderToggle from "./settings/HeaderToggle.svelte";

    export let data: any;
    export let config = [];

    const dispatch = createEventDispatcher();
    const originalsColumns = ColumnTypesUtils.getOriginalColumnTypes();

    const toggle = () => {
        openedColumnsStore.toggle(data.name);
    }

    const getComponent = (type: string) => {
        return getSettingComponent(type);
    }

    const handleDelete = () => {
        dispatch('delete', data.name);
    }

    const handleDuplicate = () => {
        dispatch('duplicate', data.name);
    }

    let isOriginalColumn: boolean = false;

    onMount(() => {
        isOriginalColumn = originalsColumns.find(c => c.type === data.type) !== undefined;
    })

    $: opened = $openedColumnsStore.includes(data.name);
</script>

<div class="ac-column" class:-opened={opened}>
	<header class="ac-column-header">
		<div class="ac-column-header__label">
			<strong on:click={toggle} on:keydown role="none">{@html data.label}</strong>
			<div class="ac-column-row-actions">
				<a class="ac-column-row-action -edit" href={'#'} on:click|preventDefault={toggle}>Edit</a>
				{#if !isOriginalColumn}
					<a class="ac-column-row-action -duplicate" href={'#'} on:click|preventDefault={handleDuplicate}>Duplicate</a>
				{/if}
				<a class="ac-column-row-action -delete" href={'#'} on:click|preventDefault={handleDelete}>Delete</a>
			</div>
		</div>
		<div class="ac-column-header__actions">
			{#if data.width }
				{data.width} {data.width_unit}
			{/if}
			<HeaderToggle bind:value={data.export} title="Enable Export">
				<span class="cpacicon cpacicon-download"></span>
			</HeaderToggle>
			<HeaderToggle bind:value={data.sort} title="Enable Sorting">
				<span class="dashicons dashicons-sort"></span>
			</HeaderToggle>
			<HeaderToggle bind:value={data.edit} title="Enable Edit">
				<span class="dashicons dashicons-edit"></span>
			</HeaderToggle>
			<HeaderToggle bind:value={data.bulk_edit} title="Enable Bulk Edit">
				<span class="cpacicon-bulk-edit" style="scale:1.4;"></span>
			</HeaderToggle>
			<HeaderToggle bind:value={data.search} title="Enable Smart Filtering">
				<span class="cpacicon-smart-filter" style="scale:1.2;"></span>
			</HeaderToggle>
			<HeaderToggle bind:value={data.filter} title="Enable Filtering">
				<span class="dashicons dashicons-filter"></span>
			</HeaderToggle>
		</div>
		<div class="ac-column-header__open-indicator">
			<button class="ac-open-indicator" class:-open={opened} on:click={toggle}>
				<span class="dashicons dashicons-arrow-down-alt2"></span>
			</button>
		</div>
	</header>

	{#if opened && config !== null && typeof config !== 'undefined' }
		<div class="ac-column-settings" transition:slide>

			{#each config as setting}
				<svelte:component
						this={getComponent(setting.type)}
						bind:data={data}
						bind:columnConfig={config}
						config={setting}>

				</svelte:component>
			{/each}
		</div>
	{/if}
</div>