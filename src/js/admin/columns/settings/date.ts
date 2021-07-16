import {Column} from "../column";
import {AxiosResponse} from "axios";

const axios = require('axios');

declare const ajaxurl: string;

export const initDateSetting = (column: Column) => {

    column.getElement().querySelectorAll<HTMLElement>('[data-setting=date]').forEach(setting => new DateSetting(column, setting));
}

class DateSetting {
    column: Column
    setting: HTMLElement
    options: NodeListOf<HTMLInputElement>
    defaultFormat: string
    customOption: CustomOption
    valueInput: HTMLInputElement

    constructor(column: Column, setting: HTMLElement) {
        this.column = column;
        this.setting = setting;
        this.options = this.setting.querySelectorAll('.radio-labels input[type=radio]');
        this.valueInput = this.setting.querySelector('[data-value-input]');

        let defaultElement = this.setting.querySelector('.radio-labels code');
        this.defaultFormat = defaultElement ? defaultElement.textContent : 'Y-m-d';

        // @ts-ignore
        let customInput = [...this.options].filter(radio => typeof radio.dataset.custom !== 'undefined');

        this.customOption = new CustomOption(
            customInput[0],
            this.setting.querySelector('[data-custom-date]'),
            this.setting.querySelector('.ac-setting-input-date__example'),
            this.valueInput
        );

        this.initEvents();
    }

    getOptionsAsArray(): Array<HTMLInputElement> {
        return Array.from(this.options);
    }

    getSelectionOption(): HTMLInputElement {
        let selected = this.getOptionsAsArray().filter(option => option.checked);

        return selected ? selected[0] : null;
    }

    initEvents() {
        this.options.forEach(radio => {
            radio.addEventListener('change', () => this.handleUpdate(radio));
        });

        this.setSelected();
    }

    setSelected() {
        let selected = this.getOptionsAsArray().find(option => option.value === this.getCurrentValue())

        if (selected) {
            selected.checked = true;
            selected.dispatchEvent(new Event('change'));
        }
    }

    handleUpdate(input: HTMLInputElement) {
        this.valueInput.value = input.value;
        this.customOption.toggle(typeof input.dataset.custom !== 'undefined');
        this.setHelpText(this.getHelpTextFromType(input));

        switch (this.valueInput.value) {
            case 'custom':
                break;
            case 'wp_default':
            case 'diff':
                this.customOption.setExample('')
                break;
            default:
                this.customOption.setExample(this.valueInput.value);

        }
        this.customOption.updateExample();
    }

    getCurrentValue() {
        return this.valueInput.value;
    }

    setHelpText(text: string) {
        let element: HTMLElement = this.setting.querySelector('.help-msg');
        element.innerHTML = text;
        element.style.display = 'block';
    }

    private getHelpTextFromType(type: HTMLElement): string {
        let helpText = type.closest('label').querySelector('[data-help]');

        return helpText ? helpText.innerHTML : null;
    }


}

class CustomOption {

    radio: HTMLInputElement
    input: HTMLInputElement
    example: HTMLElement
    valueElement: HTMLInputElement
    timeout: any;

    constructor(radio: HTMLInputElement, input: HTMLInputElement, example: HTMLElement, valueElement: HTMLInputElement) {
        this.radio = radio;
        this.input = input;
        this.example = example;
        this.valueElement = valueElement;
        this.timeout = null;

        this.input.addEventListener('change', () => {
            this.updateExample();
            if (radio.checked) {
                this.valueElement.value = this.input.value;
            }
        });

        this.input.addEventListener('keyup', () => {
            if (radio.checked) {
                this.valueElement.value = this.input.value;
            }

            if (this.timeout) {
                clearTimeout(this.timeout);
            }

            this.timeout = setTimeout(() => this.updateExample(), 500);
        });
    }

    setExample(example: string) {
        this.input.value = example;
    }

    updateExample() {
        this.getExample().then((response: AxiosResponse<string>) => {
            this.example.innerHTML = response.data;
        })
    }

    toggle(enable = true) {
        enable
            ? this.input.removeAttribute('disabled')
            : this.input.setAttribute('disabled', 'disabled')
    }

    getExample() {
        let data = new FormData();
        data.set('action', 'date_format');
        data.set('date', this.input.value);

        return axios.post(ajaxurl, data, {})
    }
}