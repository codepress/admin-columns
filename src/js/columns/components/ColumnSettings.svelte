<script lang="ts">
    import {getInputComponent} from "../helper";
    import RuleSpecificationMapper from "../../expression/rule-specification-mapper";
    import {listScreenIsReadOnly} from "../store/read_only";
    import ColumnSetting from "./ColumnSetting.svelte";

    export let data: any;
    export let settings: AC.Column.Settings.ColumnSettingCollection
    export let parent: string = '';

    let filteredSettings = settings;

    const getComponent = (type: string) => {
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
	{#each filteredSettings as setting (setting.name)}

		<ColumnSetting name={setting.name} description={setting.description} label={setting.label}>

			<svelte:component
				this={getComponent(setting.input?.type ?? 'empty')}
				bind:data={data}
				bind:value={data[setting.name]}
				disabled={$listScreenIsReadOnly}
				config={setting}>
			</svelte:component>

			<!-- Subsettings -->
			{#if setting.children && setting.is_parent }
				<svelte:self bind:data={data} settings={setting.children} parent={setting.name}/>
			{/if}

		</ColumnSetting>

		<!-- Dependent settings -->
		{#if setting.children && !setting.is_parent }
			<svelte:self bind:data={data} settings={setting.children} parent={setting.name}/>
		{/if}

	{/each}
{/if}