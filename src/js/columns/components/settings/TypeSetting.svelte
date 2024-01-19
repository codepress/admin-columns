<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import Select from "svelte-select"
    import {onMount} from "svelte";
    import {getColumnSettings} from "../../ajax/ajax";
    import {columnTypesStore} from "../../store/column-types";
    import {ListScreenData} from "../../../types/requests";

    export let data: ListScreenData;
    export let config: AC.Column.Settings.ToggleSetting;
    export let columnConfig;
    export let disabled: boolean = false;

    let collection = [];
    let selectValue: string;

    onMount(() => {
        collection = $columnTypesStore
    })

    const changeValue = () => {
        data['type'] = selectValue;

        getColumnSettings('post', selectValue).then(response => {
            columnConfig = response.data.data.columns.settings;

            setTimeout(() => {
                columnConfig = columnConfig;
            }, 1000)
        });

    }

    const groupBy = (item) => item.group;
</script>

<ColumnSetting label={config.label} description={config.description} name="type">
	<Select class="-acui"
		--list-max-height="400px"
		showChevron
		clearable={false}
		items={$columnTypesStore}
		{groupBy}
		{disabled}
		value={data['type']}
		on:change={ changeValue }
		bind:justValue={selectValue}>

	</Select>
</ColumnSetting>