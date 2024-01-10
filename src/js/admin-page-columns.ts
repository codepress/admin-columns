import {Form} from "./admin/columns/form";
import {EventConstants} from "./constants";
import {initAcServices} from "./helpers/admin-columns";
import $ from 'jquery';
import ColumnConfigurator from "./admin/columns/column-configurator";
import Modal from "./modules/modal";
import Feedback from "./admin/columns/feedback";
import InfoScreenOption from "./admin/columns/screen-options";
import {initAcTooltips} from "./plugin/tooltip";
import {initPointers} from "./modules/ac-pointer";
import {initUninitializedListScreens} from "./admin/columns/listscreen-initialize";
import Modals from "./modules/modals";
import {Column} from "./admin/columns/column";
import {LocalizedAcColumnSettings} from "./types/admin-columns";
import {registerSettingType} from "./columns/helper";
import LabelSetting from "./columns/components/settings/LabelSetting.svelte";
import EmptySetting from "./columns/components/settings/EmptySetting.svelte";
import MessageSetting from "./columns/components/settings/MessageSetting.svelte";
import HiddenSetting from "./columns/components/settings/HiddenSetting.svelte";
import WidthSetting from "./columns/components/settings/WidthSetting.svelte";
import TypeSetting from "./columns/components/settings/TypeSetting.svelte";
import ToggleSetting from "./columns/components/settings/ToggleSetting.svelte";
import TextSetting from "./columns/components/settings/TextSetting.svelte";
import DateFormatSetting from "./columns/components/settings/DateFormatSetting.svelte";
import NumberSetting from "./columns/components/settings/Number.svelte";
import NumberPreviewSetting from "./columns/components/settings/NumberPreviewSetting.svelte";
import SelectSetting from "./columns/components/settings/SelectSetting.svelte";
import ColumnsPage from "./columns/components/ColumnsPage.svelte";
import {currentListId, currentListKey} from "./columns/store/current-list-screen";
import {getColumnSettingsConfig} from "./columns/utils/global";
import ListScreenSections from "./columns/store/list-screen-sections";
import {listScreenDataStore} from "./columns/store/list-screen-data";
import {columnTypeSorter, columnTypesStore} from "./columns/store/column-types";
import {NotificationProgrammatic} from "./ui-wrapper/notification";
import {listScreenIsReadOnly} from "./columns/store/read_only";
import {SvelteComponent} from "svelte";


declare let AC: LocalizedAcColumnSettings
declare const jQuery: any;


let AcServices = initAcServices();
AcServices.registerService('Modals', new Modals());

new ColumnConfigurator(AcServices);

document.addEventListener('DOMContentLoaded', () => {
    initSaveHandlers();

    const config = getColumnSettingsConfig();

    // TODO make something more affording
    const ConfigService = {
        stores: {
            currentListId,
            currentListKey,
            listScreenDataStore
        },
        registerSettingType,
        ListScreenSections,
        updateId: (newValue: string) => {
            currentListId.update(old => newValue)
        }
    }

    currentListKey.subscribe((d) => {
        const url = new URL(window.location.href);

        url.searchParams.set('list_screen', d);

        window.history.replaceState(null, '', url);
    })

    currentListId.subscribe((d) => {
        const url = new URL(window.location.href);

        url.searchParams.set('layout_id', d);

        window.history.replaceState(null, '', url);
    })


    AcServices.registerService('ColumnPage', ConfigService);

    // START UI2.0
    registerSettingType('label', LabelSetting as typeof SvelteComponent)
    registerSettingType('width', WidthSetting as typeof SvelteComponent)
    registerSettingType('empty', EmptySetting as typeof SvelteComponent)
    registerSettingType('type', TypeSetting as typeof SvelteComponent)
    registerSettingType('toggle', ToggleSetting as typeof SvelteComponent)
    registerSettingType('text', TextSetting as typeof SvelteComponent)
    registerSettingType('message', MessageSetting as typeof SvelteComponent)
    registerSettingType('number', NumberSetting as typeof SvelteComponent)
    registerSettingType('number_preview', NumberPreviewSetting as typeof SvelteComponent)
    registerSettingType('select', SelectSetting as typeof SvelteComponent)
    registerSettingType('date_format', DateFormatSetting as typeof SvelteComponent)
    registerSettingType('hidden', HiddenSetting as typeof SvelteComponent)


    currentListKey.set(config.list_key);
    currentListId.set(config.list_screen_id)
    columnTypesStore.set(config.column_types.sort(columnTypeSorter));

    let target = document.createElement('div');

    new ColumnsPage({
        target: target,
        props: {
            menu: config.menu_items,
        }
    });

    document.querySelector('#cpac')?.prepend(target);

    // END UI2.0


    // Init the form
    document.querySelectorAll<HTMLFormElement>('#listscreen_settings').forEach((formElement: HTMLElement) => {
        AcServices.registerService('Form', new Form(formElement, AcServices))
    });

    // Init the Pro promotion Modal
    if (AcServices.hasService('Modals')) {
        document.querySelectorAll<HTMLElement>('#ac-modal-pro').forEach(proModal => {
            AcServices.getService<Modals>('Modals')?.register(new Modal(proModal), 'pro');
        });
    }

    const matchStart = (params: any, data: any) => {
        if (jQuery.trim(params.term) === '') {
            return data;
        }

        if (typeof data.children === 'undefined') {
            return null;
        }

        let filteredChildren: any[] = [];

        jQuery.each(data.children, (idx: any, child: any) => {
            if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) > -1) {
                filteredChildren.push(child);
            }
        });

        if (filteredChildren.length) {
            let d = Object.assign({}, data);
            d.children = filteredChildren;

            return d;
        }

        return null;
    }

    document.querySelectorAll<HTMLSelectElement>('#ac_list_screen').forEach(select => {
        (<any>jQuery(select)).ac_select2({
            theme: 'acs2',
            matcher: matchStart,
            width: '250px',
            dropdownCssClass: '-listkeys',
        }).on('select2:select', () => {
            document.querySelectorAll<HTMLElement>('.view-link').forEach(link => link.style.display = 'none');
            select.closest<HTMLFormElement>('form')?.submit();
            select.disabled = true;
            (select.nextElementSibling as HTMLElement).style.display = 'inline-block';
        });

    })

    document.querySelectorAll<HTMLElement>('#direct-feedback').forEach(feedbackElement => new Feedback(feedbackElement));

    if (AC.hasOwnProperty('uninitialized_list_screens')) {
        initUninitializedListScreens(AC.uninitialized_list_screens);
    }

    // Screen Options
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_column_id"] input').forEach(el => new InfoScreenOption('show_column_id', el, 'show-column-id', document.querySelector('.ac-boxes')!));
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_column_type"] input').forEach(el => new InfoScreenOption('show_column_type', el, 'show-column-type', document.querySelector('.ac-boxes')!));
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_list_screen_id"] input').forEach(el => new InfoScreenOption('show_list_screen_id', el, 'show-list-screen-id', document.querySelector('.ac-admin')!));
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_list_screen_type"] input').forEach(el => new InfoScreenOption('show_list_screen_type', el, 'show-list-screen-type', document.querySelector('.ac-admin')!));
});

AcServices.addListener(EventConstants.SETTINGS.FORM.LOADED, (form: Form) => {
    document.querySelectorAll('.add_column').forEach(el => el.addEventListener('click', () => form.createNewColumn()));
    document.querySelectorAll('a[data-clear-columns]').forEach(el => el.addEventListener('click', () => form.resetColumns()));

    if (!form.getElement().classList.contains('-disabled')) {
        // Make column settings sortable
        let $form = $(form.getElement()) as any;

        $form.hasClass('ui-sortable')
            ? $form.sortable('refresh')
            : $form.sortable({
                axis: 'y',
                items: '.ac-column',
                handle: '[data-sort-handle]',
                containment: $form
            });
    }
});

AcServices.addListener(EventConstants.SETTINGS.FORM.SAVING, () => {
    document.querySelector('#cpac .ac-admin')?.classList.add('saving');
});

AcServices.addListener(EventConstants.SETTINGS.FORM.SAVED, () => {
    document.querySelector('#cpac .ac-admin')?.classList.remove('saving');
    document.querySelector('#cpac .ac-admin')?.classList.add('stored');
});

AcServices.addListener(EventConstants.SETTINGS.COLUMN.INIT, (column: Column) => {
    initAcTooltips();
    initPointers(column.getElement().querySelectorAll('.ac-pointer'));
});

const initSaveHandlers = () => {
    const elements = document.querySelectorAll<HTMLElement>('.sidebox a.submit, .column-footer a.submit');

    AcServices.addListener(EventConstants.SETTINGS.FORM.READY, (form: Form) => {
        elements.forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                elements.forEach(el => el.setAttribute('disabled', 'disabled'));
                form.submitForm();
            });
        })
    });

    AcServices.addListener(EventConstants.SETTINGS.FORM.SAVED, () => elements.forEach(el => el.removeAttribute('disabled')));
}