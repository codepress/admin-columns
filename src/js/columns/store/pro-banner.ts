import {writable} from "svelte/store";

export const proBannerStore = writable<AC.Vars.Admin.Columns.ProBanner | null>(null);
