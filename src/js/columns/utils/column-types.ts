import {getColumnSettingsConfig} from "./global";

export class ColumnTypesUtils {

    static getColumnTypes () {
        return getColumnSettingsConfig().column_types
    }


}