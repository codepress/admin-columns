<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import Select from "svelte-select"
    import {onMount} from "svelte";
    import {SvelteSelectItem} from "../../../types/select";

    export let config: AC.Column.Settings.SelectSetting;
    export let value: string | undefined | number;

    let selectValue: SvelteSelectItem;
    let options: AC.Column.Settings.SettingOption[] = [];

    onMount(() => {
        options = config.input.options;

        if (typeof value === 'undefined') {
            selectValue = config.input.options[0]
            value = config.input.options[0].value;
        }

    })

    const changeValue = (e: CustomEvent<SvelteSelectItem>) => {
        value = e.detail.value;
    }

    const groupBy = (item: SvelteSelectItem) => item.group;
</script>

<ColumnSetting label={config.label}>
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