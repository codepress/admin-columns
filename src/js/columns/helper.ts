import {get, Writable, writable} from "svelte/store";
import {SvelteComponent} from "svelte";

export const getSettingComponent = (type: string) => {

    let config = get(settingTypes);

    return config.hasOwnProperty(type)
        ? config[type]
        : null;

}

export const registerSettingType = (type: string, config: typeof SvelteComponent) => {
    settingTypes.update(d => {
        d[type] = config;

        return d;
    });

    console.log( get( settingTypes ) );
}

export const settingTypes: Writable<{ [key: string]: any }> = writable({});