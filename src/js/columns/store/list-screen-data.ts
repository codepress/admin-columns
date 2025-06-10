import {Writable, writable} from 'svelte/store';
import {ListScreenData} from "../../types/requests";

export interface ListScreenDataStore extends Writable<ListScreenData|null> {
    deleteColumn(columnName: string): void;
}

function createListScreenData(): ListScreenDataStore {
    const {subscribe, set, update} = writable<ListScreenData>();

    return {
        subscribe,
        set,
        update,
        deleteColumn: (columnName: string) => update(data => {
            data.columns = data.columns.filter( c => c.name !== columnName );
        
            return data;
        }),
    };
}

export const listScreenDataStore = createListScreenData();

export const listScreenDataHasChanges = writable(false);
export const initialListScreenData = writable<ListScreenData|null>(null);
