<script lang="ts">

    import {getSettingComponent} from "../helper";
    import RuleSpecificationMapper from "../../expression/rule-specification-mapper";
    import {listScreenIsReadOnly} from "../store/read_only";

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
        });
    }

    const checkCondition = (condition: AC.Column.Settings.ColumnConditions) => {
        return RuleSpecificationMapper.map(condition).isSatisfiedBy(data[parent]);
    }

    const configChange = () => {
        checkConditions(data);
    }

    $: checkConditions(data);
    $: configChange(settings);
</script>

{#each filteredSettings as setting (setting.name)}

	<svelte:component
			this={getComponent(setting.input?.type ?? 'empty')}
			bind:data={data}
			bind:value={data[setting.name]}
			disabled={$listScreenIsReadOnly}
			bind:columnConfig={settings}
			config={setting}>
	</svelte:component>
{/each}