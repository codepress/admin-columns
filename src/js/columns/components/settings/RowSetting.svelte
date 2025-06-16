<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import {getInputComponent} from "../../helper";
    import {ListScreenColumnData} from "../../../types/requests";
    import ColumnSettings from "../ColumnSettings.svelte";
    import ColumnInputSetting = AC.Column.Settings.ColumnInputSetting;

    export let setting: AC.Column.Settings.AbstractColumnSetting;
    export let data: ListScreenColumnData;
    export let isSubComponent: boolean = false;
    export let disabled: boolean = false;


    let inputSetting = setting as ColumnInputSetting;
</script>

<ColumnSetting description={setting.description ?? ''} label={setting.label} {isSubComponent} attributes={setting.attributes??{}}>

	{#if inputSetting.input}
		<svelte:component
			this={getInputComponent(inputSetting.input.type ?? '')}
			bind:data={data}
			bind:value={data[inputSetting.input?.name]}
			on:refresh
			disabled={disabled}
			config={setting}>
		</svelte:component>
	{/if}

	{#if setting.children && setting.is_parent }
		<div class="acp-column-setting__subsettings">
			<ColumnSettings
				isSubComponent
				settings={setting.children}
				bind:data={data}
				on:refresh
				locked={disabled}
				parent={inputSetting.input ? inputSetting.input.name : ''}/>
		</div>
	{/if}

</ColumnSetting>

{#if setting.children && !setting.is_parent }
	<ColumnSettings
		on:refresh
		bind:data={data}
		locked={disabled}
		settings={setting.children}
		parent={inputSetting.input ? inputSetting.input.name : ''}
	/>
{/if}