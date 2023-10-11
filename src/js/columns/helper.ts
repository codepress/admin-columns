import LabelSetting from "./components/settings/LabelSetting.svelte";
import {get, Writable, writable} from "svelte/store";

export const getSettingComponent = (type: string) => {

    let config = get(test);

    console.log( type, config );

    return config.hasOwnProperty( type )
        ? config[type]
        : null;

}


export const registerSettingType = (type: string, config: any) => {
    test.update( d => {
        d[type] = config;

        return d;

    })
}


export const test: Writable<{ [key:string]: any  }> = writable({});