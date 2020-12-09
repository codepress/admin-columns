import {Column} from "../admin/columns/column";

class columnName {
    static count: number = 0;

    constructor() {
        columnName.count++;
    }

    getName(): string {
        return `_new_column_${columnName.count}`;
    }
}

export const createColumnName = (): string => {
    return new columnName().getName();
}

export const reinitInputNames = (column: Column) => {
    column.getElement().querySelectorAll('input, select').forEach((element: HTMLElement) => {
        let _name = element.getAttribute('name');

        if (_name !== null) {
            const regex = /\[(.+?)\]/;

            element.setAttribute('name', _name.replace(regex, `[${column.getName()}]`));
        }
    });
}