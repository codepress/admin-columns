import {EventConstants} from "../../constants";
import {Column, COLUMN_EVENTS} from "./column";
import {ColumnSettingsResponse, submitColumnSettings} from "./ajax";
import {AxiosResponse} from "axios";
import {fadeIn, scrollToElement} from "../../helpers/animations";
import {insertAfter} from "../../helpers/elements";
import {ListScreenStorageType, LocalizedAcColumnSettings} from "../../types/admin-columns";
import {uniqid} from "../../helpers/string";
import {keyAnyPair} from "../../helpers/types";
import AcServices from "../../modules/ac-services";
import AcHtmlElement from "../../helpers/html-element";

declare const AC: LocalizedAcColumnSettings;

export type ListScreenPreferencesValue = { [key: string]: any }

export interface ListScreenPreference {
    getPreferences(preferences: ListScreenPreferencesValue): ListScreenPreferencesValue
}

export class Form {

    private form: HTMLElement
    private services: AcServices
    private columns: Array<Column>
    private preferences: Array<ListScreenPreference>

    constructor(element: HTMLElement, services: AcServices) {
        this.form = element;
        this.services = services;
        this.columns = [];
        this.preferences = [];
        this.services.emitEvent(EventConstants.SETTINGS.FORM.LOADED, this);

        this.init();
    }

    init() {
        this.initColumns();

        if (this.isDisabled()) {
            this.disableFields();
            this.disableColumns();
        }

        this.services.emitEvent(EventConstants.SETTINGS.FORM.READY, this);
    }

    getElement(): HTMLElement {
        return this.form;
    }

    getColumns(): Array<Column> {
        return this.columns;
    }

    getSortedColumns(): Array<Column> {
        let result: Array<Column> = [];
        this.getElement().querySelectorAll<HTMLFormElement>('form.ac-column').forEach(column => {
            let c = this.columns.find(c => c.getName() === column.dataset.columnName);
            if (!!c) {
                result.push(c);
            }
        });

        return result;
    }

    placeColumn(column: Column, after: HTMLElement | null = null): this {
        if (after) {
            insertAfter(column.getElement(), after);
        } else {
            this.getElement().querySelector('.ac-columns')?.append(column.getElement());
        }

        setTimeout(() => {
            scrollToElement(column.getElement(), 300, {offset: -18});
        }, 300)

        return this;
    }

    createNewColumn(): Column {
        let column = createColumnFromTemplate(this.services);
        this.columns.push(column);
        this.placeColumn(column);
        this.bindColumnEvents(column);
        column.open(300);

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
        this.getElement().querySelectorAll('.ac-column').forEach((element: HTMLFormElement) => {
            let column = new Column(element, element.dataset.columnName ?? '', this.services);
            this.columns.push(column);
            this.bindColumnEvents(column);
        });
    }

    bindColumnEvents(column: Column) {
        column.events.addListener(COLUMN_EVENTS.REMOVE, () => {
            this.removeColumn(column.getName());
        });

        column.events.addListener(COLUMN_EVENTS.CLONE, () => {
            let cloneColumn = new Column(column.getElement().cloneNode(true) as HTMLFormElement, uniqid(), this.services);
            this.columns.push(cloneColumn);
            this.placeColumn(cloneColumn, column.getElement()).bindColumnEvents(cloneColumn);
            column.isOpen() ? cloneColumn.open() : cloneColumn.close();

            fadeIn(cloneColumn.getElement(), 300);
        });
    }

    resetColumns() {
        this.columns.forEach((column) => {
            column.destroy();
        });
        this.columns = [];
    }

    getFormData(): ListScreenStorageType {
        let columnData: any = {};
        let titleElement = this.getElement().querySelector<HTMLInputElement>('input[name=title]');

        this.getSortedColumns().forEach(column => {
            columnData[column.getName()] = column.getJson();
        });

        return {
            title: titleElement ? titleElement.value : '',
            list_screen: AC.list_screen,
            list_screen_id: AC.layout,
            columns: columnData,
            settings: this.getPreferences()
        }
    }

    disableFields() {
        this.getElement().querySelectorAll('input, select, button').forEach(el => {
            el.setAttribute('readonly', 'readonly');
            el.setAttribute('disabled', 'disabled');
        });
    }

    submitForm() {
        this.services.emitEvent(EventConstants.SETTINGS.FORM.SAVING, this);

        submitColumnSettings(this.getFormData()).then((response: AxiosResponse<ColumnSettingsResponse>) => {
            if (response.data.success) {
                this.showMessage(response.data.data.message, 'updated')
                AC.layout = response.data.data.list_id;
            } else if (response.data) {
                let error: any = response.data as unknown;
                this.showMessage(error.data.message, 'notice notice-warning');
            }

        }).catch(() => {
            this.showMessage(AC.i18n.error.save_settings);
        }).finally(() => {
            this.services.emitEvent(EventConstants.SETTINGS.FORM.SAVED, this);
        });

    }

    showMessage(message: string, className: string = 'updated') {
        let element = AcHtmlElement.create<HTMLDivElement>('div').addClass('ac-message').addClasses(...className.split(' ')).addHtml(`<p>${message}</p>`).getElement();
        let messageContainer = document.querySelector('.ac-admin__main');

        if (messageContainer) {
            messageContainer.querySelectorAll('.ac-message').forEach((el: HTMLElement) => el.remove());
            messageContainer.insertAdjacentElement('afterbegin', element);
        }
        fadeIn(element, 600);
    }

    removeColumn(name: string) {
        this.columns.forEach((c, i) => {
            if (name === c.getName()) {
                this.columns.splice(i, 1);
            }
        });
    }

    registerPreference(preference: ListScreenPreference) {
        this.preferences.push(preference);
    }

    private getPreferences(): keyAnyPair {
        let data: { [key: string]: any } = {};

        this.preferences.forEach((p) => {
            data = p.getPreferences(data)
        });

        return data;
    }
}

const createColumnFromTemplate = (services: AcServices) => {
    let columnElement = document.querySelector<HTMLFormElement>('#add-new-column-template .ac-column')?.cloneNode(true) as HTMLFormElement;
    const newColumnName = uniqid();
    columnElement.querySelectorAll<HTMLLabelElement>('label[for]').forEach(label => {
        let relatedId = label.getAttribute('for');
        if (relatedId) {
            let relatedElement = columnElement.querySelector(`#${relatedId}`);
            if (relatedElement) {
                const newID = relatedId + newColumnName;
                label.setAttribute('for', newID);
                relatedElement.id = newID;
            }
        }
    });

    return new Column(columnElement, newColumnName, services);
}
