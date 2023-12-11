<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import Select from "svelte-select"
    import {createEventDispatcher, onDestroy, onMount} from "svelte";
    import {SvelteSelectItem} from "../../../types/select";

    export let config: AC.Column.Settings.SelectSetting;
    export let data: any;
    export let value: string | undefined | number;

    const dispatch = createEventDispatcher();

    let selectValue: SvelteSelectItem|null;
    let options: AC.Column.Settings.SettingOption[] = [];


    const getValue =  ( value: string ): SvelteSelectItem => {
        const found = options.find( o => o.value === value );

        return found ? found : { value: value, label: value };
	}

    onMount(() => {
        options = config.input.options;

        if (typeof value === 'undefined') {
            if( config.input.default ){
                selectValue = getValue( config.input.default );
			}

            value = config.input.default ?? '';
        } else {
            selectValue = getValue( value.toString() );
		}

    })

    onDestroy(() => {
        dispatch('destroy', config);
    });

    const changeValue = (e: CustomEvent<SvelteSelectItem>) => {
        value = e.detail.value;
    }

    const groupBy = (item: SvelteSelectItem) => item.group;
</script>


<ColumnSetting
		label={config.label}
		description={config.description}
		config={config}
		children={config.children ?? []}
		bind:data={data}
		name="select">
	<Select
			--list-max-height="400px"
			class="-acui"
			clearable={false}
			items={options}
			showChevron
			value={selectValue}
			{groupBy}
			on:change={ changeValue }>

	</Select>
</ColumnSetting>