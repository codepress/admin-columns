import {get, Writable, writable} from "svelte/store";

export const getSettingComponent = (type: string) => {

    let config = get(settingTypes);

    return config.hasOwnProperty(type)
        ? config[type]
        : null;

}

export const registerSettingType = (type: string, config: any) => {
    settingTypes.update(d => {
        d[type] = config;

        return d;
    })
}

export const settingTypes: Writable<{ [key: string]: any }> = writable({});