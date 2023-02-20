import AcSection from "./modules/ac-section";
import {initPointers} from "./modules/ac-pointer";
import {initAcServices} from "./helpers/admin-columns";
import {initUiToggleButtons} from "./ui-wrapper/functions";
import {persistGeneralSetting} from "./ajax/settings";
import AjaxLoader from "./plugin/ajax-loader";

initAcServices();

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLElement>('.ac-section').forEach(el => {
        new AcSection(el);
    });

    jQuery(document).on('select2:open', () => {
        document.querySelector<HTMLInputElement>('.select2-container--open .select2-search__field')?.focus();
    });

    initPointers();
    initUiToggleButtons();

    const layoutStyle: HTMLInputElement | null = document.querySelector('input[name="layout_style"]');
    if (layoutStyle) {
        let loader = new AjaxLoader();

        layoutStyle.addEventListener('input', () => {
            loader?.getElement().remove();
            loader = new AjaxLoader();
            layoutStyle.closest('.ac-general-setting-row__layout')?.append( loader.getElement() );
            loader.setActive(true).setLoading( true );
            persistGeneralSetting('layout_style', layoutStyle.value).then( ()=>{
                loader.finish();
            });
        });
    }

});