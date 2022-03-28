import Columns from "./columns";
import columns from "./columns";
import AcHtmlElement from "../helpers/html-element";

export default class ScreenOptionsColumns {

    // @ts-ignore
    private columns: Columns

    constructor(columns: columns) {
        this.columns = columns;

        columns.getColumnNames().forEach((column_name: string) => {
            let column = columns.get(column_name);
            let input = ScreenOptionsColumns.getInputByName(column.name);

            if (input && input.parentElement.textContent.length === 0) {
                input.parentElement.appendChild(AcHtmlElement.create('span').addHtml(column.label).element);
            }
        });
    }

    static getInputByName(name: string) {
        let input = document.querySelector(`input[name='${name}-hide']`);

        return input ? input : false;
    }

}