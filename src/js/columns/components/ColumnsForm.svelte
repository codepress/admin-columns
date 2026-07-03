<script lang="ts">
    import ColumnItem from "./ColumnItem.svelte";
    import {getColumnSettings, loadDefaultColumns, listScreenTemplatesForScreen, listScreenTemplateSettings, ScreenTemplate} from "../ajax/ajax";
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
        listScreenLabels,
        openedColumnsStore,
        showColumnInfo
    } from "../store";
    import {createEventDispatcher, onMount, tick} from "svelte";
    import ColumnsFormSkeleton from "./skeleton/ColumnsFormSkeleton.svelte";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";
    import {getColumnSettingsTranslation} from "../utils/global";
    import {sprintf} from "@wordpress/i18n";
    import ColumnTypeDropdownV2 from "./ColumnTypeDropdownV2.svelte";
    import {AcButton, AcCheckbox, AcDropdown, AcDropdownItem, AcPanel, AcPanelFooter, AcPanelHeader, AcPanelTitle} from "ACUi/index";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
    import {clone} from "lodash-es";
    import {MaterialIcon} from "@ac/material-icons/src";
    import {persistScreenOptions} from "../ajax/settings";

    const i18n = getColumnSettingsTranslation();
    const dispatch = createEventDispatcher();

    export let data: ListScreenData;
    export let config: { [key: string]: AC.Column.Settings.ColumnSettingCollection };
    export let locked: boolean = true;
    export let isSaving: boolean = false;

    let undoState: ListScreenData | null = null;
    let start: number | null = 0;
    let end: number | null = 0;
    let sortableContainer: HTMLElement | null;
    let loadingDefaultColumns: boolean = false;
    let columnTypeComponent: AcDropdown | null;
    let templates: ScreenTemplate[] = [];
    let applyingTemplate: boolean = false;


    const clearColumns = () => {
        undoState = clone(data);
        data['columns'] = [];
    }

    const undo = () => {
        if (undoState) {
            data = clone(undoState);
            undoState = null;
        }
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
        undoState = null;
    }

    const duplicateColumn = async (columnName: string) => {
        let foundColumn = data.columns.find(c => c.name === columnName) ?? null;
        let foundIndex = data.columns.findIndex(c => c.name === columnName);

        if (!foundColumn) {
            throw new Error(sprintf(i18n.editor.sentence.column_no_duplicate, columnName));
        }

        const clonedName = ColumnUtils.generateId();

        data.columns.splice(foundIndex + 1, 0, Object.assign({}, foundColumn, {name: clonedName}))

        await tick();

        openedColumnsStore.close(foundColumn.name);
        openedColumnsStore.open(clonedName);
        config[clonedName] = config[foundColumn.name];
        scrollToColumn(clonedName);
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

    const handleLoadDefaultColumns = async () => {
        loadingDefaultColumns = true;

        loadDefaultColumns($currentListKey).then(response => {
            if (response.data.success) {
                config = response.data.data.config;
                data.columns = response.data.data.columns;
                loadingDefaultColumns = false;
            }
        })
    }

    const handleTemplateSelect = (e: CustomEvent<string>) => {
        handleApplyTemplate(e.detail);
    }

    const handleApplyTemplate = async (templateId: string) => {
        if (!templateId || applyingTemplate) {
            return;
        }

        applyingTemplate = true;

        try {
            const response = await listScreenTemplateSettings(templateId);

            if (response.data.success) {
                const payload = response.data.data;

                config = payload.config;
                data.columns = payload.columns;
            }
        } finally {
            applyingTemplate = false;
        }
    }

    const makeSortable = () => {
        const JQ: any = (window as any).jQuery;
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
	
    const handleCloseColumnTypeDropdown = (component: AcDropdown | null) => {
        component?.close();
    }

    const toggleColumnInfo = (e: CustomEvent<boolean>) => {
        persistScreenOptions('show_column_info', e.detail === true ? 1 : 0)
    }

    onMount(() => {
        setTimeout(makeSortable, 1000);
    });

    onMount(() => {
        listScreenTemplatesForScreen($currentListKey)
            .then(response => {
                if (response.data.success) {
                    templates = response.data.data;
                }
            })
            .catch(() => {
                templates = [];
            });
    });

</script>

<!--<DebugToolbar bind:data={data} bind:config={config}/>-->
{#if data && !$isLoadingColumnSettings && !loadingDefaultColumns}
	<AcPanel>
		<AcPanelHeader slot="header" border>

			<div class="acu-flex acu-gap-4 acu-items-center">
				<div class="acu-flex acu-gap-2 acu-items-center">
					<AcPanelTitle title={ListKeys.getLabelForKey( data.type ) ?? ''}/>
					{#if $listScreenIsReadOnly}
						<span class="dashicons dashicons-lock"></span>
					{/if}
				</div>
				<div class="acu-flex-grow">
					<AcInputGroup>
						<input bind:value={data.title}
							id="listTitle"
							disabled={locked}
							type="text"
							data-default={$listScreenLabels?.singular}
							placeholder={i18n.settings.label.table_view_label}/>
					</AcInputGroup>
				</div>
				{#if $showColumnInfo}
					<div class="acu-flex-shrink acu-flex acu-flex-col acu-leading-snug acu-text-[#888]">
						<div><small><strong>Table:</strong> {data.type}</small></div>
						<div><small><strong>ID:</strong> {data.id}</small></div>
					</div>
				{/if}
				<AcDropdown closeOnClick={false}>
					<MaterialIcon icon="more_vert" slot="trigger" className="acu-cursor-pointer"/>
					<div on:click|stopPropagation role="none" class="acu-p-4">
						<AcCheckbox label={i18n.settings.label.column_info} bind:value={$showColumnInfo}
							on:input={toggleColumnInfo}/>
					</div>
				</AcDropdown>
			</div>

		</AcPanelHeader>

		<div slot="body">
			{#if data.columns === null || data.columns.length === 0}
				<div class="acu-p-10 acu-bg-[#F1F5F9]">
					{#if locked}
						<div class="acu-text-center acu-text-gray-500 acu-py-4">
							<p>{@html i18n.editor.sentence.columns_read_only}</p>
						</div>
					{:else}
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

						{#if templates.length > 0}
							<div class="acu-mx-auto acu-w-[28rem] acu-max-w-full acu-text-left acu-pb-4">
								<div class="acu-flex acu-items-center acu-gap-3 acu-pb-4">
									<span class="acu-flex-1 acu-h-px acu-bg-gray-200"></span>
									<span class="acu-text-12px acu-font-semibold acu-uppercase acu-tracking-wide acu-text-gray-400">
										{i18n.editor.sentence.or_use_template}
									</span>
									<span class="acu-flex-1 acu-h-px acu-bg-gray-200"></span>
								</div>

								<label class="acu-block acu-font-bold acu-text-gray-800 acu-pb-1.5">
									{i18n.editor.label.browse_templates}
								</label>

								<AcDropdown
									position="bottom-right"
									customClass="-selectv2"
									--acui-dropdown-width="28rem"
									on:change={handleTemplateSelect}
								>
									<button
										slot="trigger"
										let:active
										type="button"
										class="ac-template-select acu-flex acu-items-center acu-justify-between acu-gap-2 acu-w-[28rem] acu-max-w-full acu-px-4 acu-py-2.5 acu-bg-white acu-rounded-md acu-text-gray-600"
										class:-active={active}
									>
										<span>{i18n.editor.label.select_template}</span>
										<svg
											class="acu-w-4 acu-h-4 acu-text-gray-500 acu-transition-transform {active ? 'acu-rotate-180' : ''}"
											viewBox="0 0 20 20"
											fill="none"
											stroke="currentColor"
											stroke-width="1.8"
											aria-hidden="true"
										>
											<path d="M6 8l4 4 4-4" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</button>

									{#each templates as template (template.id)}
										<AcDropdownItem value={template.id}>
											<span class="acu-flex acu-flex-col acu-py-1">
												<span class="acu-font-bold acu-text-gray-800">{template.title}</span>
												<span class="acu-text-gray-400">{template.column_count} {i18n.editor.label.columns_count}</span>
											</span>
										</AcDropdownItem>
									{/each}
								</AcDropdown>
							</div>
						{/if}
						<div class="acu-text-center acu-text-12px">
							<p>{@html i18n.editor.sentence.documentation}</p>
						</div>
					{/if}
				</div>
			{/if}

			<div bind:this={sortableContainer} class="acu-relative">
				{#each data.columns as column_data (column_data.name)}

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

		<AcPanelFooter slot="footer" border={data.columns.length === 0} classNames={['acu-flex acu-justify-end acu-gap-2']}>
			{#if !$listScreenIsReadOnly && !locked}

				{#if data.columns.length > 0}
					<AcButton
						type="text"
						on:click={clearColumns}
						isDestructive
						label={i18n.editor.label.clear_columns}
					/>
				{:else if undoState !== null}
					<AcButton
						type="text"
						on:click={undo}
						isDestructive
						label={i18n.editor.label.undo}
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
					customClass="-selectv2 ac-button--add_column"
					maxHeight="400px"
					value
					position="bottom-left" bind:this={columnTypeComponent}>

					<AcButton
						slot="trigger"
						label={`+ ${i18n.editor.label.add_column}`}
					/>

					<ColumnTypeDropdownV2
						on:selectItem={handleSelectColumnType}
						on:close={() => handleCloseColumnTypeDropdown(columnTypeComponent)}
					/>

				</AcDropdown>
			{:else}
				<AcButton
					type="primary"
					disabled
					label={i18n.editor.label.save}
				/>
			{/if}
		</AcPanelFooter>


	</AcPanel>
{:else}
	<ColumnsFormSkeleton/>
{/if}

<style>
	.ac-template-select {
		border: 1px solid #dcdcde;
		transition: border-color 0.1s ease-in-out, box-shadow 0.1s ease-in-out;
	}

	.ac-template-select:hover {
		border-color: #c3c4c7;
	}

	.ac-template-select:focus,
	.ac-template-select.-active {
		outline: none;
		border-color: var(--ac-primary-color);
		box-shadow: 0 0 0 1px var(--ac-primary-color);
	}
</style>


