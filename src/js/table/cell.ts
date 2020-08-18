import {AdminColumnsInterface} from "../admincolumns";

declare const AdminColumns: AdminColumnsInterface

export default class Cell {

    private object_id: number
    private column_name: string
    private original_value: string
    private el: HTMLTableCellElement

    constructor(id: number, name: string, el: HTMLTableCellElement) {
        this.object_id = id;
        this.column_name = name;
        this.original_value = el.innerHTML;
        this.el = el;
    }

    getObjectID(): number {
        return this.object_id;
    }

    getName(): string {
        return this.column_name;
    }

    getElement(): HTMLTableCellElement {
        return this.el;
    }

    getRow(): HTMLTableRowElement {
        return <HTMLTableRowElement>this.el.parentElement;
    }

    getSettings() {
        return AdminColumns.Table.Columns.get(this.getName());
    }

    hasChanged(content: string) {
        return this.original_value !== content;
    }

    setValue(value: string) {
        this.original_value = value;
        this.el.innerHTML = value;

        return this;
    }

}