import {LocalizedAcTable, LocalizedAcTableI18n} from "../../types/table";

declare const ac_table_i18n: LocalizedAcTableI18n;
declare const ac_table: LocalizedAcTable;

export const getTableTranslation = (): LocalizedAcTableI18n => {
    return ac_table_i18n;
}

export const getTableConfig = (): LocalizedAcTable => {
    return ac_table;
}