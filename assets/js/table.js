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

/***/ "../../src/editing/js/helpers/elements.js":
/*!********************************************************************************************************!*\
  !*** /Users/stefan/PhpstormProjects/AdminColumns/admin-columns-pro/src/editing/js/helpers/elements.js ***!
  \********************************************************************************************************/
/*! exports provided: insertAfter, insertBefore */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "insertAfter", function() { return insertAfter; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "insertBefore", function() { return insertBefore; });
function insertAfter(newNode, referenceNode) {
  referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}
function insertBefore(newNode, referenceNode) {
  referenceNode.parentNode.insertBefore(newNode, referenceNode);
}

/***/ }),

/***/ "./js/modules/modal.js":
/*!*****************************!*\
  !*** ./js/modules/modal.js ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modals__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modals */ "./js/modules/modals.js");


var Modal =
/** @class */
function () {
  function Modal(el) {
    if (!el) {
      return;
    }

    this.el = el;
    this.dialog = el.querySelector('.ac-modal__dialog');
    this.initEvents();
  }

  Modal.initGlobalEvents = function () {
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
  };

  Modal.prototype.initEvents = function () {
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
  };

  Modal.prototype.isOpen = function () {
    return this.el.classList.contains('-active');
  };

  Modal.prototype.close = function () {
    this.onClose();
    this.el.classList.remove('-active');
  };

  Modal.prototype.open = function () {
    var _this = this; //short delay in order to allow bubbling events to bind before opening


    setTimeout(function () {
      _this.onOpen();

      _this.el.removeAttribute('style');

      _this.el.classList.add('-active');
    });
  };

  Modal.prototype.destroy = function () {
    this.el.remove();
  };

  Modal.prototype.onClose = function () {};

  Modal.prototype.onOpen = function () {};

  return Modal;
}();

/* harmony default export */ __webpack_exports__["default"] = (Modal);

/***/ }),

/***/ "./js/modules/modals.js":
/*!******************************!*\
  !*** ./js/modules/modals.js ***!
  \******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modal */ "./js/modules/modal.js");


var Modals =
/** @class */
function () {
  function Modals() {
    this.modals = [];
    this.number = 1;
  }

  Modals.prototype.register = function (modal, key) {
    if (key === void 0) {
      key = '';
    }

    if (!key) {
      key = 'm' + this.number;
    }

    this.modals[key] = modal;
    this.number++;
    return modal;
  };

  Modals.prototype.get = function (key) {
    if (this.modals[key]) {
      return this.modals[key];
    }

    return false;
  }; // Bind self to global AdminColumns if exist


  Modals.init = function () {
    if (typeof AdminColumns.Modals === 'undefined') {
      AdminColumns.Modals = new this();
      AdminColumns.Modals._abstract = {
        modal: _modal__WEBPACK_IMPORTED_MODULE_0__["default"]
      };
    }

    return AdminColumns.Modals;
  };

  return Modals;
}();

/* harmony default export */ __webpack_exports__["default"] = (Modals);

/***/ }),

/***/ "./js/modules/show-more.js":
/*!*********************************!*\
  !*** ./js/modules/show-more.js ***!
  \*********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var ShowMore =
/** @class */
function () {
  function ShowMore(el) {
    this.el = el;
    this.initEvents();
  }

  ShowMore.prototype.initEvents = function () {
    var _this = this;

    if (this.isInited()) {
      return;
    }

    if (this.getToggler()) {
      this.getToggler().addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        _this.toggle();
      });
    }

    this.el.dataset.showMoreInit = true;
  };

  ShowMore.prototype.getToggler = function () {
    return this.el.querySelector('.ac-show-more__toggle');
  };

  ShowMore.prototype.isInited = function () {
    return this.el.dataset.showMoreInit;
  };

  ShowMore.prototype.toggle = function () {
    if (this.el.classList.contains('-on')) {
      this.hide();
    } else {
      this.show();
    }
  };

  ShowMore.prototype.show = function () {
    this.el.classList.add('-on');
    this.getToggler().innerHTML = this.getToggler().dataset.less;
  };

  ShowMore.prototype.hide = function () {
    this.el.classList.remove('-on');
    this.getToggler().innerHTML = this.getToggler().dataset.more;
  };

  return ShowMore;
}();

/* harmony default export */ __webpack_exports__["default"] = (ShowMore);

/***/ }),

/***/ "./js/modules/toggle-box-link.js":
/*!***************************************!*\
  !*** ./js/modules/toggle-box-link.js ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _src_editing_js_helpers_elements__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../src/editing/js/helpers/elements */ "../../src/editing/js/helpers/elements.js");


var ToggleBoxLink =
/** @class */
function () {
  function ToggleBoxLink(el) {
    this.element = el;
    this.initEvents();
    this.contentBox = this.element.parentElement.querySelector('.ac-toggle-box-contents');

    if (!this.contentBox) {
      this.createContenBox();
    }
  }

  ToggleBoxLink.prototype.isAjax = function () {
    return parseInt(this.element.dataset.ajaxPopulate) === 1;
  };

  ToggleBoxLink.prototype.isInited = function () {
    return this.element.dataset.toggleBoxInit;
  };

  ToggleBoxLink.prototype.createContenBox = function () {
    var contentBox = document.createElement('div');
    contentBox.classList.add('ac-toggle-box-contents');
    Object(_src_editing_js_helpers_elements__WEBPACK_IMPORTED_MODULE_0__["insertAfter"])(contentBox, this.element);
    this.contentBox = contentBox;
    return this.contentBox;
  };

  ToggleBoxLink.prototype.initEvents = function () {
    var _this = this;

    if (this.isInited()) {
      return;
    }

    this.element.addEventListener('click', function (e) {
      e.preventDefault();

      if (_this.isAjax() && !_this.hasContent()) {
        _this.manageAjaxValue();
      }

      _this.toggleContentBox();
    });
    this.element.dataset.toggleBoxInit = true;
  };

  ToggleBoxLink.prototype.hasContent = function () {
    return this.getContentBox().innerHTML.length > 0;
  };

  ToggleBoxLink.prototype.setContent = function (content) {
    this.getContentBox().innerHTML = content;
  };

  ToggleBoxLink.prototype.getContentBox = function () {
    if (!this.contentBox) {
      return this.createContenBox();
    }

    return this.contentBox;
  };

  ToggleBoxLink.prototype.setLabel = function (open) {
    var label = this.element.dataset.label;

    if (open && this.element.dataset.labelClose) {
      label = this.element.dataset.labelClose;
    }

    this.element.innerHTML = label + '<span class="spinner"></span>';
  };

  ToggleBoxLink.prototype.toggleContentBox = function () {
    if (this.getContentBox().classList.contains('-open')) {
      this.getContentBox().classList.remove('-open');
      this.setLabel(false);
    } else {
      this.getContentBox().classList.add('-open');
      this.setLabel(true);
    }
  };

  ToggleBoxLink.prototype.manageAjaxValue = function () {
    var _this = this;

    this.element.classList.add('loading');
    this.retrieveAjaxValue().done(function (response) {
      _this.setContent(response);

      jQuery(_this.element.parentElement).trigger('ajax_column_value_ready');
      AdminColumns.Tooltips.init();
    }).always(function () {
      _this.element.classList.remove('loading');
    });
  };

  ToggleBoxLink.prototype.retrieveAjaxValue = function () {
    return jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: {
        action: 'ac_get_column_value',
        list_screen: AC.list_screen,
        layout: AC.layout,
        column: this.element.dataset.column,
        pk: this.element.dataset.itemId,
        _ajax_nonce: AC.ajax_nonce
      }
    });
  };

  return ToggleBoxLink;
}();

/* harmony default export */ __webpack_exports__["default"] = (ToggleBoxLink);

/***/ }),

/***/ "./js/table.js":
/*!*********************!*\
  !*** ./js/table.js ***!
  \*********************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(global) {/* harmony import */ var _table_table__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./table/table */ "./js/table/table.js");
/* harmony import */ var _table_tooltips__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./table/tooltips */ "./js/table/tooltips.js");
/* harmony import */ var _modules_modals__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/modals */ "./js/modules/modals.js");
/* harmony import */ var _table_screen_options_columns__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./table/screen-options-columns */ "./js/table/screen-options-columns.js");
/* harmony import */ var _modules_show_more__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./modules/show-more */ "./js/modules/show-more.js");
/* harmony import */ var _modules_toggle_box_link__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./modules/toggle-box-link */ "./js/modules/toggle-box-link.js");







var nanobus = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js"); // Register the global variable


global.AdminColumns = typeof AdminColumns !== "undefined" ? AdminColumns : {};
AdminColumns.events = nanobus();
_modules_modals__WEBPACK_IMPORTED_MODULE_2__["default"].init();
jQuery(document).ready(function ($) {
  ac_quickedit_events($);
  ac_actions_column($, $('.column-actions'));
  ac_show_more($);
  ac_actions_tooltips($);
  ac_toggle_box();
  var table = document.querySelector(AC.table_id);

  if (table) {
    ac_load_table(table.parentElement);
    AdminColumns.ScreenOptionsColumns = new _table_screen_options_columns__WEBPACK_IMPORTED_MODULE_3__["default"](AdminColumns.Table.Columns);
  }

  AdminColumns.Tooltips = new _table_tooltips__WEBPACK_IMPORTED_MODULE_1__["default"]();
  $('.wp-list-table').on('updated', 'tr', function () {
    AdminColumns.Table.addCellClasses();
    ac_actions_column($, $(this).find('.column-actions'));
    ac_show_more($);
  });
  $('.wp-list-table td').on('ACP_InlineEditing_After_SetValue', function () {
    ac_show_more($);
  });
});

global.ac_toggle_box = function () {
  document.querySelectorAll('.ac-toggle-box-link').forEach(function (el) {
    new _modules_toggle_box_link__WEBPACK_IMPORTED_MODULE_5__["default"](el);
  });
};

global.ac_load_table = function (el) {
  AdminColumns.Table = new _table_table__WEBPACK_IMPORTED_MODULE_0__["default"](el);
  AC.Table = AdminColumns.Table; // TODO use AdminColumns instead of AC
};

function ac_actions_tooltips() {
  jQuery('.cpac_use_icons').parent().find('.row-actions a').qtip({
    content: {
      text: function text() {
        return jQuery(this).text();
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

global.ac_show_more = function ($) {
  document.querySelectorAll('.ac-show-more').forEach(function (el) {
    new _modules_show_more__WEBPACK_IMPORTED_MODULE_4__["default"](el);
  });
};

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

function ac_quickedit_events($) {
  $(document).ajaxComplete(function (event, request) {
    var ownerDocument = document.implementation.createHTMLDocument('quickeditevents');
    var $result = $('<div>', ownerDocument);
    $result.append(request.responseText);

    if ($result.find('tr.iedit').length === 1) {
      var id = $result.find('tr.iedit').attr('id');
      $('tr#' + id).trigger('updated', {
        id: id
      });
    }
  });
}
/** CustomEvent Polyfill */


(function () {
  if (typeof window.CustomEvent === "function") {
    return false;
  }

  function CustomEvent(event, params) {
    params = params || {
      bubbles: false,
      cancelable: false,
      detail: undefined
    };
    var evt = document.createEvent('CustomEvent');
    evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
    return evt;
  }

  CustomEvent.prototype = window.Event.prototype;
  window.CustomEvent = CustomEvent;
})();
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./js/table/actions.js":
/*!*****************************!*\
  !*** ./js/table/actions.js ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Actions =
/** @class */
function () {
  function Actions(id) {
    this.container = document.getElementById(id);
    this.buttons = this.container.querySelector('.ac-table-actions-buttons');
    this.init();
  }

  Actions.prototype.init = function () {
    var self = this;
    this.dropDownEvents();
    jQuery(this.container).on('update', function () {
      self.refresh();
    }).insertAfter(jQuery('.tablenav.top .actions:last')).addClass('-init').trigger('update');
  };

  Actions.prototype.refresh = function () {
    var $buttons = jQuery(this.buttons);
    $buttons.find('> a').removeClass('last');
    $buttons.find('> a:visible:last').addClass('last');
  };

  Actions.prototype.dropDownEvents = function () {
    jQuery(this.buttons).on('click', '[data-dropdown]', function () {
      var $button = jQuery(this);
      $button.toggleClass('-open');

      if ($button.hasClass('-open')) {
        $button[0].dispatchEvent(new CustomEvent('open'));
      } else {
        $button[0].dispatchEvent(new CustomEvent('closed'));
      }
    });
  };

  return Actions;
}();

/* harmony default export */ __webpack_exports__["default"] = (Actions);

/***/ }),

/***/ "./js/table/cell.js":
/*!**************************!*\
  !*** ./js/table/cell.js ***!
  \**************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Cell =
/** @class */
function () {
  function Cell(id, name, el) {
    this._object_id = id;
    this._column_name = name;
    this._original_value = el.innerHTML;
    this.el = el;
  }

  Cell.prototype.getObjectID = function () {
    return this._object_id;
  };

  Cell.prototype.getName = function () {
    return this._column_name;
  };

  Cell.prototype.getElement = function () {
    return this.el;
  };

  Cell.prototype.getRow = function () {
    return this.el.parentElement;
  };

  Cell.prototype.getSettings = function () {
    return AdminColumns.Table.Columns.get(this.getName());
  };

  Cell.prototype.hasChanged = function (content) {
    return this._original_value !== content;
  };

  Cell.prototype.setValue = function (value) {
    var el = this.getElement();
    this._original_value = value;
    el.innerHTML = value;
    return this;
  };

  return Cell;
}();

/* harmony default export */ __webpack_exports__["default"] = (Cell);

/***/ }),

/***/ "./js/table/cells.js":
/*!***************************!*\
  !*** ./js/table/cells.js ***!
  \***************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Cells =
/** @class */
function () {
  function Cells() {
    this._cells = new Map();
  }

  Cells.prototype.add = function (id, column) {
    if (!this._cells.has(id)) {
      this._cells.set(id, new Map());
    }

    this._cells.get(id).set(column.getName(), column);
  };

  Cells.prototype.getByID = function (id) {
    var result = [];
    var key = id.toString();

    if (!this._cells.has(key)) {
      return result;
    }

    this._cells.get(id.toString()).forEach(function (column) {
      result.push(column);
    });

    return result;
  };

  Cells.prototype.getAll = function () {
    var results = [];

    this._cells.forEach(function (columns) {
      columns.forEach(function (column) {
        results.push(column);
      });
    });

    return results;
  };

  Cells.prototype.getByName = function (name) {
    var results = [];

    this._cells.forEach(function (columns) {
      columns.forEach(function (column, column_name) {
        if (name === column_name) {
          results.push(column);
        }
      });
    });

    return results;
  };

  Cells.prototype.get = function (id, type) {
    var row = this._cells.get(id.toString());

    if (!row) {
      return false;
    }

    return row.get(type);
  };

  return Cells;
}();

/* harmony default export */ __webpack_exports__["default"] = (Cells);

/***/ }),

/***/ "./js/table/columns.js":
/*!*****************************!*\
  !*** ./js/table/columns.js ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Columns =
/** @class */
function () {
  function Columns(table) {
    this.table = table;
    this._columns = {};
    this.init();
  }

  Columns.prototype.init = function () {
    var self = this;
    var thead = this.table.querySelector('thead');
    var headers = thead.querySelectorAll('th');

    for (var i = 0; i < headers.length; i++) {
      var column = {};
      column.name = headers[i].id;
      column.type = AC.column_types[column.name];
      column.label = this.sanitizeLabel(headers[i]);
      self._columns[headers[i].id] = column;
    }
  };

  Columns.prototype.getColumns = function () {
    return this._columns;
  };

  Columns.prototype.getColumnsMap = function () {
    var map = new Map();
    var columns = this.getColumns();
    Object.keys(columns).forEach(function (k) {
      map.set(k, columns[k]);
    });
    return map;
  };
  /**
   * @returns {string[]}
   */


  Columns.prototype.getColumnNames = function () {
    return Object.keys(this._columns);
  };
  /**
   *
   * @param {String} column_name
   * @returns {Object}
   */


  Columns.prototype.get = function (column_name) {
    if (!this._columns[column_name]) {
      return false;
    }

    return this._columns[column_name];
  };

  Columns.prototype.sanitizeLabel = function (header) {
    var link = header.querySelector('a');
    var label = header.innerHTML; // If it contains a link, we presume that it is because of sorting

    if (link) {
      var elements = link.getElementsByTagName('span');

      if (elements.length > 0) {
        label = elements[0].innerHTML;
      }
    }

    return label;
  };

  return Columns;
}();

/* harmony default export */ __webpack_exports__["default"] = (Columns);

/***/ }),

/***/ "./js/table/helper.js":
/*!****************************!*\
  !*** ./js/table/helper.js ***!
  \****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Helper =
/** @class */
function () {
  function Helper() {}
  /**
   * Get query param from url
   *
   * @param param
   * @param url
   * @returns {*}
   */


  Helper.getParamFromUrl = function (param, url) {
    if (!url) {
      return null;
    }

    param = param.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + param + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);

    if (!results) {
      return null;
    }

    if (!results[2]) {
      return '';
    }

    return decodeURIComponent(results[2].replace(/\+/g, " "));
  };

  ;
  return Helper;
}();

/* harmony default export */ __webpack_exports__["default"] = (Helper);

/***/ }),

/***/ "./js/table/row-selection.js":
/*!***********************************!*\
  !*** ./js/table/row-selection.js ***!
  \***********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Selection =
/** @class */
function () {
  function Selection(Table) {
    this.Table = Table;
  }
  /**
   * Get the selected IDs in the table
   *
   * @returns {Array}
   */


  Selection.prototype.getIDs = function () {
    var ids = [];
    var checked = this.Table.el.querySelectorAll('tbody th.check-column input[type=checkbox]:checked');

    if (checked.length === 0) {
      return ids;
    }

    for (var i = 0; i < checked.length; i++) {
      ids.push(checked[i].value);
    }

    return ids;
  };
  /**
   * Get selected cells for specific column
   *
   * @param name
   */


  Selection.prototype.getSelectedCells = function (name) {
    var self = this;
    var ids = this.getIDs();

    if (ids.length === 0) {
      return false;
    }

    var cells = [];
    ids.forEach(function (id) {
      var cell = self.table.Cells.get(id, name);

      if (cell) {
        cells.push(cell);
      }
    });
    return cells;
  };
  /**
   *
   * @returns {number}
   */


  Selection.prototype.getCount = function () {
    return this.getIDs().length;
  };

  Selection.prototype.isAllSelected = function () {
    return !!this.Table.el.querySelector('thead #cb input:checked');
  };

  return Selection;
}();

/* harmony default export */ __webpack_exports__["default"] = (Selection);

/***/ }),

/***/ "./js/table/screen-options-columns.js":
/*!********************************************!*\
  !*** ./js/table/screen-options-columns.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var ScreenOptionsColumns =
/** @class */
function () {
  function ScreenOptionsColumns(columns) {
    this.columns = columns;
    columns.getColumnNames().forEach(function (column_name) {
      var column = columns.get(column_name);
      var input = ScreenOptionsColumns.getInputByName(column.name);

      if (input && input.parentElement.textContent.length === 0) {
        var label = document.createElement('span');
        label.innerHTML = column.label;
        input.parentElement.appendChild(label);
      }
    });
  }

  ScreenOptionsColumns.getInputByName = function (name) {
    var input = document.querySelector("input[name='" + name + "-hide']");
    return input ? input : false;
  };

  return ScreenOptionsColumns;
}();

/* harmony default export */ __webpack_exports__["default"] = (ScreenOptionsColumns);

/***/ }),

/***/ "./js/table/table.js":
/*!***************************!*\
  !*** ./js/table/table.js ***!
  \***************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./actions */ "./js/table/actions.js");
/* harmony import */ var _cells__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cells */ "./js/table/cells.js");
/* harmony import */ var _columns__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./columns */ "./js/table/columns.js");
/* harmony import */ var _cell__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./cell */ "./js/table/cell.js");
/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./helper */ "./js/table/helper.js");
/* harmony import */ var _row_selection__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./row-selection */ "./js/table/row-selection.js");







var Table =
/** @class */
function () {
  /**
   *
   * @param {Element} el
   */
  function Table(el) {
    this.el = el;
    this.Helper = _helper__WEBPACK_IMPORTED_MODULE_4__["default"];
    this.Columns = new _columns__WEBPACK_IMPORTED_MODULE_2__["default"](el);
    this.Cells = new _cells__WEBPACK_IMPORTED_MODULE_1__["default"]();

    if (document.getElementById('ac-table-actions')) {
      this.Actions = new _actions__WEBPACK_IMPORTED_MODULE_0__["default"]('ac-table-actions');
    }

    this.Selection = new _row_selection__WEBPACK_IMPORTED_MODULE_5__["default"](this);
    this._ids = [];
    this.init();
  }

  Table.prototype.init = function () {
    var self = this;

    this._initTable();

    this.addCellClasses();
    document.dispatchEvent(new CustomEvent('AC_Table_Ready', {
      detail: {
        table: self
      }
    }));
    AdminColumns.events.emit('Table.Ready', {
      table: self
    });
  };

  Table.prototype.updateRow = function (row) {
    var id = this._getIDFromRow(row);

    row.dataset.id = id;

    this._setCellsForRow(row, id);
  };

  Table.prototype.addCellClasses = function () {
    var self = this;
    this.Columns.getColumnNames().forEach(function (name) {
      var type = self.Columns.get(name).type;
      var cells = self.Cells.getByName(name);
      cells.forEach(function (cell) {
        cell.el.classList.add(type);
      });
    });
  };
  /**
   * Initiate the table so we can easily query it
   * Also populate the Columns Model
   *
   * @private
   */


  Table.prototype._initTable = function () {
    var self = this;
    var el = this.el.getElementsByTagName('tbody');
    var rows = el[0].getElementsByTagName('tr');

    for (var i = 0; i < rows.length; i++) {
      var row = rows[i];

      var id = this._getIDFromRow(row);

      self._ids.push(id);

      this.updateRow(row);
    }
  };

  Table.prototype._setCellsForRow = function (row) {
    var _this = this;

    var id = this._getIDFromRow(row);

    this.Columns.getColumnNames().forEach(function (name) {
      var selector = name.replace(/\./g, '\\.');
      var td = row.querySelector(".column-" + selector);

      if (td) {
        var cell = new _cell__WEBPACK_IMPORTED_MODULE_3__["default"](id, name, td);

        _this.Cells.add(id, cell);

        _this._addColumnCellMethods(cell);
      }
    });
  };

  Table.prototype._addColumnCellMethods = function (column) {
    column.el.getCell = function () {
      return column;
    };
  };
  /**
   * Get the Post ID from a table row based on it's attributes or columns
   *
   * @param {Element} row
   * @returns {int}
   * @private
   */


  Table.prototype._getIDFromRow = function (row) {
    var id = row.id;
    var id_parts = id.split(/[_,\-]+/);
    var item_id = id_parts[id_parts.length - 1];

    if (row.classList.contains('no-items')) {
      return 0;
    }

    if (!item_id) {
      var input = row.querySelector('.check-column input[type=checkbox]');

      if (input) {
        id = input.id;
        id_parts = id.split('_');
        item_id = id_parts[id_parts.length - 1];
      }
    } // Try to get the ID from the edit URL (MS Sites)


    if (!item_id) {
      var link = row.parentElement.querySelector('.edit a');

      if (link) {
        var href = link.getAttribute('href');

        if (href) {
          item_id = this.Helper.getParamFromUrl('id', href);
        }
      }
    }

    row.dataset.id = item_id;
    AdminColumns.events.emit('Table.Id', {
      id: item_id,
      row: row
    });
    return row.dataset.id;
  };

  Table.prototype.getRowCellByName = function (row, column_name) {
    return row.querySelector(".column-" + column_name);
  };

  Table.getTable = function (jQuery) {
    if (jQuery === void 0) {
      jQuery = false;
    }

    if (jQuery) {
      return jQuery(this.el);
    }

    return this.el;
  };

  return Table;
}();

/* harmony default export */ __webpack_exports__["default"] = (Table);

/***/ }),

/***/ "./js/table/tooltips.js":
/*!******************************!*\
  !*** ./js/table/tooltips.js ***!
  \******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Tooltips =
/** @class */
function () {
  function Tooltips() {
    this.isEnabled = typeof jQuery.fn.qtip !== 'undefined';
    this.init();
  }

  Tooltips.prototype.init = function () {
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
  };

  return Tooltips;
}();

/* harmony default export */ __webpack_exports__["default"] = (Tooltips);

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


/***/ })

/******/ });
//# sourceMappingURL=table.js.map