import {LocalizedScriptAC} from "../admincolumns";
import {keyAnyPair} from "../helpers/types";

type columns = {
    [key: string]: ColumnTableSettings
}

declare const AC: LocalizedScriptAC

export default class Columns {

    table: HTMLTableElement
    columns: { [key: string]: ColumnTableSettings }

    constructor(table: HTMLTableElement) {
        this.table = table;
        this.columns = {};

        this.init();
    }

    init() {
        let self = this;
        let thead = this.table.querySelector('thead');
        let headers = thead.querySelectorAll<HTMLTableHeaderCellElement>('th');

        for (let i = 0; i < headers.length; i++) {
            let headerName = headers[i].id;

            self.columns[headers[i].id] = new ColumnTableSettings(headerName, AC.column_types[headerName], this.sanitizeLabel(headers[i]));
        }
    }

    getColumns(): columns {
        return this.columns;
    }

    getColumnsMap() {
        let map = new Map();
        let columns = this.getColumns();

        Object.keys(columns).forEach((k) => {
            map.set(k, columns[k])
        });

        return map;
    }

    getColumnNames(): Array<string> {
        return Object.keys(this.columns);
    }

    get(column_name: string): ColumnTableSettings | null {
        return this.columns.hasOwnProperty(column_name) ? this.columns[column_name] : null;
    }

    sanitizeLabel(header: HTMLTableHeaderCellElement) {
        let link = header.querySelector<HTMLAnchorElement>('a');
        let label = header.innerHTML;

        // If it contains a link, we presume that it is because of sorting
        if (link) {
            let elements = link.getElementsByTagName('span');

            if (elements.length > 0) {
                label = elements[0].innerHTML;
            }
        }

        return label;
    }
}

export class ColumnTableSettings {

    name: string
    type: string
    label: string;
    services: keyAnyPair

    constructor(name: string, type: string, label: string) {
        this.name = name;
        this.type = type;
        this.label = label;
        this.services = {};
    }

    setService(name: string, service: any) {
        this.services[name] = service;
    }

    getService<T = any>(name: string): T {
        return this.hasService(name) ? this.services[name] : null;
    }

    hasService(name: string): boolean {
        return this.services.hasOwnProperty(name);
    }

}