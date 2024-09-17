import {writable} from 'svelte/store';

export const showColumnInfo = writable<boolean>();
export const showColumnName = writable<boolean>();
export const showColumnType = writable<boolean>();