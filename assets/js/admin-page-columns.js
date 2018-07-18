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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js-new/admin-page-columns.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js-new/admin-page-columns.js":
/*!**************************************!*\
  !*** ./js-new/admin-page-columns.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _columnForm = _interopRequireDefault(__webpack_require__(/*! ./classes/column-form */ "./js-new/classes/column-form.js"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * AC variables. Defined in DOM.
 * @param AC {Object}
 * @param AC.list_screen {String}
 * @param AC.layout {String}
 * @param AC.i81n {String}
 */

/**
 * DOM ready
 */
jQuery(document).ready(function ($) {
  if ($('#cpac').length === 0) {
    return false;
  }

  AC.incremental_column_name = 0;
  AC.Form = new _columnForm.default('#cpac .ac-columns form');
  cpac_menu($);
  $('.sidebox#direct-feedback').ac_feedback();
});
/*
 * Menu
 *
 * @since 1.5
 */

function cpac_menu($) {
  $('#ac_list_screen').on('change', function () {
    $('.view-link').hide();
    $(this).parents('form').submit();
    $(this).prop('disabled', true).next('.spinner').css('display', 'inline-block');
  });
}
/*
 * jQuery functions
 *
 * @since 2.0
 */


(function ($) {
  $.fn.ac_feedback = function () {
    var $box = $(this);
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
  };
  /*
   * Column: bind toggle events
   *
   * For performance we bind all other events after the click event.
   *
   * @since 2.0
   */


  $.fn.column_bind_toggle = function () {
    var $column = $(this);
    var is_disabled = $column.closest('.ac-boxes').hasClass('disabled');

    if ($column.data('bound-toggle')) {
      return;
    }

    $column.data('bound-toggle', 1);
    $column.find('[data-toggle="column"]').click(function (e) {
      e.preventDefault();
      $column.toggleClass('opened').find('.ac-column-body').slideToggle(150);

      if (is_disabled) {
        return;
      }

      if (!$column.hasClass('events-binded')) {
        $column.column_bind_events();
      }

      $column.addClass('events-binded'); // hook for addons

      $(document).trigger('column_init', $column);
    }).css('cursor', 'pointer');
  };
  /*
   * Column: bind remove events
   *
   * @since 2.0
   */


  $.fn.column_bind_remove = function () {
    $(this).find('.remove-button').click(function (e) {
      $(this).closest('.ac-column').column_remove();
      e.preventDefault();
    });
  };
  /**
   * Column: bind clone events
   *
   * @since 2.3.4
   */


  $.fn.column_bind_clone = function () {
    $(this).find('.clone-button').click(function (e) {
      e.preventDefault();
      var $clone = $(this).closest('.ac-column').column_clone();

      if (typeof $clone !== 'undefined') {
        $clone.removeClass('loading').hide().slideDown();
      }
    });
  };

  $.fn.cpac_column_refresh = function () {
    var $column = $(this);
    var $select = $column.find('[data-refresh="column"]'); // Allow plugins to hook into this event

    $(document).trigger('pre_column_refresh', $column);
    $select.prop('disabled', 1);
    $column.addClass('loading').data('column').refresh().always(function () {
      $column.removeClass('loading');
      $select.prop('disabled', false); // TODO: change to column_refresh?

      $(document).trigger('column_change', el);
    }).fail(function (error) {
      //TODO does not work?
      AC.Form.showMessage('HELLO');
      var $msg = el.closest('.ac-admin').find('.ajax-message');
      $msg.addClass('error').find('p').html(AC.i18n.error);
      $msg.slideDown();
      el.slideUp(function () {
        el.remove();
      });
    });
  };

  $.fn.column_onload = function () {
    var column = $(this);
    /** When an label contains an icon or span, the displayed label can appear empty. In this case we show the "type" label. */

    var column_label = column.find('.column_label .toggle');

    if ($.trim(column_label.html()) && column_label.width() < 1) {
      column_label.html(column.find('.column_type .inner').html());
    }
  };

  $.fn.column_bind_type_selector = function () {
    var column = $(this);

    if (column.data('event-type')) {
      return;
    }

    column.data('event-type', 1);
    column.find('select.ac-setting-input_type').change(function (e) {
      column.addClass('loading').data('column').switchToType($(this).val()).always(function () {
        column.removeClass('loading');
      });
    });
  };
  /*
   * Form Events
   *
   * @since 2.0
   */


  $.fn.column_bind_events = function () {
    var column = $(this);
    column.column_onload();
    /** When an label contains an icon or span, the displayed label can appear empty. In this case we show the "type" label. */

    var column_label = column.find('.column_label .toggle');

    if ($.trim(column_label.html()) && column_label.width() < 1) {
      column_label.html(column.find('.column_type .inner').html());
    }

    column.column_bind_type_selector();
    /** change label */

    column.find('.ac-column-setting--label input').bind('keyup change', function () {
      var value = $(this).val();
      $(this).closest('.ac-column').find('td.column_label .inner > a.toggle').html(value);
    });
    /** tooltip */

    column.find('.ac-column-body .col-label .label').hover(function () {
      $(this).parents('.col-label').find('div.tooltip').show();
    }, function () {
      $(this).parents('.col-label').find('div.tooltip').hide();
    });
    /**
     * Populates the main Label with the selected label from the dropdown,
     */

    column.find('select[data-label="update"]').change(function () {
      var $label = column.find('input.ac-setting-input_label');
      var field_label = $(this).find('option:selected').text(); // Set new label

      $label.val(field_label);
      $label.trigger('change');
    }); // refresh column and re-bind all events

    column.find('[data-refresh="column"]').change(function () {
      column.cpac_column_refresh();
    });
    $(document).trigger('init_settings', column);
  };
  /*
   * Column: remove from DOM
   *
   * @since 2.0
   */


  $.fn.column_remove = function () {
    $(this).addClass('deleting').animate({
      opacity: 0,
      height: 0
    }, 350, function () {
      $(this).remove();
    });
  };
  /*
   * Column: clone
   *
   * @since 2.3.4
   */


  $.fn.column_clone = function () {
    var column = $(this);
    var columns = $(this).closest('ac-columns');

    if ('1' === column.attr('data-original')) {
      var message = AC.i18n.clone.replace('%s', '<strong>' + column.find('.column_label .toggle').text() + '</strong>');
      column.addClass('opened').find('.ac-column-body').slideDown(150);
      column.find('.ac-setting-input_type').next('.msg').html(message).show();
      return;
    }

    var clone = $(this).clone();
    clone.cpac_update_clone_id();
    $(this).after(clone); // rebind events

    clone.column_bind_toggle();
    clone.column_bind_remove();
    clone.column_bind_clone(); // rebind all other events

    clone.column_bind_events(); // reinitialize sortability

    columns.cpac_bind_ordering(); // hook for addons

    $(document).trigger('column_add', clone);
    return clone;
  };
  /*
   * Optional Radio Click events
   *
   */


  $.fn.cpac_bind_column_addon_events = function () {
    var column = $(this);
    var inputs = column.find('[data-trigger] label');
    inputs.on('click', function () {
      var id = $(this).closest('td.input').data('trigger');
      var state = $('input', this).val(); // Toggle indicator icon

      var label = column.find('[data-indicator-id="' + id + '"]').removeClass('on');

      if ('on' == state) {
        label.addClass('on');
      } // Toggle additional options


      var additional = column.find('[data-handle="' + id + '"]').addClass('hide');

      if ('on' == state) {
        additional.removeClass('hide');
      }
    }); // On load

    column.find('[data-trigger]').each(function () {
      var trigger = $(this).data('trigger'); // Hide additional column settings

      var additional = column.find('[data-handle="' + trigger + '"]').addClass('hide');

      if ('on' == $('input:checked', this).val()) {
        additional.removeClass('hide');
      }
    });
  };
  /*
   * Indicator Click Events
   *
   */


  $.fn.cpac_bind_indicator_events = function () {
    var $column = $(this);
    var $indicators = $column.find('.ac-column-header [data-indicator-toggle]');
    $indicators.each(function () {
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

        if ('on' == value) {
          $indicator.addClass('on');
        } else {
          $indicator.removeClass('on');
        }
      });
    });
  }; // Settings fields: Image _size


  $.fn.cpac_column_setting_image_size = function () {
    function initState($setting, $select) {
      if ('cpac-custom' == $select.val()) {
        $setting.find('.ac-column-setting').show();
      } else {
        $setting.find('.ac-column-setting').hide();
      }
    }

    $(this).each(function () {
      var $setting = $(this);
      var $select = $(this).find('.ac-setting-input select');
      initState($setting, $select);
      $select.on('change', function () {
        initState($setting, $(this));
      });
    });
  };

  $.fn.cpac_column_sub_setting_toggle = function (options) {
    var settings = $.extend({
      value_show: "on",
      subfield: '.ac-column-setting'
    }, options);

    function initState($setting, $input) {
      var value = $input.filter(':checked').val();
      var $subfields = $setting.find(settings.subfield);

      if (settings.value_show == value) {
        $subfields.show();
      } else {
        $subfields.hide();
      }
    }

    $(this).each(function () {
      var $setting = $(this);
      var $input = $(this).find('.ac-setting-input input[type="radio"]');
      initState($setting, $input);
      $input.on('change', function () {
        initState($setting, $input);
      });
    });
  };

  $.fn.cpac_column_setting_date = function () {
    $(this).each(function () {
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
  }; // Settings fields: Pro


  $.fn.cpac_column_setting_pro = function () {
    $(this).each(function () {
      var $container = $(this);
      $container.find('input').on('click', function (e) {
        e.preventDefault();
        $container.find('[data-ac-open-modal]').trigger('click');
      });
    });
  };

  $(document).on('init_settings', function (e, column) {
    $(column).find('.ac-column-setting--image').cpac_column_setting_image_size();
    $(column).find('.ac-column-setting--images').cpac_column_setting_image_size();
    $(column).find('.ac-column-setting--date').cpac_column_setting_date();
    $(column).find('.ac-column-setting--pro').cpac_column_setting_pro(); // TODO: pro?

    $(column).find('.ac-column-setting--filter').cpac_column_sub_setting_toggle();
    $(column).find('.ac-column-setting--sort').cpac_column_sub_setting_toggle();
    $(column).find('.ac-column-setting--edit').cpac_column_sub_setting_toggle();
  }); // AC Modal Events (todo move to separate logic)

  $().ready(function () {
    $(document).on('click', '[data-ac-open-modal]', function (e) {
      e.preventDefault();
      $($(this).data('ac-open-modal')).addClass('-active');
    });
    $('.ac-modal__dialog__close').on('click', function (e) {
      e.preventDefault();
      $(this).closest('.ac-modal').removeClass('-active');
    });
    $('.ac-modal').on('click', function (e) {
      $(this).removeClass('-active');
    }); // Prevent bubbling

    $('.ac-modal__dialog').on('click', function (e) {
      e.stopPropagation();
    });
    $(document).keyup(function (e) {
      if (e.keyCode === 27) {
        $('.ac-modal').removeClass('-active');
      }
    });
  });
})(jQuery);

__webpack_require__(/*! ./settings/width */ "./js-new/settings/width.js");

/***/ }),

/***/ "./js-new/classes/column-form.js":
/*!***************************************!*\
  !*** ./js-new/classes/column-form.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _column = _interopRequireDefault(__webpack_require__(/*! ./column */ "./js-new/classes/column.js"));

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
    this.init(); // Todo move or remove?

    jQuery(document).trigger('cac_model_ready', this.$container.data('type'));
  }

  _createClass(Form, [{
    key: "init",
    value: function init() {
      this.initColumns();
      this.bindFormEvents();
      this.bindOrdering();
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
      var columns = [];
      Object.values(this.columns).forEach(function (column) {
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
    key: "resetColumns",
    value: function resetColumns() {
      var _this = this;

      Object.keys(this.columns).forEach(function (key) {
        console.log(key);
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
      jQuery(document).trigger('cac_update', self.$container);
      return xhr;
    }
  }, {
    key: "showMessage",
    value: function showMessage(message) {
      var attr_class = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'updated';
      var $msg = jQuery('<div class="ac-message hidden ' + attr_class + '"><p>' + message + '</p></div>');
      this.$container.find('.ac-boxes').before($msg);
      $msg.slideDown();
    }
  }, {
    key: "addColumn",
    value: function addColumn() {
      var $clone = jQuery('#add-new-column-template').find('.ac-column').clone();
      var column = new _column.default($clone);
      column.initNewInstance().bindEvents();
      this.columns[column.name] = column;
      this.$form.append(column.$el);
      column.toggle();
      jQuery('html, body').animate({
        scrollTop: column.$el.offset().top - 58
      }, 300);
      jQuery(document).trigger('column_add', column);
      return column;
    }
  }, {
    key: "removeColumn",
    value: function removeColumn(name) {
      if (this.columns[name]) {
        this.columns[name].destroy();
        delete this.columns[name];
      }
    }
  }]);

  return Form;
}();

module.exports = Form;

/***/ }),

/***/ "./js-new/classes/column.js":
/*!**********************************!*\
  !*** ./js-new/classes/column.js ***!
  \**********************************/
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
    this._name = this.$el.data('column-name');
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
      var temp_column_name = '_new_column_' + AC.incremental_column_name;
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
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      this.$el.column_bind_toggle();
      this.$el.column_bind_remove();
      this.$el.column_bind_clone();
      this.$el.column_bind_events();
      this.$el.cpac_bind_indicator_events();
      this.$el.data('column', this);
      return this;
    }
  }, {
    key: "destroy",
    value: function destroy() {
      this.$el.remove();
    }
  }, {
    key: "toggle",
    value: function toggle() {
      var duration = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 150;
      this.$el.toggleClass('opened').find('.ac-column-body').slideToggle(duration);
    }
  }, {
    key: "open",
    value: function open() {
      this.$el.toggleClass('opened').find('.ac-column-body').show();
    }
  }, {
    key: "showMessage",
    value: function showMessage(message) {
      //TODO too specific
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
            self.initNewInstance();
            self.bindEvents();
            self.open();
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
            self.initNewInstance();
            self.bindEvents();
            self.open();
          }
        }
      });
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
      this._name = name;
    }
  }]);

  return Column;
}();

module.exports = Column;

/***/ }),

/***/ "./js-new/settings/width.js":
/*!**********************************!*\
  !*** ./js-new/settings/width.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var jQuery = $ = __webpack_require__(/*! jquery */ "jquery"); // Settings fields: Width


jQuery.fn.column_width_slider = function () {
  var column_width = $(this).find('.ac-setting-input-width');
  var input_width = column_width.find('.description input'),
      input_unit = column_width.find('.unit-select input'),
      unit = input_unit.filter(':checked').val(),
      width = input_width.val(),
      slider = column_width.find('.width-slider'),
      indicator = $(this).find('.ac-column-header .ac-column-heading-setting--width'); // width

  if ('%' == unit && width > 100) {
    width = 100;
  }

  input_width.val(width);
  slider.slider({
    range: 'min',
    min: 0,
    max: '%' == unit ? 100 : 500,
    value: width,
    slide: function slide(event, ui) {
      input_width.val(ui.value);
      indicator.trigger('update');
      input_width.trigger('validate');
    }
  });
};

jQuery.fn.cpac_column_setting_width = function () {
  $(this).each(function () {
    var $column = $(this).parents('.ac-column');
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

$(document).on('init_settings', function (e, column) {
  $(column).find('.ac-column-setting--width').cpac_column_setting_width();
});

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