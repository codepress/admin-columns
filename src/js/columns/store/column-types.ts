import {Writable, writable} from 'svelte/store';
import ColumnConfig = AC.Vars.Admin.Columns.ColumnConfig;

function createColumnTypesStore(): Writable<ColumnConfig[]> {
    const {subscribe, set, update} = writable<ColumnConfig[]>();

    return {
        subscribe,
        set,
        update
    };
}

export const columnTypesStore = createColumnTypesStore();