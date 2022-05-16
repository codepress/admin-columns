import {LocalizedAcTableI18n} from "../types/admin-columns";

declare const AC_I18N: any;

export const getTableTranslation = (): LocalizedAcTableI18n => {
    return AC_I18N;
}