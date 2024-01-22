import {Writable, writable} from 'svelte/store';

export interface FavoriteListKeysStore extends Writable<string[]> {
    favorite(key: string): void;
    unfavorite(key: string): void;

}

function createFavoriteListKeysStore(): FavoriteListKeysStore {
    const {subscribe, set, update} = writable<string[]>([]);

    return {
        subscribe,
        set,
        update,
        favorite: (key: string) => update(items => {
            items.push(key)
            return items;
        }),
        unfavorite: (key: string) => update(items => {
            items = items.filter(s => s !== key);

            return items;
        })
    };
}


export const favoriteListKeysStore = createFavoriteListKeysStore();