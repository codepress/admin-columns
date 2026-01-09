<script lang="ts">
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import AcRadio from "ACUi/element/AcRadio.svelte";
    import axios from "axios";
    import SettingOption = AC.Column.Settings.SettingOption;

    declare const ajaxurl: string;

    export let config: AC.Column.Settings.DateFormatSetting;
    export let value: any;
    export let disabled: boolean = false;

    let options: SettingOption[] = [];
    let isCustom: boolean = false;
    let selectedOption: string = '';
    let customDateFormat: string = '';
    let customDateExample: string = '';
    let timer: ReturnType<typeof setTimeout>;

    const dispatch = createEventDispatcher();

    const debounceInput = () => {
        clearTimeout(timer);
        value = customDateFormat;
        updateExample();
    }

    const validExampleDates = [
        'j F Y',
        'Y-m-d',
        'm/d/Y',
        'd/m/Y',
    ];

    const updateExample = () => {
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

    const handleSelection = () => {
        value = selectedOption;
        if (validExampleDates.includes(selectedOption)) {
            customDateFormat = selectedOption;
            updateExample();
        }

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


<div style="padding-top: 5px;">
	{#each options as option}
		<AcRadio bind:group={selectedOption}
			value={option.value}
			{disabled}
			on:change={handleSelection}
			--AcuiRadioMarginBottom="5px">{option.label}
			{#if option.value === 'wp_default' && config.input?.data[ 'wp_date_format' ] }
				<code class="acu-bg-[#eee]">{config.input?.data[ 'wp_date_format' ]}</code>
			{/if}
		</AcRadio>
	{/each}
	<div class="custom">
		<AcRadio bind:group={selectedOption} value="custom" {disabled}>Custom</AcRadio>
		<div class="custom-input">
			<input type="text" bind:value={customDateFormat} on:keyup={ debounceInput } disabled={!isCustom || disabled}/>
			<div>
				{customDateExample}
			</div>
		</div>
	</div>
	{#if config.input.data[ 'wp_date_info' ] && selectedOption === 'wp_default' }
		<div class="acu-my-2">{@html config.input.data[ 'wp_date_info' ]}</div>
	{/if}
</div>

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