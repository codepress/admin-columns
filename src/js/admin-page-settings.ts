import {AcGeneralSettingsI18N, LocalizedAcGeneralSettings} from "./types/admin-columns";
import SettingsPage from "./admin-settings/SettingsPage.svelte";
import RestoreSettingsSection from "./admin-settings/RestoreSettingsSection.svelte";
import SettingsPageBridge from "./admin-settings/utils/page-bridge";
import {initAcServices} from "./helpers/admin-columns";

declare const ajaxurl: string;
declare const AC: LocalizedAcGeneralSettings
declare const AC_I18N: AcGeneralSettingsI18N


const AcServices = initAcServices();

let restoreSettingsElement = document.createElement('div');
new RestoreSettingsSection({
    target: restoreSettingsElement,
})


document.addEventListener("DOMContentLoaded", function () {
    const Bridge = new SettingsPageBridge();
    AcServices.registerService('SettingsPage', Bridge);

    let restoreSettingsElement = document.createElement('div');
    new RestoreSettingsSection({
        target: restoreSettingsElement,
    })

    Bridge.getSections().registerSection('after_general', restoreSettingsElement.firstElementChild as HTMLElement );

    document.querySelectorAll('#cpac').forEach(el => {
        new SettingsPage({
            target: el,
        })
    });

});


//
//
// const restoreSettings = () => {
//     let data = new FormData();
//     data.append('_ajax_nonce', AC._ajax_nonce);
//     data.append('action', 'ac-restore-settings');
//
//     return axios.post(ajaxurl, data);
// }
//
// document.addEventListener('DOMContentLoaded', () => {
//     document.querySelectorAll<HTMLInputElement>('.ac-settings-box input[data-ajax-setting]').forEach(el => {
//         new GeneralAdminToggleSetting(el, el.dataset.ajaxSetting ?? '');
//     });
//
//     let restoreFormButton = document.querySelector<HTMLInputElement>('#frm-ac-restore [type=submit]');
//
//     if (restoreFormButton) {
//         restoreFormButton.addEventListener('click', (e) => {
//             e.preventDefault();
//             let loader = new AjaxLoader();
//
//             new AcConfirmation({
//                 message: AC_I18N.restore_settings,
//                 confirm: () => {
//                     loader?.getElement().remove();
//                     loader = new AjaxLoader();
//                     restoreFormButton!.parentElement?.append(loader.getElement());
//                     loader.setActive(true).setLoading(true);
//
//                     restoreSettings().then(() => {
//                         loader.finish();
//                     })
//                 },
//                 lastFocus: restoreFormButton
//             }).create();
//         });
//     }
//
//     const layoutStyle: HTMLInputElement | null = document.querySelector('input[name="layout_style"]');
//     if (layoutStyle) {
//         let loader = new AjaxLoader();
//
//         layoutStyle.addEventListener('input', () => {
//             loader?.getElement().remove();
//             loader = new AjaxLoader();
//             layoutStyle.closest('.ac-general-setting-row__layout')?.append(loader.getElement());
//             loader.setActive(true).setLoading(true);
//             persistGeneralSetting('layout_style', layoutStyle.value).then(() => {
//                 loader.finish();
//             });
//         });
//     }
//
// })
//
//
// class GeneralAdminToggleSetting {
//
//     element: HTMLInputElement
//     name: string
//     loader: AjaxLoader | null
//
//     constructor(element: HTMLInputElement, name: string) {
//         this.element = element;
//         this.name = name;
//         this.loader = null;
//
//         this.init();
//     }
//
//     init() {
//         this.element.addEventListener('change', () => {
//             this.initNewLoader();
//             this.element.closest('.ac-toggle-v2')?.append(this.loader?.getElement() ?? document.createElement('div'));
//             this.loader?.setLoading(true);
//             this.persist().then(() => {
//                 this.loader?.finish();
//             });
//         });
//
//         mapDataToFormData({});
//     }
//
//     private initNewLoader() {
//         this.loader?.getElement().remove();
//         this.loader = new AjaxLoader();
//     }
//
//     persist() {
//         return persistGeneralSetting(this.name, this.element.checked ? '1' : '0');
//     }
//
//
// }