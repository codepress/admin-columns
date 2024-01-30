import {getColumnSettingsConfig} from "./global";

export class ColumnTypesUtils {

    static getColumnTypes() {
        return getColumnSettingsConfig().column_types
    }

    static getOriginalColumnTypes() {
        return ColumnTypesUtils.getColumnTypes().filter( c => c.original );
    }

    static isOriginalColumnType( columnType: string ) {
        let column = ColumnTypesUtils.getColumnTypes().find( c => c.value === columnType ) ?? null;

        return column !== null
            ? column.original
            : false;
    }

    static getColumnType( type: string ){
        const columnType =  ColumnTypesUtils.getColumnTypes().find( ct => ct.value === type );

        return typeof columnType !== 'undefined'
            ? columnType
            : null;
    }

}