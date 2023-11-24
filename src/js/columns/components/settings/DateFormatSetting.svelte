<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import AcCheckbox from "ACUi/element/AcCheckbox.svelte";
    import AcRadio from "ACUi/element/AcRadio.svelte";
    import SettingOption = AC.Column.Settings.SettingOption;

    export let config: AC.Column.Settings.DateFormatSetting;
    export let value: any;

    let options: SettingOption[] = [];

    const dispatch = createEventDispatcher();

    onMount(() => {
        options = config.children[0].input.options;
    });

    onDestroy(() => {
        dispatch('destroy', config);
    })

</script>

<ColumnSetting label={config.label} name="date_format">
		[[
	{value}
	]]
	{#each options as option}

		<AcRadio bind:group={value} value={option.value}>{option.label}</AcRadio>
		<AcCheckbox>{option.label}</AcCheckbox>
	{/each}


</ColumnSetting>