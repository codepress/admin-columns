import { get } from "svelte/store";
import { currentListId, currentListKey } from "../store";
import { loadedListId, refreshListScreenData } from "./list-screen-service";

let debounceTimeout: ReturnType<typeof setTimeout>;

function debounceRefresh(listKey: string, listId: string | null, delay = 300) {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        void refreshListScreenData(listKey, listId ?? '');
    }, delay);
}

export function startListScreenWatcher() {
    currentListKey.subscribe((listKey) => {
        debounceRefresh(listKey, get(currentListId));
    });

    currentListId.subscribe((listId) => {
        if (listId && get(loadedListId) !== listId) {
            debounceRefresh(get(currentListKey), listId);
        }
    });
}