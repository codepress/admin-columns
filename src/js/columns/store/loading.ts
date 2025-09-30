import {writable} from 'svelte/store';

export const isLoadingColumnSettings = writable<boolean>();
export const isInitializingColumnSettings = writable<boolean>();