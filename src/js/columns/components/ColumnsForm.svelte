<script lang="ts">
    import ColumnItem from "./ColumnItem.svelte";
    import {getColumnSettings, loadDefaultColumns} from "../ajax/ajax";
    import {openedColumnsStore} from "../store/opened-columns";
    import ColumnUtils from "../utils/column";
    import AcDropdown from "ACUi/acui-dropdown/AcDropdown.svelte";
    import {ColumnTypesUtils} from "../utils/column-types";
    import AcButton from "ACUi/element/AcButton.svelte";
    import ListKeys from "../utils/list-keys";
    import {ListScreenColumnData, ListScreenData} from "../../types/requests";
    import {listScreenDataStore} from "../store/list-screen-data";
    import {onMount, tick} from "svelte";
    import {currentListKey} from "../store/current-list-screen";
    import ColumnsFormSkeleton from "./skeleton/ColumnsFormSkeleton.svelte";
    import {listScreenIsReadOnly} from "../store/read_only";
    import {columnTypesStore} from "../store/column-types";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";
    import {getColumnSettingsTranslation} from "../utils/global";
    import {sprintf} from "@wordpress/i18n";
    import ColumnTypeDropdownV2 from "./ColumnTypeDropdownV2.svelte";

    const i18n = getColumnSettingsTranslation();

    export let data: ListScreenData;
    export let config: { [key: string]: AC.Column.Settings.ColumnSettingCollection };
    export let locked: boolean = true;

    let start: number | null = 0;
    let end: number | null = 0;
    let sortableContainer: HTMLElement | null;
    let loadingDefaultColumns: boolean = false;
    let columnTypeComponent: AcDropdown | null;

    const clearColumns = () => {
        data['columns'] = [];
    }

    const addColumn = (column_type: string) => {
        const columninfo = $columnTypesStore.find(c => c.value === column_type) ?? null;
        let name = ColumnUtils.generateId();

        if (!columninfo) {
            return;
        }

        if (columninfo.original) {
            name = columninfo.value;

            if (data.columns.find(c => c.name === name)) {
                return NotificationProgrammatic.open({
                    message: i18n.editor.sentence.original_already_exists,
                    type: 'warning'
                });
            }
        }

        getColumnSettings($currentListKey, column_type).then(d => {
            const columnLabel = ColumnTypesUtils.getColumnType(column_type)?.label;
            config[name] = d.data.data.column.settings;

            data['columns'].push({
                name: name,
                type: column_type,
                label: columnLabel ?? name
            });
            openedColumnsStore.open(name);
        }).catch((e) => {
            NotificationProgrammatic.open({
                message: e.message,
                type: 'error'
            });
        });
    }

    const duplicateColumn = async (columnName: string) => {
        let foundColumn = data.columns.find(c => c.name === columnName) ?? null;
        let foundIndex = data.columns.findIndex(c => c.name === columnName) ?? null;

        if (!foundColumn) {
            throw new Error(sprintf(i18n.editor.sentence.column_no_duplicate, columnName));
        }

        const clonedName = ColumnUtils.generateId();

        data.columns.splice(foundIndex + 1, 0, Object.assign({}, foundColumn, {name: clonedName}))

        await tick();

        openedColumnsStore.close(foundColumn.name);
        openedColumnsStore.open(clonedName);
        config[clonedName] = config[foundColumn.name];
        scrollToColumn(columnName);
    }

    const scrollToColumn = (columnName: string) => {
        let columnElement = document.querySelector<HTMLElement>(`.ac-column[data-name="${columnName}"]`);

        if (columnElement) {
            window.scroll({
                top: columnElement.offsetTop,
                behavior: "smooth"
            })
        }
    }

    const deleteColumn = (columnName: string) => {
        listScreenDataStore.deleteColumn(columnName);
    }

    const applyNewColumnsOrder = (from: number, to: number) => {
        let sorted_columns = data.columns;
        const item = sorted_columns[from];
        sorted_columns.splice(from, 1);
        sorted_columns.splice(to, 0, item);

        let newSortedColumns: ListScreenColumnData[] = [];

        sorted_columns.forEach(d => {
            newSortedColumns.push(d);
        });

        data.columns = newSortedColumns;
    }

    const handleLoadDefaultColumns = () => {
        loadingDefaultColumns = true;
        loadDefaultColumns($currentListKey).then(response => {
            if (response.data.success) {
                data.columns = response.data.data.columns;
                config = response.data.data.config;
                loadingDefaultColumns = false;
            }
        })
    }

    const makeSortable = () => {
        const JQ: any = jQuery;
        JQ(sortableContainer).sortable({
            axis: 'y',
            containment: JQ(sortableContainer),
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
    }


    listScreenDataStore.subscribe(() => {
        makeSortable();
    })

    const handleSelectColumnType = (d: CustomEvent<string>) => {
        addColumn(d.detail);
        columnTypeComponent!.close();
    }

    const handleCloseColumnTypeDropdown = (component) => {
        component.close();
    }

    onMount(() => {
        setTimeout(makeSortable, 1000);
    });

</script>


<!--<DebugToolbar bind:data={data} bind:config={config}/>-->

{#if data }
	<div class="ac-columns acu-shadow">
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
				<input bind:value={data.title} disabled={locked}/>
			</div>

		</header>

		<div class="ac-columns__body">

			{#if data.columns.length === 0 || data.columns === null}
				<div class="acu-p-10 acu-bg-[#F1F5F9]">
					<div class="acu-text-center acu-font-bold">
						<p>{i18n.editor.sentence.get_started}</p>
					</div>

					<div class="acu-flex acu-gap-3 acu-items-center acu-justify-center acu-pt-4 acu-pb-6">
						<AcDropdown
							--acui-dropdown-width="300px"
							value
							position="bottom-right"
							customClass="-selectv2">

							<AcButton slot="trigger">+ {i18n.editor.label.add_column}</AcButton>
							<ColumnTypeDropdownV2
								on:selectItem={handleSelectColumnType}
								on:close={() => handleCloseColumnTypeDropdown(columnTypeComponent)}
							/>

						</AcDropdown>

						<AcButton
							loading={loadingDefaultColumns}
							--acui-loading-color="#000"
							on:click={handleLoadDefaultColumns}
							label={i18n.editor.label.load_default_columns}
						/>
					</div>
					<div class="acu-text-center acu-text-12px">
						<p>{@html i18n.editor.sentence.documentation}</p>
					</div>
				</div>
			{/if}

			<div bind:this={sortableContainer}>
				{#each data.columns as column_data(column_data.name)}

					<ColumnItem
						locked={locked}
						bind:config={ config[column_data.name ?? column_data.type] }
						bind:data={ column_data }
						on:delete={ ( e ) => deleteColumn( e.detail ) }
						on:duplicate={ ( e ) => duplicateColumn( e.detail ) }
					/>

				{/each}
			</div>
		</div>
		<footer class="ac-columns__footer">
			{#if !$listScreenIsReadOnly && ! locked}
				<div>
					{#if data.columns.length > 0}
						<AcButton
							type="text"
							on:click={clearColumns}
							label={i18n.editor.label.clear_columns}
						/>
					{/if}
					<AcDropdown
						--acui-dropdown-width="300px"
						customClass="-selectv2"
						maxHeight="400px"
						value
						position="bottom-left" bind:this={columnTypeComponent}>

						<AcButton
							slot="trigger"
							type="primary"
							label={`+ ${i18n.editor.label.add_column}`}
						/>

						<ColumnTypeDropdownV2
							on:selectItem={handleSelectColumnType}
							on:close={() => handleCloseColumnTypeDropdown(columnTypeComponent)}
						/>

					</AcDropdown>
				</div>

			{/if}
		</footer>
	</div>
{:else}
	<ColumnsFormSkeleton/>
{/if}

