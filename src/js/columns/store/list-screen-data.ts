import {Writable, writable} from 'svelte/store';
import {ListScreenData} from "../../types/requests";

export interface ListScreenDataStore extends Writable<ListScreenData> {

}

function createListScreenData(): ListScreenDataStore {
    const {subscribe, set, update} = writable<ListScreenData>();

    return {
        subscribe,
        set,
        update
    };
}

export const listScreenDataStore = createListScreenData();