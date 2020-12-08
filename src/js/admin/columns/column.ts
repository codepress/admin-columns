// @ts-ignore
import $ from 'jquery';
import {EventConstants} from "../../constants";
import {AdminColumnsInterface} from "../../admincolumns";
import Nanobus from "nanobus";
import {refreshColumn, switchColumnType} from "./ajax";
import {AxiosResponse} from "axios";
import {createElementFromString} from "../../helpers/elements";
import {createColumnName} from "../../helpers/columns";
import {fadeOut} from "../../helpers/animations";

const STATES = {
    CLOSED: 'closed',
    OPEN: 'open'
};

export const COLUMN_EVENTS = {
    REMOVE: 'remove',
    CLONE: 'clone',
}

declare const AdminColumns: AdminColumnsInterface

type ajaxResponse = {
    success: boolean,
    data: any
}

export class Column {
    events: Nanobus;
    private element: HTMLElement
    private name: string
    private type: string
    private state: string
    private original: boolean
    private disabled: boolean

    constructor(element: HTMLElement, name: string) {
        this.events = new Nanobus();
        this.name = name;
        this.element = element;
        this.state = STATES.CLOSED;
        this.setPropertiesByElement(element);
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

    showMessage(message: string) {
        let msgElement = this.getElement().querySelector<HTMLElement>('.ac-column-setting--type .msg');
        if (msgElement) {
            msgElement.innerHTML = message;
            msgElement.style.display = 'block';
        }
    }

    getJson(): any {
        let tempForm = document.createElement('form');
        tempForm.appendChild(this.getElement().cloneNode(true));
        let formData = new FormData(tempForm);

        let r: any = {};

        for (let entry of formData.entries()) {
            console.log(entry);
            let nameParts = entry[0].split('[').map(p => p.split(']')[0]);
            let setter = r;
            let i = 0;
            nameParts.forEach((part) => {
                i++;
                if (!setter.hasOwnProperty(part)) {
                    setter[part] = i === nameParts.length ? entry[1] : {}
                }

                setter = setter[part];
            });
        }

        return r['columns'][this.getName()];
    }

    switchToType(type: string) {
        this.setLoading(true);

        switchColumnType(type).then((response: AxiosResponse<ajaxResponse>) => {
            if (response.data.success) {
                let name = createColumnName();
                let element = createElementFromString(response.data.data.trim()).firstChild as HTMLElement;

                setColumnNameToFormElements(name, element);
                this.name = name;
                this.reinitColumnFromElement(element)
            } else {
                this.showMessage(response.data.data.error);
            }

        }).finally(() => this.setLoading(false));
    }

    refresh() {
        this.setLoading(true);

        refreshColumn(this.getName(), JSON.stringify(this.getJson())).then((response: AxiosResponse<ajaxResponse>) => {
            if (response.data.success) {
                this.reinitColumnFromElement(createElementFromString(response.data.data.trim()).firstChild as HTMLElement)
            } else {
                this.showMessage('sdfsdfsdf');
            }

        }).finally(() => this.setLoading(false));
    }

    private reinitColumnFromElement(element: HTMLElement) {
        this.getElement().parentNode.replaceChild(element, this.getElement());
        this.element = element;
        this.setPropertiesByElement(element).init().open();
    }


    /**
     * @returns {Column}
     */
    create() {
        // TODO move out ckass
        /*this.initNewInstance();
        this.bindEvents();

        jQuery(document).trigger('AC_Column_Created', [this]);
        return this;*/
    }


    clone() {
        // TODO move out class
        /*let $clone = this.$el.clone();
        $clone.data('column-name', this.$el.data('column-name'));

        let clone = new Column($clone);

        clone.initNewInstance();
        clone.bindEvents();

        return clone;*/
    }
}

const setColumnNameToFormElements = (name: string, columnElement: HTMLElement) => {
    columnElement.querySelectorAll('input, select').forEach((element: HTMLElement) => {
        let name = element.getAttribute('name');
        if (name !== null) {
            element.setAttribute('name', name.replace('columns[]', `columns[${name}]`))
        }

    });
}