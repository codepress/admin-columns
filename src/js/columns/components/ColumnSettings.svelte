<script lang="ts">

    import {getSettingComponent} from "../helper";
    import RuleSpecificationMapper from "../../expression/rule-specification-mapper";

    export let data: any;
    export let settings: AC.Column.Settings.ColumnSettingCollection
    export let parent: string = '';

    let filteredSettings = settings;

    const getComponent = (type: string) => {
        return getSettingComponent(type);
    }

    const checkConditions = (data) => {
        filteredSettings = settings.filter(s => {
            return s.conditions
                ? checkCondition(s.conditions)
                : true;
        })
    }

    const checkCondition = (condition: AC.Column.Settings.ColumnConditions) => {
        return RuleSpecificationMapper.map(condition).isSatisfiedBy(data[parent]);
    }

    const destroySetting = (e: CustomEvent<AC.Column.Settings.ColumnSetting>) => {
        if (!filteredSettings.find(s => s.name === e.detail.name)) {
            if (data.hasOwnProperty(e.detail.name)) {
                delete data[e.detail.name];
                data = data;
            }
        }
    }

    $: checkConditions(data);
</script>

{#each filteredSettings as setting (setting.name)}

	<svelte:component
			this={getComponent(setting.input.type)}
			bind:data={data}
			bind:value={data[setting.name]}
			bind:columnConfig={setting}
			on:destroy={destroySetting}
			config={setting}>
	</svelte:component>
{/each}