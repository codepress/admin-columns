import {Writable} from "svelte/store";
import {
    currentListId,
    currentListKey,
    debugMode,
    listScreenDataStore,
    listScreenIsReadOnly,
    listScreenIsTemplate,
    hasUsagePermissions
} from "../store/";
import {registerSettingType} from "../helper";
import ListScreenSections from "../store/list-screen-sections";

export default class ColumnPageBridge {
    private stores: { [key: string]: Writable<any> }

    constructor() {
        this.stores = {
            currentListId,
            currentListKey,
            listScreenDataStore,
            listScreenIsReadOnly,
            listScreenIsTemplate,
            hasUsagePermissions,
            debugMode,
        }
    }

    addStore(name: string, store: Writable<any>) {
        this.stores[name] = store;
    }

    getStore(name: string) {
        return this.stores[name];
    }

    getTypes() {
        return registerSettingType
    }

    getSections() {
        return ListScreenSections
    }


}