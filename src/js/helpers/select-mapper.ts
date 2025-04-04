import {AcRemoteSelectValues, SvelteSelectItem} from "../types/select";


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