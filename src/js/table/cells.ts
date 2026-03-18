import Cell from "./cell";

type cellMap = {
    [key: string]: Cell
}

type rowMap = {
    [key: string]: cellMap
}

export default class Cells {


    constructor( private cells: rowMap = {} ) {
    }

    add(id: number, cell: Cell) {
        const key = id.toString();

        if (!this.cells.hasOwnProperty(key)) {
            this.cells[key] = {};
        }

        this.cells[key][cell.getName()] = cell;
    }

    getByID(id: number): Array<Cell> {
        let result: Array<Cell> = [];

        if (!this.cells.hasOwnProperty(id.toString())) {
            return result;
        }

        let cells: cellMap = this.cells[id.toString()];

        Object.keys(cells).forEach(name => result.push(cells[name]));

        return result;
    }

    getByElement( el: HTMLElement ){
        return this.getAll().find( cell => cell.getElement() === el );
    }

    getAll() {
        let results: Array<Cell> = [];

        Object.keys(this.cells).forEach(id => {
            let cells = this.cells[id];
            Object.keys(cells).forEach(name => results.push(cells[name]))
        });

        return results;
    }

    getByName(name: string) {
        let results: Array<Cell> = [];

        Object.keys(this.cells).forEach(id => {
            let cells = this.cells[id];

            Object.keys(cells).forEach(column_name => {
                if (name === column_name) {
                    results.push(cells[column_name]);
                }
            })
        });

        return results;
    }

    get(id: number, name: string): Cell|null {
        const key = id.toString();

        return this.cells.hasOwnProperty(key) ? this.cells[key][name] : null
    }

}