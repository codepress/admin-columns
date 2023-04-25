import {getParamFromUrl} from "./global";

export const getIdFromTableRow = (row: HTMLTableRowElement): number => {
    if (row.classList.contains('no-items')) {
        return 0;
    }

    let item_id: number = getIdFromString(row.id);

    if (!item_id) {
        let input = row.querySelector('.check-column input[type=checkbox]');

        if (input) {
            item_id = getIdFromString(input.id);
        }
    }

    // Try to get the ID from the edit URL (MS Sites)
    if (!item_id) {
        let link = row.parentElement?.querySelector('.edit a');

        if (!!link) {
            let href = link.getAttribute('href');

            if (href) {
                item_id = parseInt(getParamFromUrl('id', href) ?? '' );
            }
        }
    }

    if( ! item_id ){
        // TODO fix for WC with hook?
        const orderCheckbox = row.querySelector<HTMLInputElement>('.check-column input[name="order[]"]');
        if( orderCheckbox ){
            item_id = parseInt( orderCheckbox.value );
        }

    }


    row.dataset.id = item_id.toString();

    return item_id;
}

export const getIdFromString = (value: string): number => {
    let id_parts = value.split(/[_,\-]+/);

    return parseInt(id_parts[id_parts.length - 1]);
}

export const getRowCellByName = (row: HTMLTableRowElement, column_name: string): HTMLTableCellElement|null => {
    return row.querySelector<HTMLTableCellElement>(`td.column-${column_name}`);
}

export const resolveTableBySelector = (selector: string): HTMLTableElement|null => {
    let table: HTMLTableElement|null = document.querySelector(selector);

    if (!table) {
        return null;
    }

    if (table.tagName === 'TABLE') {
        return table;
    }

    if (table.tagName === 'TBODY') {
        return table.closest('table');
    }

    if (table.querySelector('table.wp-list-table')) {
        return table.querySelector('table.wp-list-table');
    }

    return null;
}