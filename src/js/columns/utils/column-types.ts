import {getColumnSettingsConfig} from "./global";

export class ColumnTypesUtils {

    static getColumnTypes() {
        return getColumnSettingsConfig().column_types
    }

    static getOriginalColumnTypes() {
        return ColumnTypesUtils.getColumnTypes().filter( c => c.original );
    }

    static getColumnType( type: string ){
        const columnType =  ColumnTypesUtils.getColumnTypes().find( ct => ct.type === type );

        return typeof columnType !== 'undefined'
            ? columnType
            : null;
    }

}