import ListScreenInitializeController from "./admin/columns/listscreen-initialize";
/**
 * AC variables. Defined in DOM.
 * @param AdminColumns {Object}
 * @param AC {Object}
 * @param AC.list_screen {String}
 * @param AC.layout {String}
 * @param AC.i81n {String}
 */
import Form from "./admin/columns/form";
import Initiator from "./admin/columns/initiator";
import Modal from "./modules/modal";
import Menu from "./admin/columns/menu";
import Feedback from "./admin/columns/feedback";
import Tooltip from "./modules/tooltips";
/** Events */
import EventToggle from './admin/columns/events/toggle';
import EventRemove from './admin/columns/events/remove';
import EventClone from './admin/columns/events/clone';
import EventRefresh from './admin/columns/events/refresh';
import EventTypeSelector from './admin/columns/events/type-selector';
import EventIndicator from './admin/columns/events/indicator';
import {EventLabel, EventLabelSettings} from './admin/columns/events/label';
import EventAddons from './admin/columns/events/addons';
/** Settings */
import SettingImageSize from './admin/columns/settings/image-size';
import SettingSubSettingToggle from './admin/columns/settings/sub-setting-toggle';
import SettingDate from './admin/columns/settings/date';
import SettingPro from './admin/columns/settings/pro';
import SettingWidth from './admin/columns/settings/width';
import SettingLabel from './admin/columns/settings/label';
import SettingCustomField from './admin/columns/settings/custom-field';
import SettingNumberFormat from './admin/columns/settings/number-format';
import SettingTypeSelector from "./admin/columns/settings/type";
import ScreenOption from "./modules/screen-option";
import {Pointer} from "./modules/ac-pointer";

require('admin-columns-js/polyfill/customevent');
require('admin-columns-js/polyfill/nodelist');


jQuery(document).on('AC_Form_Loaded', function () {

    AdminColumns.Column = new Initiator();
    AdminColumns.Tooltips = new Tooltip();

});



jQuery(document).ready(function () {
    AdminColumns.Form = new Form('#listscreen_settings');
    AdminColumns.Modals.register(new Modal(document.querySelector('#ac-modal-pro')), 'pro');

    new Menu().init();
    new Feedback('.sidebox#direct-feedback');

    Pointer
    ['AC_Column_Change', 'AC_Column_Refresh', 'AC_Column_Refresh'].forEach(hook => {
        jQuery(document).on(hook, () => ac_pointers());
    });

    jQuery(document).on('AC_Column_Created', function (e, column) {
        setTimeout(function () {
            ac_pointers();
        }, 100)
    });

    if (AC.hasOwnProperty('uninitialized_list_screens') && Object.keys(AC.uninitialized_list_screens).length > 0) {
        new ListScreenInitializeController(AC.uninitialized_list_screens);
    }



});