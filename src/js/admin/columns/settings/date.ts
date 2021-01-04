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
        this.defaultFormat = this.setting.querySelector('.radio-labels code').textContent;
        this.valueInput = this.setting.querySelector('[data-value-input]');

        // @ts-ignore
        let customInput = [...this.options].filter(radio => radio.value === 'custom');
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
            radio.addEventListener('change', () => this.setCustomValue(radio.value));
        });

        let selected = this.getSelectionOption();

        if (!selected) {
            selected = this.getOptionsAsArray().filter(option => option.value === 'wp_default')[0];
            selected.checked = true;
        }
        selected.dispatchEvent(new Event('change'));
    }

    handleUpdate(input: HTMLInputElement) {
        this.valueInput.value = input.value;
        this.customOption.toggle(input.value === 'custom');
        this.setHelpText(this.getHelpTextFromType(input.value));
    }

    setCustomValue(type: string) {
        switch (type) {
            case 'diff':
                this.customOption.setCustomValue('');
                break;
            case 'custom':
                break;
            default:
                this.customOption.setCustomValue(this.defaultFormat);
        }
    }

    setHelpText(text: string) {
        let element: HTMLElement = this.setting.querySelector('.help-msg');
        element.innerHTML = text;
        element.style.display = 'block';
    }

    private getHelpTextFromType(type: string): string {
        let input = this.getOptionsAsArray().filter(radio => radio.value === type);
        if (!input) {
            return '';
        }

        let helpText = input[0].closest('label').querySelector('[data-help]');

        return helpText ? helpText.innerHTML : null;
    }


}

class CustomOption {

    radio: HTMLInputElement
    input: HTMLInputElement
    example: HTMLElement
    valueElement: HTMLInputElement

    constructor(radio: HTMLInputElement, input: HTMLInputElement, example: HTMLElement, valueElement: HTMLInputElement) {
        this.radio = radio;
        this.input = input;
        this.example = example;
        this.valueElement = valueElement;

        this.input.addEventListener('change', () => this.updateExample());
        this.input.addEventListener('keyup', () => {
            if (radio.checked) {
                this.valueElement.value = this.input.value;
            }
        });
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

    getCustomValue() {
        return this.input.value;
    }

    setCustomValue(format: string) {
        this.input.value = format;
    }

    getExample() {
        let data = new FormData();
        data.set('action', 'date_format');
        data.set('date', this.getCustomValue());

        return axios.post(ajaxurl, data, {})
    }
}