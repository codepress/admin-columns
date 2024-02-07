<script lang="ts">

    import {openedColumnsStore} from "../store/opened-columns";
    import {slide} from 'svelte/transition';
    import {createEventDispatcher, onMount} from "svelte";
    import {ColumnTypesUtils} from "../utils/column-types";
    import ColumnSettings from "./ColumnSettings.svelte";
    import RuleSpecificationMapper from "../../expression/rule-specification-mapper";
    import ProFeatureToggles from "./ProFeatureToggles.svelte";
    import AcIcon from "ACUi/AcIcon.svelte";
    import ColumnSetting from "./ColumnSetting.svelte";
    import TypeSetting from "./settings/input/TypeInput.svelte";
    import {listScreenIsReadOnly} from "../store/read_only";

    export let data: any;
    export let config: AC.Column.Settings.ColumnSettingCollection = [];

    let labelElement: HTMLElement | null;
    let readableLabel: string = '';

    const dispatch = createEventDispatcher();
    const originalsColumns = ColumnTypesUtils.getOriginalColumnTypes();

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
            if( setting.hasOwnProperty('input') ){
                validSettings.push(setting?.input?.name);
			}
            if (setting.children) {
                checkAppliedSubSettings(validSettings, setting.children, setting?.input?.name);
            }
        })

        return validSettings;
    }

    const checkAppliedSettings = () => {
        let settings: string[] = checkAppliedSubSettings(['name','type'], config, '');

        console.log( 'check settings', settings );

        Object.keys(data).forEach(settingName => {
            if (!settings.includes(settingName)) {
                //delete (data[settingName]);
            }
        });

        data = data;
    }

    const checkVisibleLabel = (label: string) => {
        if (labelElement && labelElement.offsetWidth < 5) {
            readableLabel = document.createRange().createContextualFragment(data.label).textContent ?? '';
            if (readableLabel.length === 0) {
                readableLabel = data.type;
            }
        } else {
            readableLabel = '';
        }
    }

    // TODO
    //$: checkVisibleLabel( data.label)
    $: opened = $openedColumnsStore.includes(data.name);
</script>

<div class="ac-column" class:-opened={opened}>
	<header class="ac-column-header acu-flex acu-py-2 acu-pr-6 acu-items-center acu-bg-[#fff]">
		<div class="ac-column-header__move acu-cursor-move">
			<AcIcon icon="move" size="sm"/>
		</div>
		<div class="ac-column-header__label">
			<strong on:click={toggle} on:keydown role="none" bind:this={labelElement}>{@html data.label}{readableLabel}</strong>
			<div class="ac-column-row-actions">
				<a class="ac-column-row-action -edit" href={'#'} on:click|preventDefault={toggle}>Edit</a>
				{#if !isOriginalColumn}
					<a class="ac-column-row-action -duplicate" href={'#'} on:click|preventDefault={handleDuplicate}>Duplicate</a>
				{/if}
				<a class="ac-column-row-action -delete" href={'#'} on:click|preventDefault={handleDelete}>Delete</a>
			</div>
		</div>
		<div class="ac-column-header__actions acu-hidden lg:acu-flex acu-items-center acu-gap-1 acu-justify-end">
			{#if data.width && data.width_unit}
				{data.width} {data.width_unit}
			{/if}
			<ProFeatureToggles bind:data={data} bind:config={config}></ProFeatureToggles>
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
			<ColumnSetting name="type" description="" label="Type">
				<TypeSetting bind:data={data} bind:columnConfig={config} disabled={$listScreenIsReadOnly}/>
			</ColumnSetting>


			<ColumnSettings
				bind:data={data}
				bind:settings={config}
			/>

			<div style="padding: 10px; background: #FFDCDCFF">
				<textarea style="width:100%; height: 90px;" value={JSON.stringify(data)}></textarea>
				<button class="button" on:click={checkAppliedSettings}>Check settings</button>
			</div>
		</div>
	{/if}
</div>