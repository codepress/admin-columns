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
            if (s.conditions) {
                return checkCondition(s.conditions, s.name)
            }
            return true;
        })
    }

    const checkCondition = (conditions: AC.Column.Settings.ColumnConditions, name: string) => {
        let valid = false;

		console.log( data[parent], parent, conditions);
        return RuleSpecificationMapper.map( conditions).isSatisfiedBy( data[parent] );


        return true;

        conditions.forEach((c: AC.Column.Settings.ColumnCondition) => {
            if (data[c.setting] === c.value) {
                valid = true;
            }
        });

        return valid;
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