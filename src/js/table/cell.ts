import Nanobus from "nanobus";
import {ColumnTableSettings} from "./columns";
import ServiceContainer from "../modules/service-container";

export default class Cell {

    private readonly object_id: number
    private readonly column_name: string
    private readonly services: ServiceContainer
    private original_value: string
    private el: HTMLTableCellElement
    events: Nanobus

    constructor(id: number, name: string, el: HTMLTableCellElement, private settings: ColumnTableSettings | null = null) {
        this.object_id = id;
        this.column_name = name;
        this.original_value = el.innerHTML;
        this.el = el;
        this.services = new ServiceContainer();
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

    getSettings(): ColumnTableSettings | null {
        return this.settings;
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

        this.events.emit('setValue', this);

        return this;
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
