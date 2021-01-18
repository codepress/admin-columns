import Nanobus from "nanobus";
import {EventConstants} from "../../constants";
import {Column, COLUMN_EVENTS} from "./column";
import {ColumnSettingsResponse, submitColumnSettings} from "./ajax";
import {AxiosResponse} from "axios";
import {fadeIn, scrollToElement} from "../../helpers/animations";
import {insertAfter} from "../../helpers/elements";
import {ListScreenStorageType, LocalizedScriptColumnSettings} from "./interfaces";
import {uniqid} from "../../helpers/string";

declare const AC: LocalizedScriptColumnSettings;

export class Form {

    private form: HTMLElement
    private events: Nanobus
    private columns: Array<Column>

    constructor(element: HTMLElement, events: Nanobus) {
        this.form = element;
        this.events = events;
        this.columns = [];
        this.events.emit(EventConstants.SETTINGS.FORM.LOADED, this);

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

    getElement(): HTMLElement {
        return this.form;
    }

    getColumns(): Array<Column> {
        return this.columns;
    }

    getSortedColumns(): Array<Column> {
        let result: Array<Column> = [];
        this.getElement().querySelectorAll<HTMLFormElement>('form.ac-column').forEach( column => {
            let c:Column = this.columns.find( c => c.getName() === column.dataset.columnName );
            if( c ){
                result.push( c );
            }
        });

        return result;
    }

    placeColumn(column: Column, after: HTMLElement = null): this {
        if (after) {
            insertAfter(column.getElement(), after);
        } else {
            this.getElement().querySelector('.ac-columns').append(column.getElement());
        }

        setTimeout(() => {
            scrollToElement(column.getElement(), 300, {offset: -18});
        }, 300)

        return this;
    }

    createNewColumn(): Column {
        let column = createColumnFromTemplate();
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
            let column = new Column(element, element.dataset.columnName);
            this.columns.push(column);
            this.bindColumnEvents(column);
        });
    }

    bindColumnEvents(column: Column) {
        column.events.addListener(COLUMN_EVENTS.REMOVE, () => {
            this.removeColumn(column.getName());
        });

        column.events.addListener(COLUMN_EVENTS.CLONE, () => {
            let cloneColumn = new Column(column.getElement().cloneNode(true) as HTMLFormElement, uniqid());
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

    private getPreferences(): { [key: string]: any } {
        let data: { [key: string]: any } = {};
        document.querySelectorAll<HTMLFormElement>('form[data-form-part=preferences]').forEach(el => {
            // @ts-ignore
            for (let t of new FormData(el).entries()) {
                data[t[0]] = t[1];
            }
        });

        return data;
    }

    getFormData(): ListScreenStorageType {
        let columnData: any = {};
        this.getSortedColumns().forEach(column => {
            columnData[column.getName()] = column.getJson();
        });

        return {
            title: '',
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
        this.events.emit(EventConstants.SETTINGS.FORM.SAVING, this);

        submitColumnSettings(this.getFormData()).then((response: AxiosResponse<ColumnSettingsResponse>) => {
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
    let columnElement = document.querySelector('#add-new-column-template .ac-column').cloneNode(true) as HTMLFormElement;

    return new Column(columnElement, uniqid());
}
