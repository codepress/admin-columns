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

declare let AC: LocalizedAcColumnSettings
declare const jQuery: any;

let AcServices = initAcServices();
AcServices.registerService('Modals', new Modals());

new ColumnConfigurator(AcServices);

document.addEventListener('DOMContentLoaded', () => {
    initSaveHandlers();

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

    const matchStart = (params, data) => {
        if (jQuery.trim(params.term) === '') {
            return data;
        }

        if (typeof data.children === 'undefined') {
            return null;
        }

        let filteredChildren = [];

        jQuery.each(data.children, (idx, child) => {
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