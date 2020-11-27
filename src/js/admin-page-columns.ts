import {Form} from "./admin/columns/form";
import {AdminColumnSettingsInterface} from "./admin/columns/interfaces";
import {EventConstants} from "./constants";
import {initAdminColumnsGlobalBootstrap} from "./helpers/admin-columns";
// @ts-ignore
import $ from 'jquery';
import ColumnConfigurator from "./admin/columns/column-configurator";

declare let AdminColumns: AdminColumnSettingsInterface;

initAdminColumnsGlobalBootstrap();

new ColumnConfigurator();

document.addEventListener('DOMContentLoaded', () => {

    new SaveButtons();
    let formElement = document.querySelector<HTMLFormElement>('#listscreen_settings');

    if (formElement) {
        AdminColumns.Form = new Form(formElement, AdminColumns.events);
    }
});

AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.LOADED, (form: Form) => {
    document.querySelectorAll('.add_column').forEach(el => {
        el.addEventListener('click', (e: MouseEvent) => {
            e.preventDefault();
            form.createNewColumn();
        });
    });

    document.querySelectorAll('a[data-clear-columns]').forEach(el => {
        el.addEventListener('click', () => form.resetColumns());
    });

});

AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.SAVING, () => {
    document.querySelector('#cpac .ac-admin').classList.add('saving');
});

AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.SAVED, () => {
    document.querySelector('#cpac .ac-admin').classList.remove('saving');
    document.querySelector('#cpac .ac-admin').classList.add('stored');
});

AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.LOADED, (form: Form) => {
    let $form = $(form.getElement());

    if ($form.hasClass('ui-sortable')) {
        $form.sortable('refresh');
    } else {
        $form.sortable({
            items: '.ac-column',
            handle: '.column_sort'
        });
    }
});

AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.LOADED, (form: Form) => {
    document.querySelectorAll('a[data-clear-columns]').forEach(el => {
        el.addEventListener('click', () => form.resetColumns());
    });
});

class SaveButtons {

    elements: NodeListOf<HTMLElement>

    constructor() {
        this.elements = document.querySelectorAll<HTMLElement>('.sidebox a.submit, .column-footer a.submit');
        this.init();
    }

    init() {
        AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.READY, (form: Form) => {
            this.elements.forEach(el => {
                el.addEventListener('click', e => {
                    e.preventDefault();
                    this.disable();
                    form.submitForm();
                });
            })
        });
        AdminColumns.events.addListener(EventConstants.SETTINGS.FORM.SAVED, () => this.enable());
    }

    disable() {
        this.elements.forEach(el => el.setAttribute('disabled', 'disabled'));
    }

    enable() {
        this.elements.forEach(el => el.removeAttribute('disabled'));
    }

}