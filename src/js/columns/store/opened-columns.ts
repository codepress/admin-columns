import {Writable, writable} from 'svelte/store';

export interface OpenedColumnsStore extends Writable<string[]> {
    open(column: string): void;

    close(column: string): void;

    toggle(column: string): void;
}

function getInitialOpenedColumns(): string[] {
    const params = new URLSearchParams(window.location.search);
    const value = params.get('open_columns');

    if (!value) {
        return [];
    }

    return value.split(',').map(s => s.trim()).filter(s => s.length > 0);
}

function createOpenedColumnsStore(): OpenedColumnsStore {
    const {subscribe, set, update} = writable<string[]>(getInitialOpenedColumns());

    return {
        subscribe,
        set,
        update,
        open: (column: string) => update(items => {
            items.push(column)
            return items;
        }),
        close: (column: string) => update(items => {
            items = items.filter(s => s !== column);

            return items;
        }),
        toggle: (column: string) => update(items => {

            if (items.includes(column)) {
                items = items.filter(s => s !== column);
            } else {
                items.push(column);
            }

            return items;
        }),
    };
}


export const openedColumnsStore = createOpenedColumnsStore();