<script lang="ts">
    import ColumnItem from "./ColumnItem.svelte";
    import {columnSettingsStore} from "../store/settings";
    import {getColumnSettings, saveListScreen} from "../ajax/ajax";
    import {openedColumnsStore} from "../store/opened-columns";
    import ColumnUtils from "../utils/column";
    import AcDropdown from "ACUi/acui-dropdown/AcDropdown.svelte";
    import AcDropdownItem from "ACUi/acui-dropdown/AcDropdownItem.svelte";
    import {ColumnTypesUtils} from "../utils/column-types";
    import {MappedListScreenData} from "../../types/admin-columns";
    import AcButton from "ACUi/element/AcButton.svelte";
    import ListKeys from "../utils/ListKeys";
    import {ListScreenData} from "../../types/requests";
    import {listScreenDataStore} from "../store/list-screen-data";

    export let data: ListScreenData;
    export let config;

    const columnTypes = ColumnTypesUtils.getColumnTypes();

    const clearColumns = () => {
        data['columns'] = {};
    }

    const addColumn = (column_type: string) => {
        const name = ColumnUtils.generateId();

        getColumnSettings('post', column_type).then(d => {
            columnSettingsStore.changeSettings(name, d.data.data.columns.settings);
            data['columns'][name] ={
                name: name,
                type: column_type,
                label: ''
            };
            openedColumnsStore.open(name);
        });
    }

    const deleteColumn = ( columnName: string ) => {
		listScreenDataStore.deleteColumn( columnName );
	}

</script>


{#if data }
	<div class="ac-columns">
		<header class="ac-columns__header">
			<div>
				<h1>{ListKeys.getLabelForKey(data.type)}</h1>
			</div>
			<input bind:value={data.title}/>
		</header>
		<div class="ac-columns__body">
			{#each Object.values(data.columns) as column_data}
				<ColumnItem
						bind:config={ config[column_data.name] }
						bind:data={ column_data }
						on:delete={ ( e ) => deleteColumn( e.detail ) }
				>

				</ColumnItem>
			{/each}
		</div>
		<footer class="ac-columns__footer">
			<div>
				<AcButton type="text" on:click={clearColumns}>Clear Columns</AcButton>
				<AcDropdown maxHeight="300px" value>
					<AcButton slot="trigger" on:click={clearColumns}>+ Add Column</AcButton>
					{#each columnTypes as column, i}
						<AcDropdownItem on:click={() => addColumn(column.type) }
								value={column.type}>{@html column.label}</AcDropdownItem>
					{/each}
				</AcDropdown>
			</div>
		</footer>
	</div>

{/if}

