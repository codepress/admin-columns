import {AdminColumnsInterface} from "../../admincolumns";
import {EventConstants} from "../../constants";
import {Column} from "./column";

declare const AdminColumns: AdminColumnsInterface;

export default class ColumnConfigurator {

    constructor() {
        AdminColumns.events.addListener(EventConstants.SETTINGS.COLUMN.INIT, (column: Column) => {
            initToggle(column);
        });
    }

    configColumn() {

    }

}

const initToggle = (column: Column) => {
    column.getElement().querySelectorAll('[data-toggle="column"]').forEach(el => {
        el.addEventListener('click', e => column.toggle());
    });
}

