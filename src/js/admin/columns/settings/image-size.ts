import {Column} from "../column";

export const initImageSizeSetting = (column: Column) => {
    let setting: HTMLElement = column.getElement().querySelector('.ac-column-setting--image');
    if (setting) {
        new ImageSizeSetting(column, setting);
    }
}


export class ImageSizeSetting {

    column: Column
    setting: HTMLElement
    field: HTMLSelectElement

    constructor(column: Column, setting: HTMLElement) {
        this.column = column;
        this.setting = setting
        this.field = this.setting.querySelector('.ac-setting-input select');

        this.initState();
        this.bindEvents();
    }

    getValue() {
        return this.field.value;
    }

    bindEvents() {
        this.field.addEventListener('change', () => this.initState());
    }

    initState() {
        this.toggleSubSettings('cpac-custom' === this.getValue());
    }

    toggleSubSettings(show = true) {
        this.setting.querySelectorAll<HTMLElement>('.ac-column-setting').forEach(setting => setting.style.display = show ? 'table' : 'none');
    }

}