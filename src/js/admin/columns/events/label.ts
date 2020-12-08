import {Column} from "../column";
import {addEventListeners, onHover} from "../../../helpers/events";

export const initLabel = (column: Column) => {
    column.getElement().querySelectorAll<HTMLSelectElement>('select[data-label="update"]').forEach((select) => {
        select.addEventListener('change', () => {
            let labelSetting = column.getElement().querySelector<HTMLInputElement>('input.ac-setting-input_label');
            let option = select.querySelector('option:selected');

            if (labelSetting && option) {
                labelSetting.value = option.innerHTML;
                labelSetting.dispatchEvent(new Event('change'));
            }
        });
    });

    setTimeout(() => {
        let label = column.getElement().querySelector<HTMLElement>('.column_label .toggle');

        if (label && label.offsetWidth < 10) {
            label.innerText = column.getType();
        }
    }, 50)
}

export const initLabelSettingEvents = (column: Column) => {
    let labelInput = column.getElement().querySelector<HTMLInputElement>('.ac-column-setting--label input');

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
    console.log('S', display, label);
    let related = label.closest('.col-label').querySelector<HTMLElement>('div.tooltip');
    if (related) {
        related.style.display = display;
    }
}

const changeLabel = (labelInput: HTMLInputElement, column: Column) => {
    column.getElement().querySelector('td.column_label .inner > a.toggle').innerHTML = labelInput.value;
}