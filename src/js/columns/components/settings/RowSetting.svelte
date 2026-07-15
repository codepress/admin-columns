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
    export let showErrors: boolean = false;


    let inputSetting = setting as ColumnInputSetting;

    // A required field is invalid once the user has attempted to submit
    // (showErrors) and its value is still empty.
    $: isRequired = !!(setting.attributes && setting.attributes['required']);
    // A field can opt into read-only via a server-side `readonly` attribute
    // (e.g. the Table select when editing an existing list table).
    $: isDisabled = disabled || !!(setting.attributes && setting.attributes['readonly']);
    $: value = inputSetting.input ? data[inputSetting.input.name] : undefined;
    $: hasError = showErrors && isRequired
        && (value === undefined || value === null || String(value).trim() === '');
</script>

<ColumnSetting setting={setting?.input?.name} description={setting.description ?? ''} label={setting.label} {isSubComponent} attributes={setting.attributes??{}} error={hasError}>

	{#if inputSetting.input}
		<svelte:component
			this={getInputComponent(inputSetting.input.type ?? '')}
			bind:data={data}
			bind:value={data[inputSetting.input?.name]}
			on:refresh
			disabled={isDisabled}
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
				locked={isDisabled}
				{showErrors}
				parent={inputSetting.input ? inputSetting.input.name : ''}/>
		</div>
	{/if}

</ColumnSetting>

{#if setting.children && !setting.is_parent }
	<ColumnSettings
		on:refresh
		bind:data={data}
		locked={isDisabled}
		{showErrors}
		settings={setting.children}
		parent={inputSetting.input ? inputSetting.input.name : ''}
	/>
{/if}