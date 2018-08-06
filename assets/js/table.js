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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/table.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/table.js":
/*!*********************!*\
  !*** ./js/table.js ***!
  \*********************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _table = _interopRequireDefault(__webpack_require__(/*! ./table/table */ "./js/table/table.js"));

var _tooltips = _interopRequireDefault(__webpack_require__(/*! ./table/tooltips */ "./js/table/tooltips.js"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

jQuery(document).ready(function ($) {
  ac_quickedit_events($);
  ac_set_column_classes($);
  ac_actions_column($, $('.column-actions'));
  ac_show_more($);
  ac_toggle_box($);
  ac_toggle_box_ajax_init($);
  ac_actions_tooltips($);
  AC.Table = new _table.default('.wp-list-table');
  AC.Tooltips = new _tooltips.default();
  $('.wp-list-table').on('updated', 'tr', function () {
    ac_set_column_classes($);
    ac_actions_column($, $(this).find('.column-actions'));
    ac_show_more($);
  });
});

function ac_actions_tooltips($) {
  $('.row-actions a').qtip({
    content: {
      text: function text() {
        return $(this).text();
      }
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

function ac_toggle_box($) {
  $('.ac-toggle-box-link').click(function (e) {
    e.preventDefault();
    $(this).next('.ac-toggle-box-contents').toggle();
  });
}

function ac_toggle_box_ajax_init($) {
  /**
   * Toggle box
   */
  var do_toggle_value = function do_toggle_value(e) {
    e.preventDefault();
    $(this).next('.ac-toggle-box-contents-ajax').toggle();
  };
  /**
   * Retrieves the contents from the column through ajax
   */


  var do_retrieve_ajax_value = function do_retrieve_ajax_value(e) {
    e.preventDefault();
    var $this = $(this);
    var data = {
      action: 'ac_get_column_value',
      list_screen: AC.list_screen,
      layout: AC.layout,
      column: $this.data('column'),
      pk: $this.attr('data-item-id'),
      _ajax_nonce: AC.ajax_nonce
    };
    $this.addClass('loading');
    var xhr = $.post(ajaxurl, data, function (response) {
      if (response) {
        $this.after('<div class="ac-toggle-box-contents-ajax">' + response + '</div>'); // We only need to run the ajax request once. Unbind the event, and replace with a Toggle Box.

        $this.unbind('click', do_retrieve_ajax_value).bind('click', do_toggle_value); // Added hook on Table Cell

        $($this.parent('td')).trigger('ajax_column_value_ready'); // Re-init tooltips

        AC.Tooltips().init();
      }
    });
    xhr.always(function () {
      $this.removeClass('loading');
    });
  }; // Click event


  $('a[data-ajax-populate=1]').bind('click', do_retrieve_ajax_value);
}

function ac_show_more($) {
  $('.ac-more-link-show').click(function (e) {
    e.preventDefault();
    var td = $(this).hide().closest('td');
    td.find('.ac-show-more-block').show();
  });
  $('.ac-more-link-hide').click(function (e) {
    e.preventDefault();
    var td = $(this).closest('td');
    td.find('.ac-more-link-show').show();
    td.find('.ac-show-more-block').hide();
  });
}

function ac_actions_column($, $selector) {
  $($selector).each(function () {
    var $column = $(this);

    if ($column.find('.cpac_use_icons').length > 0) {
      $column.addClass('cpac_use_icons');
    }
  });
  $($selector).find('.cpac_use_icons + .hidden + .row-actions > span').each(function () {
    var $link = $(this).find('a');
    $link.attr('data-ac-tip', $link.text()).addClass('ac-tip');
  });
}

function ac_set_column_classes($) {
  for (var name in AC.column_types) {
    if (AC.column_types.hasOwnProperty(name)) {
      var type = AC.column_types[name];
      $('.wp-list-table td.' + name).addClass(type);
    }
  }
}

function ac_quickedit_events($) {
  $(document).ajaxComplete(function (event, request) {
    var ownerDocument = document.implementation.createHTMLDocument('quickeditevents');
    var $result = $('<div>', ownerDocument);
    $result.append(request.responseText);

    if ($result.find('tr.iedit').length === 1) {
      var id = $result.find('tr.iedit').attr('id');
      $('tr#' + id).trigger('updated');
    }
  });
}

/***/ }),

/***/ "./js/table/actions.js":
/*!*****************************!*\
  !*** ./js/table/actions.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Actions =
/*#__PURE__*/
function () {
  function Actions(el) {
    _classCallCheck(this, Actions);

    this.$el = jQuery(el);
    this.init();
  }

  _createClass(Actions, [{
    key: "init",
    value: function init() {
      var self = this;
      this.$el.on('update', function () {
        self.refresh();
      }).insertAfter(jQuery('.tablenav.top .actions:last')).trigger('update');
    }
  }, {
    key: "refresh",
    value: function refresh() {
      var $buttons = this.$el.find('.ac-table-actions-buttons');
      $buttons.find('> a').removeClass('last');
      $buttons.find('> a:visible:last').addClass('last');
    }
  }]);

  return Actions;
}();

module.exports = Actions;

/***/ }),

/***/ "./js/table/table.js":
/*!***************************!*\
  !*** ./js/table/table.js ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _actions = _interopRequireDefault(__webpack_require__(/*! ./actions */ "./js/table/actions.js"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Table = function Table(el) {
  _classCallCheck(this, Table);

  this.$table = jQuery(el);
  this.Buttons = new _actions.default('#ac-table-actions');
};

module.exports = Table;

/***/ }),

/***/ "./js/table/tooltips.js":
/*!******************************!*\
  !*** ./js/table/tooltips.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Tooltips =
/*#__PURE__*/
function () {
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

module.exports = Tooltips;

/***/ })

/******/ });
//# sourceMappingURL=table.js.map