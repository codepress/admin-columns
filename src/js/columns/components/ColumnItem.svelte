<script lang="ts">

    import {getSettingComponent} from "../helper";
    import {openedColumnsStore} from "../store/opened-columns";
    import {slide} from 'svelte/transition';
    import {createEventDispatcher, onMount} from "svelte";
    import {ColumnTypesUtils} from "../utils/column-types";
    import HeaderToggle from "./settings/HeaderToggle.svelte";
    import ColumnSettings from "./ColumnSettings.svelte";
    import RuleSpecificationMapper from "../../expression/rule-specification-mapper";
    import ColumnConfig = AC.Vars.Admin.Columns.ColumnConfig;

    export let data: any;
    export let config: AC.Column.Settings.ColumnSettingCollection = [];

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


    const checkConditions = (settings: ColumnConfig[], parent: string) => {
        return settings.filter(s => {
            return s.conditions
                ? checkCondition(s.conditions, parent)
                : true;
        });
    }

    const checkCondition = (condition: AC.Column.Settings.ColumnConditions, parent) => {
        return RuleSpecificationMapper.map(condition).isSatisfiedBy(data[parent]);
    }

    const checkAppliedSettings = () => {
        let settings: String[] = [ 'name' ];
        config.forEach(s => {
            settings.push(s.name);
            if (s.children) {
                s.children.filter(sub => {
                    return sub.conditions
                        ? checkCondition(sub.conditions, s.name)
                        : true;
                }).forEach(found => {
                    settings.push(found.name)
                });
            }
        })

        Object.keys( data ).forEach( settingName => {
            if( ! settings.includes( settingName ) ){
                delete( data[ settingName ]);
			}
		})
		data = data;

    }

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

	{#if opened && config !== null }
		<div class="ac-column-settings" transition:slide>
			<textarea style="width:100%; height: 150px;" value={JSON.stringify(data)}></textarea>
			<ColumnSettings
					bind:data={data}
					bind:settings={config}
			/>
			<button on:click={checkAppliedSettings}>Check settings</button>
		</div>
	{/if}
</div>