/**
 * AC variables. Defined in DOM.
 * @param AC {Object}
 * @param AC.list_screen {String}
 * @param AC.layout {String}
 * @param AC.i81n {String}
 */

import Form from "./admin/columns/form";
import Initiator from "./admin/columns/initiator";
import Modal from "./admin/columns/modal";
import Menu from "./admin/columns/menu";
import Feedback from "./admin/columns/feedback";

/** Events */
import EventToggle from './admin/columns/events/toggle';
import EventRemove from './admin/columns/events/remove';
import EventClone from './admin/columns/events/clone';
import EventRefresh from './admin/columns/events/refresh';
import EventTypeSelector from './admin/columns/events/type-selector';
import EventIndicator from './admin/columns/events/indicator';
import EventLabel from './admin/columns/events/label';
import EventAddons from './admin/columns/events/addons';

/** Settings */
import SettingImageSize from './admin/columns/settings/image-size';
import SettingSubSettingToggle from './admin/columns/settings/sub-setting-toggle';
import SettingDate from './admin/columns/settings/date';
import SettingPro from './admin/columns/settings/pro';
import SettingWidth from './admin/columns/settings/width';

let jQuery = $ = require( 'jquery' );

AC.Column = new Initiator();

jQuery( document ).on( 'AC.Form.loaded', function() {
	/** Register Events **/
	AC.Column.registerEvent( 'toggle', EventToggle );
	AC.Column.registerEvent( 'remove', EventRemove );
	AC.Column.registerEvent( 'clone', EventClone );
	AC.Column.registerEvent( 'refresh', EventRefresh );
	AC.Column.registerEvent( 'type_selector', EventTypeSelector );
	AC.Column.registerEvent( 'indicator', EventIndicator );
	AC.Column.registerEvent( 'label', EventLabel.label );
	AC.Column.registerEvent( 'label_setting', EventLabel.setting );
	AC.Column.registerEvent( 'addons', EventAddons );

	/** Register Settings **/
	AC.Column.registerSetting( 'date', SettingDate );
	AC.Column.registerSetting( 'image_size', SettingImageSize );
	AC.Column.registerSetting( 'pro', SettingPro );
	AC.Column.registerSetting( 'sub_setting_toggle', SettingSubSettingToggle );
	AC.Column.registerSetting( 'width', SettingWidth );
} );

jQuery( document ).ready( function() {
	AC.Form = new Form( '#cpac .ac-columns form' );

	new Modal().init();
	new Menu().init();
	new Feedback( '.sidebox#direct-feedback' );
} );