import {LocalizedAcTable} from "../types/table";
import {getTableConfig} from "./utils/global";
import ServiceContainer from "../modules/service-container";

type ColumnsValue = {
    [key: string]: ColumnTableSettings
}

export default class Columns {

    constructor(private table: HTMLTableElement, private columns: ColumnsValue = {}) {
        this.init();
    }

    init() {
        this.table.querySelector('thead')?.querySelectorAll<HTMLTableCellElement>('th').forEach(cell => {
            let headerName = cell?.id;
            this.columns[headerName] = new ColumnTableSettings(headerName, getTableConfig().column_types[headerName], this.sanitizeLabel(cell));
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
    private services: ServiceContainer

    constructor(name: string, type: string, label: string) {
        this.name = name;
        this.type = type;
        this.label = label;
        this.services = new ServiceContainer();
    }

    setService(name: string, service: any) {
        this.services.setService(name, service);
    }

    getService<T = any>(name: string): T|null {
        return this.services.getService<T>(name);
    }

    hasService(name: string): boolean {
        return this.services.hasService(name);
    }

}