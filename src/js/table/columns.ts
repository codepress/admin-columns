import {keyAnyPair} from "../helpers/types";
import {LocalizedAcTable} from "../types/table";

type ColumnsValue = {
    [key: string]: ColumnTableSettings
}

declare const AC: LocalizedAcTable

export default class Columns {

    constructor( private table: HTMLTableElement, private columns: ColumnsValue = {} ) {
        this.init();
    }

    init() {
        this.table.querySelector('thead')?.querySelectorAll<HTMLTableCellElement>('th').forEach(cell => {
            let headerName = cell?.id;
            this.columns[headerName] = new ColumnTableSettings(headerName, AC.column_types[headerName], this.sanitizeLabel(cell));
        });
    }

    getColumns(): ColumnsValue {
        return this.columns;
    }

    getColumnNames(): Array<string> {
        return Object.keys(this.columns);
    }

    get(column_name: string): ColumnTableSettings | null {
        return this.columns.hasOwnProperty(column_name) ? this.columns[column_name] : null;
    }

    sanitizeLabel(header: HTMLTableCellElement) {
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

    getService<T = any>(name: string): T | null {
        return this.hasService(name) ? this.services[name] : null;
    }

    hasService(name: string): boolean {
        return this.services.hasOwnProperty(name);
    }

}