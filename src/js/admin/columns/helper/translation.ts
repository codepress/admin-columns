import {LocalizedAcColumnSettings, LocalizedAcColumnSettingsI18n} from "../../../types/admin-columns";

declare const AC: LocalizedAcColumnSettings;

export const getSettingsTranslations = (): LocalizedAcColumnSettingsI18n => {
    return AC.i18n;
}