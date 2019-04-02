/**
 * AC variables. Defined in DOM.
 * @param AdminColumns {Object}
 * @param AC {Object}
 * @param AC.list_screen {String}
 * @param AC.layout {String}
 * @param AC.i81n {String}
 */
import Form from "./admin/columns/form";
import Modals from "./modules/modals";
import Initiator from "./admin/columns/initiator";
import Modal from "./modules/modal";
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
import SettingLabel from './admin/columns/settings/label';
import SettingCustomField from './admin/columns/settings/meta-field';

let jQuery = $ = require( 'jquery' );

AC.Column = new Initiator(); // Todo remove from
AdminColumns.Column = AC.Column;

jQuery( document ).on( 'AC_Form_Loaded', function() {

	/** Register Events **/
	AdminColumns.Column
		.registerEvent( 'toggle', EventToggle )
		.registerEvent( 'remove', EventRemove )
		.registerEvent( 'clone', EventClone )
		.registerEvent( 'refresh', EventRefresh )
		.registerEvent( 'type_selector', EventTypeSelector )
		.registerEvent( 'indicator', EventIndicator )
		.registerEvent( 'label', EventLabel.label )
		.registerEvent( 'label_setting', EventLabel.setting )
		.registerEvent( 'addons', EventAddons )

		/** Register Settings **/
		.registerSetting( 'date', SettingDate )
		.registerSetting( 'image_size', SettingImageSize )
		.registerSetting( 'pro', SettingPro )
		.registerSetting( 'sub_setting_toggle', SettingSubSettingToggle )
		.registerSetting( 'width', SettingWidth )
		.registerSetting( 'customfield', SettingCustomField )
		.registerSetting( 'label', SettingLabel );
} );

jQuery( document ).ready( function() {
	AC.Form = new Form( '#cpac .ac-columns form' );
	Modals.init().register( new Modal( document.querySelector( '#ac-modal-pro' ) ), 'pro' );

	new Menu().init();
	new Feedback( '.sidebox#direct-feedback' );
} );