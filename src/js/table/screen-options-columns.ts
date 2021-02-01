import Columns from "./columns";
import columns from "./columns";

export default class ScreenOptionsColumns {

    private columns: Columns

    constructor(columns: columns) {
        this.columns = columns;

        columns.getColumnNames().forEach((column_name: string) => {
            let column = columns.get(column_name);
            let input = ScreenOptionsColumns.getInputByName(column.name);

            if (input && input.parentElement.textContent.length === 0) {
                let label = document.createElement('span');
                label.innerHTML = column.label;
                input.parentElement.appendChild(label);
            }
        });
    }

    static getInputByName(name: string) {
        let input = document.querySelector(`input[name='${name}-hide']`);

        return input ? input : false;
    }

}