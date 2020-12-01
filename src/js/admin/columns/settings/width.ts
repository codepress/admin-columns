import {Column} from "../column";
import Nanobus from "nanobus";
// @ts-ignore
import $ from 'jquery';

export const initWidthSetting = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('table[data-setting="width"]').forEach(setting => new WidthSetting(column, setting));
}

type widthValue = {
    width: number
    unit: string
}

class WidthSetting {
    column: Column
    setting: HTMLElement
    events: Nanobus
    indicator: WidthIndicator
    widthInput: HTMLInputElement
    unitInput: NodeListOf<HTMLInputElement>

    constructor(column: Column, setting: HTMLElement) {
        this.column = column;
        this.setting = setting;
        this.events = new Nanobus();
        this.indicator = new WidthIndicator(column.getElement().querySelector('.ac-column-header .ac-column-heading-setting--width'));
        this.widthInput = this.setting.querySelector<HTMLInputElement>('[data-width-input]');
        this.unitInput = this.setting.querySelectorAll<HTMLInputElement>('[data-unit-input] input')

        this.init();
    }

    getWidth(): number {
        let widthValue = this.widthInput.value;
        return widthValue ? parseInt(widthValue) : null
    }

    setWidth(width: number) {
        this.widthInput.value = width ? width.toString() : null;
        this.updateIndicator();
    }

    updateUnit() {
        this.setting.querySelector('.description .unit').innerHTML = this.getUnit();
    }

    getUnit(): string {
        return this.setting.querySelector<HTMLInputElement>('[data-unit-input] input:checked').value
    }

    getValue(): widthValue {
        return {
            width: this.getWidth(),
            unit: this.getUnit()
        }
    }

    validate() {
        let width = this.getWidth();

        if( width ===0 || width < 0 ){
            this.setWidth(null);
        }

        if (this.getUnit() === '%') {
            if (width > 100) {
                this.setWidth(100);
            }
        }
    }

    init() {
        this.widthInput.addEventListener('keyup', () => this.updateIndicator());

        this.unitInput.forEach(el => {
            el.addEventListener('change', () => {
                this.initSlider();
                this.updateIndicator();
                this.updateUnit();
                this.validate();
            });
        });

        this.initSlider();
    }

    updateIndicator() {
        this.indicator.setValue(this.getWidth(), this.getUnit())
    }

    initEvents() {

    }

    initSlider() {
        let sliderElement: HTMLElement = this.column.getElement().querySelector('.width-slider');

        $(sliderElement).slider({
            range: 'min',
            min: 0,
            max: '%' === this.getUnit() ? 100 : 500,
            value: this.getWidth(),
            slide: (event: any, ui: any) => {
                this.setWidth(ui.value);
            }
        });
    }
}

class WidthIndicator {

    element: HTMLElement
    events: Nanobus

    constructor(element: HTMLElement) {
        this.element = element;
        this.events = new Nanobus();
    }

    setValue(width: number, unit: string) {
        if (width === null) {
            return this.element.innerText = '';
        }

        this.element.innerText = `${width.toString()}${unit}`;
    }

}


let width = function (column) {
    let $ = jQuery;
    let $column = column.$el;

    $column.find('.ac-column-setting--width').each(function () {
        $column.column_width_slider();

        // unit selector
        let width_unit_select = $column.find('.ac-setting-input-width .unit-select label');
        width_unit_select.on('click', function () {

            $column.find('span.unit').text($(this).find('input').val());
            $column.column_width_slider(); // re-init slider
            $width_indicator.trigger('update'); // update indicator
        });

        // width_input
        let width_input = $column.find('.ac-setting-input-width .description input')

            // width_input:validate
            .on('validate', function () {
                let _width = width_input.val();
                let _new_width = $.trim(_width);

                if (!jQuery.isNumeric(_new_width)) {
                    _new_width = _new_width.replace(/\D/g, '');
                }
                if (_new_width.length > 3) {
                    _new_width = _new_width.substring(0, 3);
                }
                if (_new_width <= 0) {
                    _new_width = '';
                }
                if (_new_width !== _width) {
                    width_input.val(_new_width);
                }
            });

    });
};