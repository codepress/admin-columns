import columns from "./columns";
import AcHtmlElement from "../helpers/html-element";

export default class ScreenOptionsColumns {

    constructor(private columns: columns) {

        this.columns.getColumnNames().forEach((column_name: string) => {
            let column = columns.get(column_name);

            if (column) {
                let input = ScreenOptionsColumns.getInputByName(column.name);

                if (input && input?.parentElement?.textContent?.length === 0) {
                    input.parentElement.appendChild(AcHtmlElement.create('span').addHtml(column.label).getElement());
                }
            }

        });
    }

    static getInputByName(name: string): HTMLInputElement | null {
        return document.querySelector(`input[name='${name}-hide']`);
    }

}