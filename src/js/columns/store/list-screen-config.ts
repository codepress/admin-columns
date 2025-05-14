import {writable} from 'svelte/store';

export const listScreenConfigStore = writable<AC.Vars.Settings.ColumnSetting[]>();