<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import AcRadio from "ACUi/element/AcRadio.svelte";
    import axios from "axios";
    import SettingOption = AC.Column.Settings.SettingOption;

    declare const ajax_url: string;

    export let config: AC.Column.Settings.DateFormatSetting;
    export let value: any;

    let options: SettingOption[] = [];
    let isCustom: boolean = false;
    let selectedOption: string = '';
    let customDateFormat: string = '';
    let customDateExample: string = '';
    let timer;

    const dispatch = createEventDispatcher();

    const debounceInput = v => {
        clearTimeout(timer);
        value = customDateFormat;
        timer = setTimeout(() => {
            retrieveDateExample()
        }, 750);
    }

    const retrieveDateExample = () => {
        let data = new FormData();
        data.set('action', 'date_format');
        data.set('date', customDateFormat);
        axios.post(ajaxurl, data).then(response => {
            customDateExample = response.data;
        });
    }

    onMount(() => {
        options = config.children[0].input.options;

        if (value === '' || typeof value === 'undefined') {
            let defaultValue = config.children[0].input?.default ?? null;
            value = defaultValue ? defaultValue : options[0].value;
        }

        selectedOption = value;
        if (!options.find(o => o.value === selectedOption)) {
            selectedOption = 'custom';
            customDateFormat = value;
            retrieveDateExample();
        }
    });

    onDestroy(() => {
        dispatch('destroy', config);
    });

    $: isCustom = selectedOption === 'custom';

</script>

<ColumnSetting label={config.label} description={config.description} name="date_format" top>
	<div style="padding-top: 5px;">
		{#each options as option}
			<AcRadio bind:group={selectedOption}
					value={option.value}
					--AcuiRadioMarginBottom="5px">{option.label}</AcRadio>
		{/each}
		<div class="custom">
			<AcRadio bind:group={selectedOption} value="custom">Custom</AcRadio>
			<div class="custom-input">
				<input type="text" bind:value={customDateFormat} on:keyup={ debounceInput } disabled={!isCustom}/>
				<div>
					{customDateExample}
				</div>
			</div>
		</div>
	</div>

</ColumnSetting>

<style>
	.custom {
		display: flex;
		align-items: center;
		gap: 20px;
	}

	.custom-input {
		display: flex;
		gap: 10px;
		align-items: center;
	}
</style>