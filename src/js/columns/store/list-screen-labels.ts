import {Writable, writable} from 'svelte/store';
import {ListScreenData} from "../../types/requests";

type labels = {
    singular: string;
    plural: string;
}

export const listScreenLabels = writable<labels|null>(null);
