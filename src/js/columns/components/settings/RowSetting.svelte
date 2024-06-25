<script lang="ts">
    import {listScreenIsReadOnly} from "../../store/read_only";
    import ColumnSetting from "../ColumnSetting.svelte";
    import {getInputComponent} from "../../helper";
    import {ListScreenColumnData} from "../../../types/requests";
    import ColumnSettings from "../ColumnSettings.svelte";
    import ColumnInputSetting = AC.Column.Settings.ColumnInputSetting;

    export let setting: AC.Column.Settings.AbstractColumnSetting;
    export let data: ListScreenColumnData;
    export let isSubComponent: boolean = false;

    let inputSetting = setting as ColumnInputSetting;

    const getInputType = (type: string) => {
        return getInputComponent(type);
    }

</script>

<ColumnSetting description={setting.description ?? ''} label={setting.label} {isSubComponent}>

	{#if inputSetting.input}
		<svelte:component
			this={getInputType(inputSetting.input.type ?? '')}
			bind:data={data}
			bind:value={data[inputSetting.input?.name]}
			on:refresh
			disabled={$listScreenIsReadOnly}
			config={setting}>
		</svelte:component>
	{/if}

	{#if setting.children && setting.is_parent }
		<div class="acp-column-setting__subsettings">
			<ColumnSettings isSubComponent
				bind:data={data}
				settings={setting.children}
				on:refresh
				parent={inputSetting.input ? inputSetting.input.name : ''}/>
		</div>
	{/if}

</ColumnSetting>

{#if setting.children && !setting.is_parent }
	<ColumnSettings bind:data={data} settings={setting.children} parent={inputSetting.input ? inputSetting.input.name : ''}/>
{/if}