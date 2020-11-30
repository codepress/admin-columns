import {AdminColumnsInterface} from "../../admincolumns";
import {EventConstants} from "../../constants";
import {Column} from "./column";
import {initToggle} from "./events/toggle";
import {initIndicator} from "./events/indicator";
import {initTypeSelector} from "./events/type-selector";
import {initColumnRefresh} from "./events/refresh";
import {initRemoveColumn} from "./events/remove";
import {initClone} from "./events/clone";
import {initLabel, initLabelSetting} from "./events/label";
import LabelSetting from "./settings/label";

declare const AdminColumns: AdminColumnsInterface;

export default class ColumnConfigurator {

    constructor() {
        AdminColumns.events.addListener(EventConstants.SETTINGS.COLUMN.INIT, (column: Column) => {
            initToggle(column);
            initIndicator(column);
            initTypeSelector(column);
            initColumnRefresh(column);
            initRemoveColumn(column);
            initClone(column);
            initLabel( column );
            initLabelSetting( column );

            new LabelSetting( column );

        });
    }

}

