import Actions from "./actions";
import Cells from "./cells";
import Columns from "./columns";
import Cell from "./cell";
import RowSelection from "./row-selection";
import {getIdFromTableRow, getRowCellByName} from "../helpers/table";
import {AdminColumnsInterface} from "../admincolumns";
import {EventConstants} from "../constants";

declare const AdminColumns: AdminColumnsInterface;

export default class Table {

    private el: HTMLTableElement
    Columns: Columns
    Cells: Cells
    Actions: Actions
    Selection: RowSelection
    _ids: Array<number>

    constructor(el: HTMLTableElement) {
        this.el = el;
        this.Columns = new Columns(el);
        this.Cells = new Cells();
        this.Actions = document.getElementById('ac-table-actions') ? new Actions(document.getElementById('ac-table-actions')) : null;
        this.Selection = new RowSelection(this);
        // TODO make helper function for this (INLINE EDIT NEEDS IT)
        this._ids = [];
    }

    getElement(): HTMLTableElement {
        return this.el;
    }

    init(): void {
        this.initTable();
        this.addCellClasses();


        document.dispatchEvent(new CustomEvent('AC_Table_Ready', {detail: {table: this}}));
        AdminColumns.events.emit(EventConstants.TABLE.READY, {table: this});
    }

    addCellClasses() {
        this.Columns.getColumnNames().forEach((name) => {
            let type = this.Columns.get(name).type;
            let cells = this.Cells.getByName(name);


            cells.forEach((cell: Cell) => {
                cell.getElement().classList.add(type);
            });
        });
    }

    private initTable() {
        let el = this.el.getElementsByTagName('tbody');
        let rows = el[0].querySelectorAll<HTMLTableRowElement>('tr');

        for (let i = 0; i < rows.length; i++) {
            this._ids.push(getIdFromTableRow(rows[i]));
            this.updateRow(rows[i]);
        }
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

    /**
     * @deprecated
     * TODO remove once IE uses the helper
     */
    _getIDFromRow(row: HTMLTableRowElement) {
        return getIdFromTableRow(row);
    }

    /**
     * @deprecated use Helper function instead
     * TODO remove once IE uses the helper
     */
    getRowCellByName(row: HTMLTableRowElement, column_name: string): HTMLTableCellElement {
        return getRowCellByName(row, column_name);
    }

}