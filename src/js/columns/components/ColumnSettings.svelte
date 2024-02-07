<script lang="ts">
    import {getInputComponent} from "../helper";
    import RuleSpecificationMapper from "../../expression/rule-specification-mapper";
    import {listScreenIsReadOnly} from "../store/read_only";
    import ColumnSetting from "./ColumnSetting.svelte";

    export let data: any;
    export let settings: AC.Column.Settings.ColumnSettingCollection
    export let parent: string = '';

    let filteredSettings = settings;

    const getInputType = (type: string) => {
        return getInputComponent(type);
    }

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

    $: data && checkConditions();
    $: settings && configChange();
</script>

{#if typeof filteredSettings !== 'undefined' }
	{#each filteredSettings as setting (setting.type + setting?.input?.name) }

		<ColumnSetting name={setting.name} description={setting.description} label={setting.attributes?.label}>

			<svelte:component
				this={getInputType(setting.input?.type ?? 'empty')}
				bind:data={data}
				bind:value={data[setting?.input?.name]}
				disabled={$listScreenIsReadOnly}
				config={setting}>
			</svelte:component>

			{#if setting.children && setting.is_parent }
				<svelte:self bind:data={data} settings={setting.children} parent={setting?.input?.name}/>
			{/if}

		</ColumnSetting>

		{#if setting.children && !setting.is_parent }
			<svelte:self bind:data={data} settings={setting.children} parent={setting?.input?.name}/>
		{/if}

	{/each}

{/if}