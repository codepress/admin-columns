<script lang="ts">
    import {getInputComponent} from "../../helper";
    import {ListScreenColumnData} from "../../../types/requests";
    import ColumnSettings from "../ColumnSettings.svelte";
    import ColumnInputSetting = AC.Column.Settings.ColumnInputSetting;

    export let setting: AC.Column.Settings.AbstractColumnSetting;
    export let data: ListScreenColumnData;
    export let disabled: boolean = false;

    let inputSetting = setting as ColumnInputSetting;

    const getInputType = (type: string) => {
        return getInputComponent(type);
    }

</script>

{#if inputSetting.input}
	<svelte:component
		this={getInputType(inputSetting.input.type ?? '')}
		bind:data={data}
		bind:value={data[inputSetting.input?.name]}
		disabled={disabled}
		config={setting}>
	</svelte:component>
{/if}

{#if setting.children && !setting.is_parent }
	<ColumnSettings
		locked={disabled}
		bind:data={data}
		settings={setting.children}
		parent={inputSetting.input ? inputSetting.input.name : null}
	/>
{/if}