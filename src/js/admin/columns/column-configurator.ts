import {AdminColumnsInterface} from "../../admincolumns";
import {EventConstants} from "../../constants";
import {Column} from "./column";
import {initToggle} from "./events/toggle";
import {initIndicator} from "./events/indicator";
import {initTypeSelector} from "./events/type-selector";
import {initColumnRefresh} from "./events/refresh";
import {initRemoveColumn} from "./events/remove";
import {initClone} from "./events/clone";
import {initLabel, initLabelSettingEvents, initLabelTooltipsEvent} from "./events/label";
import {initLabelSetting} from "./settings/label";
import {initImageSizeSetting} from "./settings/image-size";
import {initNumberFormatSetting} from "./settings/number-format";
import {initColumnTypeSelectorSetting} from "./settings/type";
import {initWidthSetting} from "./settings/width";
import {initDateSetting} from "./settings/date";
import {initProSetting} from "./settings/pro";
import {initCustomFieldSelector} from "./settings/custom-field";
import {initSubSettings} from "./settings/sub-setting-toggle";

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
            initLabelTooltipsEvent(column);

            initLabelSetting(column);
            initImageSizeSetting(column);
            initNumberFormatSetting(column);
            initColumnTypeSelectorSetting(column);
            initWidthSetting(column);
            initDateSetting(column);
            initProSetting(column);
            initCustomFieldSelector(column);
            initSubSettings(column);
        });
    }

}

