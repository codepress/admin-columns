<script lang="ts">
    import {listScreenIsReadOnly} from "../../store/read_only";
    import ColumnSetting from "../ColumnSetting.svelte";
    import {getInputComponent} from "../../helper";
    import {ListScreenColumnData} from "../../../types/requests";
    import ColumnSettings from "../ColumnSettings.svelte";
    import ColumnInputSetting = AC.Column.Settings.ColumnInputSetting;

    export let setting: AC.Column.Settings.AbstractColumnSetting;
    export let data: ListScreenColumnData;

    let inputSetting = setting as ColumnInputSetting;

    const getInputType = (type: string) => {
        return getInputComponent(type);
    }

</script>

<ColumnSetting description={setting.attributes?.description ?? ''} label={setting.attributes.label}>


	<svelte:component
		this={getInputType(inputSetting.input.type ?? '')}
		bind:data={data}
		bind:value={data[inputSetting.input?.name]}
		disabled={$listScreenIsReadOnly}
		config={setting}>
	</svelte:component>

	{#if setting.children && setting.is_parent }
		<ColumnSettings bind:data={data} settings={setting.children} parent={inputSetting.input.name}/>
	{/if}

</ColumnSetting>

{#if setting.children && !setting.is_parent }
	<ColumnSettings bind:data={data} settings={setting.children} parent={inputSetting.input.name}/>
{/if}