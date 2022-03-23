import {Column} from "../column";
import {addEventListeners, onHover} from "../../../helpers/events";

export const initLabel = (column: Column) => {
    column.getElement().querySelectorAll<HTMLSelectElement>('select[data-label="update"]').forEach((select) => {
        select.addEventListener('change', () => {
            let labelSetting = column.getElement().querySelector<HTMLInputElement>('input.ac-setting-input_label');
            let option = select.selectedOptions.length > 0 ? select.selectedOptions[0] : null;

            if (labelSetting &&  option ) {
                labelSetting.value = option.innerHTML;
                labelSetting.dispatchEvent(new Event('change'));
            }
        });
    });

    setTimeout(() => {
        column.getElement().querySelectorAll<HTMLElement>('[data-column-label]').forEach( el => {
            if( el.offsetWidth < 10 ){
                el.innerText = column.getType();
            }
        });
    }, 50)
}

export const initLabelSettingEvents = (column: Column) => {
    let labelInput = column.getElement().querySelector<HTMLInputElement>('.ac-column-setting--label input[type=text]');

    if (labelInput) {
        addEventListeners(labelInput, ['change', 'keyup'], () => changeLabel(labelInput, column));
    }
}

export const initLabelTooltipsEvent = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('.col-label .label').forEach(label => {
        onHover(label, () => hoverTooltip(label, 'block'), () => hoverTooltip(label, 'none'))
    });
}

const hoverTooltip = (label: HTMLElement, display: string) => {
    let related = label.closest('.col-label').querySelector<HTMLElement>('div.tooltip');
    if (related) {
        related.style.display = display;
    }
}

const changeLabel = (labelInput: HTMLInputElement, column: Column) => {
    column.getElement().querySelectorAll('[data-column-label]').forEach( el => el.innerHTML = labelInput.value );
}