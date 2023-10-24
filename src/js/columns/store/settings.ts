import {Writable, writable} from 'svelte/store';


type columnSettings = { [key: string]: columnSetting }
type columnSetting = any;

export interface ColumnSettingsStore extends Writable<columnSettings> {
    changeSettings(column: string, settings: columnSetting): void;
}

function createColumnSettings(): ColumnSettingsStore {
    const {subscribe, set, update} = writable<columnSettings>({});

    return {
        subscribe,
        set,
        update,
        changeSettings: (column: string, settings: columnSetting) => update(items => {
            items[column] = settings;

            return items;
        }),
    };
}


export const columnSettingsStore = createColumnSettings();