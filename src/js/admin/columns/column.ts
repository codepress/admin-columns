// @ts-ignore
import $ from 'jquery';
import {EventConstants} from "../../constants";
import Nanobus from "nanobus";
import {refreshColumn, switchColumnType} from "./ajax";
import {AxiosResponse} from "axios";
import {createElementFromString} from "../../helpers/elements";
import {fadeOut} from "../../helpers/animations";
import {uniqid} from "../../helpers/string";
import {LocalizedAcColumnSettings} from "../../types/admin-columns";
import AcServices from "../../modules/ac-services";

const STATES = {
    CLOSED: 'closed',
    OPEN: 'open'
};

export const COLUMN_EVENTS = {
    REMOVE: 'remove',
    CLONE: 'clone',
}

declare const AC: LocalizedAcColumnSettings

type ajaxResponse = {
    success: boolean,
    data: any
}

export class Column {
    events: Nanobus;
    private services: AcServices
    private element: HTMLFormElement
    private name: string
    private type: string
    private state: string
    private original: boolean
    private disabled: boolean

    constructor(element: HTMLFormElement, name: string, services: AcServices) {
        this.events = new Nanobus();
        this.name = name;
        this.element = element;
        this.state = STATES.CLOSED;
        this.services = services
        this.setPropertiesByElement(element);
        this.init();
    }

    private setPropertiesByElement(element: HTMLElement) {
        this.type = element.dataset.type as string;
        this.original = element.dataset.original === '1';
        this.disabled = element.classList.contains('disabled');
        element.dataset.columnName = this.name;

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
        this.services.emitEvent(EventConstants.SETTINGS.COLUMN.INIT, this);
        return this;
    }

    destroy() {
        this.element.remove();
    }

    remove(duration = 150) {
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
        for (var entry of formData.entries()) {
            let key = entry[0];
            let value = entry[1];

            if (this.fieldSupportsMultipleValues(key)) {
                let _value = obj.hasOwnProperty(key) ? obj[key] : [];
                _value.push(value);
                obj[key] = _value;
            } else {
                obj[key] = value;
            }
        }

        return obj;
    }

    switchToType(type: string) {
        this.setLoading(true);

        switchColumnType(type).then((response: AxiosResponse<ajaxResponse>) => {
            if (response.data.success) {
                let element = createElementFromString(response.data.data.trim()).firstChild as HTMLFormElement;
                this.name = uniqid();
                this.reinitColumnFromElement(element).open();
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
                this.services.emitEvent(EventConstants.SETTINGS.COLUMN.REFRESHED, this);
                if( this.isOpen() ){
                    this.open();
                }
            } else {
                this.showMessage(AC.i18n.errors.loading_column);
            }

        }).finally(() => this.setLoading(false));
    }

    private fieldSupportsMultipleValues(key: string) {
        let element = this.getElement().elements[key as any];

        return (element && element.tagName === 'SELECT' && element.hasAttribute('multiple'));
    }

    private reinitColumnFromElement(element: HTMLFormElement) {
        this.getElement().parentNode?.replaceChild(element, this.getElement());
        this.element = element;
        this.setPropertiesByElement(element).init();

        return this;
    }

}

type columnSettings = { [key: string]: any }