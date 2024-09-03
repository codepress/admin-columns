import {getColumnSettingsConfig} from "./global";

type listKeyResult = { [key: string]: string }

export default class ListKeys {

    static getListKeys(): listKeyResult {
        const menu = getColumnSettingsConfig().menu_items
        let result: listKeyResult = {}

        Object.values(menu).forEach(mi => {
            result = Object.assign(result, mi.options)
        });

        return result;
    }

    static getLabelForKey(key: string): null|string {
        let keys = ListKeys.getListKeys();

        return keys.hasOwnProperty(key)
            ? keys[key]
            : null;
    }

}