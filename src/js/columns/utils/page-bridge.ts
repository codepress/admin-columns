import {Writable} from "svelte/store";
import {currentListId, currentListKey} from "../store/current-list-screen";
import {listScreenDataStore} from "../store/list-screen-data";
import {listScreenIsReadOnly} from "../store/read_only";
import {debugMode} from "../store/debug";
import {registerSettingType} from "../helper";
import ListScreenSections from "../store/list-screen-sections";
import {hasUsagePermissions} from "../store/permissions";

export default class ColumnPageBridge {
    private stores: { [key:string] : Writable<any> }

    constructor() {
        this.stores = {
            currentListId,
            currentListKey,
            listScreenDataStore,
            listScreenIsReadOnly,
            hasUsagePermissions,
            debugMode,
        }
    }

    addStore(name: string, store: Writable<any>) {
        this.stores[ name ] = store;
    }

    getStore(name: string) {
        return this.stores[ name ];
    }

    getTypes() {
        return registerSettingType
    }

    getSections() {
        return ListScreenSections
    }


}