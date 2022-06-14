import {Column} from "../column";

export const initSubSettings = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('.ac-column-setting--filter,.ac-column-setting--sort,.ac-column-setting--edit').forEach(setting => {
        new SubsettingSetting(setting);
    });
}

class SubsettingSetting {
    input: HTMLInputElement | null
    subFields: NodeListOf<HTMLElement>

    constructor(private element: HTMLElement) {
        this.input = element.querySelector('.ac-setting-input input[type="checkbox"]');
        this.subFields = element.querySelectorAll('.ac-column-setting');
        this.initState();

        this.input?.addEventListener('input', () => this.initState())
    }

    initState() {
        this.isOptionEnabled()
            ? this.subFields.forEach(el => el.style.display = 'table')
            : this.subFields.forEach(el => el.style.display = 'none');
    }

    isOptionEnabled(): boolean {
        return this.input?.checked ?? false;
    }
}
