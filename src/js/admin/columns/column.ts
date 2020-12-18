// @ts-ignore
import $ from 'jquery';
import {EventConstants} from "../../constants";
import {AdminColumnsInterface} from "../../admincolumns";
import Nanobus from "nanobus";
import {refreshColumn, switchColumnType} from "./ajax";
import {AxiosResponse} from "axios";
import {createElementFromString} from "../../helpers/elements";
import {fadeOut} from "../../helpers/animations";
import {uniqid} from "../../helpers/string";
import {LocalizedScriptColumnSettings} from "./interfaces";

const STATES = {
    CLOSED: 'closed',
    OPEN: 'open'
};

export const COLUMN_EVENTS = {
    REMOVE: 'remove',
    CLONE: 'clone',
}

declare const AC: LocalizedScriptColumnSettings
declare const AdminColumns: AdminColumnsInterface

type ajaxResponse = {
    success: boolean,
    data: any
}

export class Column {
    events: Nanobus;
    private element: HTMLFormElement
    private name: string
    private type: string
    private state: string
    private original: boolean
    private disabled: boolean

    constructor(element: HTMLFormElement, name: string) {
        this.events = new Nanobus();
        this.name = name;
        this.element = element;
        this.state = STATES.CLOSED;
        this.setPropertiesByElement(element);
        this.init();
    }

    private setPropertiesByElement(element: HTMLElement) {
        this.type = element.dataset.type;
        this.original = element.dataset.original === '1';
        this.disabled = element.classList.contains('disabled');

        return this;
    }

    getName() {
        return this.name;
    }

    getType() {
        return this.type;
    }

    isOriginal() {
        return this.original;
    }

    getElement() {
        return this.element;
    }

    isDisabled(): boolean {
        return this.element.classList.contains('disabled');
    }

    disable() {
        this.element.classList.add('disabled');

        return this;
    }

    setLoading(enabled: boolean) {
        enabled
            ? this.getElement().classList.add('loading')
            : this.getElement().classList.remove('loading');

        return this;
    }

    enable(): this {
        this.element.classList.remove('disabled');

        return this;
    }

    init(): this {
        AdminColumns.events.emit(EventConstants.SETTINGS.COLUMN.INIT, this);
        return this;
    }

    destroy() {
        this.element.remove();
    }

    remove(duration = 350) {
        this.events.emit(COLUMN_EVENTS.REMOVE, this);
        fadeOut(this.getElement(), duration, () => {
            this.destroy();
        });
    }

    getState() {
        return this.state;
    }

    toggle(duration = 150) {
        this.getState() === STATES.OPEN
            ? this.close(duration)
            : this.open(duration);
    }

    close(duration = 0) {
        this.getElement().classList.remove('opened');
        $(this.getElement()).find('.ac-column-body').slideUp(duration);
        this.state = STATES.CLOSED;
    }

    open(duration = 0) {
        this.getElement().classList.add('opened');
        $(this.getElement()).find('.ac-column-body').slideDown(duration);
        this.state = STATES.OPEN;
    }

    isOpen(): Boolean {
        return this.state === STATES.OPEN;
    }

    showMessage(message: string) {
        let msgElement = this.getElement().querySelector<HTMLElement>('.ac-column-setting--type .msg');
        if (msgElement) {
            msgElement.innerHTML = message;
            msgElement.style.display = 'block';
        }
    }

    getJson(): columnSettings {
        let formData = new FormData(this.getElement());
        formData.set('name', this.getName());

        var obj: columnSettings = {};

        // @ts-ignore
        for (var key of formData.entries()) {
            obj[key[0]] = key[1];
        }

        return obj;
    }

    switchToType(type: string) {
        this.setLoading(true);

        switchColumnType(type).then((response: AxiosResponse<ajaxResponse>) => {
            if (response.data.success) {
                let element = createElementFromString(response.data.data.trim()).firstChild as HTMLFormElement;
                this.name = uniqid();
                this.reinitColumnFromElement(element)
            } else {
                this.showMessage(response.data.data.error);
            }

        }).catch(() => {
            this.showMessage(AC.i18n.errors.loading_column);
        }).finally(() => this.setLoading(false));
    }

    refresh() {
        this.setLoading(true);

        refreshColumn(this.getName(), JSON.stringify(this.getJson())).then((response: AxiosResponse<ajaxResponse>) => {
            if (response.data.success) {
                this.reinitColumnFromElement(createElementFromString(response.data.data.trim()).firstChild as HTMLFormElement);
                AdminColumns.events.emit(EventConstants.SETTINGS.COLUMN.REFRESHED, this);
            } else {
                this.showMessage(AC.i18n.errors.loading_column);
            }

        }).finally(() => this.setLoading(false));
    }

    private reinitColumnFromElement(element: HTMLFormElement) {
        this.getElement().parentNode.replaceChild(element, this.getElement());
        this.element = element;
        this.setPropertiesByElement(element).init().open();
    }

}

type columnSettings = { [key: string]: any }