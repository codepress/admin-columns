<script lang="ts">

    import {getSettingComponent} from "../helper";

    export let data: any;
    export let settings: AC.Column.Settings.ColumnSettingCollection

	let filteredSettings = settings;

    const getComponent = (type: string) => {
        return getSettingComponent(type);
    }


	const checkConditions = () => {
        filteredSettings = settings.filter( s => {
            if( s.conditions ){
                return checkCondition( s.conditions )
			}
            return true;
		})
	}

    const checkCondition = ( conditions: AC.Column.Settings.ColumnConditions ) => {
        let valid = false;

        conditions.forEach( (c: AC.Column.Settings.ColumnCondition) => {
            if( data[ c.setting ] === c.value  ){
                valid = true;
			}
		});

        return valid;
    }

    $: data, checkConditions();
</script>

{#each filteredSettings as setting}

	<svelte:component
			this={getComponent(setting.input.type)}
			bind:data={data}
			bind:value={data[setting.name]}
			bind:columnConfig={setting}
			config={setting}>
	</svelte:component>
{/each}