import {getColumnSettingsConfig} from "./global";

export class ColumnTypesUtils {

    static getColumnTypes() {
        return getColumnSettingsConfig().column_types
    }

    static getOriginalColumnTypes() {
        return getColumnSettingsConfig().column_types.filter( c => c.original );
    }

}