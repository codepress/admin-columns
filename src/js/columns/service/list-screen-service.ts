import {get, writable} from "svelte/store";
import {getListScreenSettings, loadDefaultColumns} from "../ajax/ajax";
import cloneDeep from "lodash-es/cloneDeep";
import {isCancel} from "axios";
import {
    columnTypeSorter,
    columnTypesStore,
    currentListId,
    currentTableUrl,
    initialListScreenData,
    isInitializingColumnSettings,
    isLoadingColumnSettings,
    listScreenDataHasChanges,
    listScreenDataStore,
    listScreenIsReadOnly,
    listScreenIsStored,
    listScreenIsTemplate,
    listScreenLabels
} from "../store";

export const config = writable<{ [key: string]: AC.Vars.Settings.ColumnSetting[] }>({});
export const loadedListId = writable<string | null>(null);

export const refreshState = {
    error: writable<string | null>(null),
};

let currentAbortController: AbortController;

export async function refreshListScreenData(listKey: string, listId: string = '') {
    if (currentAbortController) {
        currentAbortController.abort();
    }

    isLoadingColumnSettings.set(true);
    isInitializingColumnSettings.set(true);
    refreshState.error.set(null);

    listScreenDataStore.set(null);

    const abortController = new AbortController();
    currentAbortController = abortController;

    try {
        const response = await getListScreenSettings(listKey, listId, abortController);
        const data = response.data.data;
        const listScreenData = data.settings.list_screen;

        if (Array.isArray(listScreenData.settings)) {
            listScreenData.settings = {};
        }

        config.set(data.column_settings);
        loadedListId.set(listScreenData.id);
        currentTableUrl.set(data.table_url);
        columnTypesStore.set(data.column_types.sort(columnTypeSorter));
        listScreenIsReadOnly.set(data.read_only);
        listScreenDataStore.set(listScreenData);
        listScreenLabels.set(data.labels);
        currentListId.set(listScreenData.id);
        initialListScreenData.set(cloneDeep(listScreenData));
        listScreenDataHasChanges.set(false);
        listScreenIsStored.set(data.is_stored);
        listScreenIsTemplate.set(data.is_template);

        if (!data.is_stored && listScreenData.columns.length === 0) {
            const defaultData = await loadDefaultColumns(listKey);

            if (defaultData.data.success) {
                listScreenData.columns = defaultData.data.data.columns;
                let defaultConfig = defaultData.data.data.config as unknown as {
                    [key: string]: AC.Vars.Settings.ColumnSetting[]
                }
                config.set(defaultConfig);
            }
        }
    } catch (error: any) {
        if (isCancel(error)) {
            return;
        }
        refreshState.error.set(error.message ?? 'Unknown error');
        throw error;
    } finally {
        if (!abortController.signal.aborted) {
            isLoadingColumnSettings.set(false);
            setTimeout(() => {
                // Let the form 'correct' the data that is loaded by the settings
                initialListScreenData.set(cloneDeep(get(listScreenDataStore)));
                isInitializingColumnSettings.set(false);
            }, 1000)
        }

        isLoadingColumnSettings.set(false);
        setTimeout(() => {
            // Let the form 'correct' the data that is loaded by the settings
            initialListScreenData.set(cloneDeep(get(listScreenDataStore)));
            isInitializingColumnSettings.set(false);
        }, 1000)

    }
}