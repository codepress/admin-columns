<script lang="ts">
    import ColumnItem from "./ColumnItem.svelte";
    import {getColumnSettings, loadDefaultColumns} from "../ajax/ajax";
    import ColumnUtils from "../utils/column";
    import {ColumnTypesUtils} from "../utils/column-types";
    import ListKeys from "../utils/list-keys";
    import {ListScreenColumnData, ListScreenData} from "../../types/requests";
    import {
        columnTypesStore,
        currentListKey,
        isLoadingColumnSettings,
        listScreenDataHasChanges,
        listScreenDataStore,
        listScreenIsReadOnly,
        listScreenIsStored,
        openedColumnsStore
    } from "../store";
    import {createEventDispatcher, tick} from "svelte";
    import ColumnsFormSkeleton from "./skeleton/ColumnsFormSkeleton.svelte";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";
    import {getColumnSettingsTranslation} from "../utils/global";
    import {sprintf} from "@wordpress/i18n";
    import ColumnTypeDropdownV2 from "./ColumnTypeDropdownV2.svelte";
    import {AcButton, AcDropdown, AcPanel, AcPanelFooter, AcPanelHeader, AcPanelTitle} from "ACUi/index";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
    import JQSorter from "./JQSorter.svelte";
    import cloneDeep from "lodash-es/cloneDeep";

    const i18n = getColumnSettingsTranslation();
    const dispatch = createEventDispatcher();

    export let data: ListScreenData;
    export let config: { [key: string]: AC.Column.Settings.ColumnSettingCollection };
    export let locked: boolean = true;
    export let isSaving: boolean = false;

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

    const handleUpdateColumn = (item:ListScreenColumnData) => {
        const index = data.columns.findIndex(c => c.name === item.name);
        if (index !== -1) {
            const current = data.columns[index];
            const incoming = item;

            if (JSON.stringify(current) !== JSON.stringify(incoming)) {
                const updated = [...data.columns];
                updated[index] = incoming;
                data.columns = updated;
            }
        }
    }

    const handleSelectColumnType = (d: CustomEvent<string>) => {
        addColumn(d.detail);
        columnTypeComponent!.close();
    }

    const handleCloseColumnTypeDropdown = (component) => {
        component.close();
    }

</script>

<!--<DebugToolbar bind:data={data} bind:config={config}/>-->
{#if data && !$isLoadingColumnSettings}
	<AcPanel>
		<AcPanelHeader slot="header" border>

			<div class="acu-flex acu-gap-4">
				<div class="acu-flex acu-gap-2 acu-items-center">
					<AcPanelTitle title={ListKeys.getLabelForKey( data.type ) ?? ''}/>
					{#if $listScreenIsReadOnly}
						<span class="dashicons dashicons-lock"></span>
					{/if}
				</div>
				<div class="acu-flex-grow acu-max-w-[400px]">
					<AcInputGroup>
						<input bind:value={data.title} disabled={locked} type="text"/>
					</AcInputGroup>
				</div>
			</div>

		</AcPanelHeader>

		<div slot="body">
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

			<JQSorter
				items={cloneDeep( data.columns )}
				onSort={applyNewColumnsOrder}
				itemKey={(col) => col.name}
			>
				<svelte:fragment slot="item" let:item>
					<ColumnItem
						locked={locked}
						bind:config={ config[item.name ?? item.type] }
						data={ item }
						on:delete={ (e) => deleteColumn(e.detail) }
						on:duplicate={ (e) => duplicateColumn(e.detail) }
						on:update={(e) => handleUpdateColumn(e.detail)}
					/>
				</svelte:fragment>
			</JQSorter>


		</div>
		<AcPanelFooter slot="footer" classNames={['acu-flex acu-justify-end acu-gap-2']}>
			{#if !$listScreenIsReadOnly && !locked}

				{#if data.columns.length > 0}
					<AcButton
						type="text"
						on:click={clearColumns}
						label={i18n.editor.label.clear_columns}
					/>
				{/if}

				<AcButton
					type="primary"
					softDisabled={isSaving}
					loading={isSaving}
					on:click={() => dispatch('saveListScreen', data)  }
					disabled={!$listScreenDataHasChanges && $listScreenIsStored}
					label={i18n.editor.label.save}
				/>

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


			{/if}
		</AcPanelFooter>
	</AcPanel>
{:else}
	<ColumnsFormSkeleton/>
{/if}


