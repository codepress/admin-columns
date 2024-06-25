<script lang="ts">
    import Select from "svelte-select"
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import {SvelteSelectItem} from "../../../../types/select";

    export let config: AC.Column.Settings.SelectSetting;
    export let disabled: boolean = false;
    export let value: string | undefined | number;

    const dispatch = createEventDispatcher();

    let selectValue: SvelteSelectItem | null;
    let options: AC.Column.Settings.SettingOption[] = [];

    const getValue = (value: string): SvelteSelectItem => {
        const found = options.find(o => o.value === value);

        return found ? found : {value: value, label: value};
    }

    onMount(() => {
        options = config.input.options;

        if (typeof value === 'undefined') {
            if (config.input.default) {
                selectValue = getValue(config.input.default);
            }

            value = config.input.default ?? '';
        } else {
            selectValue = getValue(value.toString());
        }

    })

    onDestroy(() => {
        dispatch('destroy', config);
    });

    const mustRefresh = () => {
        return config.input.attributes?.refresh === 'config';
    }

    const changeValue = (e: CustomEvent<SvelteSelectItem>) => {
        value = e.detail.value;
        if (mustRefresh()) {
            dispatch('refresh');
        }
    }

    const groupBy = (item: SvelteSelectItem) => item.group;
</script>


<Select
	--list-max-height="400px"
	class="-acui"
	clearable={false}
	items={options}
	showChevron
	value={selectValue}
	{groupBy}
	{disabled}
	on:change={ changeValue }>

</Select>
