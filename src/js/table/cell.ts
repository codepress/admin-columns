import {AdminColumnsInterface} from "../admincolumns";
import {keyAnyPair} from "../helpers/types";

declare const AdminColumns: AdminColumnsInterface

export default class Cell {

    private object_id: number
    private column_name: string
    private original_value: string
    private el: HTMLTableCellElement
    private services: keyAnyPair

    constructor(id: number, name: string, el: HTMLTableCellElement) {
        this.object_id = id;
        this.column_name = name;
        this.original_value = el.innerHTML;
        this.el = el;
        this.services = {}
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

    setElement(element: HTMLTableCellElement) {
        this.el = element;
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