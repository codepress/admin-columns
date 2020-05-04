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
/* WEBPACK VAR INJECTION */(function(global) {/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/web.dom.iterable */ "./node_modules/core-js/modules/web.dom.iterable.js");
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es6.array.iterator */ "./node_modules/core-js/modules/es6.array.iterator.js");
/* harmony import */ var core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es6.object.to-string */ "./node_modules/core-js/modules/es6.object.to-string.js");
/* harmony import */ var core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es6.object.keys */ "./node_modules/core-js/modules/es6.object.keys.js");
/* harmony import */ var core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _admin_columns_listscreen_initialize__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./admin/columns/listscreen-initialize */ "./js/admin/columns/listscreen-initialize.js");
/* harmony import */ var _admin_columns_form__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./admin/columns/form */ "./js/admin/columns/form.js");
/* harmony import */ var _modules_modals__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./modules/modals */ "./js/modules/modals.js");
/* harmony import */ var _admin_columns_initiator__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./admin/columns/initiator */ "./js/admin/columns/initiator.js");
/* harmony import */ var _modules_modal__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./modules/modal */ "./js/modules/modal.js");
/* harmony import */ var _admin_columns_menu__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./admin/columns/menu */ "./js/admin/columns/menu.js");
/* harmony import */ var _admin_columns_feedback__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./admin/columns/feedback */ "./js/admin/columns/feedback.js");
/* harmony import */ var _modules_tooltips__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./modules/tooltips */ "./js/modules/tooltips.js");
/* harmony import */ var _admin_columns_events_toggle__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./admin/columns/events/toggle */ "./js/admin/columns/events/toggle.js");
/* harmony import */ var _admin_columns_events_remove__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./admin/columns/events/remove */ "./js/admin/columns/events/remove.js");
/* harmony import */ var _admin_columns_events_clone__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./admin/columns/events/clone */ "./js/admin/columns/events/clone.js");
/* harmony import */ var _admin_columns_events_refresh__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./admin/columns/events/refresh */ "./js/admin/columns/events/refresh.js");
/* harmony import */ var _admin_columns_events_type_selector__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./admin/columns/events/type-selector */ "./js/admin/columns/events/type-selector.js");
/* harmony import */ var _admin_columns_events_indicator__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./admin/columns/events/indicator */ "./js/admin/columns/events/indicator.js");
/* harmony import */ var _admin_columns_events_label__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./admin/columns/events/label */ "./js/admin/columns/events/label.js");
/* harmony import */ var _admin_columns_events_addons__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./admin/columns/events/addons */ "./js/admin/columns/events/addons.js");
/* harmony import */ var _admin_columns_settings_image_size__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./admin/columns/settings/image-size */ "./js/admin/columns/settings/image-size.js");
/* harmony import */ var _admin_columns_settings_sub_setting_toggle__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ./admin/columns/settings/sub-setting-toggle */ "./js/admin/columns/settings/sub-setting-toggle.js");
/* harmony import */ var _admin_columns_settings_date__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ./admin/columns/settings/date */ "./js/admin/columns/settings/date.js");
/* harmony import */ var _admin_columns_settings_pro__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ./admin/columns/settings/pro */ "./js/admin/columns/settings/pro.js");
/* harmony import */ var _admin_columns_settings_width__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ./admin/columns/settings/width */ "./js/admin/columns/settings/width.js");
/* harmony import */ var _admin_columns_settings_label__WEBPACK_IMPORTED_MODULE_25__ = __webpack_require__(/*! ./admin/columns/settings/label */ "./js/admin/columns/settings/label.js");
/* harmony import */ var _admin_columns_settings_custom_field__WEBPACK_IMPORTED_MODULE_26__ = __webpack_require__(/*! ./admin/columns/settings/custom-field */ "./js/admin/columns/settings/custom-field.js");
/* harmony import */ var _admin_columns_settings_number_format__WEBPACK_IMPORTED_MODULE_27__ = __webpack_require__(/*! ./admin/columns/settings/number-format */ "./js/admin/columns/settings/number-format.js");
/* harmony import */ var _admin_columns_settings_type__WEBPACK_IMPORTED_MODULE_28__ = __webpack_require__(/*! ./admin/columns/settings/type */ "./js/admin/columns/settings/type.js");
/* harmony import */ var _modules_screen_option__WEBPACK_IMPORTED_MODULE_29__ = __webpack_require__(/*! ./modules/screen-option */ "./js/modules/screen-option.js");





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

global.AdminColumns = typeof AdminColumns !== "undefined" ? AdminColumns : {};
AC.Column = new _admin_columns_initiator__WEBPACK_IMPORTED_MODULE_7__["default"](); // Todo remove from

AdminColumns.Column = AC.Column;
jQuery(document).on('AC_Form_Loaded', function () {
  AdminColumns.Tooltips = new _modules_tooltips__WEBPACK_IMPORTED_MODULE_11__["default"]();
  /** Register Events **/

  AdminColumns.Column.registerEvent('toggle', _admin_columns_events_toggle__WEBPACK_IMPORTED_MODULE_12__["default"]).registerEvent('remove', _admin_columns_events_remove__WEBPACK_IMPORTED_MODULE_13__["default"]).registerEvent('clone', _admin_columns_events_clone__WEBPACK_IMPORTED_MODULE_14__["default"]).registerEvent('refresh', _admin_columns_events_refresh__WEBPACK_IMPORTED_MODULE_15__["default"]).registerEvent('type_selector', _admin_columns_events_type_selector__WEBPACK_IMPORTED_MODULE_16__["default"]).registerEvent('indicator', _admin_columns_events_indicator__WEBPACK_IMPORTED_MODULE_17__["default"]).registerEvent('label', _admin_columns_events_label__WEBPACK_IMPORTED_MODULE_18__["EventLabel"]).registerEvent('label_setting', _admin_columns_events_label__WEBPACK_IMPORTED_MODULE_18__["EventLabelSettings"]).registerEvent('addons', _admin_columns_events_addons__WEBPACK_IMPORTED_MODULE_19__["default"])
  /** Register Settings **/
  .registerSetting('date', _admin_columns_settings_date__WEBPACK_IMPORTED_MODULE_22__["default"]).registerSetting('image_size', _admin_columns_settings_image_size__WEBPACK_IMPORTED_MODULE_20__["default"]).registerSetting('pro', _admin_columns_settings_pro__WEBPACK_IMPORTED_MODULE_23__["default"]).registerSetting('sub_setting_toggle', _admin_columns_settings_sub_setting_toggle__WEBPACK_IMPORTED_MODULE_21__["default"]).registerSetting('width', _admin_columns_settings_width__WEBPACK_IMPORTED_MODULE_24__["default"]).registerSetting('customfield', _admin_columns_settings_custom_field__WEBPACK_IMPORTED_MODULE_26__["default"]).registerSetting('number_format', _admin_columns_settings_number_format__WEBPACK_IMPORTED_MODULE_27__["default"]).registerSetting('type_selector', _admin_columns_settings_type__WEBPACK_IMPORTED_MODULE_28__["default"]).registerSetting('label', _admin_columns_settings_label__WEBPACK_IMPORTED_MODULE_25__["default"]);
});
jQuery(document).ready(function () {
  AC.Form = new _admin_columns_form__WEBPACK_IMPORTED_MODULE_5__["default"]('#listscreen_settings');
  AdminColumns.Form = AC.Form;
  _modules_modals__WEBPACK_IMPORTED_MODULE_6__["default"].init().register(new _modules_modal__WEBPACK_IMPORTED_MODULE_8__["default"](document.querySelector('#ac-modal-pro')), 'pro');
  new _admin_columns_menu__WEBPACK_IMPORTED_MODULE_9__["default"]().init();
  new _admin_columns_feedback__WEBPACK_IMPORTED_MODULE_10__["default"]('.sidebox#direct-feedback');
  ['AC_Column_Change', 'AC_Column_Refresh', 'AC_Column_Refresh'].forEach(function (hook) {
    jQuery(document).on(hook, function () {
      return ac_pointers();
    });
  });
  jQuery(document).on('AC_Column_Created', function (e, column) {
    setTimeout(function () {
      ac_pointers();
    }, 100);
  });

  if (AC.hasOwnProperty('uninitialized_list_screens') && Object.keys(AC.uninitialized_list_screens).length > 0) {
    new _admin_columns_listscreen_initialize__WEBPACK_IMPORTED_MODULE_4__["default"](AC.uninitialized_list_screens);
  }

  AdminColumns.ScreenOptions = {};
  document.querySelectorAll('[data-ac-screen-option]').forEach(function (el) {
    var name = el.dataset.acScreenOption;
    AdminColumns.ScreenOptions[name] = new _modules_screen_option__WEBPACK_IMPORTED_MODULE_29__["default"](el, name);
  });
  var showColumnId = document.querySelector('[data-ac-screen-option="show_column_id"] input');

  if (showColumnId) {
    showColumnId.addEventListener('change', function () {
      showColumnId.checked ? document.querySelector('.ac-boxes').classList.add('show-column-id') : document.querySelector('.ac-boxes').classList.remove('show-column-id');
    });
  }

  var showColumnType = document.querySelector('[data-ac-screen-option="show_column_type"] input');

  if (showColumnType) {
    showColumnType.addEventListener('change', function () {
      showColumnType.checked ? document.querySelector('.ac-boxes').classList.add('show-column-type') : document.querySelector('.ac-boxes').classList.remove('show-column-type');
    });
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./js/admin/columns/column.js":
/*!************************************!*\
  !*** ./js/admin/columns/column.js ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/web.dom.iterable */ "./node_modules/core-js/modules/web.dom.iterable.js");
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es6.array.iterator */ "./node_modules/core-js/modules/es6.array.iterator.js");
/* harmony import */ var core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es6.object.to-string */ "./node_modules/core-js/modules/es6.object.to-string.js");
/* harmony import */ var core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es6.object.keys */ "./node_modules/core-js/modules/es6.object.keys.js");
/* harmony import */ var core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var core_js_modules_es6_regexp_replace__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es6.regexp.replace */ "./node_modules/core-js/modules/es6.regexp.replace.js");
/* harmony import */ var core_js_modules_es6_regexp_replace__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_regexp_replace__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! core-js/modules/es6.function.name */ "./node_modules/core-js/modules/es6.function.name.js");
/* harmony import */ var core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_6__);








function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var STATES = {
  CLOSED: 'closed',
  OPEN: 'open'
};

var Column = /*#__PURE__*/function () {
  function Column($el) {
    _classCallCheck(this, Column);

    this.$el = $el;
    this.el = $el[0];
    this.settings = [];
    this.state = STATES.CLOSED;
    this._type = this.$el.data('type');
  }

  _createClass(Column, [{
    key: "isOriginal",
    value: function isOriginal() {
      return 1 === this.$el.data('original');
    }
  }, {
    key: "isDisabled",
    value: function isDisabled() {
      return this.$el.hasClass('disabled');
    }
  }, {
    key: "disable",
    value: function disable() {
      this.$el.addClass('disabled');
      return this;
    }
  }, {
    key: "enable",
    value: function enable() {
      this.$el.removeClass('disabled');
      return this;
    }
  }, {
    key: "initNewInstance",
    value: function initNewInstance() {
      var temp_column_name = '_new_column_' + AC.Column.getNewIncementalName();
      var original_column_name = this.name;
      this.$el.find('input, select, label').each(function (i, v) {
        var $input = jQuery(v); // name attributes

        if ($input.attr('name')) {
          $input.attr('name', $input.attr('name').replace("columns[".concat(original_column_name, "]"), "columns[".concat(temp_column_name, "]")));
        } // id attributes


        if ($input.attr('id')) {
          $input.attr('id', $input.attr('id').replace("-".concat(original_column_name, "-"), "-".concat(temp_column_name, "-")));
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

  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var column = this;
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
  }, {
    key: "bindSettings",
    value: function bindSettings() {
      var column = this;
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

  }, {
    key: "isBound",
    value: function isBound(key) {
      return this.$el.data(key);
    }
  }, {
    key: "bind",
    value: function bind(key) {
      this.$el.data(key, true);
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.$el.remove();
    }
  }, {
    key: "remove",
    value: function remove() {
      var duration = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 350;
      var self = this;
      this.$el.addClass('deleting').animate({
        opacity: 0,
        height: 0
      }, duration, function () {
        self.destroy();
      });
    }
  }, {
    key: "getState",
    value: function getState() {
      return this.state;
    }
  }, {
    key: "toggle",
    value: function toggle() {
      var duration = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 150;

      if (this.getState() === STATES.OPEN) {
        this.close(duration);
      } else {
        this.open(duration);
      }
    }
  }, {
    key: "close",
    value: function close() {
      var duration = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
      this.$el.removeClass('opened').find('.ac-column-body').slideUp(duration);
      this.state = STATES.CLOSED;
    }
  }, {
    key: "open",
    value: function open() {
      var duration = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
      this.$el.addClass('opened').find('.ac-column-body').slideDown(duration);
      this.state = STATES.OPEN;
    }
  }, {
    key: "showMessage",
    value: function showMessage(message) {
      this.$el.find('.ac-column-setting--type .msg').html(message).show();
    }
  }, {
    key: "switchToType",
    value: function switchToType(type) {
      var self = this;
      return jQuery.ajax({
        url: ajaxurl,
        method: 'post',
        dataType: 'json',
        data: {
          action: 'ac-columns',
          id: 'select',
          type: type,
          data: AC.Form.serialize(),
          current_original_columns: AC.Form.originalColumns(),
          original_columns: AC.original_columns,
          _ajax_nonce: AC._ajax_nonce
        },
        success: function success(response) {
          if (true === response.success) {
            var column = jQuery(response.data);
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
  }, {
    key: "refresh",
    value: function refresh() {
      var self = this;
      var data = this.$el.find(':input').serializeArray();
      var request_data = {
        action: 'ac-columns',
        id: 'refresh',
        _ajax_nonce: AC._ajax_nonce,
        data: AC.Form.serialize(),
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
        success: function success(response) {
          if (true === response.success) {
            var column = jQuery(response.data);
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

  }, {
    key: "create",
    value: function create() {
      this.initNewInstance();
      this.bindEvents();
      jQuery(document).trigger('AC_Column_Created', [this]);
      return this;
    }
    /**
     * @returns {Column}
     */

  }, {
    key: "clone",
    value: function clone() {
      var $clone = this.$el.clone();
      $clone.data('column-name', this.$el.data('column-name'));
      var clone = new Column($clone);
      clone.initNewInstance();
      clone.bindEvents();
      return clone;
    }
  }, {
    key: "name",
    get: function get() {
      return this.$el.data('column-name');
    },
    set: function set(name) {
      this.$el.data('column-name', name);
    }
  }, {
    key: "type",
    get: function get() {
      return this._type;
    },
    set: function set(type) {
      this.$el.data('type', type);
    }
  }]);

  return Column;
}();

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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


/*
 * Optional Radio Click events
 * TODO: Is not used anymore?
 */
var addons = function addons(column) {
  var $ = jQuery;
  var $column = column.$el;
  var inputs = $column.find('[data-trigger] label');
  inputs.on('click', function () {
    var id = $(this).closest('td.input').data('trigger');
    var state = $('input', this).val(); // Toggle indicator icon

    var label = $column.find('[data-indicator-id="' + id + '"]').removeClass('on');

    if ('on' === state) {
      label.addClass('on');
    } // Toggle additional options


    var additional = $column.find('[data-handle="' + id + '"]').addClass('hide');

    if ('on' === state) {
      additional.removeClass('hide');
    }
  }); // On load

  $column.find('[data-trigger]').each(function () {
    var trigger = $(this).data('trigger'); // Hide additional column settings

    var additional = $column.find('[data-handle="' + trigger + '"]').addClass('hide');

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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


/*
* Column: bind clone events
*
* @since 2.0
*/
var clone = function clone(column) {
  column.$el.find('.clone-button').click(function (e) {
    e.preventDefault();

    if (column.isOriginal()) {
      return;
    }

    AC.Form.cloneColumn(column.$el);
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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


var indicator = function indicator(column) {
  var $column = column.$el;
  $column.find('.ac-column-header [data-indicator-toggle]').each(function () {
    var $ = jQuery;
    var $indicator = $(this);
    var setting = $(this).data('setting');
    var $setting = $column.find('.ac-column-setting[data-setting=' + setting + ']');
    var $input = $setting.find('.col-input:first .ac-setting-input:first input[type=radio]');
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

      var value = $input.filter(':checked').val();

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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


var EventLabel = function EventLabel(column) {
  var $column = column.$el;
  /**
   * Populates the main Label with the selected label from the dropdown,
   */

  $column.find('select[data-label="update"]').change(function () {
    var $label = $column.find('input.ac-setting-input_label');
    var field_label = jQuery(this).find('option:selected').text(); // Set new label

    $label.val(field_label);
    $label.trigger('change');
  });
  /** When an label contains an icon or span, the displayed label can appear empty. In this case we show the "type" label. */

  setTimeout(function () {
    var column_label = $column.find('.column_label .toggle');

    if (jQuery.trim(column_label.html()) && column_label.width() < 1) {
      column_label.html($column.find('.column_type .inner').html());
    }
  }, 50);
};

var EventLabelSettings = function EventLabelSettings(column) {
  var $column = column.$el;
  /** change label */

  $column.find('.ac-column-setting--label input').bind('keyup change', function () {
    var value = jQuery(this).val();
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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


var refresh = function refresh(column) {
  var $ = jQuery;
  column.$el.find('[data-refresh="column"]').on('change', function () {
    // Allow plugins to hook into this event
    $(document).trigger('AC.column.prerefresh', column.$el);
    column.$el.addClass('loading');
    setTimeout(function () {
      column.refresh().always(function () {
        column.$el.removeClass('loading');
      }).fail(function () {
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
/* harmony import */ var core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.function.name */ "./node_modules/core-js/modules/es6.function.name.js");
/* harmony import */ var core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_1__);



/*
 * Column: bind remove events
 *
 * @since 2.0
 */
var remove = function remove(column) {
  column.$el.find('.remove-button').click(function (e) {
    e.preventDefault();
    AC.Form.removeColumn(column.name);
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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


/*
 * Column: bind toggle events
 *
 * For performance we bind all other events after the click event.
 *
 * @since 2.0
 */
var toggle = function toggle(column) {
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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


var selector = function selector(column) {
  var $ = jQuery;
  column.$el.find('select.ac-setting-input_type').change(function () {
    column.$el.addClass('loading');
    column.switchToType($(this).val()).always(function () {
      column.$el.removeClass('loading');
      AC.Form.reindexColumns();
    }).fail(function () {
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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Feedback = /*#__PURE__*/function () {
  function Feedback($el) {
    _classCallCheck(this, Feedback);

    this.$el = jQuery($el);
    this.init();
  }

  _createClass(Feedback, [{
    key: "init",
    value: function init() {
      var $box = this.$el;
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
  }]);

  return Feedback;
}();

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
/* harmony import */ var core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.function.name */ "./node_modules/core-js/modules/es6.function.name.js");
/* harmony import */ var core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/web.dom.iterable */ "./node_modules/core-js/modules/web.dom.iterable.js");
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es6.array.iterator */ "./node_modules/core-js/modules/es6.array.iterator.js");
/* harmony import */ var core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es6.object.to-string */ "./node_modules/core-js/modules/es6.object.to-string.js");
/* harmony import */ var core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es6.object.keys */ "./node_modules/core-js/modules/es6.object.keys.js");
/* harmony import */ var core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _column__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./column */ "./js/admin/columns/column.js");







function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var Form = /*#__PURE__*/function () {
  function Form(el) {
    _classCallCheck(this, Form);

    this.form = el;
    this.$form = jQuery(el);
    this.$column_container = this.$form.find('.ac-columns');
    this.$container = jQuery('#cpac .ac-admin');
    this.columns = {};
    this._validators = [];
    jQuery(document).trigger('AC_Form_Loaded');
    this.init();
  }

  _createClass(Form, [{
    key: "init",
    value: function init() {
      this.initColumns();
      this.bindFormEvents();
      this.bindOrdering();

      if (this.$form.hasClass('-disabled')) {
        this.disableFields();
      }

      jQuery(document).trigger('AC_Form_Ready', this);
    }
  }, {
    key: "bindOrdering",
    value: function bindOrdering() {
      if (this.$form.hasClass('ui-sortable')) {
        this.$form.sortable('refresh');
      } else {
        this.$form.sortable({
          items: '.ac-column',
          handle: '.column_sort'
        });
      }
    }
  }, {
    key: "originalColumns",
    value: function originalColumns() {
      var self = this;
      var columns = [];
      Object.keys(self.columns).forEach(function (key) {
        var column = self.columns[key];

        if (column.isOriginal()) {
          columns.push(column.type);
        }
      });
      return columns;
    }
  }, {
    key: "validateForm",
    value: function validateForm() {
      var _this = this;

      var valid = true;

      this._validators.forEach(function (validator) {
        valid = validator.call(_this, _this);
      });

      return valid;
    }
  }, {
    key: "addValidator",
    value: function addValidator(validator) {
      this._validators.push(validator);
    }
  }, {
    key: "bindFormEvents",
    value: function bindFormEvents() {
      var self = this;
      var $buttons = jQuery('.sidebox a.submit, .column-footer a.submit');
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
      var $boxes = jQuery('#cpac .ac-boxes');

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
  }, {
    key: "initColumns",
    value: function initColumns() {
      var self = this;
      self.columns = {};
      this.$form.find('.ac-column').each(function () {
        var $el = jQuery(this);
        var column = new _column__WEBPACK_IMPORTED_MODULE_6__["default"]($el);
        column.bindEvents();
        $el.data('column', column);
        self.columns[column.name] = column;
      });
    }
  }, {
    key: "reindexColumns",
    value: function reindexColumns() {
      var self = this;
      self.columns = {};
      this.$form.find('.ac-column').each(function () {
        var column = jQuery(this).data('column');
        self.columns[column.name] = column;
      });
    }
  }, {
    key: "resetColumns",
    value: function resetColumns() {
      var _this2 = this;

      Object.keys(this.columns).forEach(function (key) {
        var column = _this2.columns[key];
        column.destroy();
      });
    }
  }, {
    key: "serialize",
    value: function serialize() {
      return this.$form.serialize();
    }
  }, {
    key: "disableFields",
    value: function disableFields() {
      var form = document.querySelector(this.form);

      if (!form) {
        return;
      }

      var elements = form.elements;

      for (var i = 0; i < elements.length; i++) {
        elements[i].readOnly = true;
        elements[i].setAttribute('disabled', true);
      }
    }
  }, {
    key: "enableFields",
    value: function enableFields() {}
  }, {
    key: "submitForm",
    value: function submitForm() {
      var self = this;
      var xhr = jQuery.post(ajaxurl, {
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
  }, {
    key: "showMessage",
    value: function showMessage(message) {
      var attr_class = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'updated';
      var $msg = jQuery('<div class="ac-message hidden ' + attr_class + '"><p>' + message + '</p></div>');
      this.$container.find('.ac-message').stop().remove();
      this.$container.find('.ac-admin__main').prepend($msg);
      $msg.slideDown();
    }
  }, {
    key: "cloneColumn",
    value: function cloneColumn($el) {
      return this._addColumnToForm(new _column__WEBPACK_IMPORTED_MODULE_6__["default"]($el).clone(), $el.hasClass('opened'), $el);
    }
  }, {
    key: "addColumn",
    value: function addColumn() {
      var $clone = jQuery('#add-new-column-template').find('.ac-column').clone();
      var column = new _column__WEBPACK_IMPORTED_MODULE_6__["default"]($clone).create();
      return this._addColumnToForm(column);
    }
  }, {
    key: "removeColumn",
    value: function removeColumn(name) {
      if (this.columns[name]) {
        this.columns[name].remove();
        delete this.columns[name];
      }
    }
  }, {
    key: "getListScreen",
    value: function getListScreen() {
      return this.$form.find('input[name="list_screen"]').val();
    }
  }, {
    key: "getListScreenID",
    value: function getListScreenID() {
      return this.$form.find('input[name="list_screen_id"]').val();
    }
  }, {
    key: "getTitle",
    value: function getTitle() {
      return this.$form.find('input[name="title"]').val();
    }
  }, {
    key: "getColumnSettings",
    value: function getColumnSettings() {
      return this.$form.find('[name^="columns["]').serialize();
    }
  }, {
    key: "_addColumnToForm",
    value: function _addColumnToForm(column) {
      var open = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      var $after = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
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
  }]);

  return Form;
}();

/* harmony default export */ __webpack_exports__["default"] = (Form);

var isInViewport = function isInViewport($el) {
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
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Initiator = /*#__PURE__*/function () {
  function Initiator() {
    _classCallCheck(this, Initiator);

    this.events = {};
    this.settings = {};
    this.incremental_name = 0;
  }

  _createClass(Initiator, [{
    key: "registerSetting",
    value: function registerSetting(k, setting) {
      var key = 's_' + k;

      if (this.settings[key]) {
        console.error('Setting key already exists: ' + k);
      }

      this.settings[key] = setting;
      return this;
    }
  }, {
    key: "registerEvent",
    value: function registerEvent(k, event) {
      var key = 'e_' + k;

      if (this.settings[key]) {
        console.error('Event key already exists: ' + key);
      }

      this.events[key] = event;
      return this;
    }
  }, {
    key: "getNewIncementalName",
    value: function getNewIncementalName() {
      var oldName = this.incremental_name;
      this.incremental_name++;
      return oldName;
    }
  }]);

  return Initiator;
}();



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
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/web.dom.iterable */ "./node_modules/core-js/modules/web.dom.iterable.js");
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es6.array.iterator */ "./node_modules/core-js/modules/es6.array.iterator.js");
/* harmony import */ var core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_iterator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es6.object.to-string */ "./node_modules/core-js/modules/es6.object.to-string.js");
/* harmony import */ var core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_object_to_string__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es6.object.keys */ "./node_modules/core-js/modules/es6.object.keys.js");
/* harmony import */ var core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_object_keys__WEBPACK_IMPORTED_MODULE_3__);





function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var nanobus = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");

var ListscreenInitialize = /*#__PURE__*/function () {
  function ListscreenInitialize(list_screens) {
    _classCallCheck(this, ListscreenInitialize);

    this.list_screens = list_screens;
    this.processed = [];
    this.errors = [];
    this.success = [];
    this.events = nanobus();
  }

  _createClass(ListscreenInitialize, [{
    key: "doAjaxCall",
    value: function doAjaxCall(list_screen) {
      return jQuery.ajax({
        url: list_screen.screen_link,
        method: 'get'
      });
    }
  }, {
    key: "run",
    value: function run() {
      var _this = this;

      Object.keys(this.list_screens).forEach(function (key) {
        _this.processListScreen(_this.list_screens[key]);
      });
    }
  }, {
    key: "onFinish",
    value: function onFinish() {
      if (this.success.length === Object.keys(this.list_screens).length) {
        this.events.emit('success');
      }

      if (this.errors.length > 0) {
        this.events.emit('error');
      }
    }
  }, {
    key: "checkFinish",
    value: function checkFinish() {
      if (this.processed.length === Object.keys(this.list_screens).length) {
        this.onFinish();
      }
    }
  }, {
    key: "processListScreen",
    value: function processListScreen(list_screen) {
      var _this2 = this;

      return this.doAjaxCall(list_screen).done(function (r) {
        if (r === 'ac_success') {
          _this2.success.push(list_screen);
        } else {
          _this2.errors.push(list_screen);
        }
      }).fail(function () {
        _this2.errors.push(list_screen);
      }).always(function () {
        _this2.processed.push(list_screen);

        _this2.checkFinish();
      });
    }
  }]);

  return ListscreenInitialize;
}();

var ListScreenInitializeController = /*#__PURE__*/function () {
  function ListScreenInitializeController(list_screens) {
    _classCallCheck(this, ListScreenInitializeController);

    this.list_screens = list_screens;
    this.run();
  }

  _createClass(ListScreenInitializeController, [{
    key: "run",
    value: function run() {
      if (Object.keys(this.list_screens).length > 0) {
        // Only load main screen first if unitialized, otherwise do the rest in background
        if (this.list_screens.hasOwnProperty(AC.list_screen)) {
          var main_initializer = new ListscreenInitialize(_defineProperty({}, AC.list_screen, this.list_screens[AC.list_screen]));
          main_initializer.run();
          main_initializer.events.on('error', function () {
            document.querySelectorAll('.ac-loading-msg-wrapper').forEach(function (el) {
              return el.remove();
            });
            document.querySelectorAll('.menu').forEach(function (el) {
              return el.classList.remove('hidden');
            });
          });
          main_initializer.events.on('success', function () {
            window.location = "".concat(location.href, "&t=").concat(Date.now());
          });
        } else {
          var background_initializer = new ListscreenInitialize(this.list_screens);
          background_initializer.run();
        }
      }
    }
  }]);

  return ListScreenInitializeController;
}();



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
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Menu = /*#__PURE__*/function () {
  function Menu() {
    _classCallCheck(this, Menu);
  }

  _createClass(Menu, [{
    key: "init",
    value: function init() {
      var $ = jQuery;
      $('#ac_list_screen').on('change', function () {
        $('.view-link').hide();
        $(this).parents('form').submit();
        $(this).prop('disabled', true).next('.spinner').css('display', 'inline-block');
      });
    }
  }]);

  return Menu;
}();



/***/ }),

/***/ "./js/admin/columns/settings/custom-field.js":
/*!***************************************************!*\
  !*** ./js/admin/columns/settings/custom-field.js ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(global) {function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var nanobus = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");

var SingleCustomFieldRequestManager = /*#__PURE__*/function () {
  function SingleCustomFieldRequestManager(meta_type, post_type) {
    _classCallCheck(this, SingleCustomFieldRequestManager);

    this.meta_type = meta_type;
    this.post_type = post_type;
    this.loading = false;
    this.data = false;
    this.events = nanobus();
  }

  _createClass(SingleCustomFieldRequestManager, [{
    key: "retrieveOptions",
    value: function retrieveOptions() {
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
  }, {
    key: "getOptions",
    value: function getOptions() {
      var _this = this;

      var defer = jQuery.Deferred();

      if (this.data) {
        defer.resolve(this.data);
      } else if (this.loading) {
        this.events.on('loaded', function () {
          defer.resolve(_this.data);
        });
      } else {
        this.retrieveOptions().done(function (response) {
          if (!response.success) {
            defer.reject();
          }

          _this.data = response.data.results;

          _this.events.emit('loaded');

          defer.resolve(_this.data);
        });
      }

      return defer.promise();
    }
  }]);

  return SingleCustomFieldRequestManager;
}();

var loadSingleRequestManager = function loadSingleRequestManager(meta_type, post_type) {
  var key = "custom_field_".concat(meta_type, "_").concat(post_type);

  if (typeof AC_Requests === 'undefined') {
    global.AC_Requests = {};
  }

  if (!AC_Requests.hasOwnProperty(key)) {
    AC_Requests[key] = new SingleCustomFieldRequestManager(meta_type, post_type);
  }

  return AC_Requests[key];
};

var CustomField = /*#__PURE__*/function () {
  function CustomField(column) {
    _classCallCheck(this, CustomField);

    this.column = column;
    this.setting = column.$el[0].querySelector('.ac-column-setting--custom_field');

    if (!this.setting || !this.setting.querySelector('.custom_field')) {
      return;
    }

    this.bindEvents();
  }

  _createClass(CustomField, [{
    key: "bindEvents",
    value: function bindEvents() {
      var input = this.setting.querySelector('.custom_field');
      var request = loadSingleRequestManager(input.dataset.type, input.dataset.post_type);
      var editingAvailable = this.column.el.querySelectorAll('[data-setting="edit"][data-indicator-toggle]').length > 0; // Ensure you won't get any duplicates on clone

      input.querySelectorAll('optgroup').forEach(function (el) {
        el.remove();
      });
      input.removeAttribute('data-select2-id');
      this.setting.querySelectorAll('.select2').forEach(function (el) {
        el.remove();
      });
      request.getOptions().done(function (data) {
        jQuery(input).ac_select2({
          theme: 'acs2',
          width: '100%',
          tags: editingAvailable,
          dropdownCssClass: '-customfields',
          data: data
        });
      });
    }
  }]);

  return CustomField;
}();

var customfield = function customfield(column) {
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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


var date = function date(column) {
  var $ = jQuery;
  var $column = column.$el;
  var $setting = $column.find('.ac-column-setting--date');
  $setting.each(function () {
    var $container = $(this); // Custom input

    var $radio_custom = $container.find('input.custom');
    var $input_custom = $container.find('.ac-setting-input-date__custom');
    var $input_value = $container.find('.ac-setting-input-date__value');
    var $example_custom = $container.find('.ac-setting-input-date__example');
    var $selected = $container.find('input[type=radio]:checked');
    var $help_msg = $container.find('.help-msg'); // Click Event

    $container.find('input[type=radio]').on('change', function () {
      var $input = $(this);
      var $input_container = $input.closest('label');
      var date_format = $input_container.find('code').text();
      var description = $input_container.find('.ac-setting-input-date__more').html();

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
      var $custom_value = $(this).val();

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
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Image = /*#__PURE__*/function () {
  function Image(column) {
    _classCallCheck(this, Image);

    this.column = column;
    this.setting = column.$el[0].querySelector('.ac-column-setting--image');

    if (!this.setting) {
      return;
    }

    this.field = this.setting.querySelector('.ac-setting-input select');
    this.initState();
    this.bindEvents();
  }

  _createClass(Image, [{
    key: "getValue",
    value: function getValue() {
      return this.field.value;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var self = this;
      this.field.addEventListener('change', function (e) {
        self.initState();
      });
    }
  }, {
    key: "initState",
    value: function initState() {
      if ('cpac-custom' === this.getValue()) {
        this.showSubsettings();
      } else {
        this.hideSubsettings();
      }
    }
  }, {
    key: "hideSubsettings",
    value: function hideSubsettings() {
      var subsetting = this.setting.querySelectorAll('.ac-column-setting');

      for (var i = 0; i < subsetting.length; ++i) {
        subsetting[i].style.display = 'none';
      }
    }
  }, {
    key: "showSubsettings",
    value: function showSubsettings() {
      var subsetting = this.setting.querySelectorAll('.ac-column-setting');

      for (var i = 0; i < subsetting.length; ++i) {
        subsetting[i].style.display = 'table';
      }
    }
  }, {
    key: "setValue",
    value: function setValue(value) {
      this.field.value = value;
      this.trigger(this.field, 'change');
      return this;
    }
  }, {
    key: "setWidth",
    value: function setWidth(width) {
      var field = this.setting.querySelector('.ac-column-setting [name*="image_size_w"]');
      field.value = width;
      this.trigger(field, 'change');
      return this;
    }
  }, {
    key: "setHeight",
    value: function setHeight(height) {
      var field = this.setting.querySelector('.ac-column-setting [name*="image_size_h"]');
      field.value = height;
      this.trigger(field, 'change');
      return this;
    }
  }, {
    key: "setSize",
    value: function setSize(width, height) {
      this.setWidth(width);
      this.setHeight(height);
      return this;
    }
  }, {
    key: "trigger",
    value: function trigger(el, event) {
      el.dispatchEvent(new Event(event));
      return this;
    }
  }]);

  return Image;
}();

var image = function image(column) {
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
/* harmony import */ var _modules_modals__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../modules/modals */ "./js/modules/modals.js");
/* harmony import */ var _modules_modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../modules/modal */ "./js/modules/modal.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }




var Label = /*#__PURE__*/function () {
  function Label(column) {
    _classCallCheck(this, Label);

    this.column = column;
    this.setting = column.el.querySelector('.ac-column-setting--label');
    this.iconpicker = this.setting.querySelector('.-iconpicker');

    if (!this.iconpicker) {
      return;
    }

    this._dashicon = false;
    this.modal = _modules_modals__WEBPACK_IMPORTED_MODULE_0__["default"].init().register(new _modules_modal__WEBPACK_IMPORTED_MODULE_1__["default"](this.setting.querySelector('.-iconpicker')));
    this.field = this.setting.querySelector('.ac-setting-input_label');
    this.initValue();
    this.bindEvents();
  }

  _createClass(Label, [{
    key: "initValue",
    value: function initValue() {
      var self = this;
      var html = document.createRange().createContextualFragment(this.getValue());
      var dashicon = html.querySelector('.dashicons');

      if (dashicon) {
        var classList = dashicon.classList;
        classList.forEach(function (cls) {
          if (cls.indexOf('dashicons-') !== -1) {
            var selector = '.' + cls;
            var icon = self.iconpicker.querySelector(selector);

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
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var self = this;
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
      var icons = this.iconpicker.querySelectorAll('.ac-ipicker__icon');
      icons.forEach(function (icon) {
        icon.addEventListener('click', function (e) {
          e.preventDefault();
          var dashicon = this.dataset.dashicon;

          if (dashicon) {
            self.setIconSelection(dashicon);
          }

          var icons = self.setting.querySelectorAll('.ac-ipicker__icon');
          icons.forEach(function (icon) {
            icon.classList.remove('active');
          });
          icon.classList.add('active');
        });
      });
    }
  }, {
    key: "getValue",
    value: function getValue() {
      return this.field.value;
    }
  }, {
    key: "setValue",
    value: function setValue(value) {
      this.field.value = value;
      var event = new Event('change');
      this.field.dispatchEvent(event);
    }
  }, {
    key: "setIconSelection",
    value: function setIconSelection(dashicon) {
      var selection = this.setting.querySelector('.ac-ipicker__selection');
      this._dashicon = dashicon;
      selection.innerHTML = "<span class=\"dashicons dashicons-".concat(dashicon, "\"></span>");

      if (!dashicon) {
        selection.style.visibility = 'hidden';
      } else {
        selection.style.visibility = 'visible';
      }
    }
  }, {
    key: "getIconSelection",
    value: function getIconSelection() {
      return this._dashicon;
    }
  }, {
    key: "setDashicon",
    value: function setDashicon(dashicon) {
      this.setValue("<span class=\"dashicons dashicons-".concat(dashicon, "\"></span>"));
    }
  }]);

  return Label;
}();

var label = function label(column) {
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
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var NumberFormat = /*#__PURE__*/function () {
  function NumberFormat(column) {
    _classCallCheck(this, NumberFormat);

    this.column = column;
    this.setting = column.$el[0].querySelector('.ac-column-setting--number_format');

    if (!this.setting) {
      return;
    }

    this.bindEvents();
  }

  _createClass(NumberFormat, [{
    key: "bindEvents",
    value: function bindEvents() {
      var _this = this;

      this.refreshPreview();
      this.setting.querySelectorAll('input').forEach(function (el) {
        el.addEventListener('change', function (e) {
          _this.refreshPreview();
        });
      });
    }
  }, {
    key: "refreshPreview",
    value: function refreshPreview() {
      var _this2 = this;

      this.getExampleRequest().done(function (example) {
        var preview = _this2.setting.querySelector('[data-preview]');

        if (preview) {
          preview.innerText = example;
        }
      });
    }
  }, {
    key: "getExampleRequest",
    value: function getExampleRequest() {
      var decimals = this.setting.querySelector('.ac-setting-input_number_decimals');
      var decimal_point = this.setting.querySelector('.ac-setting-input_number_decimal_point');
      var thousands_point = this.setting.querySelector('.ac-setting-input_number_thousands_separator');
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
  }]);

  return NumberFormat;
}();

var numberformat = function numberformat(column) {
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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


var pro = function pro(column) {
  var $column = column.$el;
  var $setting = $column.find('.ac-column-setting--pro');
  $setting.each(function () {
    var $container = jQuery(this);
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
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_0__);


var subsetting = function subsetting(column) {
  var $ = jQuery;
  var settings = {
    value_show: "on",
    subfield: '.ac-column-setting'
  };

  function initState($setting, $input) {
    var value = $input.filter(':checked').val();
    var $subfields = $setting.find(settings.subfield);

    if (settings.value_show === value) {
      $subfields.show();
    } else {
      $subfields.hide();
    }
  }

  var $column = column.$el;
  var $settings = $column.find('.ac-column-setting--filter,.ac-column-setting--sort,.ac-column-setting--edit');
  $settings.each(function () {
    var $setting = $(this);
    var $input = $(this).find('.ac-setting-input input[type="radio"]');
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
/* harmony import */ var core_js_modules_es7_array_includes__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es7.array.includes */ "./node_modules/core-js/modules/es7.array.includes.js");
/* harmony import */ var core_js_modules_es7_array_includes__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es7_array_includes__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es6_string_includes__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es6.string.includes */ "./node_modules/core-js/modules/es6.string.includes.js");
/* harmony import */ var core_js_modules_es6_string_includes__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_string_includes__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _select2_excludegroup_matcher__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../select2/excludegroup.matcher */ "./js/select2/excludegroup.matcher.js");



function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var TypeSelector = /*#__PURE__*/function () {
  function TypeSelector(column) {
    _classCallCheck(this, TypeSelector);

    this.column = column;
    this.setting = column.$el[0].querySelector('[data-setting="type"]');

    if (!this.setting) {
      return;
    }

    this.bindEvents();
  }

  _createClass(TypeSelector, [{
    key: "bindEvents",
    value: function bindEvents() {
      var select = this.setting.querySelector('.ac-setting-input_type');

      if (select) {
        select.removeAttribute('data-select2-id');
        this.setting.querySelectorAll('.select2').forEach(function (el) {
          el.remove();
        });
        jQuery(select).ac_select2({
          theme: 'acs2',
          width: '100%',
          dropdownCssClass: '-type-selector',
          escapeMarkup: function escapeMarkup(text) {
            return text;
          },
          templateResult: function templateResult(result) {
            var text = result.text;

            if (result.hasOwnProperty('id') && result.id.includes('placeholder-')) {
              text += "<span style=\"background-color:#FE3D6C; color:#fff; font-size: 10px; margin-top: -1px; padding: 1px 5px; border-radius: 2px; text-transform: uppercase;float: right; margin-right 10px;\">PRO</span>";
            }

            return text;
          },
          matcher: _select2_excludegroup_matcher__WEBPACK_IMPORTED_MODULE_2__["default"]
        });
      }
    }
  }]);

  return TypeSelector;
}();

var type = function type(column) {
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
/* harmony import */ var core_js_modules_es6_regexp_replace__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.regexp.replace */ "./node_modules/core-js/modules/es6.regexp.replace.js");
/* harmony import */ var core_js_modules_es6_regexp_replace__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_regexp_replace__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es6.array.find */ "./node_modules/core-js/modules/es6.array.find.js");
/* harmony import */ var core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_array_find__WEBPACK_IMPORTED_MODULE_1__);



// Settings fields: Width
jQuery.fn.column_width_slider = function () {
  var $ = jQuery;
  var $column_width = $(this).find('.ac-setting-input-width');
  var input_width = $column_width.find('.description input'),
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
    slide: function slide(event, ui) {
      input_width.val(ui.value);
      indicator.trigger('update');
      input_width.trigger('validate');
    }
  });
};

var width = function width(column) {
  var $ = jQuery;
  var $column = column.$el;
  $column.find('.ac-column-setting--width').each(function () {
    $column.column_width_slider(); // indicator

    var $width_indicator = $column.find('.ac-column-header .ac-column-heading-setting--width');
    $width_indicator.on('update', function () {
      var _width = $column.find('.ac-setting-input-width .description input').val();

      var _unit = $column.find('.ac-setting-input-width .description .unit').text();

      if (_width > 0) {
        jQuery(this).text(_width + _unit);
      } else {
        jQuery(this).text('');
      }
    }); // unit selector

    var width_unit_select = $column.find('.ac-setting-input-width .unit-select label');
    width_unit_select.on('click', function () {
      $column.find('span.unit').text($(this).find('input').val());
      $column.column_width_slider(); // re-init slider

      $width_indicator.trigger('update'); // update indicator
    }); // width_input

    var width_input = $column.find('.ac-setting-input-width .description input').on('keyup', function () {
      $column.column_width_slider(); // re-init slider

      jQuery(this).trigger('validate'); // validate input

      $width_indicator.trigger('update'); // update indicator
    }) // width_input:validate
    .on('validate', function () {
      var _width = width_input.val();

      var _new_width = $.trim(_width);

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

/***/ "./js/modules/modal.js":
/*!*****************************!*\
  !*** ./js/modules/modal.js ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Modal; });
/* harmony import */ var _modals__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modals */ "./js/modules/modals.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var Modal = /*#__PURE__*/function () {
  function Modal(el) {
    _classCallCheck(this, Modal);

    if (!el) {
      return;
    }

    this.el = el;
    this.dialog = el.querySelector('.ac-modal__dialog');
    this.initEvents();
  }

  _createClass(Modal, [{
    key: "initEvents",
    value: function initEvents() {
      var _this = this;

      var self = this;
      document.addEventListener('keydown', function (e) {
        var keyName = event.key;

        if (!_this.isOpen()) {
          return;
        }

        if ('Escape' === keyName) {
          _this.close();
        }
      });
      var dismissButtons = this.el.querySelectorAll('[data-dismiss="modal"], .ac-modal__dialog__close');

      if (dismissButtons.length > 0) {
        dismissButtons.forEach(function (b) {
          b.addEventListener('click', function (e) {
            e.preventDefault();
            self.close();
          });
        });
      }

      this.el.addEventListener('click', function (e) {
        if (e.target.classList.contains('ac-modal')) {
          self.close();
        }
      });

      if (typeof document.querySelector('body').dataset.ac_modal_init === 'undefined') {
        Modal.initGlobalEvents();
        document.querySelector('body').dataset.ac_modal_init = 1;
      }

      this.el.AC_MODAL = self;
    }
  }, {
    key: "isOpen",
    value: function isOpen() {
      return this.el.classList.contains('-active');
    }
  }, {
    key: "close",
    value: function close() {
      this.onClose();
      this.el.classList.remove('-active');
    }
  }, {
    key: "open",
    value: function open() {
      var _this2 = this;

      //short delay in order to allow bubbling events to bind before opening
      setTimeout(function () {
        _this2.onOpen();

        _this2.el.removeAttribute('style');

        _this2.el.classList.add('-active');
      });
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.el.remove();
    }
  }, {
    key: "onClose",
    value: function onClose() {}
  }, {
    key: "onOpen",
    value: function onOpen() {}
  }], [{
    key: "initGlobalEvents",
    value: function initGlobalEvents() {
      jQuery(document).on('click', '[data-ac-open-modal]', function (e) {
        e.preventDefault();
        var target = e.target.dataset.acOpenModal;
        var el = document.querySelector(target);

        if (el && el.AC_MODAL) {
          el.AC_MODAL.open();
        }
      });
      jQuery(document).on('click', '[data-ac-modal]', function (e) {
        e.preventDefault();
        var modal_key = jQuery(this).data('ac-modal');

        if (_modals__WEBPACK_IMPORTED_MODULE_0__["default"].init().get(modal_key)) {
          _modals__WEBPACK_IMPORTED_MODULE_0__["default"].init().get(modal_key).open();
        }
      });
    }
  }]);

  return Modal;
}();



/***/ }),

/***/ "./js/modules/modals.js":
/*!******************************!*\
  !*** ./js/modules/modals.js ***!
  \******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Modals; });
/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modal */ "./js/modules/modal.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var Modals = /*#__PURE__*/function () {
  function Modals() {
    _classCallCheck(this, Modals);

    this.modals = [];
    this.number = 1;
  }

  _createClass(Modals, [{
    key: "register",
    value: function register(modal) {
      var key = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';

      if (!key) {
        key = 'm' + this.number;
      }

      this.modals[key] = modal;
      this.number++;
      return modal;
    }
  }, {
    key: "get",
    value: function get(key) {
      if (this.modals[key]) {
        return this.modals[key];
      }

      return false;
    } // Bind self to global AdminColumns if exist

  }], [{
    key: "init",
    value: function init() {
      if (typeof AdminColumns.Modals === 'undefined') {
        AdminColumns.Modals = new this();
        AdminColumns.Modals._abstract = {
          modal: _modal__WEBPACK_IMPORTED_MODULE_0__["default"]
        };
      }

      return AdminColumns.Modals;
    }
  }]);

  return Modals;
}();



/***/ }),

/***/ "./js/modules/screen-option.js":
/*!*************************************!*\
  !*** ./js/modules/screen-option.js ***!
  \*************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ScreenOption; });
/* harmony import */ var core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es6.function.name */ "./node_modules/core-js/modules/es6.function.name.js");
/* harmony import */ var core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_0__);


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ScreenOption = /*#__PURE__*/function () {
  function ScreenOption(element, name) {
    _classCallCheck(this, ScreenOption);

    this.name = name;
    this.element = element;
    this.init();
  }

  _createClass(ScreenOption, [{
    key: "getInput",
    value: function getInput() {
      return this.element.querySelector('input');
    }
  }, {
    key: "init",
    value: function init() {
      var _this = this;

      var input = this.getInput();

      if (input) {
        input.addEventListener('change', function () {
          _this.persist();
        });
      }
    }
  }, {
    key: "persist",
    value: function persist() {
      return jQuery.ajax({
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
  }]);

  return ScreenOption;
}();



/***/ }),

/***/ "./js/modules/tooltips.js":
/*!********************************!*\
  !*** ./js/modules/tooltips.js ***!
  \********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Tooltips = /*#__PURE__*/function () {
  function Tooltips() {
    _classCallCheck(this, Tooltips);

    this.isEnabled = typeof jQuery.fn.qtip !== 'undefined';
    this.init();
  }

  _createClass(Tooltips, [{
    key: "init",
    value: function init() {
      if (!this.isEnabled) {
        console.log('Tooltips not loaded!');
        return;
      }

      jQuery('[data-ac-tip]').qtip({
        content: {
          attr: 'data-ac-tip'
        },
        position: {
          my: 'top center',
          at: 'bottom center'
        },
        style: {
          tip: true,
          classes: 'qtip-tipsy'
        }
      });
    }
  }]);

  return Tooltips;
}();

/* harmony default export */ __webpack_exports__["default"] = (Tooltips);

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

/***/ "./node_modules/core-js/modules/_a-function.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_a-function.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function (it) {
  if (typeof it != 'function') throw TypeError(it + ' is not a function!');
  return it;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_add-to-unscopables.js":
/*!*************************************************************!*\
  !*** ./node_modules/core-js/modules/_add-to-unscopables.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 22.1.3.31 Array.prototype[@@unscopables]
var UNSCOPABLES = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('unscopables');
var ArrayProto = Array.prototype;
if (ArrayProto[UNSCOPABLES] == undefined) __webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js")(ArrayProto, UNSCOPABLES, {});
module.exports = function (key) {
  ArrayProto[UNSCOPABLES][key] = true;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_advance-string-index.js":
/*!***************************************************************!*\
  !*** ./node_modules/core-js/modules/_advance-string-index.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var at = __webpack_require__(/*! ./_string-at */ "./node_modules/core-js/modules/_string-at.js")(true);

 // `AdvanceStringIndex` abstract operation
// https://tc39.github.io/ecma262/#sec-advancestringindex
module.exports = function (S, index, unicode) {
  return index + (unicode ? at(S, index).length : 1);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_an-object.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_an-object.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(/*! ./_is-object */ "./node_modules/core-js/modules/_is-object.js");
module.exports = function (it) {
  if (!isObject(it)) throw TypeError(it + ' is not an object!');
  return it;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_array-includes.js":
/*!*********************************************************!*\
  !*** ./node_modules/core-js/modules/_array-includes.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// false -> Array#indexOf
// true  -> Array#includes
var toIObject = __webpack_require__(/*! ./_to-iobject */ "./node_modules/core-js/modules/_to-iobject.js");
var toLength = __webpack_require__(/*! ./_to-length */ "./node_modules/core-js/modules/_to-length.js");
var toAbsoluteIndex = __webpack_require__(/*! ./_to-absolute-index */ "./node_modules/core-js/modules/_to-absolute-index.js");
module.exports = function (IS_INCLUDES) {
  return function ($this, el, fromIndex) {
    var O = toIObject($this);
    var length = toLength(O.length);
    var index = toAbsoluteIndex(fromIndex, length);
    var value;
    // Array#includes uses SameValueZero equality algorithm
    // eslint-disable-next-line no-self-compare
    if (IS_INCLUDES && el != el) while (length > index) {
      value = O[index++];
      // eslint-disable-next-line no-self-compare
      if (value != value) return true;
    // Array#indexOf ignores holes, Array#includes - not
    } else for (;length > index; index++) if (IS_INCLUDES || index in O) {
      if (O[index] === el) return IS_INCLUDES || index || 0;
    } return !IS_INCLUDES && -1;
  };
};


/***/ }),

/***/ "./node_modules/core-js/modules/_array-methods.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/modules/_array-methods.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 0 -> Array#forEach
// 1 -> Array#map
// 2 -> Array#filter
// 3 -> Array#some
// 4 -> Array#every
// 5 -> Array#find
// 6 -> Array#findIndex
var ctx = __webpack_require__(/*! ./_ctx */ "./node_modules/core-js/modules/_ctx.js");
var IObject = __webpack_require__(/*! ./_iobject */ "./node_modules/core-js/modules/_iobject.js");
var toObject = __webpack_require__(/*! ./_to-object */ "./node_modules/core-js/modules/_to-object.js");
var toLength = __webpack_require__(/*! ./_to-length */ "./node_modules/core-js/modules/_to-length.js");
var asc = __webpack_require__(/*! ./_array-species-create */ "./node_modules/core-js/modules/_array-species-create.js");
module.exports = function (TYPE, $create) {
  var IS_MAP = TYPE == 1;
  var IS_FILTER = TYPE == 2;
  var IS_SOME = TYPE == 3;
  var IS_EVERY = TYPE == 4;
  var IS_FIND_INDEX = TYPE == 6;
  var NO_HOLES = TYPE == 5 || IS_FIND_INDEX;
  var create = $create || asc;
  return function ($this, callbackfn, that) {
    var O = toObject($this);
    var self = IObject(O);
    var f = ctx(callbackfn, that, 3);
    var length = toLength(self.length);
    var index = 0;
    var result = IS_MAP ? create($this, length) : IS_FILTER ? create($this, 0) : undefined;
    var val, res;
    for (;length > index; index++) if (NO_HOLES || index in self) {
      val = self[index];
      res = f(val, index, O);
      if (TYPE) {
        if (IS_MAP) result[index] = res;   // map
        else if (res) switch (TYPE) {
          case 3: return true;             // some
          case 5: return val;              // find
          case 6: return index;            // findIndex
          case 2: result.push(val);        // filter
        } else if (IS_EVERY) return false; // every
      }
    }
    return IS_FIND_INDEX ? -1 : IS_SOME || IS_EVERY ? IS_EVERY : result;
  };
};


/***/ }),

/***/ "./node_modules/core-js/modules/_array-species-constructor.js":
/*!********************************************************************!*\
  !*** ./node_modules/core-js/modules/_array-species-constructor.js ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(/*! ./_is-object */ "./node_modules/core-js/modules/_is-object.js");
var isArray = __webpack_require__(/*! ./_is-array */ "./node_modules/core-js/modules/_is-array.js");
var SPECIES = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('species');

module.exports = function (original) {
  var C;
  if (isArray(original)) {
    C = original.constructor;
    // cross-realm fallback
    if (typeof C == 'function' && (C === Array || isArray(C.prototype))) C = undefined;
    if (isObject(C)) {
      C = C[SPECIES];
      if (C === null) C = undefined;
    }
  } return C === undefined ? Array : C;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_array-species-create.js":
/*!***************************************************************!*\
  !*** ./node_modules/core-js/modules/_array-species-create.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 9.4.2.3 ArraySpeciesCreate(originalArray, length)
var speciesConstructor = __webpack_require__(/*! ./_array-species-constructor */ "./node_modules/core-js/modules/_array-species-constructor.js");

module.exports = function (original, length) {
  return new (speciesConstructor(original))(length);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_classof.js":
/*!**************************************************!*\
  !*** ./node_modules/core-js/modules/_classof.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// getting tag from 19.1.3.6 Object.prototype.toString()
var cof = __webpack_require__(/*! ./_cof */ "./node_modules/core-js/modules/_cof.js");
var TAG = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('toStringTag');
// ES3 wrong here
var ARG = cof(function () { return arguments; }()) == 'Arguments';

// fallback for IE11 Script Access Denied error
var tryGet = function (it, key) {
  try {
    return it[key];
  } catch (e) { /* empty */ }
};

module.exports = function (it) {
  var O, T, B;
  return it === undefined ? 'Undefined' : it === null ? 'Null'
    // @@toStringTag case
    : typeof (T = tryGet(O = Object(it), TAG)) == 'string' ? T
    // builtinTag case
    : ARG ? cof(O)
    // ES3 arguments fallback
    : (B = cof(O)) == 'Object' && typeof O.callee == 'function' ? 'Arguments' : B;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_cof.js":
/*!**********************************************!*\
  !*** ./node_modules/core-js/modules/_cof.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var toString = {}.toString;

module.exports = function (it) {
  return toString.call(it).slice(8, -1);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_core.js":
/*!***********************************************!*\
  !*** ./node_modules/core-js/modules/_core.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var core = module.exports = { version: '2.6.11' };
if (typeof __e == 'number') __e = core; // eslint-disable-line no-undef


/***/ }),

/***/ "./node_modules/core-js/modules/_ctx.js":
/*!**********************************************!*\
  !*** ./node_modules/core-js/modules/_ctx.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// optional / simple context binding
var aFunction = __webpack_require__(/*! ./_a-function */ "./node_modules/core-js/modules/_a-function.js");
module.exports = function (fn, that, length) {
  aFunction(fn);
  if (that === undefined) return fn;
  switch (length) {
    case 1: return function (a) {
      return fn.call(that, a);
    };
    case 2: return function (a, b) {
      return fn.call(that, a, b);
    };
    case 3: return function (a, b, c) {
      return fn.call(that, a, b, c);
    };
  }
  return function (/* ...args */) {
    return fn.apply(that, arguments);
  };
};


/***/ }),

/***/ "./node_modules/core-js/modules/_defined.js":
/*!**************************************************!*\
  !*** ./node_modules/core-js/modules/_defined.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// 7.2.1 RequireObjectCoercible(argument)
module.exports = function (it) {
  if (it == undefined) throw TypeError("Can't call method on  " + it);
  return it;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_descriptors.js":
/*!******************************************************!*\
  !*** ./node_modules/core-js/modules/_descriptors.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// Thank's IE8 for his funny defineProperty
module.exports = !__webpack_require__(/*! ./_fails */ "./node_modules/core-js/modules/_fails.js")(function () {
  return Object.defineProperty({}, 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ "./node_modules/core-js/modules/_dom-create.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_dom-create.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(/*! ./_is-object */ "./node_modules/core-js/modules/_is-object.js");
var document = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js").document;
// typeof document.createElement is 'object' in old IE
var is = isObject(document) && isObject(document.createElement);
module.exports = function (it) {
  return is ? document.createElement(it) : {};
};


/***/ }),

/***/ "./node_modules/core-js/modules/_enum-bug-keys.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/modules/_enum-bug-keys.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// IE 8- don't enum bug keys
module.exports = (
  'constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf'
).split(',');


/***/ }),

/***/ "./node_modules/core-js/modules/_export.js":
/*!*************************************************!*\
  !*** ./node_modules/core-js/modules/_export.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js");
var core = __webpack_require__(/*! ./_core */ "./node_modules/core-js/modules/_core.js");
var hide = __webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js");
var redefine = __webpack_require__(/*! ./_redefine */ "./node_modules/core-js/modules/_redefine.js");
var ctx = __webpack_require__(/*! ./_ctx */ "./node_modules/core-js/modules/_ctx.js");
var PROTOTYPE = 'prototype';

var $export = function (type, name, source) {
  var IS_FORCED = type & $export.F;
  var IS_GLOBAL = type & $export.G;
  var IS_STATIC = type & $export.S;
  var IS_PROTO = type & $export.P;
  var IS_BIND = type & $export.B;
  var target = IS_GLOBAL ? global : IS_STATIC ? global[name] || (global[name] = {}) : (global[name] || {})[PROTOTYPE];
  var exports = IS_GLOBAL ? core : core[name] || (core[name] = {});
  var expProto = exports[PROTOTYPE] || (exports[PROTOTYPE] = {});
  var key, own, out, exp;
  if (IS_GLOBAL) source = name;
  for (key in source) {
    // contains in native
    own = !IS_FORCED && target && target[key] !== undefined;
    // export native or passed
    out = (own ? target : source)[key];
    // bind timers to global for call from export context
    exp = IS_BIND && own ? ctx(out, global) : IS_PROTO && typeof out == 'function' ? ctx(Function.call, out) : out;
    // extend global
    if (target) redefine(target, key, out, type & $export.U);
    // export
    if (exports[key] != out) hide(exports, key, exp);
    if (IS_PROTO && expProto[key] != out) expProto[key] = out;
  }
};
global.core = core;
// type bitmap
$export.F = 1;   // forced
$export.G = 2;   // global
$export.S = 4;   // static
$export.P = 8;   // proto
$export.B = 16;  // bind
$export.W = 32;  // wrap
$export.U = 64;  // safe
$export.R = 128; // real proto method for `library`
module.exports = $export;


/***/ }),

/***/ "./node_modules/core-js/modules/_fails-is-regexp.js":
/*!**********************************************************!*\
  !*** ./node_modules/core-js/modules/_fails-is-regexp.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var MATCH = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('match');
module.exports = function (KEY) {
  var re = /./;
  try {
    '/./'[KEY](re);
  } catch (e) {
    try {
      re[MATCH] = false;
      return !'/./'[KEY](re);
    } catch (f) { /* empty */ }
  } return true;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_fails.js":
/*!************************************************!*\
  !*** ./node_modules/core-js/modules/_fails.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function (exec) {
  try {
    return !!exec();
  } catch (e) {
    return true;
  }
};


/***/ }),

/***/ "./node_modules/core-js/modules/_fix-re-wks.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_fix-re-wks.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

__webpack_require__(/*! ./es6.regexp.exec */ "./node_modules/core-js/modules/es6.regexp.exec.js");
var redefine = __webpack_require__(/*! ./_redefine */ "./node_modules/core-js/modules/_redefine.js");
var hide = __webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js");
var fails = __webpack_require__(/*! ./_fails */ "./node_modules/core-js/modules/_fails.js");
var defined = __webpack_require__(/*! ./_defined */ "./node_modules/core-js/modules/_defined.js");
var wks = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js");
var regexpExec = __webpack_require__(/*! ./_regexp-exec */ "./node_modules/core-js/modules/_regexp-exec.js");

var SPECIES = wks('species');

var REPLACE_SUPPORTS_NAMED_GROUPS = !fails(function () {
  // #replace needs built-in support for named groups.
  // #match works fine because it just return the exec results, even if it has
  // a "grops" property.
  var re = /./;
  re.exec = function () {
    var result = [];
    result.groups = { a: '7' };
    return result;
  };
  return ''.replace(re, '$<a>') !== '7';
});

var SPLIT_WORKS_WITH_OVERWRITTEN_EXEC = (function () {
  // Chrome 51 has a buggy "split" implementation when RegExp#exec !== nativeExec
  var re = /(?:)/;
  var originalExec = re.exec;
  re.exec = function () { return originalExec.apply(this, arguments); };
  var result = 'ab'.split(re);
  return result.length === 2 && result[0] === 'a' && result[1] === 'b';
})();

module.exports = function (KEY, length, exec) {
  var SYMBOL = wks(KEY);

  var DELEGATES_TO_SYMBOL = !fails(function () {
    // String methods call symbol-named RegEp methods
    var O = {};
    O[SYMBOL] = function () { return 7; };
    return ''[KEY](O) != 7;
  });

  var DELEGATES_TO_EXEC = DELEGATES_TO_SYMBOL ? !fails(function () {
    // Symbol-named RegExp methods call .exec
    var execCalled = false;
    var re = /a/;
    re.exec = function () { execCalled = true; return null; };
    if (KEY === 'split') {
      // RegExp[@@split] doesn't call the regex's exec method, but first creates
      // a new one. We need to return the patched regex when creating the new one.
      re.constructor = {};
      re.constructor[SPECIES] = function () { return re; };
    }
    re[SYMBOL]('');
    return !execCalled;
  }) : undefined;

  if (
    !DELEGATES_TO_SYMBOL ||
    !DELEGATES_TO_EXEC ||
    (KEY === 'replace' && !REPLACE_SUPPORTS_NAMED_GROUPS) ||
    (KEY === 'split' && !SPLIT_WORKS_WITH_OVERWRITTEN_EXEC)
  ) {
    var nativeRegExpMethod = /./[SYMBOL];
    var fns = exec(
      defined,
      SYMBOL,
      ''[KEY],
      function maybeCallNative(nativeMethod, regexp, str, arg2, forceStringMethod) {
        if (regexp.exec === regexpExec) {
          if (DELEGATES_TO_SYMBOL && !forceStringMethod) {
            // The native String method already delegates to @@method (this
            // polyfilled function), leasing to infinite recursion.
            // We avoid it by directly calling the native @@method method.
            return { done: true, value: nativeRegExpMethod.call(regexp, str, arg2) };
          }
          return { done: true, value: nativeMethod.call(str, regexp, arg2) };
        }
        return { done: false };
      }
    );
    var strfn = fns[0];
    var rxfn = fns[1];

    redefine(String.prototype, KEY, strfn);
    hide(RegExp.prototype, SYMBOL, length == 2
      // 21.2.5.8 RegExp.prototype[@@replace](string, replaceValue)
      // 21.2.5.11 RegExp.prototype[@@split](string, limit)
      ? function (string, arg) { return rxfn.call(string, this, arg); }
      // 21.2.5.6 RegExp.prototype[@@match](string)
      // 21.2.5.9 RegExp.prototype[@@search](string)
      : function (string) { return rxfn.call(string, this); }
    );
  }
};


/***/ }),

/***/ "./node_modules/core-js/modules/_flags.js":
/*!************************************************!*\
  !*** ./node_modules/core-js/modules/_flags.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// 21.2.5.3 get RegExp.prototype.flags
var anObject = __webpack_require__(/*! ./_an-object */ "./node_modules/core-js/modules/_an-object.js");
module.exports = function () {
  var that = anObject(this);
  var result = '';
  if (that.global) result += 'g';
  if (that.ignoreCase) result += 'i';
  if (that.multiline) result += 'm';
  if (that.unicode) result += 'u';
  if (that.sticky) result += 'y';
  return result;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_function-to-string.js":
/*!*************************************************************!*\
  !*** ./node_modules/core-js/modules/_function-to-string.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./_shared */ "./node_modules/core-js/modules/_shared.js")('native-function-to-string', Function.toString);


/***/ }),

/***/ "./node_modules/core-js/modules/_global.js":
/*!*************************************************!*\
  !*** ./node_modules/core-js/modules/_global.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self
  // eslint-disable-next-line no-new-func
  : Function('return this')();
if (typeof __g == 'number') __g = global; // eslint-disable-line no-undef


/***/ }),

/***/ "./node_modules/core-js/modules/_has.js":
/*!**********************************************!*\
  !*** ./node_modules/core-js/modules/_has.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var hasOwnProperty = {}.hasOwnProperty;
module.exports = function (it, key) {
  return hasOwnProperty.call(it, key);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_hide.js":
/*!***********************************************!*\
  !*** ./node_modules/core-js/modules/_hide.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(/*! ./_object-dp */ "./node_modules/core-js/modules/_object-dp.js");
var createDesc = __webpack_require__(/*! ./_property-desc */ "./node_modules/core-js/modules/_property-desc.js");
module.exports = __webpack_require__(/*! ./_descriptors */ "./node_modules/core-js/modules/_descriptors.js") ? function (object, key, value) {
  return dP.f(object, key, createDesc(1, value));
} : function (object, key, value) {
  object[key] = value;
  return object;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_html.js":
/*!***********************************************!*\
  !*** ./node_modules/core-js/modules/_html.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var document = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js").document;
module.exports = document && document.documentElement;


/***/ }),

/***/ "./node_modules/core-js/modules/_ie8-dom-define.js":
/*!*********************************************************!*\
  !*** ./node_modules/core-js/modules/_ie8-dom-define.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = !__webpack_require__(/*! ./_descriptors */ "./node_modules/core-js/modules/_descriptors.js") && !__webpack_require__(/*! ./_fails */ "./node_modules/core-js/modules/_fails.js")(function () {
  return Object.defineProperty(__webpack_require__(/*! ./_dom-create */ "./node_modules/core-js/modules/_dom-create.js")('div'), 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ "./node_modules/core-js/modules/_iobject.js":
/*!**************************************************!*\
  !*** ./node_modules/core-js/modules/_iobject.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// fallback for non-array-like ES3 and non-enumerable old V8 strings
var cof = __webpack_require__(/*! ./_cof */ "./node_modules/core-js/modules/_cof.js");
// eslint-disable-next-line no-prototype-builtins
module.exports = Object('z').propertyIsEnumerable(0) ? Object : function (it) {
  return cof(it) == 'String' ? it.split('') : Object(it);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_is-array.js":
/*!***************************************************!*\
  !*** ./node_modules/core-js/modules/_is-array.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 7.2.2 IsArray(argument)
var cof = __webpack_require__(/*! ./_cof */ "./node_modules/core-js/modules/_cof.js");
module.exports = Array.isArray || function isArray(arg) {
  return cof(arg) == 'Array';
};


/***/ }),

/***/ "./node_modules/core-js/modules/_is-object.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_is-object.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function (it) {
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};


/***/ }),

/***/ "./node_modules/core-js/modules/_is-regexp.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_is-regexp.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 7.2.8 IsRegExp(argument)
var isObject = __webpack_require__(/*! ./_is-object */ "./node_modules/core-js/modules/_is-object.js");
var cof = __webpack_require__(/*! ./_cof */ "./node_modules/core-js/modules/_cof.js");
var MATCH = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('match');
module.exports = function (it) {
  var isRegExp;
  return isObject(it) && ((isRegExp = it[MATCH]) !== undefined ? !!isRegExp : cof(it) == 'RegExp');
};


/***/ }),

/***/ "./node_modules/core-js/modules/_iter-create.js":
/*!******************************************************!*\
  !*** ./node_modules/core-js/modules/_iter-create.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var create = __webpack_require__(/*! ./_object-create */ "./node_modules/core-js/modules/_object-create.js");
var descriptor = __webpack_require__(/*! ./_property-desc */ "./node_modules/core-js/modules/_property-desc.js");
var setToStringTag = __webpack_require__(/*! ./_set-to-string-tag */ "./node_modules/core-js/modules/_set-to-string-tag.js");
var IteratorPrototype = {};

// 25.1.2.1.1 %IteratorPrototype%[@@iterator]()
__webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js")(IteratorPrototype, __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('iterator'), function () { return this; });

module.exports = function (Constructor, NAME, next) {
  Constructor.prototype = create(IteratorPrototype, { next: descriptor(1, next) });
  setToStringTag(Constructor, NAME + ' Iterator');
};


/***/ }),

/***/ "./node_modules/core-js/modules/_iter-define.js":
/*!******************************************************!*\
  !*** ./node_modules/core-js/modules/_iter-define.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var LIBRARY = __webpack_require__(/*! ./_library */ "./node_modules/core-js/modules/_library.js");
var $export = __webpack_require__(/*! ./_export */ "./node_modules/core-js/modules/_export.js");
var redefine = __webpack_require__(/*! ./_redefine */ "./node_modules/core-js/modules/_redefine.js");
var hide = __webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js");
var Iterators = __webpack_require__(/*! ./_iterators */ "./node_modules/core-js/modules/_iterators.js");
var $iterCreate = __webpack_require__(/*! ./_iter-create */ "./node_modules/core-js/modules/_iter-create.js");
var setToStringTag = __webpack_require__(/*! ./_set-to-string-tag */ "./node_modules/core-js/modules/_set-to-string-tag.js");
var getPrototypeOf = __webpack_require__(/*! ./_object-gpo */ "./node_modules/core-js/modules/_object-gpo.js");
var ITERATOR = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('iterator');
var BUGGY = !([].keys && 'next' in [].keys()); // Safari has buggy iterators w/o `next`
var FF_ITERATOR = '@@iterator';
var KEYS = 'keys';
var VALUES = 'values';

var returnThis = function () { return this; };

module.exports = function (Base, NAME, Constructor, next, DEFAULT, IS_SET, FORCED) {
  $iterCreate(Constructor, NAME, next);
  var getMethod = function (kind) {
    if (!BUGGY && kind in proto) return proto[kind];
    switch (kind) {
      case KEYS: return function keys() { return new Constructor(this, kind); };
      case VALUES: return function values() { return new Constructor(this, kind); };
    } return function entries() { return new Constructor(this, kind); };
  };
  var TAG = NAME + ' Iterator';
  var DEF_VALUES = DEFAULT == VALUES;
  var VALUES_BUG = false;
  var proto = Base.prototype;
  var $native = proto[ITERATOR] || proto[FF_ITERATOR] || DEFAULT && proto[DEFAULT];
  var $default = $native || getMethod(DEFAULT);
  var $entries = DEFAULT ? !DEF_VALUES ? $default : getMethod('entries') : undefined;
  var $anyNative = NAME == 'Array' ? proto.entries || $native : $native;
  var methods, key, IteratorPrototype;
  // Fix native
  if ($anyNative) {
    IteratorPrototype = getPrototypeOf($anyNative.call(new Base()));
    if (IteratorPrototype !== Object.prototype && IteratorPrototype.next) {
      // Set @@toStringTag to native iterators
      setToStringTag(IteratorPrototype, TAG, true);
      // fix for some old engines
      if (!LIBRARY && typeof IteratorPrototype[ITERATOR] != 'function') hide(IteratorPrototype, ITERATOR, returnThis);
    }
  }
  // fix Array#{values, @@iterator}.name in V8 / FF
  if (DEF_VALUES && $native && $native.name !== VALUES) {
    VALUES_BUG = true;
    $default = function values() { return $native.call(this); };
  }
  // Define iterator
  if ((!LIBRARY || FORCED) && (BUGGY || VALUES_BUG || !proto[ITERATOR])) {
    hide(proto, ITERATOR, $default);
  }
  // Plug for library
  Iterators[NAME] = $default;
  Iterators[TAG] = returnThis;
  if (DEFAULT) {
    methods = {
      values: DEF_VALUES ? $default : getMethod(VALUES),
      keys: IS_SET ? $default : getMethod(KEYS),
      entries: $entries
    };
    if (FORCED) for (key in methods) {
      if (!(key in proto)) redefine(proto, key, methods[key]);
    } else $export($export.P + $export.F * (BUGGY || VALUES_BUG), NAME, methods);
  }
  return methods;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_iter-step.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_iter-step.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function (done, value) {
  return { value: value, done: !!done };
};


/***/ }),

/***/ "./node_modules/core-js/modules/_iterators.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_iterators.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = {};


/***/ }),

/***/ "./node_modules/core-js/modules/_library.js":
/*!**************************************************!*\
  !*** ./node_modules/core-js/modules/_library.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = false;


/***/ }),

/***/ "./node_modules/core-js/modules/_object-create.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/modules/_object-create.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.2 / 15.2.3.5 Object.create(O [, Properties])
var anObject = __webpack_require__(/*! ./_an-object */ "./node_modules/core-js/modules/_an-object.js");
var dPs = __webpack_require__(/*! ./_object-dps */ "./node_modules/core-js/modules/_object-dps.js");
var enumBugKeys = __webpack_require__(/*! ./_enum-bug-keys */ "./node_modules/core-js/modules/_enum-bug-keys.js");
var IE_PROTO = __webpack_require__(/*! ./_shared-key */ "./node_modules/core-js/modules/_shared-key.js")('IE_PROTO');
var Empty = function () { /* empty */ };
var PROTOTYPE = 'prototype';

// Create object with fake `null` prototype: use iframe Object with cleared prototype
var createDict = function () {
  // Thrash, waste and sodomy: IE GC bug
  var iframe = __webpack_require__(/*! ./_dom-create */ "./node_modules/core-js/modules/_dom-create.js")('iframe');
  var i = enumBugKeys.length;
  var lt = '<';
  var gt = '>';
  var iframeDocument;
  iframe.style.display = 'none';
  __webpack_require__(/*! ./_html */ "./node_modules/core-js/modules/_html.js").appendChild(iframe);
  iframe.src = 'javascript:'; // eslint-disable-line no-script-url
  // createDict = iframe.contentWindow.Object;
  // html.removeChild(iframe);
  iframeDocument = iframe.contentWindow.document;
  iframeDocument.open();
  iframeDocument.write(lt + 'script' + gt + 'document.F=Object' + lt + '/script' + gt);
  iframeDocument.close();
  createDict = iframeDocument.F;
  while (i--) delete createDict[PROTOTYPE][enumBugKeys[i]];
  return createDict();
};

module.exports = Object.create || function create(O, Properties) {
  var result;
  if (O !== null) {
    Empty[PROTOTYPE] = anObject(O);
    result = new Empty();
    Empty[PROTOTYPE] = null;
    // add "__proto__" for Object.getPrototypeOf polyfill
    result[IE_PROTO] = O;
  } else result = createDict();
  return Properties === undefined ? result : dPs(result, Properties);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_object-dp.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_object-dp.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var anObject = __webpack_require__(/*! ./_an-object */ "./node_modules/core-js/modules/_an-object.js");
var IE8_DOM_DEFINE = __webpack_require__(/*! ./_ie8-dom-define */ "./node_modules/core-js/modules/_ie8-dom-define.js");
var toPrimitive = __webpack_require__(/*! ./_to-primitive */ "./node_modules/core-js/modules/_to-primitive.js");
var dP = Object.defineProperty;

exports.f = __webpack_require__(/*! ./_descriptors */ "./node_modules/core-js/modules/_descriptors.js") ? Object.defineProperty : function defineProperty(O, P, Attributes) {
  anObject(O);
  P = toPrimitive(P, true);
  anObject(Attributes);
  if (IE8_DOM_DEFINE) try {
    return dP(O, P, Attributes);
  } catch (e) { /* empty */ }
  if ('get' in Attributes || 'set' in Attributes) throw TypeError('Accessors not supported!');
  if ('value' in Attributes) O[P] = Attributes.value;
  return O;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_object-dps.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_object-dps.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(/*! ./_object-dp */ "./node_modules/core-js/modules/_object-dp.js");
var anObject = __webpack_require__(/*! ./_an-object */ "./node_modules/core-js/modules/_an-object.js");
var getKeys = __webpack_require__(/*! ./_object-keys */ "./node_modules/core-js/modules/_object-keys.js");

module.exports = __webpack_require__(/*! ./_descriptors */ "./node_modules/core-js/modules/_descriptors.js") ? Object.defineProperties : function defineProperties(O, Properties) {
  anObject(O);
  var keys = getKeys(Properties);
  var length = keys.length;
  var i = 0;
  var P;
  while (length > i) dP.f(O, P = keys[i++], Properties[P]);
  return O;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_object-gpo.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_object-gpo.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.9 / 15.2.3.2 Object.getPrototypeOf(O)
var has = __webpack_require__(/*! ./_has */ "./node_modules/core-js/modules/_has.js");
var toObject = __webpack_require__(/*! ./_to-object */ "./node_modules/core-js/modules/_to-object.js");
var IE_PROTO = __webpack_require__(/*! ./_shared-key */ "./node_modules/core-js/modules/_shared-key.js")('IE_PROTO');
var ObjectProto = Object.prototype;

module.exports = Object.getPrototypeOf || function (O) {
  O = toObject(O);
  if (has(O, IE_PROTO)) return O[IE_PROTO];
  if (typeof O.constructor == 'function' && O instanceof O.constructor) {
    return O.constructor.prototype;
  } return O instanceof Object ? ObjectProto : null;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_object-keys-internal.js":
/*!***************************************************************!*\
  !*** ./node_modules/core-js/modules/_object-keys-internal.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var has = __webpack_require__(/*! ./_has */ "./node_modules/core-js/modules/_has.js");
var toIObject = __webpack_require__(/*! ./_to-iobject */ "./node_modules/core-js/modules/_to-iobject.js");
var arrayIndexOf = __webpack_require__(/*! ./_array-includes */ "./node_modules/core-js/modules/_array-includes.js")(false);
var IE_PROTO = __webpack_require__(/*! ./_shared-key */ "./node_modules/core-js/modules/_shared-key.js")('IE_PROTO');

module.exports = function (object, names) {
  var O = toIObject(object);
  var i = 0;
  var result = [];
  var key;
  for (key in O) if (key != IE_PROTO) has(O, key) && result.push(key);
  // Don't enum bug & hidden keys
  while (names.length > i) if (has(O, key = names[i++])) {
    ~arrayIndexOf(result, key) || result.push(key);
  }
  return result;
};


/***/ }),

/***/ "./node_modules/core-js/modules/_object-keys.js":
/*!******************************************************!*\
  !*** ./node_modules/core-js/modules/_object-keys.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.14 / 15.2.3.14 Object.keys(O)
var $keys = __webpack_require__(/*! ./_object-keys-internal */ "./node_modules/core-js/modules/_object-keys-internal.js");
var enumBugKeys = __webpack_require__(/*! ./_enum-bug-keys */ "./node_modules/core-js/modules/_enum-bug-keys.js");

module.exports = Object.keys || function keys(O) {
  return $keys(O, enumBugKeys);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_object-sap.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_object-sap.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// most Object methods by ES6 should accept primitives
var $export = __webpack_require__(/*! ./_export */ "./node_modules/core-js/modules/_export.js");
var core = __webpack_require__(/*! ./_core */ "./node_modules/core-js/modules/_core.js");
var fails = __webpack_require__(/*! ./_fails */ "./node_modules/core-js/modules/_fails.js");
module.exports = function (KEY, exec) {
  var fn = (core.Object || {})[KEY] || Object[KEY];
  var exp = {};
  exp[KEY] = exec(fn);
  $export($export.S + $export.F * fails(function () { fn(1); }), 'Object', exp);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_property-desc.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/modules/_property-desc.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function (bitmap, value) {
  return {
    enumerable: !(bitmap & 1),
    configurable: !(bitmap & 2),
    writable: !(bitmap & 4),
    value: value
  };
};


/***/ }),

/***/ "./node_modules/core-js/modules/_redefine.js":
/*!***************************************************!*\
  !*** ./node_modules/core-js/modules/_redefine.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js");
var hide = __webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js");
var has = __webpack_require__(/*! ./_has */ "./node_modules/core-js/modules/_has.js");
var SRC = __webpack_require__(/*! ./_uid */ "./node_modules/core-js/modules/_uid.js")('src');
var $toString = __webpack_require__(/*! ./_function-to-string */ "./node_modules/core-js/modules/_function-to-string.js");
var TO_STRING = 'toString';
var TPL = ('' + $toString).split(TO_STRING);

__webpack_require__(/*! ./_core */ "./node_modules/core-js/modules/_core.js").inspectSource = function (it) {
  return $toString.call(it);
};

(module.exports = function (O, key, val, safe) {
  var isFunction = typeof val == 'function';
  if (isFunction) has(val, 'name') || hide(val, 'name', key);
  if (O[key] === val) return;
  if (isFunction) has(val, SRC) || hide(val, SRC, O[key] ? '' + O[key] : TPL.join(String(key)));
  if (O === global) {
    O[key] = val;
  } else if (!safe) {
    delete O[key];
    hide(O, key, val);
  } else if (O[key]) {
    O[key] = val;
  } else {
    hide(O, key, val);
  }
// add fake Function#toString for correct work wrapped methods / constructors with methods like LoDash isNative
})(Function.prototype, TO_STRING, function toString() {
  return typeof this == 'function' && this[SRC] || $toString.call(this);
});


/***/ }),

/***/ "./node_modules/core-js/modules/_regexp-exec-abstract.js":
/*!***************************************************************!*\
  !*** ./node_modules/core-js/modules/_regexp-exec-abstract.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var classof = __webpack_require__(/*! ./_classof */ "./node_modules/core-js/modules/_classof.js");
var builtinExec = RegExp.prototype.exec;

 // `RegExpExec` abstract operation
// https://tc39.github.io/ecma262/#sec-regexpexec
module.exports = function (R, S) {
  var exec = R.exec;
  if (typeof exec === 'function') {
    var result = exec.call(R, S);
    if (typeof result !== 'object') {
      throw new TypeError('RegExp exec method returned something other than an Object or null');
    }
    return result;
  }
  if (classof(R) !== 'RegExp') {
    throw new TypeError('RegExp#exec called on incompatible receiver');
  }
  return builtinExec.call(R, S);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_regexp-exec.js":
/*!******************************************************!*\
  !*** ./node_modules/core-js/modules/_regexp-exec.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var regexpFlags = __webpack_require__(/*! ./_flags */ "./node_modules/core-js/modules/_flags.js");

var nativeExec = RegExp.prototype.exec;
// This always refers to the native implementation, because the
// String#replace polyfill uses ./fix-regexp-well-known-symbol-logic.js,
// which loads this file before patching the method.
var nativeReplace = String.prototype.replace;

var patchedExec = nativeExec;

var LAST_INDEX = 'lastIndex';

var UPDATES_LAST_INDEX_WRONG = (function () {
  var re1 = /a/,
      re2 = /b*/g;
  nativeExec.call(re1, 'a');
  nativeExec.call(re2, 'a');
  return re1[LAST_INDEX] !== 0 || re2[LAST_INDEX] !== 0;
})();

// nonparticipating capturing group, copied from es5-shim's String#split patch.
var NPCG_INCLUDED = /()??/.exec('')[1] !== undefined;

var PATCH = UPDATES_LAST_INDEX_WRONG || NPCG_INCLUDED;

if (PATCH) {
  patchedExec = function exec(str) {
    var re = this;
    var lastIndex, reCopy, match, i;

    if (NPCG_INCLUDED) {
      reCopy = new RegExp('^' + re.source + '$(?!\\s)', regexpFlags.call(re));
    }
    if (UPDATES_LAST_INDEX_WRONG) lastIndex = re[LAST_INDEX];

    match = nativeExec.call(re, str);

    if (UPDATES_LAST_INDEX_WRONG && match) {
      re[LAST_INDEX] = re.global ? match.index + match[0].length : lastIndex;
    }
    if (NPCG_INCLUDED && match && match.length > 1) {
      // Fix browsers whose `exec` methods don't consistently return `undefined`
      // for NPCG, like IE8. NOTE: This doesn' work for /(.?)?/
      // eslint-disable-next-line no-loop-func
      nativeReplace.call(match[0], reCopy, function () {
        for (i = 1; i < arguments.length - 2; i++) {
          if (arguments[i] === undefined) match[i] = undefined;
        }
      });
    }

    return match;
  };
}

module.exports = patchedExec;


/***/ }),

/***/ "./node_modules/core-js/modules/_set-to-string-tag.js":
/*!************************************************************!*\
  !*** ./node_modules/core-js/modules/_set-to-string-tag.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var def = __webpack_require__(/*! ./_object-dp */ "./node_modules/core-js/modules/_object-dp.js").f;
var has = __webpack_require__(/*! ./_has */ "./node_modules/core-js/modules/_has.js");
var TAG = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('toStringTag');

module.exports = function (it, tag, stat) {
  if (it && !has(it = stat ? it : it.prototype, TAG)) def(it, TAG, { configurable: true, value: tag });
};


/***/ }),

/***/ "./node_modules/core-js/modules/_shared-key.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_shared-key.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var shared = __webpack_require__(/*! ./_shared */ "./node_modules/core-js/modules/_shared.js")('keys');
var uid = __webpack_require__(/*! ./_uid */ "./node_modules/core-js/modules/_uid.js");
module.exports = function (key) {
  return shared[key] || (shared[key] = uid(key));
};


/***/ }),

/***/ "./node_modules/core-js/modules/_shared.js":
/*!*************************************************!*\
  !*** ./node_modules/core-js/modules/_shared.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var core = __webpack_require__(/*! ./_core */ "./node_modules/core-js/modules/_core.js");
var global = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js");
var SHARED = '__core-js_shared__';
var store = global[SHARED] || (global[SHARED] = {});

(module.exports = function (key, value) {
  return store[key] || (store[key] = value !== undefined ? value : {});
})('versions', []).push({
  version: core.version,
  mode: __webpack_require__(/*! ./_library */ "./node_modules/core-js/modules/_library.js") ? 'pure' : 'global',
  copyright: '© 2019 Denis Pushkarev (zloirock.ru)'
});


/***/ }),

/***/ "./node_modules/core-js/modules/_string-at.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_string-at.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var toInteger = __webpack_require__(/*! ./_to-integer */ "./node_modules/core-js/modules/_to-integer.js");
var defined = __webpack_require__(/*! ./_defined */ "./node_modules/core-js/modules/_defined.js");
// true  -> String#at
// false -> String#codePointAt
module.exports = function (TO_STRING) {
  return function (that, pos) {
    var s = String(defined(that));
    var i = toInteger(pos);
    var l = s.length;
    var a, b;
    if (i < 0 || i >= l) return TO_STRING ? '' : undefined;
    a = s.charCodeAt(i);
    return a < 0xd800 || a > 0xdbff || i + 1 === l || (b = s.charCodeAt(i + 1)) < 0xdc00 || b > 0xdfff
      ? TO_STRING ? s.charAt(i) : a
      : TO_STRING ? s.slice(i, i + 2) : (a - 0xd800 << 10) + (b - 0xdc00) + 0x10000;
  };
};


/***/ }),

/***/ "./node_modules/core-js/modules/_string-context.js":
/*!*********************************************************!*\
  !*** ./node_modules/core-js/modules/_string-context.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// helper for String#{startsWith, endsWith, includes}
var isRegExp = __webpack_require__(/*! ./_is-regexp */ "./node_modules/core-js/modules/_is-regexp.js");
var defined = __webpack_require__(/*! ./_defined */ "./node_modules/core-js/modules/_defined.js");

module.exports = function (that, searchString, NAME) {
  if (isRegExp(searchString)) throw TypeError('String#' + NAME + " doesn't accept regex!");
  return String(defined(that));
};


/***/ }),

/***/ "./node_modules/core-js/modules/_to-absolute-index.js":
/*!************************************************************!*\
  !*** ./node_modules/core-js/modules/_to-absolute-index.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var toInteger = __webpack_require__(/*! ./_to-integer */ "./node_modules/core-js/modules/_to-integer.js");
var max = Math.max;
var min = Math.min;
module.exports = function (index, length) {
  index = toInteger(index);
  return index < 0 ? max(index + length, 0) : min(index, length);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_to-integer.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_to-integer.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// 7.1.4 ToInteger
var ceil = Math.ceil;
var floor = Math.floor;
module.exports = function (it) {
  return isNaN(it = +it) ? 0 : (it > 0 ? floor : ceil)(it);
};


/***/ }),

/***/ "./node_modules/core-js/modules/_to-iobject.js":
/*!*****************************************************!*\
  !*** ./node_modules/core-js/modules/_to-iobject.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// to indexed object, toObject with fallback for non-array-like ES3 strings
var IObject = __webpack_require__(/*! ./_iobject */ "./node_modules/core-js/modules/_iobject.js");
var defined = __webpack_require__(/*! ./_defined */ "./node_modules/core-js/modules/_defined.js");
module.exports = function (it) {
  return IObject(defined(it));
};


/***/ }),

/***/ "./node_modules/core-js/modules/_to-length.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_to-length.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.15 ToLength
var toInteger = __webpack_require__(/*! ./_to-integer */ "./node_modules/core-js/modules/_to-integer.js");
var min = Math.min;
module.exports = function (it) {
  return it > 0 ? min(toInteger(it), 0x1fffffffffffff) : 0; // pow(2, 53) - 1 == 9007199254740991
};


/***/ }),

/***/ "./node_modules/core-js/modules/_to-object.js":
/*!****************************************************!*\
  !*** ./node_modules/core-js/modules/_to-object.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.13 ToObject(argument)
var defined = __webpack_require__(/*! ./_defined */ "./node_modules/core-js/modules/_defined.js");
module.exports = function (it) {
  return Object(defined(it));
};


/***/ }),

/***/ "./node_modules/core-js/modules/_to-primitive.js":
/*!*******************************************************!*\
  !*** ./node_modules/core-js/modules/_to-primitive.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.1 ToPrimitive(input [, PreferredType])
var isObject = __webpack_require__(/*! ./_is-object */ "./node_modules/core-js/modules/_is-object.js");
// instead of the ES6 spec version, we didn't implement @@toPrimitive case
// and the second argument - flag - preferred type is a string
module.exports = function (it, S) {
  if (!isObject(it)) return it;
  var fn, val;
  if (S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  if (typeof (fn = it.valueOf) == 'function' && !isObject(val = fn.call(it))) return val;
  if (!S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  throw TypeError("Can't convert object to primitive value");
};


/***/ }),

/***/ "./node_modules/core-js/modules/_uid.js":
/*!**********************************************!*\
  !*** ./node_modules/core-js/modules/_uid.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var id = 0;
var px = Math.random();
module.exports = function (key) {
  return 'Symbol('.concat(key === undefined ? '' : key, ')_', (++id + px).toString(36));
};


/***/ }),

/***/ "./node_modules/core-js/modules/_wks.js":
/*!**********************************************!*\
  !*** ./node_modules/core-js/modules/_wks.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var store = __webpack_require__(/*! ./_shared */ "./node_modules/core-js/modules/_shared.js")('wks');
var uid = __webpack_require__(/*! ./_uid */ "./node_modules/core-js/modules/_uid.js");
var Symbol = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js").Symbol;
var USE_SYMBOL = typeof Symbol == 'function';

var $exports = module.exports = function (name) {
  return store[name] || (store[name] =
    USE_SYMBOL && Symbol[name] || (USE_SYMBOL ? Symbol : uid)('Symbol.' + name));
};

$exports.store = store;


/***/ }),

/***/ "./node_modules/core-js/modules/es6.array.find.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/modules/es6.array.find.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// 22.1.3.8 Array.prototype.find(predicate, thisArg = undefined)
var $export = __webpack_require__(/*! ./_export */ "./node_modules/core-js/modules/_export.js");
var $find = __webpack_require__(/*! ./_array-methods */ "./node_modules/core-js/modules/_array-methods.js")(5);
var KEY = 'find';
var forced = true;
// Shouldn't skip holes
if (KEY in []) Array(1)[KEY](function () { forced = false; });
$export($export.P + $export.F * forced, 'Array', {
  find: function find(callbackfn /* , that = undefined */) {
    return $find(this, callbackfn, arguments.length > 1 ? arguments[1] : undefined);
  }
});
__webpack_require__(/*! ./_add-to-unscopables */ "./node_modules/core-js/modules/_add-to-unscopables.js")(KEY);


/***/ }),

/***/ "./node_modules/core-js/modules/es6.array.iterator.js":
/*!************************************************************!*\
  !*** ./node_modules/core-js/modules/es6.array.iterator.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var addToUnscopables = __webpack_require__(/*! ./_add-to-unscopables */ "./node_modules/core-js/modules/_add-to-unscopables.js");
var step = __webpack_require__(/*! ./_iter-step */ "./node_modules/core-js/modules/_iter-step.js");
var Iterators = __webpack_require__(/*! ./_iterators */ "./node_modules/core-js/modules/_iterators.js");
var toIObject = __webpack_require__(/*! ./_to-iobject */ "./node_modules/core-js/modules/_to-iobject.js");

// 22.1.3.4 Array.prototype.entries()
// 22.1.3.13 Array.prototype.keys()
// 22.1.3.29 Array.prototype.values()
// 22.1.3.30 Array.prototype[@@iterator]()
module.exports = __webpack_require__(/*! ./_iter-define */ "./node_modules/core-js/modules/_iter-define.js")(Array, 'Array', function (iterated, kind) {
  this._t = toIObject(iterated); // target
  this._i = 0;                   // next index
  this._k = kind;                // kind
// 22.1.5.2.1 %ArrayIteratorPrototype%.next()
}, function () {
  var O = this._t;
  var kind = this._k;
  var index = this._i++;
  if (!O || index >= O.length) {
    this._t = undefined;
    return step(1);
  }
  if (kind == 'keys') return step(0, index);
  if (kind == 'values') return step(0, O[index]);
  return step(0, [index, O[index]]);
}, 'values');

// argumentsList[@@iterator] is %ArrayProto_values% (9.4.4.6, 9.4.4.7)
Iterators.Arguments = Iterators.Array;

addToUnscopables('keys');
addToUnscopables('values');
addToUnscopables('entries');


/***/ }),

/***/ "./node_modules/core-js/modules/es6.function.name.js":
/*!***********************************************************!*\
  !*** ./node_modules/core-js/modules/es6.function.name.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(/*! ./_object-dp */ "./node_modules/core-js/modules/_object-dp.js").f;
var FProto = Function.prototype;
var nameRE = /^\s*function ([^ (]*)/;
var NAME = 'name';

// 19.2.4.2 name
NAME in FProto || __webpack_require__(/*! ./_descriptors */ "./node_modules/core-js/modules/_descriptors.js") && dP(FProto, NAME, {
  configurable: true,
  get: function () {
    try {
      return ('' + this).match(nameRE)[1];
    } catch (e) {
      return '';
    }
  }
});


/***/ }),

/***/ "./node_modules/core-js/modules/es6.object.keys.js":
/*!*********************************************************!*\
  !*** ./node_modules/core-js/modules/es6.object.keys.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.14 Object.keys(O)
var toObject = __webpack_require__(/*! ./_to-object */ "./node_modules/core-js/modules/_to-object.js");
var $keys = __webpack_require__(/*! ./_object-keys */ "./node_modules/core-js/modules/_object-keys.js");

__webpack_require__(/*! ./_object-sap */ "./node_modules/core-js/modules/_object-sap.js")('keys', function () {
  return function keys(it) {
    return $keys(toObject(it));
  };
});


/***/ }),

/***/ "./node_modules/core-js/modules/es6.object.to-string.js":
/*!**************************************************************!*\
  !*** ./node_modules/core-js/modules/es6.object.to-string.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// 19.1.3.6 Object.prototype.toString()
var classof = __webpack_require__(/*! ./_classof */ "./node_modules/core-js/modules/_classof.js");
var test = {};
test[__webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js")('toStringTag')] = 'z';
if (test + '' != '[object z]') {
  __webpack_require__(/*! ./_redefine */ "./node_modules/core-js/modules/_redefine.js")(Object.prototype, 'toString', function toString() {
    return '[object ' + classof(this) + ']';
  }, true);
}


/***/ }),

/***/ "./node_modules/core-js/modules/es6.regexp.exec.js":
/*!*********************************************************!*\
  !*** ./node_modules/core-js/modules/es6.regexp.exec.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var regexpExec = __webpack_require__(/*! ./_regexp-exec */ "./node_modules/core-js/modules/_regexp-exec.js");
__webpack_require__(/*! ./_export */ "./node_modules/core-js/modules/_export.js")({
  target: 'RegExp',
  proto: true,
  forced: regexpExec !== /./.exec
}, {
  exec: regexpExec
});


/***/ }),

/***/ "./node_modules/core-js/modules/es6.regexp.replace.js":
/*!************************************************************!*\
  !*** ./node_modules/core-js/modules/es6.regexp.replace.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var anObject = __webpack_require__(/*! ./_an-object */ "./node_modules/core-js/modules/_an-object.js");
var toObject = __webpack_require__(/*! ./_to-object */ "./node_modules/core-js/modules/_to-object.js");
var toLength = __webpack_require__(/*! ./_to-length */ "./node_modules/core-js/modules/_to-length.js");
var toInteger = __webpack_require__(/*! ./_to-integer */ "./node_modules/core-js/modules/_to-integer.js");
var advanceStringIndex = __webpack_require__(/*! ./_advance-string-index */ "./node_modules/core-js/modules/_advance-string-index.js");
var regExpExec = __webpack_require__(/*! ./_regexp-exec-abstract */ "./node_modules/core-js/modules/_regexp-exec-abstract.js");
var max = Math.max;
var min = Math.min;
var floor = Math.floor;
var SUBSTITUTION_SYMBOLS = /\$([$&`']|\d\d?|<[^>]*>)/g;
var SUBSTITUTION_SYMBOLS_NO_NAMED = /\$([$&`']|\d\d?)/g;

var maybeToString = function (it) {
  return it === undefined ? it : String(it);
};

// @@replace logic
__webpack_require__(/*! ./_fix-re-wks */ "./node_modules/core-js/modules/_fix-re-wks.js")('replace', 2, function (defined, REPLACE, $replace, maybeCallNative) {
  return [
    // `String.prototype.replace` method
    // https://tc39.github.io/ecma262/#sec-string.prototype.replace
    function replace(searchValue, replaceValue) {
      var O = defined(this);
      var fn = searchValue == undefined ? undefined : searchValue[REPLACE];
      return fn !== undefined
        ? fn.call(searchValue, O, replaceValue)
        : $replace.call(String(O), searchValue, replaceValue);
    },
    // `RegExp.prototype[@@replace]` method
    // https://tc39.github.io/ecma262/#sec-regexp.prototype-@@replace
    function (regexp, replaceValue) {
      var res = maybeCallNative($replace, regexp, this, replaceValue);
      if (res.done) return res.value;

      var rx = anObject(regexp);
      var S = String(this);
      var functionalReplace = typeof replaceValue === 'function';
      if (!functionalReplace) replaceValue = String(replaceValue);
      var global = rx.global;
      if (global) {
        var fullUnicode = rx.unicode;
        rx.lastIndex = 0;
      }
      var results = [];
      while (true) {
        var result = regExpExec(rx, S);
        if (result === null) break;
        results.push(result);
        if (!global) break;
        var matchStr = String(result[0]);
        if (matchStr === '') rx.lastIndex = advanceStringIndex(S, toLength(rx.lastIndex), fullUnicode);
      }
      var accumulatedResult = '';
      var nextSourcePosition = 0;
      for (var i = 0; i < results.length; i++) {
        result = results[i];
        var matched = String(result[0]);
        var position = max(min(toInteger(result.index), S.length), 0);
        var captures = [];
        // NOTE: This is equivalent to
        //   captures = result.slice(1).map(maybeToString)
        // but for some reason `nativeSlice.call(result, 1, result.length)` (called in
        // the slice polyfill when slicing native arrays) "doesn't work" in safari 9 and
        // causes a crash (https://pastebin.com/N21QzeQA) when trying to debug it.
        for (var j = 1; j < result.length; j++) captures.push(maybeToString(result[j]));
        var namedCaptures = result.groups;
        if (functionalReplace) {
          var replacerArgs = [matched].concat(captures, position, S);
          if (namedCaptures !== undefined) replacerArgs.push(namedCaptures);
          var replacement = String(replaceValue.apply(undefined, replacerArgs));
        } else {
          replacement = getSubstitution(matched, S, position, captures, namedCaptures, replaceValue);
        }
        if (position >= nextSourcePosition) {
          accumulatedResult += S.slice(nextSourcePosition, position) + replacement;
          nextSourcePosition = position + matched.length;
        }
      }
      return accumulatedResult + S.slice(nextSourcePosition);
    }
  ];

    // https://tc39.github.io/ecma262/#sec-getsubstitution
  function getSubstitution(matched, str, position, captures, namedCaptures, replacement) {
    var tailPos = position + matched.length;
    var m = captures.length;
    var symbols = SUBSTITUTION_SYMBOLS_NO_NAMED;
    if (namedCaptures !== undefined) {
      namedCaptures = toObject(namedCaptures);
      symbols = SUBSTITUTION_SYMBOLS;
    }
    return $replace.call(replacement, symbols, function (match, ch) {
      var capture;
      switch (ch.charAt(0)) {
        case '$': return '$';
        case '&': return matched;
        case '`': return str.slice(0, position);
        case "'": return str.slice(tailPos);
        case '<':
          capture = namedCaptures[ch.slice(1, -1)];
          break;
        default: // \d\d?
          var n = +ch;
          if (n === 0) return match;
          if (n > m) {
            var f = floor(n / 10);
            if (f === 0) return match;
            if (f <= m) return captures[f - 1] === undefined ? ch.charAt(1) : captures[f - 1] + ch.charAt(1);
            return match;
          }
          capture = captures[n - 1];
      }
      return capture === undefined ? '' : capture;
    });
  }
});


/***/ }),

/***/ "./node_modules/core-js/modules/es6.string.includes.js":
/*!*************************************************************!*\
  !*** ./node_modules/core-js/modules/es6.string.includes.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
// 21.1.3.7 String.prototype.includes(searchString, position = 0)

var $export = __webpack_require__(/*! ./_export */ "./node_modules/core-js/modules/_export.js");
var context = __webpack_require__(/*! ./_string-context */ "./node_modules/core-js/modules/_string-context.js");
var INCLUDES = 'includes';

$export($export.P + $export.F * __webpack_require__(/*! ./_fails-is-regexp */ "./node_modules/core-js/modules/_fails-is-regexp.js")(INCLUDES), 'String', {
  includes: function includes(searchString /* , position = 0 */) {
    return !!~context(this, searchString, INCLUDES)
      .indexOf(searchString, arguments.length > 1 ? arguments[1] : undefined);
  }
});


/***/ }),

/***/ "./node_modules/core-js/modules/es7.array.includes.js":
/*!************************************************************!*\
  !*** ./node_modules/core-js/modules/es7.array.includes.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// https://github.com/tc39/Array.prototype.includes
var $export = __webpack_require__(/*! ./_export */ "./node_modules/core-js/modules/_export.js");
var $includes = __webpack_require__(/*! ./_array-includes */ "./node_modules/core-js/modules/_array-includes.js")(true);

$export($export.P, 'Array', {
  includes: function includes(el /* , fromIndex = 0 */) {
    return $includes(this, el, arguments.length > 1 ? arguments[1] : undefined);
  }
});

__webpack_require__(/*! ./_add-to-unscopables */ "./node_modules/core-js/modules/_add-to-unscopables.js")('includes');


/***/ }),

/***/ "./node_modules/core-js/modules/web.dom.iterable.js":
/*!**********************************************************!*\
  !*** ./node_modules/core-js/modules/web.dom.iterable.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var $iterators = __webpack_require__(/*! ./es6.array.iterator */ "./node_modules/core-js/modules/es6.array.iterator.js");
var getKeys = __webpack_require__(/*! ./_object-keys */ "./node_modules/core-js/modules/_object-keys.js");
var redefine = __webpack_require__(/*! ./_redefine */ "./node_modules/core-js/modules/_redefine.js");
var global = __webpack_require__(/*! ./_global */ "./node_modules/core-js/modules/_global.js");
var hide = __webpack_require__(/*! ./_hide */ "./node_modules/core-js/modules/_hide.js");
var Iterators = __webpack_require__(/*! ./_iterators */ "./node_modules/core-js/modules/_iterators.js");
var wks = __webpack_require__(/*! ./_wks */ "./node_modules/core-js/modules/_wks.js");
var ITERATOR = wks('iterator');
var TO_STRING_TAG = wks('toStringTag');
var ArrayValues = Iterators.Array;

var DOMIterables = {
  CSSRuleList: true, // TODO: Not spec compliant, should be false.
  CSSStyleDeclaration: false,
  CSSValueList: false,
  ClientRectList: false,
  DOMRectList: false,
  DOMStringList: false,
  DOMTokenList: true,
  DataTransferItemList: false,
  FileList: false,
  HTMLAllCollection: false,
  HTMLCollection: false,
  HTMLFormElement: false,
  HTMLSelectElement: false,
  MediaList: true, // TODO: Not spec compliant, should be false.
  MimeTypeArray: false,
  NamedNodeMap: false,
  NodeList: true,
  PaintRequestList: false,
  Plugin: false,
  PluginArray: false,
  SVGLengthList: false,
  SVGNumberList: false,
  SVGPathSegList: false,
  SVGPointList: false,
  SVGStringList: false,
  SVGTransformList: false,
  SourceBufferList: false,
  StyleSheetList: true, // TODO: Not spec compliant, should be false.
  TextTrackCueList: false,
  TextTrackList: false,
  TouchList: false
};

for (var collections = getKeys(DOMIterables), i = 0; i < collections.length; i++) {
  var NAME = collections[i];
  var explicit = DOMIterables[NAME];
  var Collection = global[NAME];
  var proto = Collection && Collection.prototype;
  var key;
  if (proto) {
    if (!proto[ITERATOR]) hide(proto, ITERATOR, ArrayValues);
    if (!proto[TO_STRING_TAG]) hide(proto, TO_STRING_TAG, NAME);
    Iterators[NAME] = ArrayValues;
    if (explicit) for (key in $iterators) if (!proto[key]) redefine(proto, key, $iterators[key], true);
  }
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


/***/ })

/******/ });
//# sourceMappingURL=admin-page-columns.js.map