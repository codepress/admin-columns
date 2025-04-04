import AcServices from "../modules/ac-services";
import Table from "./table";
import Nanobus from "nanobus";

declare const AC_SERVICES: AcServices;

export default class Cell {

    private readonly object_id: number
    private readonly column_name: string
    private readonly services: Record<string, any>
    private original_value: string
    private el: HTMLTableCellElement
    events: Nanobus

    constructor(id: number, name: string, el: HTMLTableCellElement) {
        this.object_id = id;
        this.column_name = name;
        this.original_value = el.innerHTML;
        this.el = el;
        this.services = {}
        this.events = new Nanobus();
    }

    getOriginalValue() {
        return this.original_value;
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
        return AC_SERVICES.getService<Table>('Table')?.Columns.get(this.getName());
    }

    hasChanged(content: string) {
        return this.original_value !== content;
    }

    setValue(value: string) {
        let rowActions = this.el.querySelector('.row-actions');

        this.original_value = value;
        this.el.innerHTML = value;

        if (rowActions) {
            this.el.append(rowActions);
        }

        this.original_value = value;
        this.events.emit('setValue', this);

        return this;
    }

    setService(name: string, service: any) {
        this.services[name] = service;
    }

    getService<T = any>(name: string): T|null {
        return this.hasService(name) ? this.services[name] : null;
    }

    hasService(name: string): boolean {
        return this.services.hasOwnProperty(name);
    }

}