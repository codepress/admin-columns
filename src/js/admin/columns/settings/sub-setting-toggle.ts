import {Column} from "../column";

export const initSubSettings = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('.ac-column-setting--filter,.ac-column-setting--sort,.ac-column-setting--edit').forEach(setting => {
        new SubsettingSetting(setting);
    });
}

class SubsettingSetting {
    element: HTMLElement
    inputs: NodeListOf<HTMLInputElement>
    subFields: NodeListOf<HTMLElement>

    constructor(element: HTMLElement) {
        this.element = element;
        this.inputs = element.querySelectorAll('.ac-setting-input input[type="radio"]');
        this.subFields = element.querySelectorAll('.ac-column-setting');
        this.initState();
        this.initEvents();
    }

    initEvents() {
        this.inputs.forEach(el => {
            el.addEventListener('change', () => this.initState());
        })
    }

    initState() {
        this.isOptionEnabled()
            ? this.subFields.forEach(el => el.style.display = 'table')
            : this.subFields.forEach(el => el.style.display = 'none');
    }

    isOptionEnabled(): boolean {
        let checked = [...this.inputs].filter(input => {
            return input.checked;
        });

        return checked.length ? checked[0].value === 'on' : false;
    }
}
