import Modal from "../../../modules/modal";
import {Column} from "../column";
import Nanobus from "nanobus";

export default class LabelSetting {
    column: Column
    modal: IconPickerModal;
    field: HTMLInputElement;

    constructor(column: Column) {
        this.column = column;
        this.modal = new IconPickerModal(column.getElement().querySelector('.-iconpicker'));
        this.field = this.column.getElement().querySelector<HTMLInputElement>('.ac-column-setting--label .ac-setting-input_label');
        this.initEvents();
        this.modal.setIconSelection(this.getDashIconFromValue());
    }

    initEvents() {
        this.column.getElement().querySelectorAll('.ac-setting-label-icon').forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                this.modal.open();
            });
        });

        this.modal.onSubmit(() => {
            this.setLabel(this.modal.getDashIconMarkup());
            this.modal.close();
        });
    }

    getDashIconFromValue(): string {
        let html = document.createRange().createContextualFragment(this.getValue());
        let dashicon = html.querySelector('.dashicons');
        let value = null;

        if (!dashicon) {
            return value;
        }

        dashicon.classList.forEach(cls => {
            if (cls.indexOf('dashicons-') === 0) {
                value = cls.replace('dashicons-', '');
            }
        })

        return value;
    }

    getValue() {
        return this.field.value;
    }

    setLabel(label: string) {
        if (this.field) {
            this.field.value = label;
            this.field.dispatchEvent(new Event('change'));
        }
    }
}


class IconPickerModal extends Modal {
    events: Nanobus;
    dashIcon: string;

    constructor(element: HTMLElement) {
        super(element);
        this.events = new Nanobus()
        this.dashIcon = null;
    }

    initEvents() {
        super.initEvents();

        this.getElement().querySelectorAll('[data-action="submit"]').forEach((element: HTMLElement) => {
            element.addEventListener('click', (e) => {
                e.preventDefault();

                this.events.emit('submit');
            });
        });


        this.getIconElements().forEach(icon => {
            icon.addEventListener('click', (e) => {
                e.preventDefault();
                this.setIconSelection(icon.dataset.dashicon);

                this.getIconElements().forEach(el => el.classList.remove('active'));
                icon.classList.add('active');

            });
        });

    }

    getIconElements() {
        return this.getElement().querySelectorAll<HTMLElement>('.ac-ipicker__icon');
    }

    onSubmit(cb: any) {
        this.events.on('submit', cb);
    }

    getDashIconMarkup(): string {
        return `<span class="dashicons dashicons-${this.dashIcon}"></span>`
    }

    setIconSelection(dashicon: string) {
        let selection: HTMLElement = this.getElement().querySelector('.ac-ipicker__selection');

        this.dashIcon = dashicon;
        selection.innerHTML = this.getDashIconMarkup();
        selection.style.visibility = 'visible';
    }

}