<script lang="ts">
    import Select from "svelte-select"
    import {getColumnSettings} from "../../../ajax/ajax";
    import {columnTypesStore} from "../../../store/column-types";
    import {ListScreenColumnData} from "../../../../types/requests";
    import {SvelteSelectItem} from "../../../../types/select";
    import ColumnUtils from "../../../utils/column";
    import {ColumnTypesUtils} from "../../../utils/column-types";
    import {listScreenDataStore} from "../../../store/list-screen-data";
    import {NotificationProgrammatic} from "../../../../ui-wrapper/notification";
    import {getColumnSettingsTranslation} from "../../../utils/global";
    import {currentListKey} from "../../../store/current-list-screen";
    import {openedColumnsStore} from "../../../store/opened-columns";
    import ColumnTypeGroupIcon from "../../ColumnTypeGroupIcon.svelte";

    export let data: ListScreenColumnData;
    export let columnConfig: AC.Column.Settings.ColumnSettingCollection;
    export let disabled: boolean = false;

    const i18n = getColumnSettingsTranslation();

    let selectValue: string;
    let value = data.type

    const changeValue = () => {
        const oldValue = data.type ?? '';
        const columnType = $columnTypesStore.find( c => c.value === selectValue);

        if (ColumnTypesUtils.isOriginalColumnType(selectValue)) {
            if ($listScreenDataStore.columns.find(c => c.name === selectValue)) {
                value = data.type;
                NotificationProgrammatic.open({
                    type: "error",
                    message: i18n.errors.original_exist.replace('%s', selectValue)
                })
                return;
            }
            data.name = selectValue;
        }

        if (ColumnTypesUtils.isOriginalColumnType(oldValue)) {
            data.name = ColumnUtils.generateId();
        }

        if( columnType){
            data.label = columnType.label;
            data.type = columnType.value;
		}

        openedColumnsStore.open(data.name);

        getColumnSettings($currentListKey, selectValue).then(response => {
            columnConfig = response.data.data.column.settings;

            setTimeout(() => {
                columnConfig = columnConfig;
            }, 500)
        });

    }

    const groupBy = (item: SvelteSelectItem) => item.group;
</script>

<Select class="-acui"
	--list-max-height="400px"
	showChevron
	clearable={false}
	items={$columnTypesStore}
	{groupBy}
	{disabled}
	bind:value={value}
	on:change={ changeValue }
	bind:justValue={selectValue}>
	<div slot="item" let:item>
		{#if item.groupItem}
				<span class="acu-flex acu-items-center acu-relative acu-pl-1">
					<ColumnTypeGroupIcon group_key={item.group_key}/>
					{item.label}
				</span>
		{:else}
			{item.label}
		{/if}
	</div>
</Select>
