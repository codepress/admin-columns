import {AcRemoteSelectValues, AcSelectValue, SvelteSelectItem} from "../types/select";

export let mapAcSelectValues2SvelteSelect = (selectValues: AcSelectValue): SvelteSelectItem[] => {
    let result: SvelteSelectItem[] = [];


    Object.values(selectValues).forEach(item => {
        const group = item.title;

        for (const [value, label] of Object.entries(item.options)) {
            result.push({
                value,
                label,
                group
            });
        }
    });

    return result;
}

export let mapAcRemoteSelectValues2SvelteSelect = (selectValues: AcRemoteSelectValues): SvelteSelectItem[] => {
    let result: SvelteSelectItem[] = [];

    selectValues.forEach((item) => {

        if ('id' in item) {
            result.push({
                value: item.id,
                label: item.text
            })
        }

        if ('children' in item) {
            item.children.forEach(child => {
                result.push({
                    group: item.text,
                    value: child.id,
                    label: child.text
                })
            })
        }
    })


    return result;
}