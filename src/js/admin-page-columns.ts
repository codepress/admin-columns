import {Form} from "./admin/columns/form";
import {AdminColumnSettingsInterface} from "./admin/columns/interfaces";
import {EventConstants} from "./constants";
import {initAdminColumnsGlobalBootstrap} from "./helpers/admin-columns";
// @ts-ignore
import $ from 'jquery';
import ColumnConfigurator from "./admin/columns/column-configurator";
import Modal from "./modules/modal";
import Feedback from "./admin/columns/feedback";
import InfoScreenOption from "./admin/columns/screen-options";
import {initAcTooltips} from "./plugin/tooltip";
import {initPointers} from "./modules/ac-pointer";

declare let AdminColumns: AdminColumnSettingsInterface;

initAdminColumnsGlobalBootstrap();

new ColumnConfigurator();

document.addEventListener('DOMContentLoaded', () => {
    initSaveHandlers();

    // Init the form
    let formElement = document.querySelector<HTMLFormElement>('#listscreen_settings');
    if (formElement) {
        AdminColumns.Form = new Form(formElement, AdminColumns.events);
    }

    // Init the Pro promotion Modal
    let proModal: HTMLElement = document.querySelector('#ac-modal-pro');
    if (proModal) {
        AdminColumns.Modals.register(new Modal(proModal), 'pro');
    }

    let select: HTMLSelectElement = document.querySelector('#ac_list_screen');
    if (select) {
        select.addEventListener('change', () => {
            document.querySelectorAll<HTMLElement>('.view-link').forEach(link => link.style.display = 'none');
            select.closest<HTMLFormElement>('form').submit();
            select.disabled = true;
            (select.nextElementSibling as HTMLElement).style.display = 'inline-block';
        });
    }

    let feedback = document.querySelector<HTMLElement>('#direct-feedback');
    if (feedback) {
        new Feedback(feedback);
    }

    // Screen Options
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_column_id"] input').forEach(el => new InfoScreenOption(el, 'show-column-id', document.querySelector('.ac-boxes')));
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_column_type"] input').forEach(el => new InfoScreenOption(el, 'show-column-type', document.querySelector('.ac-boxes')));
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_list_screen_id"] input').forEach(el => new InfoScreenOption(el, 'show-list-screen-id', document.querySelector('.ac-admin')));
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_list_screen_type"] input').forEach(el => new InfoScreenOption(el, 'show-list-screen-type', document.querySelector('.ac-admin')));
});

AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.LOADED, (form: Form) => {
    document.querySelectorAll('.add_column').forEach(el => el.addEventListener('click', () => form.createNewColumn()));
    document.querySelectorAll('a[data-clear-columns]').forEach(el => el.addEventListener('click', () => form.resetColumns()));

    // Make column settings sortable
    let $form = $(form.getElement());
    $form.hasClass('ui-sortable')
        ? $form.sortable('refresh')
        : $form.sortable({items: '.ac-column', handle: '.column_sort'});
});

AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.SAVING, () => {
    document.querySelector('#cpac .ac-admin').classList.add('saving');
});

AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.SAVED, () => {
    document.querySelector('#cpac .ac-admin').classList.remove('saving');
    document.querySelector('#cpac .ac-admin').classList.add('stored');
});

AdminColumns.events.addListener(EventConstants.SETTINGS.COLUMN.INIT, () => {
    initAcTooltips();
    initPointers();
});


const initSaveHandlers = () => {
    const elements = document.querySelectorAll<HTMLElement>('.sidebox a.submit, .column-footer a.submit');
    AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.READY, (form: Form) => {
        elements.forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                elements.forEach(el => el.setAttribute('disabled', 'disabled'));
                form.submitForm();
            });
        })
    });
    AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.SAVED, () => elements.forEach(el => el.removeAttribute('disabled')));
}