import {AdminColumnsInterface} from "../../admincolumns";
import {EventConstants} from "../../constants";
import {Column} from "./column";
import {initToggle} from "./events/toggle";
import {initIndicator} from "./events/indicator";
import {initTypeSelector} from "./events/type-selector";
import {initColumnRefresh} from "./events/refresh";
import {initRemoveColumn} from "./events/remove";
import {initClone} from "./events/clone";
import {initLabel, initLabelSettingEvents} from "./events/label";
import {initLabelSetting} from "./settings/label";
import {initImageSizeSetting} from "./settings/image-size";
import {initNumberFormatSetting} from "./settings/number-format";
import {initColumnTypeSelectorSetting} from "./settings/type";
import {initWidthSetting} from "./settings/width";
import {initDateSetting} from "./settings/date";
import {initProSetting} from "./settings/pro";

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
            initLabel(column);
            initLabelSettingEvents(column);

            initLabelSetting(column);
            initImageSizeSetting(column);
            initNumberFormatSetting(column);
            initColumnTypeSelectorSetting(column);
            initWidthSetting(column);
            initDateSetting(column);
            initProSetting(column);
        });
    }

}

