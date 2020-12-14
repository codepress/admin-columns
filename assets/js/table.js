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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/table.ts");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/constants.ts":
/*!*************************!*\
  !*** ./js/constants.ts ***!
  \*************************/
/*! exports provided: EventConstants */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "EventConstants", function() { return EventConstants; });
var EventConstants = {
  TABLE: {
    READY: 'Table.Ready'
  }
};

/***/ }),

/***/ "./js/helpers/admin-columns.ts":
/*!*************************************!*\
  !*** ./js/helpers/admin-columns.ts ***!
  \*************************************/
/*! exports provided: initAdminColumnsGlobalBootstrap */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "initAdminColumnsGlobalBootstrap", function() { return initAdminColumnsGlobalBootstrap; });
/* harmony import */ var _modules_modals__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modules/modals */ "./js/modules/modals.ts");


var nanobus = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");

var initAdminColumnsGlobalBootstrap = function () {
  if (!window.AdminColumns) {
    window.AdminColumns = window.AdminColumns || {};
    AdminColumns.events = nanobus();
    AdminColumns.Modals = new _modules_modals__WEBPACK_IMPORTED_MODULE_0__["default"]();
  }

  return window.AdminColumns;
};

/***/ }),

/***/ "./js/helpers/elements.ts":
/*!********************************!*\
  !*** ./js/helpers/elements.ts ***!
  \********************************/
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

/***/ "./js/helpers/global.ts":
/*!******************************!*\
  !*** ./js/helpers/global.ts ***!
  \******************************/
/*! exports provided: getParamFromUrl */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getParamFromUrl", function() { return getParamFromUrl; });
var getParamFromUrl = function (param, url) {
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

/***/ }),

/***/ "./js/helpers/table.ts":
/*!*****************************!*\
  !*** ./js/helpers/table.ts ***!
  \*****************************/
/*! exports provided: getIdFromTableRow, getIdFromString, getRowCellByName, resolveTableBySelector */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getIdFromTableRow", function() { return getIdFromTableRow; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getIdFromString", function() { return getIdFromString; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getRowCellByName", function() { return getRowCellByName; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "resolveTableBySelector", function() { return resolveTableBySelector; });
/* harmony import */ var _global__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./global */ "./js/helpers/global.ts");

var getIdFromTableRow = function (row) {
  if (row.classList.contains('no-items')) {
    return 0;
  }

  var item_id = getIdFromString(row.id);

  if (!item_id) {
    var input = row.querySelector('.check-column input[type=checkbox]');

    if (input) {
      item_id = getIdFromString(input.id);
    }
  } // Try to get the ID from the edit URL (MS Sites)


  if (!item_id) {
    var link = row.parentElement.querySelector('.edit a');

    if (link) {
      var href = link.getAttribute('href');

      if (href) {
        item_id = parseInt(Object(_global__WEBPACK_IMPORTED_MODULE_0__["getParamFromUrl"])('id', href));
      }
    }
  }

  row.dataset.id = item_id.toString();
  return item_id;
};
var getIdFromString = function (value) {
  var id_parts = value.split(/[_,\-]+/);
  return parseInt(id_parts[id_parts.length - 1]);
};
var getRowCellByName = function (row, column_name) {
  return row.querySelector("td.column-" + column_name);
};
var resolveTableBySelector = function (selector) {
  var table = document.querySelector(selector);

  if (!table) {
    return null;
  }

  if (table.tagName === 'TABLE') {
    return table;
  }

  if (table.tagName === 'TBODY') {
    return table.closest('table');
  }

  if (table.querySelector('table.wp-list-table')) {
    return table.querySelector('table.wp-list-table');
  }

  return null;
};

/***/ }),

/***/ "./js/modules/modal.ts":
/*!*****************************!*\
  !*** ./js/modules/modal.ts ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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

  Modal.prototype.initEvents = function () {
    var _this = this;

    var self = this;
    document.addEventListener('keydown', function (e) {
      var keyName = e.key;

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

/***/ "./js/modules/modals.ts":
/*!******************************!*\
  !*** ./js/modules/modals.ts ***!
  \******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modal */ "./js/modules/modal.ts");


var Modals =
/** @class */
function () {
  function Modals() {
    this.modals = {};
    this.number = 0;
    this.defaults = {
      modal: _modal__WEBPACK_IMPORTED_MODULE_0__["default"]
    };
    this.initGlobalEvents();
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
    return this.modals.hasOwnProperty(key) ? this.modals[key] : null;
  };

  Modals.prototype.open = function (key) {
    if (this.get(key)) {
      this.get(key).open();
    }
  };

  Modals.prototype.close = function (key) {
    if (this.get(key)) {
      this.get(key).close();
    }
  };

  Modals.prototype.closeAll = function () {
    for (var key in this.modals) {
      this.close(key);
    }
  };

  Modals.prototype.initGlobalEvents = function () {
    var _this = this;

    document.addEventListener('click', function (e) {
      var target = e.target;

      if (target.dataset.acModal) {
        e.preventDefault();

        _this.open(target.dataset.acModal);
      }
    });
  };

  return Modals;
}();

/* harmony default export */ __webpack_exports__["default"] = (Modals);

/***/ }),

/***/ "./js/modules/toggle-box-link.ts":
/*!***************************************!*\
  !*** ./js/modules/toggle-box-link.ts ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helpers_elements__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/elements */ "./js/helpers/elements.ts");


var $ = __webpack_require__(/*! jquery */ "jquery");

var ToggleBoxLink =
/** @class */
function () {
  function ToggleBoxLink(element) {
    this.element = element;
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
    Object(_helpers_elements__WEBPACK_IMPORTED_MODULE_0__["insertAfter"])(contentBox, this.element);
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
    this.element.dataset.toggleBoxInit = 'true';
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

      $(_this.element.parentElement).trigger('ajax_column_value_ready');
      AdminColumns.Tooltips.init();
    }).always(function () {
      _this.element.classList.remove('loading');
    });
  };

  ToggleBoxLink.prototype.retrieveAjaxValue = function () {
    return $.ajax({
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

/***/ "./js/modules/tooltips.ts":
/*!********************************!*\
  !*** ./js/modules/tooltips.ts ***!
  \********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../plugin/tooltip */ "./js/plugin/tooltip.ts");


var Tooltips =
/** @class */
function () {
  function Tooltips() {
    this.init();
  }

  Tooltips.prototype.init = function () {
    Object(_plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__["initAcTooltips"])();
  };

  return Tooltips;
}();

/* harmony default export */ __webpack_exports__["default"] = (Tooltips);

/***/ }),

/***/ "./js/plugin/show-more.ts":
/*!********************************!*\
  !*** ./js/plugin/show-more.ts ***!
  \********************************/
/*! exports provided: auto_init_show_more, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "auto_init_show_more", function() { return auto_init_show_more; });
var auto_init_show_more = function () {
  document.querySelectorAll('.ac-show-more').forEach(function (el) {
    new ShowMore(el);
  });
};

var ShowMore =
/** @class */
function () {
  function ShowMore(el) {
    this.element = el;
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

    this.element.dataset.showMoreInit = 'true';
  };

  ShowMore.prototype.getToggler = function () {
    return this.element.querySelector('.ac-show-more__toggle');
  };

  ShowMore.prototype.isInited = function () {
    return this.element.dataset.showMoreInit === 'true';
  };

  ShowMore.prototype.toggle = function () {
    if (this.element.classList.contains('-on')) {
      this.hide();
    } else {
      this.show();
    }
  };

  ShowMore.prototype.show = function () {
    this.element.classList.add('-on');
    this.getToggler().innerHTML = this.getToggler().dataset.less;
  };

  ShowMore.prototype.hide = function () {
    this.element.classList.remove('-on');
    this.getToggler().innerHTML = this.getToggler().dataset.more;
  };

  return ShowMore;
}();

/* harmony default export */ __webpack_exports__["default"] = (ShowMore);

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
var initAcTooltips = function () {
  document.querySelectorAll('[data-ac-tip]').forEach(function (element) {
    new Tooltip(element);
  });
};

var Tooltip =
/** @class */
function () {
  function Tooltip(el, content) {
    if (content === void 0) {
      content = '';
    }

    this.element = el;
    this.content = content ? content : el.dataset.acTip;
    this.tip = createTooltip(this.content);
    this.initEvents();
  }

  Tooltip.prototype.initEvents = function () {
    var _this = this;

    if (this.element.dataset.acTooltipInit === '1') {
      return;
    }

    this.element.dataset.acTooltipInit = '1';
    document.body.appendChild(this.tip);
    this.element.addEventListener('mouseenter', function () {
      var bodyOffset = document.body.getBoundingClientRect();

      var viewportOffset = _this.element.getBoundingClientRect();

      _this.tip.style.left = viewportOffset.left - bodyOffset.left + _this.element.offsetWidth / 2 + 'px';
      _this.tip.style.top = viewportOffset.top - bodyOffset.top + _this.element.offsetHeight + 'px';

      _this.tip.classList.add('hover');
    });
    this.element.addEventListener('mouseleave', function () {
      _this.tip.classList.remove('hover');
    });
  };

  return Tooltip;
}();



var createTooltip = function (content) {
  var tip = document.createElement('div');
  tip.classList.add('ac-tooltip');
  tip.innerHTML = content;
  return tip;
};

/***/ }),

/***/ "./js/table.ts":
/*!*********************!*\
  !*** ./js/table.ts ***!
  \*********************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _table_table__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./table/table */ "./js/table/table.ts");
/* harmony import */ var _modules_tooltips__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/tooltips */ "./js/modules/tooltips.ts");
/* harmony import */ var _table_screen_options_columns__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./table/screen-options-columns */ "./js/table/screen-options-columns.ts");
/* harmony import */ var _modules_toggle_box_link__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modules/toggle-box-link */ "./js/modules/toggle-box-link.ts");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _plugin_show_more__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./plugin/show-more */ "./js/plugin/show-more.ts");
/* harmony import */ var _table_functions__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./table/functions */ "./js/table/functions.ts");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./constants */ "./js/constants.ts");
/* harmony import */ var _helpers_table__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./helpers/table */ "./js/helpers/table.ts");
/* harmony import */ var _helpers_admin_columns__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./helpers/admin-columns */ "./js/helpers/admin-columns.ts");



 // @ts-ignore







var AdminColumns = Object(_helpers_admin_columns__WEBPACK_IMPORTED_MODULE_9__["initAdminColumnsGlobalBootstrap"])();
jquery__WEBPACK_IMPORTED_MODULE_4___default()(document).ready(function () {
  var table = Object(_helpers_table__WEBPACK_IMPORTED_MODULE_8__["resolveTableBySelector"])(AC.table_id);

  if (table) {
    AdminColumns.Table = new _table_table__WEBPACK_IMPORTED_MODULE_0__["default"](table);
    AdminColumns.Table.init();
    AdminColumns.ScreenOptionsColumns = new _table_screen_options_columns__WEBPACK_IMPORTED_MODULE_2__["default"](AdminColumns.Table.Columns);
  }

  AdminColumns.Tooltips = new _modules_tooltips__WEBPACK_IMPORTED_MODULE_1__["default"]();
  document.querySelectorAll('.ac-toggle-box-link').forEach(function (el) {
    new _modules_toggle_box_link__WEBPACK_IMPORTED_MODULE_3__["default"](el);
  });
  jquery__WEBPACK_IMPORTED_MODULE_4___default()('.wp-list-table').on('updated', 'tr', function () {
    AdminColumns.Table.addCellClasses();
    Object(_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__["auto_init_show_more"])();
  }); // TODO use more global event name instead of IE

  jquery__WEBPACK_IMPORTED_MODULE_4___default()('.wp-list-table td').on('ACP_InlineEditing_After_SetValue', function () {
    Object(_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__["auto_init_show_more"])();
  });
});
AdminColumns.events.addListener(_constants__WEBPACK_IMPORTED_MODULE_7__["EventConstants"].TABLE.READY, function (e) {
  Object(_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__["auto_init_show_more"])();
  Object(_table_functions__WEBPACK_IMPORTED_MODULE_6__["init_actions_tooltips"])();
  e.table.getElement().addEventListener('DOMNodeInserted', function (e) {
    var element = e.target;

    if (element.tagName !== 'TR' || !element.classList.contains('iedit')) {
      return;
    }

    jquery__WEBPACK_IMPORTED_MODULE_4___default()(element).trigger('updated', {
      id: Object(_helpers_table__WEBPACK_IMPORTED_MODULE_8__["getIdFromTableRow"])(element),
      row: element
    });
  });
});

window.ac_load_table = function (el) {
  AdminColumns.Table = new _table_table__WEBPACK_IMPORTED_MODULE_0__["default"](el);
};

/***/ }),

/***/ "./js/table/actions.ts":
/*!*****************************!*\
  !*** ./js/table/actions.ts ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helpers_elements__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/elements */ "./js/helpers/elements.ts");


var nanobus = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");

var Actions =
/** @class */
function () {
  function Actions(element) {
    this.container = element;
    this.events = nanobus();
    this.init();
  }

  Actions.prototype.init = function () {
    var _this = this;

    this.container.addEventListener('update', function () {
      _this.refresh();
    });
    var reference = document.querySelectorAll('.tablenav.top .actions');

    if (reference) {
      Object(_helpers_elements__WEBPACK_IMPORTED_MODULE_0__["insertAfter"])(this.container, reference[reference.length - 1]);
      this.container.classList.add('-init');
      this.container.dispatchEvent(new CustomEvent('update'));
    }
  };

  Actions.prototype.refresh = function () {
    this.container.querySelectorAll('.ac-table-actions-buttons > a').forEach(function (element) {
      element.classList.remove('last');
    });
    var buttons = [].slice.call(this.container.querySelectorAll('.ac-table-actions-buttons > a'), 0);
    buttons.reverse();

    for (var i = 0; i < buttons.length; i++) {
      if (buttons[i].offsetParent) {
        buttons[i].classList.add('last');
        break;
      }
    }
  };

  return Actions;
}();

/* harmony default export */ __webpack_exports__["default"] = (Actions);

/***/ }),

/***/ "./js/table/cell.ts":
/*!**************************!*\
  !*** ./js/table/cell.ts ***!
  \**************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Cell =
/** @class */
function () {
  function Cell(id, name, el) {
    this.object_id = id;
    this.column_name = name;
    this.original_value = el.innerHTML;
    this.el = el;
  }

  Cell.prototype.getObjectID = function () {
    return this.object_id;
  };

  Cell.prototype.getName = function () {
    return this.column_name;
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
    return this.original_value !== content;
  };

  Cell.prototype.setValue = function (value) {
    this.original_value = value;
    this.el.innerHTML = value;
    return this;
  };

  return Cell;
}();

/* harmony default export */ __webpack_exports__["default"] = (Cell);

/***/ }),

/***/ "./js/table/cells.ts":
/*!***************************!*\
  !*** ./js/table/cells.ts ***!
  \***************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Cells =
/** @class */
function () {
  function Cells() {
    this.cells = {};
  }

  Cells.prototype.add = function (id, cell) {
    if (!this.cells.hasOwnProperty(id)) {
      this.cells[id] = {};
    }

    this.cells[id][cell.getName()] = cell;
  };

  Cells.prototype.getByID = function (id) {
    var result = [];

    if (!this.cells.hasOwnProperty(id.toString())) {
      return result;
    }

    var cells = this.cells[id.toString()];
    Object.keys(cells).forEach(function (name) {
      return result.push(cells[name]);
    });
    return result;
  };

  Cells.prototype.getAll = function () {
    var _this = this;

    var results = [];
    Object.keys(this.cells).forEach(function (id) {
      var cells = _this.cells[id];
      Object.keys(cells).forEach(function (name) {
        return results.push(cells[name]);
      });
    });
    return results;
  };

  Cells.prototype.getByName = function (name) {
    var _this = this;

    var results = [];
    Object.keys(this.cells).forEach(function (id) {
      var cells = _this.cells[id];
      Object.keys(cells).forEach(function (column_name) {
        if (name === column_name) {
          results.push(cells[column_name]);
        }
      });
    });
    return results;
  };

  Cells.prototype.get = function (id, name) {
    return this.cells.hasOwnProperty(id.toString()) ? this.cells[id][name] : null;
  };

  return Cells;
}();

/* harmony default export */ __webpack_exports__["default"] = (Cells);

/***/ }),

/***/ "./js/table/columns.ts":
/*!*****************************!*\
  !*** ./js/table/columns.ts ***!
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
    this.columns = {};
    this.init();
  }

  Columns.prototype.init = function () {
    var self = this;
    var thead = this.table.querySelector('thead');
    var headers = thead.querySelectorAll('th');

    for (var i = 0; i < headers.length; i++) {
      var headerName = headers[i].id;
      self.columns[headers[i].id] = {
        name: headerName,
        type: AC.column_types[headerName],
        label: this.sanitizeLabel(headers[i])
      };
    }
  };

  Columns.prototype.getColumns = function () {
    return this.columns;
  };

  Columns.prototype.getColumnsMap = function () {
    var map = new Map();
    var columns = this.getColumns();
    Object.keys(columns).forEach(function (k) {
      map.set(k, columns[k]);
    });
    return map;
  };

  Columns.prototype.getColumnNames = function () {
    return Object.keys(this.columns);
  };

  Columns.prototype.get = function (column_name) {
    return this.columns.hasOwnProperty(column_name) ? this.columns[column_name] : null;
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

/***/ "./js/table/functions.ts":
/*!*******************************!*\
  !*** ./js/table/functions.ts ***!
  \*******************************/
/*! exports provided: init_actions_tooltips */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "init_actions_tooltips", function() { return init_actions_tooltips; });
/* harmony import */ var _plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../plugin/tooltip */ "./js/plugin/tooltip.ts");

var init_actions_tooltips = function () {
  document.querySelectorAll('.cpac_use_icons').forEach(function (el) {
    el.parentElement.querySelectorAll('.row-actions a').forEach(function (el) {
      new _plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__["Tooltip"](el, el.innerText);
    });
  });
};

/***/ }),

/***/ "./js/table/row-selection.ts":
/*!***********************************!*\
  !*** ./js/table/row-selection.ts ***!
  \***********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var RowSelection =
/** @class */
function () {
  function RowSelection(table) {
    this.Table = table;
  }

  RowSelection.prototype.getIDs = function () {
    var ids = [];
    var checked = this.Table.getElement().querySelectorAll('tbody th.check-column input[type=checkbox]:checked');

    if (checked.length === 0) {
      return ids;
    }

    for (var i = 0; i < checked.length; i++) {
      ids.push(parseInt(checked[i].value));
    }

    return ids;
  };
  /**
   * Get selected cells for specific column
   */


  RowSelection.prototype.getSelectedCells = function (name) {
    var _this = this;

    var ids = this.getIDs();

    if (ids.length === 0) {
      return null;
    }

    var cells = [];
    ids.forEach(function (id) {
      var cell = _this.Table.Cells.get(id, name);

      if (cell) {
        cells.push(cell);
      }
    });
    return cells;
  };

  RowSelection.prototype.getCount = function () {
    return this.getIDs().length;
  };

  RowSelection.prototype.isAllSelected = function () {
    return !!this.Table.getElement().querySelector('thead #cb input:checked');
  };

  return RowSelection;
}();

/* harmony default export */ __webpack_exports__["default"] = (RowSelection);

/***/ }),

/***/ "./js/table/screen-options-columns.ts":
/*!********************************************!*\
  !*** ./js/table/screen-options-columns.ts ***!
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

/***/ "./js/table/table.ts":
/*!***************************!*\
  !*** ./js/table/table.ts ***!
  \***************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./actions */ "./js/table/actions.ts");
/* harmony import */ var _cells__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cells */ "./js/table/cells.ts");
/* harmony import */ var _columns__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./columns */ "./js/table/columns.ts");
/* harmony import */ var _cell__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./cell */ "./js/table/cell.ts");
/* harmony import */ var _row_selection__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./row-selection */ "./js/table/row-selection.ts");
/* harmony import */ var _helpers_table__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../helpers/table */ "./js/helpers/table.ts");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../constants */ "./js/constants.ts");








var Table =
/** @class */
function () {
  function Table(el) {
    this.el = el;
    this.Columns = new _columns__WEBPACK_IMPORTED_MODULE_2__["default"](el);
    this.Cells = new _cells__WEBPACK_IMPORTED_MODULE_1__["default"]();
    this.Actions = document.getElementById('ac-table-actions') ? new _actions__WEBPACK_IMPORTED_MODULE_0__["default"](document.getElementById('ac-table-actions')) : null;
    this.Selection = new _row_selection__WEBPACK_IMPORTED_MODULE_4__["default"](this); // TODO make helper function for this (INLINE EDIT NEEDS IT)

    this._ids = [];
  }

  Table.prototype.getElement = function () {
    return this.el;
  };

  Table.prototype.init = function () {
    this.initTable();
    this.addCellClasses();
    document.dispatchEvent(new CustomEvent('AC_Table_Ready', {
      detail: {
        table: this
      }
    }));
    AdminColumns.events.emit(_constants__WEBPACK_IMPORTED_MODULE_6__["EventConstants"].TABLE.READY, {
      table: this
    });
  };

  Table.prototype.addCellClasses = function () {
    var _this = this;

    this.Columns.getColumnNames().forEach(function (name) {
      var type = _this.Columns.get(name).type;

      var cells = _this.Cells.getByName(name);

      cells.forEach(function (cell) {
        cell.getElement().classList.add(type);
      });
    });
  };

  Table.prototype.initTable = function () {
    var el = this.el.getElementsByTagName('tbody');
    var rows = el[0].querySelectorAll('tr');

    for (var i = 0; i < rows.length; i++) {
      this._ids.push(Object(_helpers_table__WEBPACK_IMPORTED_MODULE_5__["getIdFromTableRow"])(rows[i]));

      this.updateRow(rows[i]);
    }
  };

  Table.prototype.updateRow = function (row) {
    var id = Object(_helpers_table__WEBPACK_IMPORTED_MODULE_5__["getIdFromTableRow"])(row);
    row.dataset.id = id.toString();
    this.setCellsForRow(row);
  };

  Table.prototype.setCellsForRow = function (row) {
    var _this = this;

    var id = Object(_helpers_table__WEBPACK_IMPORTED_MODULE_5__["getIdFromTableRow"])(row);
    this.Columns.getColumnNames().forEach(function (name) {
      var selector = name.replace(/\./g, '\\.');
      var td = row.querySelector("td.column-" + selector);

      if (td) {
        var cell = new _cell__WEBPACK_IMPORTED_MODULE_3__["default"](id, name, td);

        _this.Cells.add(id, cell);
      }
    });
  };
  /**
   * @deprecated
   * TODO remove once IE uses the helper
   */


  Table.prototype._getIDFromRow = function (row) {
    return Object(_helpers_table__WEBPACK_IMPORTED_MODULE_5__["getIdFromTableRow"])(row);
  };
  /**
   * @deprecated use Helper function instead
   * TODO remove once IE uses the helper
   */


  Table.prototype.getRowCellByName = function (row, column_name) {
    return Object(_helpers_table__WEBPACK_IMPORTED_MODULE_5__["getRowCellByName"])(row, column_name);
  };

  return Table;
}();

/* harmony default export */ __webpack_exports__["default"] = (Table);

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

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = jQuery;

/***/ })

/******/ });
//# sourceMappingURL=table.js.map