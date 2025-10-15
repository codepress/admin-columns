import {get, writable} from "svelte/store";
import {getListScreenSettings} from "../ajax/ajax";
import cloneDeep from "lodash-es/cloneDeep";
import {isCancel} from "axios";
import {
    columnTypeSorter,
    columnTypesStore,
    currentListId,
    currentTableUrl,
    initialListScreenData, isInitializingColumnSettings,
    isLoadingColumnSettings,
    listScreenDataHasChanges,
    listScreenDataStore,
    listScreenIsReadOnly,
    listScreenIsStored,
    listScreenIsTemplate, listScreenLabels
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
    isInitializingColumnSettings.set( true );
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
    } catch (error: any) {
        if (isCancel(error)) {
            return;
        }
        refreshState.error.set(error.message ?? 'Unknown error');
        throw error;
    } finally {
        if (abortController.signal.aborted) {
            return;
        }
        isLoadingColumnSettings.set(false);
        setTimeout( () => {
            // Let the form 'correct' the data that is loaded by the settings
            initialListScreenData.set( cloneDeep(get(listScreenDataStore)) );
            isInitializingColumnSettings.set( false );
        },1000)

    }
}