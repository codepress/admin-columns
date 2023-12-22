<script lang="ts">
    import ColumnItem from "./ColumnItem.svelte";
    import {getColumnSettings} from "../ajax/ajax";
    import {openedColumnsStore} from "../store/opened-columns";
    import ColumnUtils from "../utils/column";
    import AcDropdown from "ACUi/acui-dropdown/AcDropdown.svelte";
    import {ColumnTypesUtils} from "../utils/column-types";
    import AcButton from "ACUi/element/AcButton.svelte";
    import ListKeys from "../utils/ListKeys";
    import {ListScreenData} from "../../types/requests";
    import {listScreenDataStore} from "../store/list-screen-data";
    import {tick} from "svelte";
    import ColumnTypeDropdown from "./ColumnTypeDropdown.svelte";
    import {currentListKey} from "../store/current-list-screen";
    import ColumnsFormSkeleton from "./skeleton/ColumnsFormSkeleton.svelte";
    import {listScreenIsReadOnly} from "../store/read_only";

    export let data: ListScreenData;
    export let config: { [key: string]: AC.Column.Settings.ColumnSettingCollection };
    export let tableUrl: string;

    const columnTypes = ColumnTypesUtils.getColumnTypes();

    const clearColumns = () => {
        data['columns'] = {};
    }

    const addColumn = (column_type: string) => {
        const name = ColumnUtils.generateId();

        getColumnSettings($currentListKey, column_type).then(d => {
            const columnLabel = ColumnTypesUtils.getColumnType(column_type)?.label;
            config[name] = d.data.data.columns.settings;

            data['columns'][name] = {
                name: name,
                type: column_type,
                label: columnLabel ?? name
            };
            openedColumnsStore.open(name);
        });
    }

    const duplicateColumn = async (columnName: string) => {
        let foundColumn = data['columns'][columnName] ?? null;

        if (!foundColumn) {
            throw new Error(`Column ${columnName} could not be duplicated`);
        }

        const clonedName = ColumnUtils.generateId()

        data['columns'][clonedName] = Object.assign({}, foundColumn, {name: clonedName});
        await tick();
        openedColumnsStore.close(foundColumn.name);
        openedColumnsStore.open(clonedName);
        config[clonedName] = config[foundColumn.name];
    }

    const deleteColumn = (columnName: string) => {
        listScreenDataStore.deleteColumn(columnName);
    }

</script>


{#if data }
	<div class="ac-columns">
		<header class="ac-columns__header">
			<div class="ac-columns__header__table">
				<h1>{ListKeys.getLabelForKey( data.type )} <span class="dashicons dashicons-lock"></span></h1>
			</div>
			<div class="ac-columns__header__title">
				<input bind:value={data.title} disabled={$listScreenIsReadOnly}/>
			</div>
			<div class="ac-columns__header__action">
				<a href={tableUrl} class="acui-button  acui-button-primary">View</a>
			</div>
		</header>
		<div class="ac-columns__body">
			{#each Object.values( data.columns ) as column_data}
				<ColumnItem
						bind:config={ config[column_data.name] }
						bind:data={ column_data }
						on:delete={ ( e ) => deleteColumn( e.detail ) }
						on:duplicate={ ( e ) => duplicateColumn( e.detail ) }
				>

				</ColumnItem>
			{/each}
		</div>
		{#if !$listScreenIsReadOnly}
			<footer class="ac-columns__footer">
				<div>
					<AcButton type="text" on:click={clearColumns}>Clear Columns</AcButton>
					<AcDropdown maxHeight="300px" value position="bottom-left">
						<AcButton slot="trigger">+ Add Column</AcButton>
						<ColumnTypeDropdown on:selectItem={( e ) => addColumn(e.detail)}>

						</ColumnTypeDropdown>
					</AcDropdown>
				</div>
			</footer>
		{/if}
	</div>
{:else}
	<ColumnsFormSkeleton></ColumnsFormSkeleton>
{/if}

