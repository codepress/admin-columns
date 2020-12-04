import Nanobus from "nanobus";
import {EventConstants} from "../../constants";
import {Column, COLUMN_EVENTS} from "./column";
import {ColumnSettingsResponse, submitColumnSettings} from "./ajax";
import {AxiosResponse} from "axios";
import {fadeIn, scrollToElement} from "../../helpers/animations";
import {createColumnName} from "../../helpers/columns";
import {insertAfter} from "../../helpers/elements";
import {LocalizedScriptColumnSettings} from "../../admincolumns";

declare const AC: LocalizedScriptColumnSettings;

export class Form {

    private form: HTMLFormElement
    private events: Nanobus
    private columns: Array<Column>

    constructor(element: HTMLFormElement, events: Nanobus) {
        this.form = element;
        this.events = events;
        this.columns = [];

        this.events.emit(EventConstants.SETTINGS.FORM.LOADED, this);
        // TODO See usage
        // jQuery(document).trigger('AC_Form_Loaded');

        this.init();
    }

    init() {
        this.initColumns();

        if (this.isDisabled()) {
            this.disableFields();
            this.disableColumns();
        }

        this.events.emit(EventConstants.SETTINGS.FORM.READY, this);
    }

    getElement(): HTMLFormElement {
        return this.form;
    }

    placeColumn(column: Column, after: HTMLElement = null): this {
        if (after) {
            insertAfter(column.getElement(), after);
        } else {
            this.getElement().querySelector('.ac-columns').append(column.getElement());
        }
        scrollToElement(column.getElement(), 300, {offset: -58});

        return this;
    }

    createNewColumn(): Column {
        let column = createColumnFromTemplate();
        column.init().open();
        this.columns.push(column);

        this.placeColumn(column);

        return column;
    }

    isDisabled() {
        return this.form.classList.contains('-disabled');
    }

    getOriginalColumns(): Array<Column> {
        return this.columns.filter(column => column.isOriginal());
    }

    disableColumns() {
        this.columns.forEach(col => col.disable());
    }

    initColumns() {
        this.getElement().querySelectorAll('.ac-column').forEach((element: HTMLElement) => {
            let column = new Column(element, element.dataset.columnName);
            column.init();
            this.columns.push(column);
            this.bindColumnEvents(column);
        });
    }

    bindColumnEvents(column: Column) {
        column.events.addListener(COLUMN_EVENTS.REMOVE, () => {
            this.removeColumn(column.getName());
        });

        column.events.addListener(COLUMN_EVENTS.CLONE, () => {
            let cloneColumn = new Column(column.getElement().cloneNode(true) as HTMLElement, createColumnName());
            cloneColumn.init();

            this.columns.push(cloneColumn);
            this.placeColumn(cloneColumn, column.getElement()).bindColumnEvents(cloneColumn);
            fadeIn(cloneColumn.getElement(), 300);
        });
    }

    resetColumns() {
        this.columns.forEach((column) => {
            column.destroy();
        });
        this.columns = [];
    }

    getSerializedFormData(): string {
        let params = new URLSearchParams(new FormData(this.getElement()) as any)

        return params.toString();
    }

    disableFields() {
        let elements = this.getElement().elements;

        for (let i = 0; i < elements.length; i++) {
            elements[i].setAttribute('readonly', 'readonly');
            elements[i].setAttribute('disabled', 'disabled');
        }
    }

    submitForm() {
        this.events.emit(EventConstants.SETTINGS.FORM.SAVING, this);

        submitColumnSettings(this.getSerializedFormData()).then((response: AxiosResponse<ColumnSettingsResponse>) => {
            if (response.data.success) {
                this.showMessage(response.data.data, 'updated')
            } else if (response.data) {
                let error: any = response.data as unknown;
                this.showMessage(error.data.message, 'notice notice-warning');
            }

        }).catch(() => {
            this.showMessage(AC.i18n.error.save_settings);
        }).finally(() => {
            this.events.emit(EventConstants.SETTINGS.FORM.SAVED, this);
        });

    }

    showMessage(message: string, className: string = 'updated') {
        let messageContainer = document.querySelector('.ac-admin__main');
        messageContainer.querySelectorAll('.ac-message').forEach((el: HTMLElement) => el.remove());

        let element: HTMLDivElement = document.createElement('div');
        element.classList.add('ac-message');
        element.classList.add(...className.split(' '));
        element.innerHTML = `<p>${message}</p>`;
        messageContainer.insertAdjacentElement('afterbegin', element);
        fadeIn(element, 600);
    }

    removeColumn(name: string) {
        this.columns.forEach((c, i) => {
            if (name === c.getName()) {
                this.columns.splice(i, 1);
            }
        });
    }

}

const createColumnFromTemplate = () => {
    let columnElement = document.querySelector('#add-new-column-template .ac-column').cloneNode(true) as HTMLElement;

    return new Column(columnElement, '_new_column');
}
