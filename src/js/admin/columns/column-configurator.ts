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
import {initMultiSelectFields} from "./settings/multi-select";
import AcServices from "../../modules/ac-services";
import {initSelectOptions} from "./settings/select-options";

export default class ColumnConfigurator {

    constructor( Services: AcServices ) {
        Services.addListener(EventConstants.SETTINGS.COLUMN.INIT, (column: Column) => {
            initToggle(column);
            initIndicator(column);
            initTypeSelector(column);
            initRemoveColumn(column);
            initClone(column);
            initLabelSettingEvents(column);
            initLabelTooltipsEvent(column);
            initLabel(column);
            initColumnRefresh(column);

            initMultiSelectFields(column);
            initLabelSetting(column);
            initImageSizeSetting(column);
            initNumberFormatSetting(column);
            initColumnTypeSelectorSetting(column);
            initWidthSetting(column);
            initDateSetting(column);
            initProSetting(column);
            initCustomFieldSelector(column);
            initSubSettings(column);
            initSelectOptions(column)
        });
    }

}

