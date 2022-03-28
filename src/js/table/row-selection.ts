import Table from "./table";
import Cell from "./cell";

export default class RowSelection {

    Table: Table

    constructor(table: Table) {
        this.Table = table;
    }

    getIDs(): Array<number> {
        let ids: Array<number> = [];
        let checked = this.Table.getElement().querySelectorAll<HTMLInputElement>('tbody th.check-column input[type=checkbox]:checked');

        if (checked.length === 0) {
            return ids;
        }

        for (let i = 0; i < checked.length; i++) {
            ids.push(parseInt(checked[i].value));
        }

        return ids;
    }

    /**
     * Get selected cells for specific column
     */
    getSelectedCells(name: string): Array<Cell> {
        let ids = this.getIDs();

        if (ids.length === 0) {
            return [];
        }

        let cells: Array<Cell> = [];

        ids.forEach((id) => {
            let cell = this.Table.Cells.get(id, name);

            if (cell) {
                cells.push(cell);
            }
        });

        return cells;
    }

    getCount(): number {
        return this.getIDs().length;
    }

    isAllSelected(): boolean {
        return !!this.Table.getElement().querySelector('thead #cb input:checked');
    }

}