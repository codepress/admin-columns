import Cell from "./cell";

type cellMap = {
    [key: string]: Cell
}

type rowMap = {
    [key: string]: cellMap
}

export default class Cells {

    private elementIndex: WeakMap<HTMLElement, Cell> = new WeakMap();

    constructor( private cells: rowMap = {} ) {
    }

    add(id: number, cell: Cell) {
        const key = id.toString();

        if (!this.cells.hasOwnProperty(key)) {
            this.cells[key] = {};
        }

        this.cells[key][cell.getName()] = cell;
        this.elementIndex.set(cell.getElement(), cell);
    }

    updateElement(cell: Cell, newEl: HTMLElement) {
        this.elementIndex.delete(cell.getElement());
        this.elementIndex.set(newEl, cell);
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

    getByElement( el: HTMLElement ): Cell | undefined {
        return this.elementIndex.get(el);
    }

    getAll() {
        let results: Array<Cell> = [];

        Object.keys(this.cells).forEach(id => {
            let cells = this.cells[id];
            Object.keys(cells).forEach(name => results.push(cells[name]))
        });

        return results;
    }

    getByName(name: string): Array<Cell> {
        let results: Array<Cell> = [];

        Object.keys(this.cells).forEach(id => {
            const cell = this.cells[id][name];
            if (cell !== undefined) {
                results.push(cell);
            }
        });

        return results;
    }

    get(id: number, name: string): Cell|null {
        const key = id.toString();

        return this.cells.hasOwnProperty(key) ? this.cells[key][name] : null
    }

}