import {writable} from 'svelte/store';

export const showColumnInfo = writable<boolean>();
export const showColumnPreview = writable<boolean>(true);
export const columnPreviewSupported = writable<boolean>(false);
export const showColumnName = writable<boolean>();
export const showColumnType = writable<boolean>();