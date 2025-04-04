import Actions from "./actions";
import Cells from "./cells";
import Columns from "./columns";
import Cell from "./cell";
import RowSelection from "./row-selection";
import {getIdFromTableRow} from "../helpers/table";
import {EventConstants} from "../constants";
import AcServices from "../modules/ac-services";
import ServiceContainer from "../modules/service-container";

export type TableEventPayload = {
    table: Table
}

export default class Table {

    private readonly el: HTMLTableElement
    private AcServices: AcServices
    Columns: Columns
    Cells: Cells
    Actions: Actions | null
    Selection: RowSelection
    Services: ServiceContainer

    constructor(el: HTMLTableElement, services: AcServices) {
        this.el = el;
        this.AcServices = services;
        this.Services = new ServiceContainer();
        this.Columns = new Columns(el);
        this.Cells = new Cells();
        this.Selection = new RowSelection(this);

        let actionsElement = document.getElementById('ac-table-actions');
        this.Actions = actionsElement ? new Actions(actionsElement) : null;
    }

    getElement(): HTMLTableElement {
        return this.el;
    }

    getIdsFromTable(): Array<number> {
        let result: Array<number> = [];

        this.el.getElementsByTagName('tbody')[0].querySelectorAll('tr').forEach(row => {
            result.push(getIdFromTableRow(row));
        });

        result = result.filter(id => id !== 0);

        return result;
    }

    init(): this {
        this.initTable();
        this.addCellClasses();

        document.dispatchEvent(new CustomEvent('AC_Table_Ready', {detail: {table: this}}));
        this.AcServices.emitEvent(EventConstants.TABLE.READY, {table: this});

        return this;
    }

    addCellClasses() {
        this.Columns.getColumnNames().forEach((name) => {
            let type = this.Columns.get(name)?.type;
            let cells = this.Cells.getByName(name);

            if (type) {
                cells.forEach((cell: Cell) => {
                    cell.getElement().classList.add(type ?? '');
                });
            }
        });
    }

    private initTable() {
        this.el.getElementsByTagName('tbody')[0].querySelectorAll<HTMLTableRowElement>('tr').forEach(row => {
            this.updateRow(row);
        });
    }

    updateRow(row: HTMLTableRowElement): void {
        let id = getIdFromTableRow(row);

        row.dataset.id = id.toString();
        this.setCellsForRow(row);
    }

    private setCellsForRow(row: HTMLTableRowElement) {
        let id = getIdFromTableRow(row);

        this.Columns.getColumnNames().forEach((name) => {
            let selector = name.replace(/\./g, '\\.');
            let td = row.querySelector<HTMLTableCellElement>("td.column-" + selector);

            if (td) {
                let cell = new Cell(id, name, td);
                this.Cells.add(id, cell);
            }
        });
    }

}