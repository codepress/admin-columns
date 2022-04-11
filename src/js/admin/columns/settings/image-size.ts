import {Column} from "../column";

export const initImageSizeSetting = (column: Column) => {
    let setting = column.getElement().querySelector<HTMLElement>('.ac-column-setting--image');
    if (setting) {
        new ImageSizeSetting(column, setting);
    }
}


export class ImageSizeSetting {
    field: HTMLSelectElement | null

    constructor(public column: Column, public setting: HTMLElement) {
        this.field = this.setting.querySelector<HTMLSelectElement>('.ac-setting-input select');

        this.initState();
        this.bindEvents();
    }

    getValue() {
        return this.field?.value;
    }

    bindEvents() {
        this.field?.addEventListener('change', () => this.initState());
    }

    initState() {
        this.toggleSubSettings('cpac-custom' === this.getValue());
    }

    toggleSubSettings(show = true) {
        this.setting.querySelectorAll<HTMLElement>('.ac-column-setting').forEach(setting => setting.style.display = show ? 'table' : 'none');
    }

}