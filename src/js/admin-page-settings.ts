import {mapDataToFormData} from "./helpers/global";
import {AcGeneralSettingsI18N, LocalizedAcGeneralSettings} from "./types/admin-columns";
import AjaxLoader from "./plugin/ajax-loader";
import AcConfirmation from "./plugin/ac-confirmation";
import {persistGeneralSetting} from "./ajax/settings";

declare const ajaxurl: string;
declare const AC: LocalizedAcGeneralSettings
declare const AC_I18N: AcGeneralSettingsI18N

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLInputElement>('.ac-settings-box input[data-ajax-setting]').forEach(el => {
        new GeneralAdminToggleSetting(el, el.dataset.ajaxSetting ?? '');
    });

    let restoreFormButton = document.querySelector<HTMLInputElement>('#frm-ac-restore [type=submit]');

    if (restoreFormButton) {
        restoreFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            new AcConfirmation({
                message: AC_I18N.restore_settings,
                confirm: () => {
                    restoreFormButton?.closest('form')?.submit();
                },
                lastFocus: restoreFormButton
            }).create();
        });
    }
    
    const layoutStyle: HTMLInputElement | null = document.querySelector('input[name="layout_style"]');
    if (layoutStyle) {
        let loader = new AjaxLoader();

        layoutStyle.addEventListener('input', () => {
            loader?.getElement().remove();
            loader = new AjaxLoader();
            layoutStyle.closest('.ac-general-setting-row__layout')?.append(loader.getElement());
            loader.setActive(true).setLoading(true);
            persistGeneralSetting('layout_style', layoutStyle.value).then(() => {
                loader.finish();
            });
        });
    }

})


class GeneralAdminToggleSetting {

    element: HTMLInputElement
    name: string
    loader: AjaxLoader | null

    constructor(element: HTMLInputElement, name: string) {
        this.element = element;
        this.name = name;
        this.loader = null;

        this.init();
    }

    init() {
        this.element.addEventListener('change', () => {
            this.initNewLoader();
            this.element.closest('.ac-toggle-v2')?.append(this.loader?.getElement() ?? document.createElement('div'));
            this.loader?.setLoading(true);
            this.persist().then(() => {
                this.loader?.finish();
            });
        });

        mapDataToFormData({});
    }

    private initNewLoader() {
        this.loader?.getElement().remove();
        this.loader = new AjaxLoader();
    }

    persist() {
        return persistGeneralSetting(this.name, this.element.checked ? '1' : '0');
    }


}