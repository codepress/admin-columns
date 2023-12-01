<script lang="ts">

    import {getSettingComponent} from "../helper";
    import {openedColumnsStore} from "../store/opened-columns";
    import {slide} from 'svelte/transition';
    import {createEventDispatcher, onMount} from "svelte";
    import {ColumnTypesUtils} from "../utils/column-types";
    import HeaderToggle from "./settings/HeaderToggle.svelte";
    import ColumnSettings from "./ColumnSettings.svelte";
    import RuleSpecificationMapper from "../../expression/rule-specification-mapper";
    import ProFeatureToggles from "./ProFeatureToggles.svelte";

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
        isOriginalColumn = typeof originalsColumns.find(c => c.value === data.type) !== 'undefined';
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
            validSettings.push(setting.name);
            if (setting.children) {
                checkAppliedSubSettings(validSettings, setting.children, setting.name);
            }
        })

        return validSettings;
    }

    const checkAppliedSettings = () => {
        let settings: string[] = checkAppliedSubSettings(['name'], config, '');

        Object.keys(data).forEach(settingName => {
            if (!settings.includes(settingName)) {
                delete (data[settingName]);
            }
        });

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
			<textarea style="width:100%; height: 90px;" value={JSON.stringify(data)}></textarea>
			<ProFeatureToggles bind:data={data} bind:config={config}></ProFeatureToggles>

		</div>
		<div class="ac-column-header__open-indicator">
			<button class="ac-open-indicator" class:-open={opened} on:click={toggle}>
				<span class="dashicons dashicons-arrow-down-alt2"></span>
			</button>
		</div>
	</header>

	{#if opened && config !== null }
		<div class="ac-column-settings" transition:slide>

			<ColumnSettings
					bind:data={data}
					bind:settings={config}
			/>
			<textarea style="width:100%; height: 90px;" value={JSON.stringify(data)}></textarea>
			<button on:click={checkAppliedSettings}>Check settings</button>
		</div>
	{/if}
</div>