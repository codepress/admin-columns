import {Writable, writable} from 'svelte/store';


type columnSettings = { [key: string]: columnSetting }
type columnSetting = any;

export interface ColumnSettingsStore extends Writable<columnSettings> {
    changeSettings( column: string, settings: columnSetting ): void;
}


function createColumnSettings(): ColumnSettingsStore {
    const {subscribe, set, update} = writable<columnSettings>({});

    return {
        subscribe,
        set,
        update,
        changeSettings: (column: string, settings: columnSetting) => update(items => {
            console.log( 'before', column, items );
            items[ column ] = settings;

            console.log( 'after', items[column] );

            return items;
        }),
    };
}


export const columnSettingsStore = createColumnSettings();