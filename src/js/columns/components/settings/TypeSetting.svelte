<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import Select from "svelte-select"
    import {getColumnSettings} from "../../ajax/ajax";
    import {columnTypesStore} from "../../store/column-types";
    import {ListScreenColumnData, ListScreenData} from "../../../types/requests";
    import {SvelteSelectItem} from "../../../types/select";

    export let data: ListScreenData;
    export let config: AC.Column.Settings.ToggleSetting;
    export let columnConfig: ListScreenColumnData;
    export let disabled: boolean = false;

    let selectValue: string;

    const changeValue = () => {
        data['type'] = selectValue;

        getColumnSettings('post', selectValue).then(response => {
            columnConfig = response.data.data.columns.settings;

            setTimeout(() => {
                columnConfig = columnConfig;
            }, 1000)
        });

    }

    const groupBy = (item: SvelteSelectItem) => item.group;
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