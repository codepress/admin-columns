<script lang="ts">
    import ColumnItem from "./ColumnItem.svelte";
    import {getColumnSettings} from "../ajax/ajax";
    import {openedColumnsStore} from "../store/opened-columns";
    import ColumnUtils from "../utils/column";
    import AcDropdown from "ACUi/acui-dropdown/AcDropdown.svelte";
    import {ColumnTypesUtils} from "../utils/column-types";
    import AcButton from "ACUi/element/AcButton.svelte";
    import ListKeys from "../utils/ListKeys";
    import {ListScreenColumnsData, ListScreenData} from "../../types/requests";
    import {listScreenDataStore} from "../store/list-screen-data";
    import {tick} from "svelte";
    import ColumnTypeDropdown from "./ColumnTypeDropdown.svelte";
    import {currentListKey} from "../store/current-list-screen";
    import ColumnsFormSkeleton from "./skeleton/ColumnsFormSkeleton.svelte";
    import {listScreenIsReadOnly} from "../store/read_only";
    import {columnTypesStore} from "../store/column-types";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";

    declare const jQuery: any;

    export let data: ListScreenData;
    export let config: { [key: string]: AC.Column.Settings.ColumnSettingCollection };
    export let tableUrl: string;

    export let start: number | null = 0;
    export let end: number | null = 0;

    let sortableContainer: HTMLElement | null;

    const clearColumns = () => {
        data['columns'] = {};
    }

    const addColumn = (column_type: string) => {
        const columninfo = $columnTypesStore.find(c => c.value === column_type) ?? null;
        let name = ColumnUtils.generateId();

        if (!columninfo) {
            return;
        }

        if (columninfo.original) {
            name = columninfo.value;

            if (data.columns.hasOwnProperty(name)) {
                return NotificationProgrammatic.open({
                    message: 'Original column already available',
                    type: 'warning'
                });
            }
        }

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

    const applyNewColumnsOrder = (from: number, to: number) => {
        let sorted_columns = Object.values(data.columns);
        const item = sorted_columns[from];
        sorted_columns.splice(from, 1);
        sorted_columns.splice(to, 0, item);

        let newSortedColumns: ListScreenColumnsData = {};

        sorted_columns.forEach(d => {
            newSortedColumns[d.name] = d;
        });

        data.columns = newSortedColumns;
    }


    listScreenDataStore.subscribe(() => {
        jQuery(sortableContainer).sortable({
            axis: 'y',
            containment: jQuery(sortableContainer),
            handle: '.ac-column-header__move',
            start: (e: Event, ui: any) => {
                start = parseInt(ui.item.index());
            },
            stop: (e: Event, ui: any) => {
                end = ui.item.index();

                if (start !== null && end !== null) {
                    applyNewColumnsOrder(start, end);
                    start = null;
                    end = null;
                }
            }
        });
    })

</script>


{#if data }
	<div class="ac-columns">
		<header class="ac-columns__header">
			<div class="ac-columns__header__table">
				<h1>
					{ListKeys.getLabelForKey( data.type )}
					{#if $listScreenIsReadOnly}
						<span class="dashicons dashicons-lock"></span>
					{/if}
				</h1>
			</div>
			<div class="ac-columns__header__title">
				<input bind:value={data.title} disabled={$listScreenIsReadOnly}/>
			</div>
			<div class="ac-columns__header__action">
				<a href={tableUrl} class="acui-button  acui-button-primary">View</a>
			</div>
		</header>

		<div class="ac-columns__body">
			{#if Object.keys( data.columns ).length === 0}
				<div class="acu-p-10 acu-bg-[#F1F5F9]">
					<div class="acu-text-center">
						<h2>Add Columns</h2>
						<p>The default columns will be shown on the list table when no columns are added.</p>
					</div>

					<div class="acu-flex acu-gap-3 acu-items-center acu-justify-center acu-py-8">
						<AcDropdown maxHeight="300px" value position="bottom-left">
							<AcButton slot="trigger">+ Add Column</AcButton>
							<ColumnTypeDropdown on:selectItem={( e ) => addColumn(e.detail)}>

							</ColumnTypeDropdown>
						</AcDropdown>
						<span>Or</span>
						<AcButton>Load default columns</AcButton>
					</div>
					<div class="acu-text-center">
						<p>New to Admin Columns? Take a look at our <a href="#d">getting started guide</a>.</p>
					</div>
				</div>
			{/if}

			<div bind:this={sortableContainer}>
				{#each Object.values( data.columns ) as column_data(column_data.name)}
					<ColumnItem
						bind:config={ config[column_data.name ?? column_data.type] }
						bind:data={ column_data }
						on:delete={ ( e ) => deleteColumn( e.detail ) }
						on:duplicate={ ( e ) => duplicateColumn( e.detail ) }
					/>
				{/each}
			</div>
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

