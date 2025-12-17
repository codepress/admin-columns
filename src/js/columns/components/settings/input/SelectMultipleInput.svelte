<script lang="ts">
    import Select from "svelte-select"
    import {createEventDispatcher, onDestroy, onMount, tick} from "svelte";
    import {SvelteSelectItem} from "../../../../types/select";

    export let config: AC.Column.Settings.SelectSetting;
    export let disabled: boolean = false;
    export let value: Array<string> | undefined;

    const dispatch = createEventDispatcher();

    let selectValue: SvelteSelectItem[] | null;
    let justValue: Array<string>;
    let options: AC.Column.Settings.SettingOption[] = [];

    const getValue = (value: string): SvelteSelectItem => {
        const found = options.find(o => o.value === value);

        return found ? found : {value: value, label: value};
    }

    const mapArrayToValue = (array: Array<string>): SvelteSelectItem[] => {
        let newValue: SvelteSelectItem[] = [];

        if( Array.isArray(array) && array.length > 0 ) {
            array.forEach(v => {
                let mapped = getValue(v);
                if (mapped) {
                    newValue.push(mapped);
                }
            })
        }

        return newValue;
    }

    onMount(() => {
        options = config.input.options;

        if (typeof value === 'undefined') {
            if (config.input.default && Array.isArray(config.input.default)) {
                selectValue = mapArrayToValue(config.input.default);
            }

            value = (config.input.default as unknown as string[]) ?? [];
        } else {
            selectValue = mapArrayToValue(value);
        }

    })

    onDestroy(() => {
        dispatch('destroy', config);
    });

    const changeValue = (e: CustomEvent<SvelteSelectItem>) => {
        value = justValue;
    }

    const clearValue = (e: CustomEvent<SvelteSelectItem>) => {
        value = Array.isArray(selectValue) ? selectValue.map(i => i.value.toString()) : [];
    }

    const groupBy = (item: SvelteSelectItem) => item.group;
</script>

<Select
	--list-max-height="400px"
	class="-acui"
	clearable={false}
	items={options}
	showChevron
	multiple
	bind:value={selectValue}
	bind:justValue={justValue}
	{groupBy}
	{disabled}
	on:clear={ clearValue }
	on:change={ changeValue }>

</Select>
