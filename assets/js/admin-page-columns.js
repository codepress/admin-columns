/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/admin-page-columns.js":
/*!**********************************!*\
  !*** ./js/admin-page-columns.js ***!
  \**********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _admin_columns_listscreen_initialize__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./admin/columns/listscreen-initialize */ "./js/admin/columns/listscreen-initialize.js");
/* harmony import */ var _admin_columns_form__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./admin/columns/form */ "./js/admin/columns/form.js");
/* harmony import */ var _admin_columns_initiator__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./admin/columns/initiator */ "./js/admin/columns/initiator.js");
/* harmony import */ var _modules_modal__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modules/modal */ "./js/modules/modal.ts");
/* harmony import */ var _admin_columns_menu__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./admin/columns/menu */ "./js/admin/columns/menu.js");
/* harmony import */ var _admin_columns_feedback__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./admin/columns/feedback */ "./js/admin/columns/feedback.js");
/* harmony import */ var _modules_tooltips__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./modules/tooltips */ "./js/modules/tooltips.ts");
/* harmony import */ var _admin_columns_events_toggle__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./admin/columns/events/toggle */ "./js/admin/columns/events/toggle.js");
/* harmony import */ var _admin_columns_events_remove__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./admin/columns/events/remove */ "./js/admin/columns/events/remove.js");
/* harmony import */ var _admin_columns_events_clone__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./admin/columns/events/clone */ "./js/admin/columns/events/clone.js");
/* harmony import */ var _admin_columns_events_refresh__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./admin/columns/events/refresh */ "./js/admin/columns/events/refresh.js");
/* harmony import */ var _admin_columns_events_type_selector__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./admin/columns/events/type-selector */ "./js/admin/columns/events/type-selector.js");
/* harmony import */ var _admin_columns_events_indicator__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./admin/columns/events/indicator */ "./js/admin/columns/events/indicator.js");
/* harmony import */ var _admin_columns_events_label__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./admin/columns/events/label */ "./js/admin/columns/events/label.js");
/* harmony import */ var _admin_columns_events_addons__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./admin/columns/events/addons */ "./js/admin/columns/events/addons.js");
/* harmony import */ var _admin_columns_settings_image_size__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./admin/columns/settings/image-size */ "./js/admin/columns/settings/image-size.js");
/* harmony import */ var _admin_columns_settings_sub_setting_toggle__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./admin/columns/settings/sub-setting-toggle */ "./js/admin/columns/settings/sub-setting-toggle.js");
/* harmony import */ var _admin_columns_settings_date__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./admin/columns/settings/date */ "./js/admin/columns/settings/date.js");
/* harmony import */ var _admin_columns_settings_pro__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./admin/columns/settings/pro */ "./js/admin/columns/settings/pro.js");
/* harmony import */ var _admin_columns_settings_width__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./admin/columns/settings/width */ "./js/admin/columns/settings/width.js");
/* harmony import */ var _admin_columns_settings_label__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./admin/columns/settings/label */ "./js/admin/columns/settings/label.js");
/* harmony import */ var _admin_columns_settings_custom_field__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ./admin/columns/settings/custom-field */ "./js/admin/columns/settings/custom-field.js");
/* harmony import */ var _admin_columns_settings_number_format__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ./admin/columns/settings/number-format */ "./js/admin/columns/settings/number-format.js");
/* harmony import */ var _admin_columns_settings_type__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ./admin/columns/settings/type */ "./js/admin/columns/settings/type.js");
/* harmony import */ var _modules_screen_option__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ./modules/screen-option */ "./js/modules/screen-option.ts");

/**
 * AC variables. Defined in DOM.
 * @param AdminColumns {Object}
 * @param AC {Object}
 * @param AC.list_screen {String}
 * @param AC.layout {String}
 * @param AC.i81n {String}
 */







/** Events */









/** Settings */












__webpack_require__(/*! admin-columns-js/polyfill/customevent */ "./node_modules/admin-columns-js/polyfill/customevent.js");

__webpack_require__(/*! admin-columns-js/polyfill/nodelist */ "./node_modules/admin-columns-js/polyfill/nodelist.js");

AC.Column = new _admin_columns_initiator__WEBPACK_IMPORTED_MODULE_2__["default"](); // Todo remove from

jQuery(document).on('AC_Form_Loaded', function () {
  AdminColumns.Column = AC.Column;
  AdminColumns.Tooltips = new _modules_tooltips__WEBPACK_IMPORTED_MODULE_6__["default"]();
  /** Register Events **/

  AdminColumns.Column.registerEvent('toggle', _admin_columns_events_toggle__WEBPACK_IMPORTED_MODULE_7__["default"]).registerEvent('remove', _admin_columns_events_remove__WEBPACK_IMPORTED_MODULE_8__["default"]).registerEvent('clone', _admin_columns_events_clone__WEBPACK_IMPORTED_MODULE_9__["default"]).registerEvent('refresh', _admin_columns_events_refresh__WEBPACK_IMPORTED_MODULE_10__["default"]).registerEvent('type_selector', _admin_columns_events_type_selector__WEBPACK_IMPORTED_MODULE_11__["default"]).registerEvent('indicator', _admin_columns_events_indicator__WEBPACK_IMPORTED_MODULE_12__["default"]).registerEvent('label', _admin_columns_events_label__WEBPACK_IMPORTED_MODULE_13__["EventLabel"]).registerEvent('label_setting', _admin_columns_events_label__WEBPACK_IMPORTED_MODULE_13__["EventLabelSettings"]).registerEvent('addons', _admin_columns_events_addons__WEBPACK_IMPORTED_MODULE_14__["default"])
  /** Register Settings **/
  .registerSetting('date', _admin_columns_settings_date__WEBPACK_IMPORTED_MODULE_17__["default"]).registerSetting('image_size', _admin_columns_settings_image_size__WEBPACK_IMPORTED_MODULE_15__["default"]).registerSetting('pro', _admin_columns_settings_pro__WEBPACK_IMPORTED_MODULE_18__["default"]).registerSetting('sub_setting_toggle', _admin_columns_settings_sub_setting_toggle__WEBPACK_IMPORTED_MODULE_16__["default"]).registerSetting('width', _admin_columns_settings_width__WEBPACK_IMPORTED_MODULE_19__["default"]).registerSetting('customfield', _admin_columns_settings_custom_field__WEBPACK_IMPORTED_MODULE_21__["default"]).registerSetting('number_format', _admin_columns_settings_number_format__WEBPACK_IMPORTED_MODULE_22__["default"]).registerSetting('type_selector', _admin_columns_settings_type__WEBPACK_IMPORTED_MODULE_23__["default"]).registerSetting('label', _admin_columns_settings_label__WEBPACK_IMPORTED_MODULE_20__["default"]);
});
jQuery(document).ready(function () {
  AdminColumns.Form = new _admin_columns_form__WEBPACK_IMPORTED_MODULE_1__["default"]('#listscreen_settings');
  AdminColumns.Modals.register(new _modules_modal__WEBPACK_IMPORTED_MODULE_3__["default"](document.querySelector('#ac-modal-pro')), 'pro');
  new _admin_columns_menu__WEBPACK_IMPORTED_MODULE_4__["default"]().init();
  new _admin_columns_feedback__WEBPACK_IMPORTED_MODULE_5__["default"]('.sidebox#direct-feedback');
  ['AC_Column_Change', 'AC_Column_Refresh', 'AC_Column_Refresh'].forEach(hook => {
    jQuery(document).on(hook, () => ac_pointers());
  });
  jQuery(document).on('AC_Column_Created', function (e, column) {
    setTimeout(function () {
      ac_pointers();
    }, 100);
  });

  if (AC.hasOwnProperty('uninitialized_list_screens') && Object.keys(AC.uninitialized_list_screens).length > 0) {
    new _admin_columns_listscreen_initialize__WEBPACK_IMPORTED_MODULE_0__["default"](AC.uninitialized_list_screens);
  }

  AdminColumns.ScreenOptions = {};
  document.querySelectorAll('[data-ac-screen-option]').forEach(el => {
    let name = el.dataset.acScreenOption;
    AdminColumns.ScreenOptions[name] = new _modules_screen_option__WEBPACK_IMPORTED_MODULE_24__["default"](el, name);
  });
  let showColumnId = document.querySelector('[data-ac-screen-option="show_column_id"] input');

  if (showColumnId) {
    showColumnId.addEventListener('change', () => {
      showColumnId.checked ? document.querySelector('.ac-boxes').classList.add('show-column-id') : document.querySelector('.ac-boxes').classList.remove('show-column-id');
    });
  }

  let showColumnType = document.querySelector('[data-ac-screen-option="show_column_type"] input');

  if (showColumnType) {
    showColumnType.addEventListener('change', () => {
      showColumnType.checked ? document.querySelector('.ac-boxes').classList.add('show-column-type') : document.querySelector('.ac-boxes').classList.remove('show-column-type');
    });
  }

  let showListScreenId = document.querySelector('[data-ac-screen-option="show_list_screen_id"] input');

  if (showListScreenId) {
    showListScreenId.addEventListener('change', () => {
      showListScreenId.checked ? document.querySelector('.ac-admin').classList.add('show-list-screen-id') : document.querySelector('.ac-admin').classList.remove('show-list-screen-id');
    });
  }

  let showListScreenType = document.querySelector('[data-ac-screen-option="show_list_screen_type"] input');

  if (showListScreenType) {
    showListScreenType.addEventListener('change', () => {
      showListScreenType.checked ? document.querySelector('.ac-admin').classList.add('show-list-screen-type') : document.querySelector('.ac-admin').classList.remove('show-list-screen-type');
    });
  }
});

/***/ }),

/***/ "./js/admin/columns/column.js":
/*!************************************!*\
  !*** ./js/admin/columns/column.js ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
const STATES = {
  CLOSED: 'closed',
  OPEN: 'open'
};

class Column {
  constructor($el) {
    this.$el = $el;
    this.el = $el[0];
    this.settings = [];
    this.state = STATES.CLOSED;
    this._type = this.$el.data('type');
  }

  get name() {
    return this.$el.data('column-name');
  }

  set name(name) {
    this.$el.data('column-name', name);
  }

  get type() {
    return this._type;
  }

  set type(type) {
    this.$el.data('type', type);
  }

  isOriginal() {
    return 1 === this.$el.data('original');
  }

  isDisabled() {
    return this.$el.hasClass('disabled');
  }

  disable() {
    this.$el.addClass('disabled');
    return this;
  }

  enable() {
    this.$el.removeClass('disabled');
    return this;
  }

  initNewInstance() {
    let temp_column_name = '_new_column_' + AC.Column.getNewIncementalName();
    let original_column_name = this.name;
    this.$el.find('input, select, label').each(function (i, v) {
      let $input = jQuery(v); // name attributes

      if ($input.attr('name')) {
        $input.attr('name', $input.attr('name').replace(`columns[${original_column_name}]`, `columns[${temp_column_name}]`));
      } // id attributes


      if ($input.attr('id')) {
        $input.attr('id', $input.attr('id').replace(`-${original_column_name}-`, `-${temp_column_name}-`));
      }
    });
    this.name = temp_column_name;
    AC.incremental_column_name++;
    return this;
  }
  /**
   *
   * @returns {Column}
   */


  bindEvents() {
    let column = this;
    column.$el.data('column', column);
    Object.keys(AC.Column.events).forEach(function (key) {
      if (!column.isBound(key)) {
        AC.Column.events[key](column);
        column.bind(key);
      }
    });
    this.bindSettings();
    jQuery(document).trigger('AC_Column_InitSettings', [column]);
    return this;
  }

  bindSettings() {
    let column = this;
    Object.keys(AC.Column.settings).forEach(function (key) {
      if (!column.isBound(key)) {
        AC.Column.settings[key](column);
        column.bind(key);
      }
    });
  }
  /**
   *
   * @param key
   * @returns {bool}
   */


  isBound(key) {
    return this.$el.data(key);
  }

  bind(key) {
    this.$el.data(key, true);
  }

  destroy() {
    this.$el.remove();
  }

  remove(duration = 350) {
    let self = this;
    this.$el.addClass('deleting').animate({
      opacity: 0,
      height: 0
    }, duration, function () {
      self.destroy();
    });
  }

  getState() {
    return this.state;
  }

  toggle(duration = 150) {
    if (this.getState() === STATES.OPEN) {
      this.close(duration);
    } else {
      this.open(duration);
    }
  }

  close(duration = 0) {
    this.$el.removeClass('opened').find('.ac-column-body').slideUp(duration);
    this.state = STATES.CLOSED;
  }

  open(duration = 0) {
    this.$el.addClass('opened').find('.ac-column-body').slideDown(duration);
    this.state = STATES.OPEN;
  }

  showMessage(message) {
    this.$el.find('.ac-column-setting--type .msg').html(message).show();
  }

  switchToType(type) {
    let self = this;
    return jQuery.ajax({
      url: ajaxurl,
      method: 'post',
      dataType: 'json',
      data: {
        action: 'ac-columns',
        id: 'select',
        type: type,
        data: AdminColumns.Form.serialize(),
        current_original_columns: AdminColumns.Form.originalColumns(),
        original_columns: AC.original_columns,
        _ajax_nonce: AC._ajax_nonce
      },
      success: function (response) {
        if (true === response.success) {
          let column = jQuery(response.data);
          self.$el.replaceWith(column);
          self.$el = column;
          self.el = column[0];
          self._type = type;
          self.initNewInstance();
          self.bindEvents();
          self.open();
          jQuery(document).trigger('AC_Column_Change', [self]);
        } else {
          self.showMessage(response.data.error);
        }
      }
    });
  }

  refresh() {
    let self = this;
    let data = this.$el.find(':input').serializeArray();
    let request_data = {
      action: 'ac-columns',
      id: 'refresh',
      _ajax_nonce: AC._ajax_nonce,
      data: AdminColumns.Form.serialize(),
      column_name: this.name,
      original_columns: AC.original_columns
    };
    jQuery.each(request_data, function (name, value) {
      data.push({
        name: name,
        value: value
      });
    });
    return jQuery.ajax({
      type: 'post',
      url: ajaxurl,
      data: data,
      success: function (response) {
        if (true === response.success) {
          let column = jQuery(response.data);
          self.$el.replaceWith(column);
          self.$el = column;
          self.el = column[0];
          self.bindEvents();

          if (self.getState() === STATES.OPEN) {
            self.open();
          }

          jQuery(document).trigger('AC_Column_Refresh', [self]);
        }
      }
    });
  }
  /**
   * @returns {Column}
   */


  create() {
    this.initNewInstance();
    this.bindEvents();
    jQuery(document).trigger('AC_Column_Created', [this]);
    return this;
  }
  /**
   * @returns {Column}
   */


  clone() {
    let $clone = this.$el.clone();
    $clone.data('column-name', this.$el.data('column-name'));
    let clone = new Column($clone);
    clone.initNewInstance();
    clone.bindEvents();
    return clone;
  }

}

/* harmony default export */ __webpack_exports__["default"] = (Column);

/***/ }),

/***/ "./js/admin/columns/events/addons.js":
/*!*******************************************!*\
  !*** ./js/admin/columns/events/addons.js ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*
 * Optional Radio Click events
 * TODO: Is not used anymore?
 */
let addons = function (column) {
  let $ = jQuery;
  let $column = column.$el;
  let inputs = $column.find('[data-trigger] label');
  inputs.on('click', function () {
    let id = $(this).closest('td.input').data('trigger');
    let state = $('input', this).val(); // Toggle indicator icon

    let label = $column.find('[data-indicator-id="' + id + '"]').removeClass('on');

    if ('on' === state) {
      label.addClass('on');
    } // Toggle additional options


    let additional = $column.find('[data-handle="' + id + '"]').addClass('hide');

    if ('on' === state) {
      additional.removeClass('hide');
    }
  }); // On load

  $column.find('[data-trigger]').each(function () {
    let trigger = $(this).data('trigger'); // Hide additional column settings

    let additional = $column.find('[data-handle="' + trigger + '"]').addClass('hide');

    if ('on' === $('input:checked', this).val()) {
      additional.removeClass('hide');
    }
  });
};

/* harmony default export */ __webpack_exports__["default"] = (addons);

/***/ }),

/***/ "./js/admin/columns/events/clone.js":
/*!******************************************!*\
  !*** ./js/admin/columns/events/clone.js ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*
* Column: bind clone events
*
* @since 2.0
*/
let clone = function (column) {
  column.$el.find('.clone-button').click(function (e) {
    e.preventDefault();

    if (column.isOriginal()) {
      return;
    }

    AdminColumns.Form.cloneColumn(column.$el);
  });
};

/* harmony default export */ __webpack_exports__["default"] = (clone);

/***/ }),

/***/ "./js/admin/columns/events/indicator.js":
/*!**********************************************!*\
  !*** ./js/admin/columns/events/indicator.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
let indicator = function (column) {
  let $column = column.$el;
  $column.find('.ac-column-header [data-indicator-toggle]').each(function () {
    let $ = jQuery;
    let $indicator = $(this);
    let setting = $(this).data('setting');
    let $setting = $column.find('.ac-column-setting[data-setting=' + setting + ']');
    let $input = $setting.find('.col-input:first .ac-setting-input:first input[type=radio]');
    $indicator.unbind('click').on('click', function (e) {
      e.preventDefault();

      if ($column.hasClass('disabled')) {
        return;
      }

      $indicator.toggleClass('on');

      if ($(this).hasClass('on')) {
        $input.filter('[value=on]').prop('checked', true).trigger('click').trigger('change');
      } else {
        $input.filter('[value=off]').prop('checked', true).trigger('click').trigger('change');
      }
    });
    $input.on('change', function () {
      if ($column.hasClass('disabled')) {
        return;
      }

      let value = $input.filter(':checked').val();

      if ('on' === value) {
        $indicator.addClass('on');
      } else {
        $indicator.removeClass('on');
      }
    });
  });
};

/* harmony default export */ __webpack_exports__["default"] = (indicator);

/***/ }),

/***/ "./js/admin/columns/events/label.js":
/*!******************************************!*\
  !*** ./js/admin/columns/events/label.js ***!
  \******************************************/
/*! exports provided: EventLabel, EventLabelSettings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "EventLabel", function() { return EventLabel; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "EventLabelSettings", function() { return EventLabelSettings; });
let EventLabel = function (column) {
  let $column = column.$el;
  /**
   * Populates the main Label with the selected label from the dropdown,
   */

  $column.find('select[data-label="update"]').change(function () {
    let $label = $column.find('input.ac-setting-input_label');
    let field_label = jQuery(this).find('option:selected').text(); // Set new label

    $label.val(field_label);
    $label.trigger('change');
  });
  /** When an label contains an icon or span, the displayed label can appear empty. In this case we show the "type" label. */

  setTimeout(function () {
    let column_label = $column.find('.column_label .toggle');

    if (jQuery.trim(column_label.html()) && column_label.width() < 1) {
      column_label.html($column.find('.column_type .inner').html());
    }
  }, 50);
};

let EventLabelSettings = function (column) {
  let $column = column.$el;
  /** change label */

  $column.find('.ac-column-setting--label input').bind('keyup change', function () {
    let value = jQuery(this).val();
    jQuery(this).closest('.ac-column').find('td.column_label .inner > a.toggle').html(value);
  }).trigger('change');
  /** tooltip */

  $column.find('.ac-column-body .col-label .label').hover(function () {
    jQuery(this).parents('.col-label').find('div.tooltip').show();
  }, function () {
    jQuery(this).parents('.col-label').find('div.tooltip').hide();
  });
};



/***/ }),

/***/ "./js/admin/columns/events/refresh.js":
/*!********************************************!*\
  !*** ./js/admin/columns/events/refresh.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
let refresh = function (column) {
  let $ = jQuery;
  column.$el.find('[data-refresh="column"]').on('change', function () {
    // Allow plugins to hook into this event
    $(document).trigger('AC.column.prerefresh', column.$el);
    column.$el.addClass('loading');
    setTimeout(function () {
      column.refresh().always(function () {
        column.$el.removeClass('loading');
      }).fail(() => {
        column.showMessage(AC.i18n.errors.loading_column);
      });
    }, 200);
  });
};

/* harmony default export */ __webpack_exports__["default"] = (refresh);

/***/ }),

/***/ "./js/admin/columns/events/remove.js":
/*!*******************************************!*\
  !*** ./js/admin/columns/events/remove.js ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*
 * Column: bind remove events
 *
 * @since 2.0
 */
let remove = function (column) {
  column.$el.find('.remove-button').click(function (e) {
    e.preventDefault();
    AdminColumns.Form.removeColumn(column.name);
  });
};

/* harmony default export */ __webpack_exports__["default"] = (remove);

/***/ }),

/***/ "./js/admin/columns/events/toggle.js":
/*!*******************************************!*\
  !*** ./js/admin/columns/events/toggle.js ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*
 * Column: bind toggle events
 *
 * For performance we bind all other events after the click event.
 *
 * @since 2.0
 */
let toggle = function (column) {
  column.$el.find('[data-toggle="column"]').click(function (e) {
    e.preventDefault();
    column.toggle();
  }).css('cursor', 'pointer');
};

/* harmony default export */ __webpack_exports__["default"] = (toggle);

/***/ }),

/***/ "./js/admin/columns/events/type-selector.js":
/*!**************************************************!*\
  !*** ./js/admin/columns/events/type-selector.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
let selector = function (column) {
  let $ = jQuery;
  column.$el.find('select.ac-setting-input_type').change(function () {
    column.$el.addClass('loading');
    column.switchToType($(this).val()).always(function () {
      column.$el.removeClass('loading');
      AdminColumns.Form.reindexColumns();
    }).fail(() => {
      column.showMessage(AC.i18n.errors.loading_column);
    });
  });
};

/* harmony default export */ __webpack_exports__["default"] = (selector);

/***/ }),

/***/ "./js/admin/columns/feedback.js":
/*!**************************************!*\
  !*** ./js/admin/columns/feedback.js ***!
  \**************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
class Feedback {
  constructor($el) {
    this.$el = jQuery($el);
    this.init();
  }

  init() {
    let $box = this.$el;
    $box.find('#feedback-choice a.no').click(function (e) {
      e.preventDefault();
      $box.find('#feedback-choice').slideUp();
      $box.find('#feedback-support').slideDown();
    });
    $box.find('#feedback-choice a.yes').click(function (e) {
      e.preventDefault();
      $box.find('#feedback-choice').slideUp();
      $box.find('#feedback-rate').slideDown();
    });
  }

}

/* harmony default export */ __webpack_exports__["default"] = (Feedback);

/***/ }),

/***/ "./js/admin/columns/form.js":
/*!**********************************!*\
  !*** ./js/admin/columns/form.js ***!
  \**********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _column__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./column */ "./js/admin/columns/column.js");


class Form {
  constructor(el) {
    this.form = el;
    this.$form = jQuery(el);
    this.$column_container = this.$form.find('.ac-columns');
    this.$container = jQuery('#cpac .ac-admin');
    this.columns = {};
    this._validators = [];
    jQuery(document).trigger('AC_Form_Loaded');
    this.init();
  }

  init() {
    this.initColumns();
    this.bindFormEvents();
    this.bindOrdering();

    if (this.$form.hasClass('-disabled')) {
      this.disableFields();
    }

    jQuery(document).trigger('AC_Form_Ready', this);
  }

  bindOrdering() {
    if (this.$form.hasClass('ui-sortable')) {
      this.$form.sortable('refresh');
    } else {
      this.$form.sortable({
        items: '.ac-column',
        handle: '.column_sort'
      });
    }
  }

  originalColumns() {
    let self = this;
    let columns = [];
    Object.keys(self.columns).forEach(function (key) {
      let column = self.columns[key];

      if (column.isOriginal()) {
        columns.push(column.type);
      }
    });
    return columns;
  }

  validateForm() {
    let valid = true;

    this._validators.forEach(validator => {
      valid = validator.call(this, this);
    });

    return valid;
  }

  addValidator(validator) {
    this._validators.push(validator);
  }

  bindFormEvents() {
    let self = this;
    let $buttons = jQuery('.sidebox a.submit, .column-footer a.submit');
    $buttons.on('click', function () {
      if (!self.validateForm()) {
        return;
      }

      $buttons.attr('disabled', 'disabled');
      self.$container.addClass('saving');
      self.submitForm().always(function () {
        $buttons.removeAttr('disabled', 'disabled');
        self.$container.removeClass('saving');
      });
    });
    self.$container.find('.add_column').on('click', function () {
      self.addColumn();
    });
    let $boxes = jQuery('#cpac .ac-boxes');

    if ($boxes.hasClass('disabled')) {
      $boxes.find('.ac-column').each(function (i, col) {
        jQuery(col).data('column').disable();
        jQuery(col).find('input, select').prop('disabled', true);
      });
    }

    jQuery('a[data-clear-columns]').on('click', function () {
      self.resetColumns();
    });
  }

  initColumns() {
    let self = this;
    self.columns = {};
    this.$form.find('.ac-column').each(function () {
      let $el = jQuery(this);
      let column = new _column__WEBPACK_IMPORTED_MODULE_0__["default"]($el);
      column.bindEvents();
      $el.data('column', column);
      self.columns[column.name] = column;
    });
  }

  reindexColumns() {
    let self = this;
    self.columns = {};
    this.$form.find('.ac-column').each(function () {
      let column = jQuery(this).data('column');
      self.columns[column.name] = column;
    });
  }

  resetColumns() {
    Object.keys(this.columns).forEach(key => {
      let column = this.columns[key];
      column.destroy();
    });
  }

  serialize() {
    return this.$form.serialize();
  }

  disableFields() {
    let form = document.querySelector(this.form);

    if (!form) {
      return;
    }

    let elements = form.elements;

    for (let i = 0; i < elements.length; i++) {
      elements[i].readOnly = true;
      elements[i].setAttribute('disabled', true);
    }
  }

  enableFields() {}

  submitForm() {
    let self = this;
    let xhr = jQuery.post(ajaxurl, {
      action: 'ac-columns',
      id: 'save',
      _ajax_nonce: AC._ajax_nonce,
      data: this.serialize()
    }, function (response) {
      if (response) {
        if (response.success) {
          self.showMessage(response.data, 'updated');
          self.$container.addClass('stored');
        } // Error message
        else if (response.data) {
            self.showMessage(response.data.message, 'notice notice-warning');
          }
      }
    }, 'json'); // No JSON

    xhr.fail(function (error) {
      self.showMessage(AC.i18n.errors.save_settings, 'notice notice-warning');
    });
    jQuery(document).trigger('AC_Form_AfterUpdate', [self.$container]);
    return xhr;
  }

  showMessage(message, attr_class = 'updated') {
    let $msg = jQuery('<div class="ac-message hidden ' + attr_class + '"><p>' + message + '</p></div>');
    this.$container.find('.ac-message').stop().remove();
    this.$container.find('.ac-admin__main').prepend($msg);
    $msg.slideDown();
  }

  cloneColumn($el) {
    return this._addColumnToForm(new _column__WEBPACK_IMPORTED_MODULE_0__["default"]($el).clone(), $el.hasClass('opened'), $el);
  }

  addColumn() {
    let $clone = jQuery('#add-new-column-template').find('.ac-column').clone();
    let column = new _column__WEBPACK_IMPORTED_MODULE_0__["default"]($clone).create();
    return this._addColumnToForm(column);
  }

  removeColumn(name) {
    if (this.columns[name]) {
      this.columns[name].remove();
      delete this.columns[name];
    }
  }

  getListScreen() {
    return this.$form.find('input[name="list_screen"]').val();
  }

  getListScreenID() {
    return this.$form.find('input[name="list_screen_id"]').val();
  }

  getTitle() {
    return this.$form.find('input[name="title"]').val();
  }

  getColumnSettings() {
    return this.$form.find('[name^="columns["]').serialize();
  }

  _addColumnToForm(column, open = true, $after = null) {
    this.columns[column.name] = column;

    if ($after) {
      column.$el.insertAfter($after);
    } else {
      this.$column_container.append(column.$el);
    }

    if (open) {
      column.open();
    }

    column.$el.hide().slideDown();
    jQuery(document).trigger('AC_Column_Added', [column]);

    if (!isInViewport(column.$el)) {
      jQuery('html, body').animate({
        scrollTop: column.$el.offset().top - 58
      }, 300);
    }

    return column;
  }

}

/* harmony default export */ __webpack_exports__["default"] = (Form);

let isInViewport = $el => {
  var elementTop = $el.offset().top;
  var elementBottom = elementTop + $el.outerHeight();
  var viewportTop = jQuery(window).scrollTop();
  var viewportBottom = viewportTop + jQuery(window).height();
  return elementBottom > viewportTop && elementTop < viewportBottom;
};

/***/ }),

/***/ "./js/admin/columns/initiator.js":
/*!***************************************!*\
  !*** ./js/admin/columns/initiator.js ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Initiator; });
class Initiator {
  constructor() {
    this.events = {};
    this.settings = {};
    this.incremental_name = 0;
  }

  registerSetting(k, setting) {
    let key = 's_' + k;

    if (this.settings[key]) {
      console.error('Setting key already exists: ' + k);
    }

    this.settings[key] = setting;
    return this;
  }

  registerEvent(k, event) {
    let key = 'e_' + k;

    if (this.settings[key]) {
      console.error('Event key already exists: ' + key);
    }

    this.events[key] = event;
    return this;
  }

  getNewIncementalName() {
    let oldName = this.incremental_name;
    this.incremental_name++;
    return oldName;
  }

}

/***/ }),

/***/ "./js/admin/columns/listscreen-initialize.js":
/*!***************************************************!*\
  !*** ./js/admin/columns/listscreen-initialize.js ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ListScreenInitializeController; });
var nanobus = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");

class ListscreenInitialize {
  constructor(list_screens) {
    this.list_screens = list_screens;
    this.processed = [];
    this.errors = [];
    this.success = [];
    this.events = nanobus();
  }

  doAjaxCall(list_screen) {
    return jQuery.ajax({
      url: list_screen.screen_link,
      method: 'get'
    });
  }

  run() {
    Object.keys(this.list_screens).forEach(key => {
      this.processListScreen(this.list_screens[key]);
    });
  }

  onFinish() {
    if (this.success.length === Object.keys(this.list_screens).length) {
      this.events.emit('success');
    }

    if (this.errors.length > 0) {
      this.events.emit('error');
    }
  }

  checkFinish() {
    if (this.processed.length === Object.keys(this.list_screens).length) {
      this.onFinish();
    }
  }

  processListScreen(list_screen) {
    return this.doAjaxCall(list_screen).done(r => {
      if (r === 'ac_success') {
        this.success.push(list_screen);
      } else {
        this.errors.push(list_screen);
      }
    }).fail(() => {
      this.errors.push(list_screen);
    }).always(() => {
      this.processed.push(list_screen);
      this.checkFinish();
    });
  }

}

class ListScreenInitializeController {
  constructor(list_screens) {
    this.list_screens = list_screens;
    this.run();
  }

  run() {
    if (Object.keys(this.list_screens).length > 0) {
      // Only load main screen first if unitialized, otherwise do the rest in background
      if (this.list_screens.hasOwnProperty(AC.list_screen)) {
        let main_initializer = new ListscreenInitialize({
          [AC.list_screen]: this.list_screens[AC.list_screen]
        });
        main_initializer.run();
        main_initializer.events.on('error', () => {
          document.querySelectorAll('.ac-loading-msg-wrapper').forEach(el => el.remove());
          document.querySelectorAll('.menu').forEach(el => el.classList.remove('hidden'));
        });
        main_initializer.events.on('success', () => {
          window.location = `${location.href}&t=${Date.now()}`;
        });
      } else {
        let background_initializer = new ListscreenInitialize(this.list_screens);
        background_initializer.run();
      }
    }
  }

}

/***/ }),

/***/ "./js/admin/columns/menu.js":
/*!**********************************!*\
  !*** ./js/admin/columns/menu.js ***!
  \**********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Menu; });
class Menu {
  init() {
    let $ = jQuery;
    $('#ac_list_screen').on('change', function () {
      $('.view-link').hide();
      $(this).parents('form').submit();
      $(this).prop('disabled', true).next('.spinner').css('display', 'inline-block');
    });
  }

}

/***/ }),

/***/ "./js/admin/columns/settings/custom-field.js":
/*!***************************************************!*\
  !*** ./js/admin/columns/settings/custom-field.js ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(global) {var nanobus = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");

class SingleCustomFieldRequestManager {
  constructor(meta_type, post_type) {
    this.meta_type = meta_type;
    this.post_type = post_type;
    this.loading = false;
    this.data = false;
    this.events = nanobus();
  }

  retrieveOptions() {
    this.loading = true;
    return jQuery.ajax({
      url: ajaxurl,
      dataType: 'json',
      method: 'post',
      data: {
        action: 'ac_custom_field_options',
        post_type: this.post_type,
        meta_type: this.meta_type,
        _ajax_nonce: AC._ajax_nonce
      }
    });
  }

  getOptions() {
    const defer = jQuery.Deferred();

    if (this.data) {
      defer.resolve(this.data);
    } else if (this.loading) {
      this.events.on('loaded', () => {
        defer.resolve(this.data);
      });
    } else {
      this.retrieveOptions().done(response => {
        if (!response.success) {
          defer.reject();
        }

        this.data = response.data.results;
        this.events.emit('loaded');
        defer.resolve(this.data);
      });
    }

    return defer.promise();
  }

}

const loadSingleRequestManager = (meta_type, post_type) => {
  const key = `custom_field_${meta_type}_${post_type}`;

  if (typeof AC_Requests === 'undefined') {
    global.AC_Requests = {};
  }

  if (!AC_Requests.hasOwnProperty(key)) {
    AC_Requests[key] = new SingleCustomFieldRequestManager(meta_type, post_type);
  }

  return AC_Requests[key];
};

class CustomField {
  constructor(column) {
    this.column = column;
    this.setting = column.$el[0].querySelector('.ac-column-setting--custom_field');

    if (!this.setting || !this.setting.querySelector('.custom_field')) {
      return;
    }

    this.bindEvents();
  }

  bindEvents() {
    const input = this.setting.querySelector('.custom_field');
    const request = loadSingleRequestManager(input.dataset.type, input.dataset.post_type);
    const editingAvailable = this.column.el.querySelectorAll('[data-setting="edit"][data-indicator-toggle]').length > 0; // Ensure you won't get any duplicates on clone

    input.querySelectorAll('optgroup').forEach(el => {
      el.remove();
    });
    input.removeAttribute('data-select2-id');
    this.setting.querySelectorAll('.select2').forEach(el => {
      el.remove();
    });
    request.getOptions().done(data => {
      jQuery(input).ac_select2({
        theme: 'acs2',
        width: '100%',
        tags: editingAvailable,
        dropdownCssClass: '-customfields',
        data: data
      });
    });
  }

}

const customfield = column => {
  column.settings.customfield = new CustomField(column);
};

/* harmony default export */ __webpack_exports__["default"] = (customfield);
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./js/admin/columns/settings/date.js":
/*!*******************************************!*\
  !*** ./js/admin/columns/settings/date.js ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
let date = function (column) {
  let $ = jQuery;
  let $column = column.$el;
  let $setting = $column.find('.ac-column-setting--date');
  $setting.each(function () {
    let $container = $(this); // Custom input

    let $radio_custom = $container.find('input.custom');
    let $input_custom = $container.find('.ac-setting-input-date__custom');
    let $input_value = $container.find('.ac-setting-input-date__value');
    let $example_custom = $container.find('.ac-setting-input-date__example');
    let $selected = $container.find('input[type=radio]:checked');
    let $help_msg = $container.find('.help-msg'); // Click Event

    $container.find('input[type=radio]').on('change', function () {
      let $input = $(this);
      let $input_container = $input.closest('label');
      let date_format = $input_container.find('code').text();
      let description = $input_container.find('.ac-setting-input-date__more').html();

      if (date_format) {
        $input_custom.val(date_format).trigger('change');
      }

      if ($input.hasClass('diff')) {
        $input_custom.val('');
        $example_custom.text('');
      }

      $input_custom.prop('disabled', true); // Custom input selected

      if ($input.hasClass('custom')) {
        $input.val($input_custom.val());
        $input_custom.prop('disabled', false);
        $help_msg.show();
      } // Show more description


      $help_msg.hide();

      if (description) {
        $help_msg.html(description).show();
      }

      $input_value.val($input.val());
    }); // Custom input

    $input_custom.on('change', function () {
      $example_custom.html('<span class="spinner is-active"></span>');
      $radio_custom.val($input_custom.val());
      let $custom_value = $(this).val();

      if (!$custom_value) {
        $example_custom.text('');
        return;
      }

      $.ajax({
        url: ajaxurl,
        method: 'post',
        data: {
          action: 'date_format',
          date: $custom_value
        }
      }).done(function (date) {
        $example_custom.text(date);
      });
      $input_value.val($custom_value);
    }); // Update date example box

    $selected.trigger('change'); // Select custom input as a default

    if (0 === $selected.length) {
      $radio_custom.trigger('click');
    }
  });
};

/* harmony default export */ __webpack_exports__["default"] = (date);

/***/ }),

/***/ "./js/admin/columns/settings/image-size.js":
/*!*************************************************!*\
  !*** ./js/admin/columns/settings/image-size.js ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
class Image {
  constructor(column) {
    this.column = column;
    this.setting = column.$el[0].querySelector('.ac-column-setting--image');

    if (!this.setting) {
      return;
    }

    this.field = this.setting.querySelector('.ac-setting-input select');
    this.initState();
    this.bindEvents();
  }

  getValue() {
    return this.field.value;
  }

  bindEvents() {
    let self = this;
    this.field.addEventListener('change', function (e) {
      self.initState();
    });
  }

  initState() {
    if ('cpac-custom' === this.getValue()) {
      this.showSubsettings();
    } else {
      this.hideSubsettings();
    }
  }

  hideSubsettings() {
    let subsetting = this.setting.querySelectorAll('.ac-column-setting');

    for (let i = 0; i < subsetting.length; ++i) {
      subsetting[i].style.display = 'none';
    }
  }

  showSubsettings() {
    let subsetting = this.setting.querySelectorAll('.ac-column-setting');

    for (let i = 0; i < subsetting.length; ++i) {
      subsetting[i].style.display = 'table';
    }
  }

  setValue(value) {
    this.field.value = value;
    this.trigger(this.field, 'change');
    return this;
  }

  setWidth(width) {
    let field = this.setting.querySelector('.ac-column-setting [name*="image_size_w"]');
    field.value = width;
    this.trigger(field, 'change');
    return this;
  }

  setHeight(height) {
    let field = this.setting.querySelector('.ac-column-setting [name*="image_size_h"]');
    field.value = height;
    this.trigger(field, 'change');
    return this;
  }

  setSize(width, height) {
    this.setWidth(width);
    this.setHeight(height);
    return this;
  }

  trigger(el, event) {
    el.dispatchEvent(new Event(event));
    return this;
  }

}

let image = function (column) {
  column.settings.image = new Image(column);
};

/* harmony default export */ __webpack_exports__["default"] = (image);

/***/ }),

/***/ "./js/admin/columns/settings/label.js":
/*!********************************************!*\
  !*** ./js/admin/columns/settings/label.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../modules/modal */ "./js/modules/modal.ts");


class Label {
  constructor(column) {
    this.column = column;
    this.setting = column.el.querySelector('.ac-column-setting--label');
    this.iconpicker = this.setting.querySelector('.-iconpicker');

    if (!this.iconpicker) {
      return;
    }

    this._dashicon = false;
    this.modal = AdminColumns.Modals.register(new _modules_modal__WEBPACK_IMPORTED_MODULE_0__["default"](this.setting.querySelector('.-iconpicker')));
    this.field = this.setting.querySelector('.ac-setting-input_label');
    this.initValue();
    this.bindEvents();
  }

  initValue() {
    let self = this;
    let html = document.createRange().createContextualFragment(this.getValue());
    let dashicon = html.querySelector('.dashicons');

    if (dashicon) {
      let classList = dashicon.classList;
      classList.forEach(cls => {
        if (cls.indexOf('dashicons-') !== -1) {
          let selector = '.' + cls;
          let icon = self.iconpicker.querySelector(selector);

          if (icon) {
            icon.parentElement.classList.add('active');
            self.setIconSelection(icon.parentElement.dataset.dashicon);
          }
        }
      });
    } else {
      self.setIconSelection(false);
    }
  }

  bindEvents() {
    let self = this;
    this.setting.querySelector('.ac-setting-label-icon').addEventListener('click', function (e) {
      e.preventDefault();
      self.modal.open();
    });
    this.setting.querySelector('[data-action="submit"]').addEventListener('click', function (e) {
      e.preventDefault();

      if (self.getIconSelection()) {
        self.setDashicon(self.getIconSelection());
      }

      self.modal.close();
    });
    let icons = this.iconpicker.querySelectorAll('.ac-ipicker__icon');
    icons.forEach(icon => {
      icon.addEventListener('click', function (e) {
        e.preventDefault();
        let dashicon = this.dataset.dashicon;

        if (dashicon) {
          self.setIconSelection(dashicon);
        }

        let icons = self.setting.querySelectorAll('.ac-ipicker__icon');
        icons.forEach(icon => {
          icon.classList.remove('active');
        });
        icon.classList.add('active');
      });
    });
  }

  getValue() {
    return this.field.value;
  }

  setValue(value) {
    this.field.value = value;
    let event = new Event('change');
    this.field.dispatchEvent(event);
  }

  setIconSelection(dashicon) {
    let selection = this.setting.querySelector('.ac-ipicker__selection');
    this._dashicon = dashicon;
    selection.innerHTML = `<span class="dashicons dashicons-${dashicon}"></span>`;

    if (!dashicon) {
      selection.style.visibility = 'hidden';
    } else {
      selection.style.visibility = 'visible';
    }
  }

  getIconSelection() {
    return this._dashicon;
  }

  setDashicon(dashicon) {
    this.setValue(`<span class="dashicons dashicons-${dashicon}"></span>`);
  }

}

let label = function (column) {
  column.settings.label = new Label(column);
};

/* harmony default export */ __webpack_exports__["default"] = (label);

/***/ }),

/***/ "./js/admin/columns/settings/number-format.js":
/*!****************************************************!*\
  !*** ./js/admin/columns/settings/number-format.js ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
class NumberFormat {
  constructor(column) {
    this.column = column;
    this.setting = column.$el[0].querySelector('.ac-column-setting--number_format');

    if (!this.setting) {
      return;
    }

    this.bindEvents();
  }

  bindEvents() {
    this.refreshPreview();
    this.setting.querySelectorAll('input').forEach(el => {
      el.addEventListener('change', e => {
        this.refreshPreview();
      });
    });
  }

  refreshPreview() {
    this.getExampleRequest().done(example => {
      let preview = this.setting.querySelector('[data-preview]');

      if (preview) {
        preview.innerText = example;
      }
    });
  }

  getExampleRequest() {
    let decimals = this.setting.querySelector('.ac-setting-input_number_decimals');
    let decimal_point = this.setting.querySelector('.ac-setting-input_number_decimal_point');
    let thousands_point = this.setting.querySelector('.ac-setting-input_number_thousands_separator');
    return jQuery.ajax({
      url: ajaxurl,
      method: 'post',
      data: {
        action: 'ac_number_format',
        number: 7500,
        decimals: decimals ? decimals.value : '',
        decimal_point: decimal_point ? decimal_point.value : '',
        thousands_point: thousands_point ? thousands_point.value : ''
      }
    });
  }

}

const numberformat = column => {
  column.settings.number_format = new NumberFormat(column);
};

/* harmony default export */ __webpack_exports__["default"] = (numberformat);

/***/ }),

/***/ "./js/admin/columns/settings/pro.js":
/*!******************************************!*\
  !*** ./js/admin/columns/settings/pro.js ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
let pro = function (column) {
  let $column = column.$el;
  let $setting = $column.find('.ac-column-setting--pro');
  $setting.each(function () {
    let $container = jQuery(this);
    $container.find('input').on('click', function (e) {
      e.preventDefault();
      $container.find('[data-ac-open-modal]').trigger('click');
    });
  });
};

/* harmony default export */ __webpack_exports__["default"] = (pro);

/***/ }),

/***/ "./js/admin/columns/settings/sub-setting-toggle.js":
/*!*********************************************************!*\
  !*** ./js/admin/columns/settings/sub-setting-toggle.js ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
let subsetting = function (column) {
  let $ = jQuery;
  let settings = {
    value_show: "on",
    subfield: '.ac-column-setting'
  };

  function initState($setting, $input) {
    let value = $input.filter(':checked').val();
    let $subfields = $setting.find(settings.subfield);

    if (settings.value_show === value) {
      $subfields.show();
    } else {
      $subfields.hide();
    }
  }

  let $column = column.$el;
  let $settings = $column.find('.ac-column-setting--filter,.ac-column-setting--sort,.ac-column-setting--edit');
  $settings.each(function () {
    let $setting = $(this);
    let $input = $(this).find('.ac-setting-input input[type="radio"]');
    initState($setting, $input);
    $input.on('change', function () {
      initState($setting, $input);
    });
  });
};

/* harmony default export */ __webpack_exports__["default"] = (subsetting);

/***/ }),

/***/ "./js/admin/columns/settings/type.js":
/*!*******************************************!*\
  !*** ./js/admin/columns/settings/type.js ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _select2_excludegroup_matcher__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../select2/excludegroup.matcher */ "./js/select2/excludegroup.matcher.js");


class TypeSelector {
  constructor(column) {
    this.column = column;
    this.setting = column.$el[0].querySelector('[data-setting="type"]');

    if (!this.setting) {
      return;
    }

    this.bindEvents();
  }

  bindEvents() {
    const select = this.setting.querySelector('.ac-setting-input_type');

    if (select) {
      select.removeAttribute('data-select2-id');
      this.setting.querySelectorAll('.select2').forEach(el => {
        el.remove();
      });
      jQuery(select).ac_select2({
        theme: 'acs2',
        width: '100%',
        dropdownCssClass: '-type-selector',
        escapeMarkup: function (text) {
          return text;
        },
        templateResult: function (result) {
          let text = result.text;

          if (result.hasOwnProperty('id') && result.id.includes('placeholder-')) {
            text += `<span style="background-color:#FE3D6C; color:#fff; font-size: 10px; margin-top: -1px; padding: 1px 5px; border-radius: 2px; text-transform: uppercase;float: right; margin-right 10px;">PRO</span>`;
          }

          return text;
        },
        matcher: _select2_excludegroup_matcher__WEBPACK_IMPORTED_MODULE_0__["default"]
      });
    }
  }

}

const type = column => {
  column.settings.typeSelector = new TypeSelector(column);
};

/* harmony default export */ __webpack_exports__["default"] = (type);

/***/ }),

/***/ "./js/admin/columns/settings/width.js":
/*!********************************************!*\
  !*** ./js/admin/columns/settings/width.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// Settings fields: Width
jQuery.fn.column_width_slider = function () {
  let $ = jQuery;
  let $column_width = $(this).find('.ac-setting-input-width');
  let input_width = $column_width.find('.description input'),
      input_unit = $column_width.find('.unit-select input'),
      unit = input_unit.filter(':checked').val(),
      width = input_width.val(),
      slider = $column_width.find('.width-slider'),
      indicator = $(this).find('.ac-column-header .ac-column-heading-setting--width'); // width

  if ('%' === unit && width > 100) {
    width = 100;
  }

  input_width.val(width);
  slider.slider({
    range: 'min',
    min: 0,
    max: '%' === unit ? 100 : 500,
    value: width,
    slide: function (event, ui) {
      input_width.val(ui.value);
      indicator.trigger('update');
      input_width.trigger('validate');
    }
  });
};

let width = function (column) {
  let $ = jQuery;
  let $column = column.$el;
  $column.find('.ac-column-setting--width').each(function () {
    $column.column_width_slider(); // indicator

    let $width_indicator = $column.find('.ac-column-header .ac-column-heading-setting--width');
    $width_indicator.on('update', function () {
      let _width = $column.find('.ac-setting-input-width .description input').val();

      let _unit = $column.find('.ac-setting-input-width .description .unit').text();

      if (_width > 0) {
        jQuery(this).text(_width + _unit);
      } else {
        jQuery(this).text('');
      }
    }); // unit selector

    let width_unit_select = $column.find('.ac-setting-input-width .unit-select label');
    width_unit_select.on('click', function () {
      $column.find('span.unit').text($(this).find('input').val());
      $column.column_width_slider(); // re-init slider

      $width_indicator.trigger('update'); // update indicator
    }); // width_input

    let width_input = $column.find('.ac-setting-input-width .description input').on('keyup', function () {
      $column.column_width_slider(); // re-init slider

      jQuery(this).trigger('validate'); // validate input

      $width_indicator.trigger('update'); // update indicator
    }) // width_input:validate
    .on('validate', function () {
      let _width = width_input.val();

      let _new_width = $.trim(_width);

      if (!jQuery.isNumeric(_new_width)) {
        _new_width = _new_width.replace(/\D/g, '');
      }

      if (_new_width.length > 3) {
        _new_width = _new_width.substring(0, 3);
      }

      if (_new_width <= 0) {
        _new_width = '';
      }

      if (_new_width !== _width) {
        width_input.val(_new_width);
      }
    });
  });
};

/* harmony default export */ __webpack_exports__["default"] = (width);

/***/ }),

/***/ "./js/modules/modal.ts":
/*!*****************************!*\
  !*** ./js/modules/modal.ts ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Modal; });
class Modal {
  constructor(el) {
    if (!el) {
      return;
    }

    this.el = el;
    this.dialog = el.querySelector('.ac-modal__dialog');
    this.initEvents();
  }

  initEvents() {
    let self = this;
    document.addEventListener('keydown', e => {
      const keyName = e.key;

      if (!this.isOpen()) {
        return;
      }

      if ('Escape' === keyName) {
        this.close();
      }
    });
    let dismissButtons = this.el.querySelectorAll('[data-dismiss="modal"], .ac-modal__dialog__close');

    if (dismissButtons.length > 0) {
      dismissButtons.forEach(b => {
        b.addEventListener('click', e => {
          e.preventDefault();
          self.close();
        });
      });
    }

    this.el.addEventListener('click', e => {
      if (e.target.classList.contains('ac-modal')) {
        self.close();
      }
    });
  }

  isOpen() {
    return this.el.classList.contains('-active');
  }

  close() {
    this.onClose();
    this.el.classList.remove('-active');
  }

  open() {
    //short delay in order to allow bubbling events to bind before opening
    setTimeout(() => {
      this.onOpen();
      this.el.removeAttribute('style');
      this.el.classList.add('-active');
    });
  }

  destroy() {
    this.el.remove();
  }

  onClose() {}

  onOpen() {}

}

/***/ }),

/***/ "./js/modules/screen-option.ts":
/*!*************************************!*\
  !*** ./js/modules/screen-option.ts ***!
  \*************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ScreenOption; });
const $ = __webpack_require__(/*! jquery */ "jquery");

class ScreenOption {
  constructor(element, name) {
    this.name = name;
    this.element = element;
    this.init();
  }

  getInput() {
    return this.element.querySelector('input');
  }

  init() {
    let input = this.getInput();

    if (input) {
      input.addEventListener('change', () => {
        this.persist();
      });
    }
  }

  persist() {
    return $.ajax({
      url: ajaxurl,
      method: 'POST',
      data: {
        action: 'ac_admin_screen_options',
        option_name: this.name,
        option_value: this.getInput().checked ? 1 : 0,
        _ajax_nonce: AC._ajax_nonce
      }
    });
  }

}

/***/ }),

/***/ "./js/modules/tooltips.ts":
/*!********************************!*\
  !*** ./js/modules/tooltips.ts ***!
  \********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../plugin/tooltip */ "./js/plugin/tooltip.ts");


class Tooltips {
  constructor() {
    this.init();
  }

  init() {
    Object(_plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__["initAcTooltips"])();
  }

}

/* harmony default export */ __webpack_exports__["default"] = (Tooltips);

/***/ }),

/***/ "./js/plugin/tooltip.ts":
/*!******************************!*\
  !*** ./js/plugin/tooltip.ts ***!
  \******************************/
/*! exports provided: initAcTooltips, Tooltip */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "initAcTooltips", function() { return initAcTooltips; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Tooltip", function() { return Tooltip; });
const initAcTooltips = () => {
  document.querySelectorAll('[data-ac-tip]').forEach(element => {
    new Tooltip(element);
  });
};
class Tooltip {
  constructor(el, content = '') {
    this.element = el;
    this.content = content ? content : el.dataset.acTip;
    this.tip = createTooltip(this.content);
    this.initEvents();
  }

  initEvents() {
    if (this.element.dataset.acTooltipInit === '1') {
      return;
    }

    this.element.dataset.acTooltipInit = '1';
    document.body.appendChild(this.tip);
    this.element.addEventListener('mouseenter', () => {
      const bodyOffset = document.body.getBoundingClientRect();
      const viewportOffset = this.element.getBoundingClientRect();
      this.tip.style.left = viewportOffset.left - bodyOffset.left + this.element.offsetWidth / 2 + 'px';
      this.tip.style.top = viewportOffset.top - bodyOffset.top + this.element.offsetHeight + 'px';
      this.tip.classList.add('hover');
    });
    this.element.addEventListener('mouseleave', () => {
      this.tip.classList.remove('hover');
    });
  }

}

const createTooltip = content => {
  let tip = document.createElement('div');
  tip.classList.add('ac-tooltip');
  tip.innerHTML = content;
  return tip;
};

/***/ }),

/***/ "./js/select2/excludegroup.matcher.js":
/*!********************************************!*\
  !*** ./js/select2/excludegroup.matcher.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return excludeGroupsMather; });
function excludeGroupsMather(params, data) {
  if (jQuery.trim(params.term) === '') {
    return data;
  }

  if (typeof data.children === 'undefined') {
    return null;
  }

  var filteredChildren = [];
  jQuery.each(data.children, function (idx, child) {
    if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) > -1) {
      filteredChildren.push(child);
    }
  });

  if (filteredChildren.length) {
    var modifiedData = jQuery.extend({}, data, true);
    modifiedData.children = filteredChildren;
    return modifiedData;
  }

  return null;
}

/***/ }),

/***/ "./node_modules/admin-columns-js/polyfill/customevent.js":
/*!***************************************************************!*\
  !*** ./node_modules/admin-columns-js/polyfill/customevent.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/** CustomEvent Polyfill */
(function() {

	if ( typeof window.CustomEvent === "function" ) {
		return false;
	}

	function CustomEvent( event, params ) {
		params = params || { bubbles : false, cancelable : false, detail : undefined };
		let evt = document.createEvent( 'CustomEvent' );
		evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
		return evt;
	}

	CustomEvent.prototype = window.Event.prototype;

	window.CustomEvent = CustomEvent;
})();

/***/ }),

/***/ "./node_modules/admin-columns-js/polyfill/nodelist.js":
/*!************************************************************!*\
  !*** ./node_modules/admin-columns-js/polyfill/nodelist.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

if ( window.NodeList && !NodeList.prototype.forEach ) {
	NodeList.prototype.forEach = Array.prototype.forEach;
}

/***/ }),

/***/ "./node_modules/nanoassert/index.js":
/*!******************************************!*\
  !*** ./node_modules/nanoassert/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

assert.notEqual = notEqual
assert.notOk = notOk
assert.equal = equal
assert.ok = assert

module.exports = assert

function equal (a, b, m) {
  assert(a == b, m) // eslint-disable-line eqeqeq
}

function notEqual (a, b, m) {
  assert(a != b, m) // eslint-disable-line eqeqeq
}

function notOk (t, m) {
  assert(!t, m)
}

function assert (t, m) {
  if (!t) throw new Error(m || 'AssertionError')
}


/***/ }),

/***/ "./node_modules/nanobus/index.js":
/*!***************************************!*\
  !*** ./node_modules/nanobus/index.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var splice = __webpack_require__(/*! remove-array-items */ "./node_modules/remove-array-items/index.js")
var nanotiming = __webpack_require__(/*! nanotiming */ "./node_modules/nanotiming/browser.js")
var assert = __webpack_require__(/*! assert */ "./node_modules/nanoassert/index.js")

module.exports = Nanobus

function Nanobus (name) {
  if (!(this instanceof Nanobus)) return new Nanobus(name)

  this._name = name || 'nanobus'
  this._starListeners = []
  this._listeners = {}
}

Nanobus.prototype.emit = function (eventName) {
  assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.emit: eventName should be type string or symbol')

  var data = []
  for (var i = 1, len = arguments.length; i < len; i++) {
    data.push(arguments[i])
  }

  var emitTiming = nanotiming(this._name + "('" + eventName.toString() + "')")
  var listeners = this._listeners[eventName]
  if (listeners && listeners.length > 0) {
    this._emit(this._listeners[eventName], data)
  }

  if (this._starListeners.length > 0) {
    this._emit(this._starListeners, eventName, data, emitTiming.uuid)
  }
  emitTiming()

  return this
}

Nanobus.prototype.on = Nanobus.prototype.addListener = function (eventName, listener) {
  assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.on: eventName should be type string or symbol')
  assert.equal(typeof listener, 'function', 'nanobus.on: listener should be type function')

  if (eventName === '*') {
    this._starListeners.push(listener)
  } else {
    if (!this._listeners[eventName]) this._listeners[eventName] = []
    this._listeners[eventName].push(listener)
  }
  return this
}

Nanobus.prototype.prependListener = function (eventName, listener) {
  assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.prependListener: eventName should be type string or symbol')
  assert.equal(typeof listener, 'function', 'nanobus.prependListener: listener should be type function')

  if (eventName === '*') {
    this._starListeners.unshift(listener)
  } else {
    if (!this._listeners[eventName]) this._listeners[eventName] = []
    this._listeners[eventName].unshift(listener)
  }
  return this
}

Nanobus.prototype.once = function (eventName, listener) {
  assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.once: eventName should be type string or symbol')
  assert.equal(typeof listener, 'function', 'nanobus.once: listener should be type function')

  var self = this
  this.on(eventName, once)
  function once () {
    listener.apply(self, arguments)
    self.removeListener(eventName, once)
  }
  return this
}

Nanobus.prototype.prependOnceListener = function (eventName, listener) {
  assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.prependOnceListener: eventName should be type string or symbol')
  assert.equal(typeof listener, 'function', 'nanobus.prependOnceListener: listener should be type function')

  var self = this
  this.prependListener(eventName, once)
  function once () {
    listener.apply(self, arguments)
    self.removeListener(eventName, once)
  }
  return this
}

Nanobus.prototype.removeListener = function (eventName, listener) {
  assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.removeListener: eventName should be type string or symbol')
  assert.equal(typeof listener, 'function', 'nanobus.removeListener: listener should be type function')

  if (eventName === '*') {
    this._starListeners = this._starListeners.slice()
    return remove(this._starListeners, listener)
  } else {
    if (typeof this._listeners[eventName] !== 'undefined') {
      this._listeners[eventName] = this._listeners[eventName].slice()
    }

    return remove(this._listeners[eventName], listener)
  }

  function remove (arr, listener) {
    if (!arr) return
    var index = arr.indexOf(listener)
    if (index !== -1) {
      splice(arr, index, 1)
      return true
    }
  }
}

Nanobus.prototype.removeAllListeners = function (eventName) {
  if (eventName) {
    if (eventName === '*') {
      this._starListeners = []
    } else {
      this._listeners[eventName] = []
    }
  } else {
    this._starListeners = []
    this._listeners = {}
  }
  return this
}

Nanobus.prototype.listeners = function (eventName) {
  var listeners = eventName !== '*'
    ? this._listeners[eventName]
    : this._starListeners

  var ret = []
  if (listeners) {
    var ilength = listeners.length
    for (var i = 0; i < ilength; i++) ret.push(listeners[i])
  }
  return ret
}

Nanobus.prototype._emit = function (arr, eventName, data, uuid) {
  if (typeof arr === 'undefined') return
  if (arr.length === 0) return
  if (data === undefined) {
    data = eventName
    eventName = null
  }

  if (eventName) {
    if (uuid !== undefined) {
      data = [eventName].concat(data, uuid)
    } else {
      data = [eventName].concat(data)
    }
  }

  var length = arr.length
  for (var i = 0; i < length; i++) {
    var listener = arr[i]
    listener.apply(listener, data)
  }
}


/***/ }),

/***/ "./node_modules/nanoscheduler/index.js":
/*!*********************************************!*\
  !*** ./node_modules/nanoscheduler/index.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var assert = __webpack_require__(/*! assert */ "./node_modules/nanoassert/index.js")

var hasWindow = typeof window !== 'undefined'

function createScheduler () {
  var scheduler
  if (hasWindow) {
    if (!window._nanoScheduler) window._nanoScheduler = new NanoScheduler(true)
    scheduler = window._nanoScheduler
  } else {
    scheduler = new NanoScheduler()
  }
  return scheduler
}

function NanoScheduler (hasWindow) {
  this.hasWindow = hasWindow
  this.hasIdle = this.hasWindow && window.requestIdleCallback
  this.method = this.hasIdle ? window.requestIdleCallback.bind(window) : this.setTimeout
  this.scheduled = false
  this.queue = []
}

NanoScheduler.prototype.push = function (cb) {
  assert.equal(typeof cb, 'function', 'nanoscheduler.push: cb should be type function')

  this.queue.push(cb)
  this.schedule()
}

NanoScheduler.prototype.schedule = function () {
  if (this.scheduled) return

  this.scheduled = true
  var self = this
  this.method(function (idleDeadline) {
    var cb
    while (self.queue.length && idleDeadline.timeRemaining() > 0) {
      cb = self.queue.shift()
      cb(idleDeadline)
    }
    self.scheduled = false
    if (self.queue.length) self.schedule()
  })
}

NanoScheduler.prototype.setTimeout = function (cb) {
  setTimeout(cb, 0, {
    timeRemaining: function () {
      return 1
    }
  })
}

module.exports = createScheduler


/***/ }),

/***/ "./node_modules/nanotiming/browser.js":
/*!********************************************!*\
  !*** ./node_modules/nanotiming/browser.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var scheduler = __webpack_require__(/*! nanoscheduler */ "./node_modules/nanoscheduler/index.js")()
var assert = __webpack_require__(/*! assert */ "./node_modules/nanoassert/index.js")

var perf
nanotiming.disabled = true
try {
  perf = window.performance
  nanotiming.disabled = window.localStorage.DISABLE_NANOTIMING === 'true' || !perf.mark
} catch (e) { }

module.exports = nanotiming

function nanotiming (name) {
  assert.equal(typeof name, 'string', 'nanotiming: name should be type string')

  if (nanotiming.disabled) return noop

  var uuid = (perf.now() * 10000).toFixed() % Number.MAX_SAFE_INTEGER
  var startName = 'start-' + uuid + '-' + name
  perf.mark(startName)

  function end (cb) {
    var endName = 'end-' + uuid + '-' + name
    perf.mark(endName)

    scheduler.push(function () {
      var err = null
      try {
        var measureName = name + ' [' + uuid + ']'
        perf.measure(measureName, startName, endName)
        perf.clearMarks(startName)
        perf.clearMarks(endName)
      } catch (e) { err = e }
      if (cb) cb(err, name)
    })
  }

  end.uuid = uuid
  return end
}

function noop (cb) {
  if (cb) {
    scheduler.push(function () {
      cb(new Error('nanotiming: performance API unavailable'))
    })
  }
}


/***/ }),

/***/ "./node_modules/remove-array-items/index.js":
/*!**************************************************!*\
  !*** ./node_modules/remove-array-items/index.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Remove a range of items from an array
 *
 * @function removeItems
 * @param {Array<*>} arr The target array
 * @param {number} startIdx The index to begin removing from (inclusive)
 * @param {number} removeCount How many items to remove
 */
module.exports = function removeItems (arr, startIdx, removeCount) {
  var i, length = arr.length

  if (startIdx >= length || removeCount === 0) {
    return
  }

  removeCount = (startIdx + removeCount > length ? length - startIdx : removeCount)

  var len = length - removeCount

  for (i = startIdx; i < len; ++i) {
    arr[i] = arr[i + removeCount]
  }

  arr.length = len
}


/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ 0:
/*!****************************************!*\
  !*** multi ./js/admin-page-columns.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./js/admin-page-columns.js */"./js/admin-page-columns.js");


/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = jQuery;

/***/ })

/******/ });
//# sourceMappingURL=admin-page-columns.js.map