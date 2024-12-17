<script lang="ts">
    import RuleSpecificationMapper from "../../expression/rule-specification-mapper";
    import RowSetting from "./settings/RowSetting.svelte";
    import WidthRowSetting from "./settings/WidthRowSetting.svelte";
    import InputOnlySetting from "./settings/InputOnlySetting.svelte";

    export let data: any;
    export let settings: AC.Column.Settings.ColumnSettingCollection
    export let parent: string = '';
    export let isSubComponent: boolean = false;
    export let locked: boolean = false;

    let filteredSettings = settings;

    const checkConditions = () => {
        if (typeof settings === 'undefined') {
            return;
        }

        filteredSettings = settings.filter(s => {
            return s.conditions
                ? checkCondition(s.conditions)
                : true;
        });
    }

    const checkCondition = (condition: AC.Specification.Rule) => {
        return RuleSpecificationMapper.map(condition).isSatisfiedBy(data[parent]);
    }

    const configChange = () => {
        checkConditions();
    }

    const settingComponents: { [key: string]: any } = {
        'default': RowSetting,
        'input_only': InputOnlySetting,
        'width': WidthRowSetting
    }

    const getSettingComponent = (type: string) => {
        return settingComponents.hasOwnProperty(type)
            ? settingComponents[type]
            : RowSetting;
    }

    $: data && checkConditions();
    $: settings && configChange();
</script>


{#if typeof filteredSettings !== 'undefined' }
	{#each filteredSettings as setting (JSON.stringify( setting )) }

		<svelte:component
			this={getSettingComponent(setting.type ?? '')}
			setting={setting}
			disabled={locked}
			bind:data={data}
			on:refresh
			{isSubComponent}
		/>

	{/each}

{/if}