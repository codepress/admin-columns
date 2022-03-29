import Modal from "../../../modules/modal";
import {Column} from "../column";
import Nanobus from "nanobus";

export const initLabelSetting = (column: Column) => {
    let setting = column.getElement().querySelector<HTMLElement>('.ac-column-setting--label');
    if (setting) {
        new LabelSetting(column, setting);
    }
}

class LabelSetting {
    modal: IconPickerModal
    field: HTMLInputElement | null

    constructor(private column: Column, private setting: HTMLElement) {
        this.column = column;
        this.setting = setting;
        this.field = this.setting.querySelector<HTMLInputElement>('.ac-setting-input_label');

        let iconPicker = column.getElement().querySelector<HTMLElement>('.-iconpicker');

        if (iconPicker) {
            this.modal = new IconPickerModal(iconPicker);
            this.modal.setIconSelection(this.getDashIconFromValue());
            this.initEvents();
        }
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
        let dashicon = html.querySelector<HTMLElement>('.dashicons');
        let value = '';

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
        return this.field?.value ?? '';
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
    search: IconSearch;

    constructor(element: HTMLElement) {
        super(element);
        this.events = new Nanobus()
        this.dashIcon = '';
        this.search = new IconSearch(this);
    }

    initEvents() {
        super.initEvents();

        this.getElement().querySelectorAll('[data-action="submit"]').forEach((element: HTMLElement) => {
            element.addEventListener('click', (e) => {
                e.preventDefault();

                this.events.emit('submit');
            });
        });

        this.getElement().querySelectorAll('input[type=search]').forEach((element: HTMLInputElement) => {

            ['keyup', 'search'].forEach(event => {
                element.addEventListener(event, (e) => {
                    e.preventDefault();
                    this.search.searchFor(element.value);
                });
            });

        });

        this.getIconElements().forEach(icon => {
            icon.addEventListener('click', (e) => {
                e.preventDefault();
                this.setIconSelection(icon.dataset.dashicon ?? '');

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
        let selection = this.getElement().querySelector<HTMLElement>('.ac-ipicker__selection');

        this.dashIcon = dashicon;
        if (selection) {
            selection.innerHTML = this.getDashIconMarkup();
            selection.style.visibility = 'visible';
        }
    }

}

class IconSearch {
    modal: IconPickerModal

    constructor(modal: IconPickerModal) {
        this.modal = modal;
    }

    searchFor(query: string) {
        this.modal.getElement().querySelectorAll<HTMLElement>('[data-dashicon]').forEach(el => {
            if (el.dataset.dashicon?.indexOf(query.toLowerCase()) !== -1) {
                el.style.display = 'inline-block';
            } else {
                el.style.display = 'none';
            }
        });

        this.determineVisibilityGroups();
    }

    private determineVisibilityGroups() {
        this.modal.getElement().querySelectorAll<HTMLElement>('.ac-ipicker__group').forEach(group => {
            let icons = group.querySelectorAll<HTMLElement>('[data-dashicon]');
            let hiddenItems = Array.from(icons).filter(el => {
                return (el.offsetParent === null);
            });

            let header = group.querySelector('h3');

            if (header) {
                header.style.display = icons.length == hiddenItems.length ? 'none' : 'block';
            }
        });
    }

}