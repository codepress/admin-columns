import {writable} from "svelte/store";
import {getListScreenSettings} from "../ajax/ajax";
import cloneDeep from "lodash-es/cloneDeep";
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
    listScreenIsTemplate
} from "../store";

export const config = writable<{ [key: string]: AC.Vars.Settings.ColumnSetting[] }>({});
export const loadedListId = writable<string | null>(null);

export const refreshState = {
    error: writable<string | null>(null),
};

export async function refreshListScreenData(listKey: string, listId: string = '') {
    isLoadingColumnSettings.set(true);
    isInitializingColumnSettings.set( true );
    refreshState.error.set(null);

    listScreenDataStore.set(null);

    const abortController = new AbortController();

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
        currentListId.set(listScreenData.id);
        initialListScreenData.set(cloneDeep(listScreenData));
        listScreenDataHasChanges.set(false);
        listScreenIsStored.set(data.is_stored);
        listScreenIsTemplate.set(data.is_template);
    } catch (error: any) {
        refreshState.error.set(error.message ?? 'Unknown error');
        throw error;
    } finally {
        isLoadingColumnSettings.set(false);
        setTimeout( () => {
            isInitializingColumnSettings.set( false );
        },1000);
    }
}