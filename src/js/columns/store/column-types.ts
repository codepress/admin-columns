import {Writable, writable} from 'svelte/store';
import {getColumnSettingsConfig} from "../utils/global";
import ColumnConfig = AC.Vars.Admin.Columns.ColumnConfig;

function createColumnTypesStore(): Writable<ColumnConfig[]> {
    const {subscribe, set, update} = writable<ColumnConfig[]>();

    return {
        subscribe,
        set,
        update
    };
}

export const getSortedColumnGroups = () => {
    return getColumnSettingsConfig().column_groups.sort((a, b) => {
        return a.priority > b.priority ? 1 : -1;
    });
}


export const columnTypeSorter = (a: ColumnConfig, b: ColumnConfig) => {
    // Compare based on group priority
    const sortedColumnGroups = getSortedColumnGroups().map(g => g.slug);
    const groupPriorityA = sortedColumnGroups.indexOf(a.group_key);
    const groupPriorityB = sortedColumnGroups.indexOf(b.group_key);

    if (groupPriorityA !== groupPriorityB) {
        return groupPriorityA - groupPriorityB;
    }

    // If the groups have the same priority, compare based on the value
    if (a.value < b.value) return -1;
    if (a.value > b.value) return 1;
    return 0;
}


export const columnTypesStore = createColumnTypesStore();