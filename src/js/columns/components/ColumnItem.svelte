<script lang="ts">

    import {columnTypesStore, currentListKey, debugMode, openedColumnsStore, showColumnInfo} from "../store";
    import {slide} from 'svelte/transition';
    import {createEventDispatcher, onMount} from "svelte";
    import {ColumnTypesUtils} from "../utils/column-types";
    import ColumnSettings from "./ColumnSettings.svelte";
    import RuleSpecificationMapper from "../../expression/rule-specification-mapper";
    import ProFeatureToggles from "./ProFeatureToggles.svelte";
    import AcIcon from "ACUi/AcIcon.svelte";
    import ColumnSetting from "./ColumnSetting.svelte";
    import TypeSetting from "./settings/input/TypeInput.svelte";
    import {refreshColumn} from "../ajax/ajax";
    import ColumnLabel from "./ColumnLabel.svelte";

    export let data: any;
    export let config: AC.Column.Settings.ColumnSettingCollection = [];
    export let locked: boolean = false;

    const dispatch = createEventDispatcher();
    const originalsColumns = ColumnTypesUtils.getOriginalColumnTypes();

    let columnTypeLabel: string;
    let columnTypeName: string;

    const toggle = () => {
        openedColumnsStore.toggle(data.name);
    }

    const handleDelete = () => {
        dispatch('delete', data.name);
    }

    const handleDuplicate = () => {
        dispatch('duplicate', data.name);
    }

    let isOriginalColumn: boolean = false;

    onMount(() => {
        isOriginalColumn = typeof originalsColumns.find(c => c.value === data.type) !== 'undefined';
        let columnInfo = $columnTypesStore.find(c => c.value === data.type);
        columnTypeLabel = columnInfo?.label ?? ''
        columnTypeName = columnInfo?.value ?? ''
    })

    const checkCondition = (condition: AC.Specification.Rule, parent: string) => {
        return RuleSpecificationMapper.map(condition).isSatisfiedBy(data[parent]);
    }

    const checkAppliedSubSettings = (validSettings: string[], children: AC.Column.Settings.ColumnSetting[], parent: string) => {
        children.filter(sub => {
            return sub.conditions
                ? checkCondition(sub.conditions, parent)
                : true;
        }).forEach(setting => {
            if (setting.hasOwnProperty('input')) {
                validSettings.push((setting as any).input?.name);
            }
            if (setting.children) {
                checkAppliedSubSettings(validSettings, setting.children, setting?.input?.name ?? '');
            }
        })

        return validSettings;
    }

    const hasProfeatures = (config: AC.Column.Settings.ColumnSettingCollection) => {
        const proFeatureNames: string[] = ['export', 'sort', 'edit', 'bulk_edit', 'search', 'filter'];

        return config.filter(c => c.input && proFeatureNames.includes(c.input.name)).length > 0
    }

    export const checkAppliedSettings = () => {
        let settings: string[] = checkAppliedSubSettings(['name', 'type'], config, '');

        Object.keys(data).forEach(settingName => {
            if (!settings.includes(settingName)) {
                delete (data[settingName]);
            }
        });

        data = data;
    }

    const refreshSetting = () => {
        checkAppliedSettings();
        refreshColumn(data, $currentListKey).then(d => {
            if (d.data.success) {
                config = d.data.data.column.settings;
            }
        })
    }

    $: opened = $openedColumnsStore.includes(data.name);
</script>

<div class="ac-column" class:-opened={opened} data-name={data.name}>
	<header class="ac-column-header acu-flex acu-py-2 acu-pr-6 rtl:acu-pl-6 acu-items-center acu-bg-[#fff]">
		<div class="ac-column-header__move acu-cursor-move">
			<AcIcon icon="move" size="sm"/>
		</div>
		<div class="ac-column-header__label">
			<strong on:click={toggle} on:keydown role="none">
				<ColumnLabel bind:value={data.label} fallback={columnTypeLabel}/>
			</strong>
			<div class="ac-column-row-actions">
				<a class="ac-column-row-action -edit" href={'#'} on:click|preventDefault={toggle}>Edit</a>
				{#if !isOriginalColumn}
					<a class="ac-column-row-action -duplicate" href={'#'} on:click|preventDefault={handleDuplicate}>Duplicate</a>
				{/if}
				<a class="ac-column-row-action -delete" href={'#'} on:click|preventDefault={handleDelete}>Delete</a>
			</div>
		</div>
		{#if $showColumnInfo}
			<div class="acu-flex acu-flex-col acu-text-right acu-pr-2 acu-text-[#999] acu-leading-snug">
				<small><strong class="acu-text-[#777] acu-pr-1">type:</strong>{columnTypeName}</small>
				<small><strong class="acu-text-[#777] acu-pr-1">name:</strong>{data.name}</small>
			</div>
		{/if}
		<div class="ac-column-header__actions acu-hidden lg:acu-flex acu-items-center acu-gap-1 acu-justify-end">
			{#if data.width && data.width_unit}
				{data.width} {data.width_unit}
			{/if}
			{#if hasProfeatures( config )}
				<ProFeatureToggles bind:data={data} bind:config={config} disabled={locked}/>
			{/if}
		</div>
		<div class="ac-column-header__open-indicator acu-flex acu-justify-end">
			<button class="ac-open-indicator" class:-open={opened} on:click={toggle}>
				<span class="dashicons dashicons-arrow-down-alt2"></span>
			</button>
		</div>
	</header>

	{#if opened && config !== null }
		<div class="ac-column-settings" transition:slide>

			<!-- Specific Type setting -->
			<ColumnSetting description="" label="Type" extraClass="-type">
				<TypeSetting bind:data={data} bind:columnConfig={config} disabled={locked}/>
			</ColumnSetting>

			<ColumnSettings
				locked={locked}
				bind:data={data}
				bind:settings={config}
				on:refresh={refreshSetting}
			/>

			{#if $debugMode}
				<div style="padding: 10px; background: #FFDCDCFF">
					<textarea style="width:100%; height: 90px;" value={JSON.stringify(data)}></textarea>
					<button class="button" on:click={checkAppliedSettings}>Check settings</button>
					<button class="button" on:click={refreshSetting}>Refresh settings</button>
				</div>
			{/if}
		</div>
	{/if}
</div>