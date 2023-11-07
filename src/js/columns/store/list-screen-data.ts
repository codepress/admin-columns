import {Writable, writable} from 'svelte/store';
import {ListScreenData} from "../../types/requests";

export interface ListScreenDataStore extends Writable<ListScreenData> {
    deleteColumn(columnName: string): void;
}

function createListScreenData(): ListScreenDataStore {
    const {subscribe, set, update} = writable<ListScreenData>();

    return {
        subscribe,
        set,
        update,
        deleteColumn: (columnName: string) => update(items => {
            delete items.columns[ columnName ];

            return items;
        }),
    };
}

export const listScreenDataStore = createListScreenData();