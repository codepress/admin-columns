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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/admin-page-columns.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/admin-page-columns.js":
/*!**********************************!*\
  !*** ./js/admin-page-columns.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _form = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/form */ "./js/admin/columns/form.js"));

var _modals = _interopRequireDefault(__webpack_require__(/*! ./modules/modals */ "./js/modules/modals.js"));

var _initiator = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/initiator */ "./js/admin/columns/initiator.js"));

var _modal = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/modal */ "./js/admin/columns/modal.js"));

var _menu = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/menu */ "./js/admin/columns/menu.js"));

var _feedback = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/feedback */ "./js/admin/columns/feedback.js"));

var _toggle = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/events/toggle */ "./js/admin/columns/events/toggle.js"));

var _remove = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/events/remove */ "./js/admin/columns/events/remove.js"));

var _clone = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/events/clone */ "./js/admin/columns/events/clone.js"));

var _refresh = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/events/refresh */ "./js/admin/columns/events/refresh.js"));

var _typeSelector = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/events/type-selector */ "./js/admin/columns/events/type-selector.js"));

var _indicator = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/events/indicator */ "./js/admin/columns/events/indicator.js"));

var _label = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/events/label */ "./js/admin/columns/events/label.js"));

var _addons = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/events/addons */ "./js/admin/columns/events/addons.js"));

var _imageSize = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/settings/image-size */ "./js/admin/columns/settings/image-size.js"));

var _subSettingToggle = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/settings/sub-setting-toggle */ "./js/admin/columns/settings/sub-setting-toggle.js"));

var _date = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/settings/date */ "./js/admin/columns/settings/date.js"));

var _pro = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/settings/pro */ "./js/admin/columns/settings/pro.js"));

var _width = _interopRequireDefault(__webpack_require__(/*! ./admin/columns/settings/width */ "./js/admin/columns/settings/width.js"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * AC variables. Defined in DOM.
 * @param AC {Object}
 * @param AC.list_screen {String}
 * @param AC.layout {String}
 * @param AC.i81n {String}
 */

/** Events */

/** Settings */
var jQuery = $ = __webpack_require__(/*! jquery */ "jquery");

AC.Column = new _initiator.default();
AC.Modals = new _modals.default();
document.addEventListener('AC_Form_Loaded', function () {
  /** Register Events **/
  AC.Column.registerEvent('toggle', _toggle.default).registerEvent('remove', _remove.default).registerEvent('clone', _clone.default).registerEvent('refresh', _refresh.default).registerEvent('type_selector', _typeSelector.default).registerEvent('indicator', _indicator.default).registerEvent('label', _label.default.label).registerEvent('label_setting', _label.default.setting).registerEvent('addons', _addons.default)
  /** Register Settings **/
  .registerSetting('date', _date.default).registerSetting('image_size', _imageSize.default).registerSetting('pro', _pro.default).registerSetting('sub_setting_toggle', _subSettingToggle.default).registerSetting('width', _width.default);
});
jQuery(document).ready(function () {
  AC.Form = new _form.default('#cpac .ac-columns form');
  AC.Modals.register(new _modal.default(document.querySelector('#ac-modal-pro')), 'pro');
  new _menu.default().init();
  new _feedback.default('.sidebox#direct-feedback');
});

/***/ }),

/***/ "./js/admin/columns/column.js":
/*!************************************!*\
  !*** ./js/admin/columns/column.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Column =
/*#__PURE__*/
function () {
  function Column($el) {
    _classCallCheck(this, Column);

    this.$el = $el;
    this.el = $el[0];
    this.settings = [];
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
      document.dispatchEvent(new CustomEvent('AC_Column_InitSettings', {
        detail: {
          column: self
        }
      }));
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
    key: "toggle",
    value: function toggle() {
      var duration = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 150;

      if (this.$el.hasClass('opened')) {
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
    }
  }, {
    key: "open",
    value: function open() {
      var duration = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
      this.$el.addClass('opened').find('.ac-column-body').slideDown(duration);
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
          action: 'ac_column_select',
          type: type,
          current_original_columns: AC.Form.originalColumns(),
          original_columns: AC.original_columns,
          list_screen: AC.list_screen,
          layout: AC.layout,
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
            document.dispatchEvent(new CustomEvent('AC_Column_Change', {
              detail: {
                column: self
              }
            }));
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
        action: 'ac_column_refresh',
        _ajax_nonce: AC._ajax_nonce,
        list_screen: AC.list_screen,
        layout: AC.layout,
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
            self.open();
            document.dispatchEvent(new CustomEvent('AC_Column_Refresh', {
              detail: {
                column: self
              }
            }));
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
      document.dispatchEvent(new CustomEvent('AC_Column_Created', {
        detail: {
          column: self
        }
      }));
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

module.exports = Column;

/***/ }),

/***/ "./js/admin/columns/events/addons.js":
/*!*******************************************!*\
  !*** ./js/admin/columns/events/addons.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/*
 * Optional Radio Click events
 * TODO: Is not used anymore?
 */
var addons = function addons(column) {
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

module.exports = addons;

/***/ }),

/***/ "./js/admin/columns/events/clone.js":
/*!******************************************!*\
  !*** ./js/admin/columns/events/clone.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


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

module.exports = clone;

/***/ }),

/***/ "./js/admin/columns/events/indicator.js":
/*!**********************************************!*\
  !*** ./js/admin/columns/events/indicator.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var indicator = function indicator(column) {
  var $column = column.$el;
  $column.find('.ac-column-header [data-indicator-toggle]').each(function () {
    var $indicator = $(this);
    var setting = $(this).data('setting');
    var $setting = $column.find('.ac-column-setting[data-setting=' + setting + ']');
    var $input = $setting.find('.col-input:first .ac-setting-input:first input[type=radio]');
    $indicator.unbind('click').on('click', function (e) {
      e.preventDefault();
      $indicator.toggleClass('on');

      if ($(this).hasClass('on')) {
        $input.filter('[value=on]').prop('checked', true).trigger('click').trigger('change');
      } else {
        $input.filter('[value=off]').prop('checked', true).trigger('click').trigger('change');
      }
    });
    $input.on('change', function () {
      var value = $input.filter(':checked').val();

      if ('on' === value) {
        $indicator.addClass('on');
      } else {
        $indicator.removeClass('on');
      }
    });
  });
};

module.exports = indicator;

/***/ }),

/***/ "./js/admin/columns/events/label.js":
/*!******************************************!*\
  !*** ./js/admin/columns/events/label.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var label = function label(column) {
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

var settingLabel = function settingLabel(column) {
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

module.exports = {
  label: label,
  setting: settingLabel
};

/***/ }),

/***/ "./js/admin/columns/events/refresh.js":
/*!********************************************!*\
  !*** ./js/admin/columns/events/refresh.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var refresh = function refresh(column) {
  column.$el.find('[data-refresh="column"]').on('change', function () {
    // Allow plugins to hook into this event
    $(document).trigger('AC.column.prerefresh', column.$el);
    column.$el.addClass('loading');
    setTimeout(function () {
      column.refresh().always(function () {
        column.$el.removeClass('loading');
      });
    }, 200);
  });
};

module.exports = refresh;

/***/ }),

/***/ "./js/admin/columns/events/remove.js":
/*!*******************************************!*\
  !*** ./js/admin/columns/events/remove.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


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

module.exports = remove;

/***/ }),

/***/ "./js/admin/columns/events/toggle.js":
/*!*******************************************!*\
  !*** ./js/admin/columns/events/toggle.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


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

module.exports = toggle;

/***/ }),

/***/ "./js/admin/columns/events/type-selector.js":
/*!**************************************************!*\
  !*** ./js/admin/columns/events/type-selector.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var selector = function selector(column) {
  column.$el.find('select.ac-setting-input_type').change(function () {
    column.$el.addClass('loading');
    column.switchToType($(this).val()).always(function () {
      column.$el.removeClass('loading');
      AC.Form.reindexColumns();
    });
  });
};

module.exports = selector;

/***/ }),

/***/ "./js/admin/columns/feedback.js":
/*!**************************************!*\
  !*** ./js/admin/columns/feedback.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Feedback =
/*#__PURE__*/
function () {
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

module.exports = Feedback;

/***/ }),

/***/ "./js/admin/columns/form.js":
/*!**********************************!*\
  !*** ./js/admin/columns/form.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _column = _interopRequireDefault(__webpack_require__(/*! ./column */ "./js/admin/columns/column.js"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Form =
/*#__PURE__*/
function () {
  function Form(el) {
    _classCallCheck(this, Form);

    this.$form = jQuery(el);
    this.$container = jQuery('#cpac .ac-admin');
    this.columns = {};
    document.dispatchEvent(new CustomEvent('AC_Form_Loaded'));
    this.init();
  }

  _createClass(Form, [{
    key: "init",
    value: function init() {
      this.initColumns();
      this.bindFormEvents();
      this.bindOrdering();
      document.dispatchEvent(new CustomEvent('AC_Form_Ready'));
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
    key: "bindFormEvents",
    value: function bindFormEvents() {
      var self = this;
      var $buttons = jQuery('.sidebox a.submit, .column-footer a.submit');
      $buttons.on('click', function () {
        $buttons.attr('disabled', 'disabled');
        self.submitForm().always(function () {
          $buttons.removeAttr('disabled', 'disabled');
        });
      });
      self.$container.find('.add_column').on('click', function () {
        self.addColumn();
      });
      var $boxes = jQuery('#cpac .ac-boxes');

      if ($boxes.hasClass('disabled')) {
        $boxes.find('.ac-column').each(function (i, col) {
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
      self.columns = [];
      this.$form.find('.ac-column').each(function () {
        var $el = jQuery(this);
        var column = new _column.default($el);
        column.bindEvents();
        $el.data('column', column);
        self.columns[column.name] = column;
      });
    }
  }, {
    key: "reindexColumns",
    value: function reindexColumns() {
      var self = this;
      self.columns = [];
      this.$form.find('.ac-column').each(function () {
        var column = jQuery(this).data('column');
        self.columns[column.name] = column;
      });
    }
  }, {
    key: "resetColumns",
    value: function resetColumns() {
      var _this = this;

      Object.keys(this.columns).forEach(function (key) {
        var column = _this.columns[key];
        column.destroy();
      });
    }
  }, {
    key: "serialize",
    value: function serialize() {
      return this.$form.serialize();
    }
  }, {
    key: "submitForm",
    value: function submitForm() {
      var self = this;
      var xhr = jQuery.post(ajaxurl, {
        action: 'ac_columns_save',
        data: this.serialize(),
        _ajax_nonce: AC._ajax_nonce,
        list_screen: AC.list_screen,
        layout: AC.layout,
        original_columns: AC.original_columns
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

      xhr.fail(function (error) {// We choose not to notify the user of errors, because the settings will have
        // been saved correctly despite of PHP notices/errors from plugin or themes.
      });
      document.dispatchEvent(new CustomEvent('AC_Form_AfterUpdate', {
        detail: {
          container: self.$container
        }
      }));
      return xhr;
    }
  }, {
    key: "showMessage",
    value: function showMessage(message) {
      var attr_class = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'updated';
      var $msg = jQuery('<div class="ac-message hidden ' + attr_class + '"><p>' + message + '</p></div>');
      this.$container.find('.ac-message').stop().remove();
      this.$container.find('.ac-boxes').before($msg);
      $msg.slideDown();
    }
  }, {
    key: "cloneColumn",
    value: function cloneColumn($el) {
      return this._addColumnToForm(new _column.default($el).clone(), $el.hasClass('opened'));
    }
  }, {
    key: "addColumn",
    value: function addColumn() {
      var $clone = jQuery('#add-new-column-template').find('.ac-column').clone();
      var column = new _column.default($clone).create();
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
    key: "_addColumnToForm",
    value: function _addColumnToForm(column) {
      var open = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      this.columns[column.name] = column;
      this.$form.append(column.$el);

      if (open) {
        column.open();
      }

      column.$el.hide().slideDown();
      jQuery('html, body').animate({
        scrollTop: column.$el.offset().top - 58
      }, 300);
      document.dispatchEvent(new CustomEvent('AC_Column_Added', {
        detail: {
          column: column
        }
      }));
      return column;
    }
  }]);

  return Form;
}();

module.exports = Form;

/***/ }),

/***/ "./js/admin/columns/initiator.js":
/*!***************************************!*\
  !*** ./js/admin/columns/initiator.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Initiator =
/*#__PURE__*/
function () {
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

module.exports = Initiator;

/***/ }),

/***/ "./js/admin/columns/menu.js":
/*!**********************************!*\
  !*** ./js/admin/columns/menu.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Menu =
/*#__PURE__*/
function () {
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

module.exports = Menu;

/***/ }),

/***/ "./js/admin/columns/modal.js":
/*!***********************************!*\
  !*** ./js/admin/columns/modal.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Modal =
/*#__PURE__*/
function () {
  function Modal(el) {
    _classCallCheck(this, Modal);

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
      var dismissButtons = this.el.querySelectorAll('[data-dismiss="modal"]');

      if (dismissButtons.length > 0) {
        dismissButtons.forEach(function (b) {
          b.addEventListener('click', function (e) {
            e.preventDefault();
            self.close();
          });
        });
      }

      this.el.addEventListener('click', function () {
        self.close();
      });
      this.el.querySelector('.ac-modal__dialog').addEventListener('click', function (e) {
        e.stopPropagation();
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
      this.el.classList.remove('-active');
    }
  }, {
    key: "open",
    value: function open() {
      this.el.classList.add('-active');
    }
  }], [{
    key: "initGlobalEvents",
    value: function initGlobalEvents() {
      var buttons = document.querySelectorAll('[data-ac-open-modal]');

      if (buttons.length) {
        buttons.forEach(function (button) {
          button.addEventListener('click', function (e) {
            e.preventDefault();
            var target = e.target.dataset.acOpenModal;
            var el = document.querySelector(target);

            if (el && el.AC_MODAL) {
              el.AC_MODAL.open();
            }
          });
        });
      }
    }
  }]);

  return Modal;
}();

module.exports = Modal;

/***/ }),

/***/ "./js/admin/columns/settings/date.js":
/*!*******************************************!*\
  !*** ./js/admin/columns/settings/date.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var date = function date(column) {
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

module.exports = date;

/***/ }),

/***/ "./js/admin/columns/settings/image-size.js":
/*!*************************************************!*\
  !*** ./js/admin/columns/settings/image-size.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Image =
/*#__PURE__*/
function () {
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
      subsetting.forEach(function (setting) {
        setting.style.display = 'none';
      });
    }
  }, {
    key: "showSubsettings",
    value: function showSubsettings() {
      var subsetting = this.setting.querySelectorAll('.ac-column-setting');
      subsetting.forEach(function (setting) {
        setting.style.display = 'table';
      });
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

module.exports = image;

/***/ }),

/***/ "./js/admin/columns/settings/pro.js":
/*!******************************************!*\
  !*** ./js/admin/columns/settings/pro.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


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

module.exports = pro;

/***/ }),

/***/ "./js/admin/columns/settings/sub-setting-toggle.js":
/*!*********************************************************!*\
  !*** ./js/admin/columns/settings/sub-setting-toggle.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var subsetting = function subsetting(column) {
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

module.exports = subsetting;

/***/ }),

/***/ "./js/admin/columns/settings/width.js":
/*!********************************************!*\
  !*** ./js/admin/columns/settings/width.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


// Settings fields: Width
jQuery.fn.column_width_slider = function () {
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
  var $column = column.$el;
  $column.find('.ac-column-setting--width').each(function () {
    $column.column_width_slider(); // indicator

    var $width_indicator = $column.find('.ac-column-header .ac-column-heading-setting--width');
    $width_indicator.on('update', function () {
      var _width = $column.find('.ac-setting-input-width .description input').val();

      var _unit = $column.find('.ac-setting-input-width .description .unit').text();

      if (_width > 0) {
        $(this).text(_width + _unit);
      } else {
        $(this).text('');
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

      $(this).trigger('validate'); // validate input

      $width_indicator.trigger('update'); // update indicator
    }) // width_input:validate
    .on('validate', function () {
      var _width = width_input.val();

      var _new_width = $.trim(_width);

      if (!$.isNumeric(_new_width)) {
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

module.exports = width;

/***/ }),

/***/ "./js/modules/modals.js":
/*!******************************!*\
  !*** ./js/modules/modals.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Modals =
/*#__PURE__*/
function () {
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
    }
  }, {
    key: "get",
    value: function get(key) {
      if (this.modals[key]) {
        return this.modals[key];
      }

      return false;
    }
  }]);

  return Modals;
}();

module.exports = Modals;

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