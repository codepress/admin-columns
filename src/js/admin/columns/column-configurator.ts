import {AdminColumnsInterface} from "../../admincolumns";
import {EventConstants} from "../../constants";
import {Column} from "./column";
import {initToggle} from "./events/toggle";
import {initIndicator} from "./events/indicator";
import {initTypeSelector} from "./events/type-selector";

declare const AdminColumns: AdminColumnsInterface;

export default class ColumnConfigurator {

    constructor() {
        AdminColumns.events.addListener(EventConstants.SETTINGS.COLUMN.INIT, (column: Column) => {
            initToggle(column);
            initIndicator( column );
            initTypeSelector( column );
        });
    }

    configColumn() {

    }

}

