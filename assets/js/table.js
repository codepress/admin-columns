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
    },
    SETTINGS: {
        FORM: {
            LOADED: 'Settings.Form.Loaded',
            READY: 'Settings.Form.Ready',
            SAVING: 'Settings.Form.Saving',
            SAVED: 'Settings.Form.Saved'
        },
        COLUMN: {
            INIT: 'Settings.Column.Init',
            SWITCH: 'Settings.Column.SwitchToType',
            REFRESHED: 'Settings.Column.Refreshed'
        }
    }
};


/***/ }),

/***/ "./js/helpers/admin-columns.ts":
/*!*************************************!*\
  !*** ./js/helpers/admin-columns.ts ***!
  \*************************************/
/*! exports provided: initAcServices */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "initAcServices", function() { return initAcServices; });
/* harmony import */ var _modules_ac_services__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modules/ac-services */ "./js/modules/ac-services.ts");

var initAcServices = function () {
    if (!window.AC_SERVICES) {
        window.AC_SERVICES = new _modules_ac_services__WEBPACK_IMPORTED_MODULE_0__["default"]();
    }
    return window.AC_SERVICES;
};


/***/ }),

/***/ "./js/helpers/elements.ts":
/*!********************************!*\
  !*** ./js/helpers/elements.ts ***!
  \********************************/
/*! exports provided: insertAfter, insertBefore, createElementFromString */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "insertAfter", function() { return insertAfter; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "insertBefore", function() { return insertBefore; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "createElementFromString", function() { return createElementFromString; });
var insertAfter = function (newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
};
var insertBefore = function (newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode);
};
var createElementFromString = function (content, baseElement) {
    if (baseElement === void 0) { baseElement = 'div'; }
    var element = document.createElement(baseElement);
    element.innerHTML = content;
    return element;
};
function isInViewport(element) {
    var rect = element.getBoundingClientRect();
    return (rect.top >= 0 && rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth));
}


/***/ }),

/***/ "./js/helpers/global.ts":
/*!******************************!*\
  !*** ./js/helpers/global.ts ***!
  \******************************/
/*! exports provided: getParamFromUrl, mapDataToFormData */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getParamFromUrl", function() { return getParamFromUrl; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "mapDataToFormData", function() { return mapDataToFormData; });
var getParamFromUrl = function (param, url) {
    if (!url.includes('?')) {
        return null;
    }
    var params = new URLSearchParams(url.split('?')[1]);
    return params.get(param);
};
var mapDataToFormData = function (data, formData) {
    if (formData === void 0) { formData = null; }
    if (!formData) {
        formData = new FormData();
    }
    Object.keys(data).forEach(function (key) {
        var value = data[key];
        if (Array.isArray(value)) {
            value.forEach(function (d) {
                formData.append(key + "[]", d);
            });
        }
        else {
            formData.append(key, data[key]);
        }
    });
    return formData;
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
    }
    // Try to get the ID from the edit URL (MS Sites)
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

/***/ "./js/modules/ac-pointer.ts":
/*!**********************************!*\
  !*** ./js/modules/ac-pointer.ts ***!
  \**********************************/
/*! exports provided: Pointer, initPointers */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Pointer", function() { return Pointer; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "initPointers", function() { return initPointers; });
/* harmony import */ var _tooltips__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tooltips */ "./js/modules/tooltips.ts");

var $ = __webpack_require__(/*! jquery */ "jquery");
var Pointer = /** @class */ (function () {
    function Pointer(el) {
        this.element = el;
        this.settings = this.getDefaults();
        this.init();
        this.setInitialized();
    }
    Pointer.prototype.setInitialized = function () {
        AcPointers.add(this.element);
    };
    Pointer.prototype.getDefaults = function () {
        return {
            width: this.element.getAttribute('data-width') ? this.element.getAttribute('data-width') : 250,
            noclick: this.element.getAttribute('data-noclick') ? this.element.getAttribute('data-noclick') : false,
            position: this.getPosition()
        };
    };
    Pointer.prototype.isInitialized = function () {
        return AcPointers.isInitialized(this.element);
    };
    Pointer.prototype.init = function () {
        if (this.isInitialized()) {
            return;
        }
        // create pointer
        $(this.element).pointer({
            content: this.getRelatedHTML(),
            position: this.settings.position,
            pointerWidth: this.settings.width,
            pointerClass: this.getPointerClass()
        });
        this.initEvents();
    };
    Pointer.prototype.getPosition = function () {
        var position = {
            at: 'left top',
            my: 'right top',
            edge: 'right', // position of arrow
        };
        var pos = this.element.getAttribute('data-pos');
        var edge = this.element.getAttribute('data-pos_edge');
        if ('right' === pos) {
            position = {
                at: 'right middle',
                my: 'left middle',
                edge: 'left'
            };
        }
        if ('right_bottom' === pos) {
            position = {
                at: 'right middle',
                my: 'left bottom',
                edge: 'none'
            };
        }
        if ('left' === pos) {
            position = {
                at: 'left middle',
                my: 'right middle',
                edge: 'right'
            };
        }
        if (edge) {
            position.edge = edge;
        }
        return position;
    };
    Pointer.prototype.getPointerClass = function () {
        var classes = [
            'ac-wp-pointer',
            'wp-pointer',
            'wp-pointer-' + this.settings.position.edge
        ];
        if (this.settings.noclick) {
            classes.push('noclick');
        }
        return classes.join(' ');
    };
    Pointer.prototype.getRelatedHTML = function () {
        var related_element = document.getElementById(this.element.getAttribute('rel'));
        return related_element ? related_element.innerHTML : '';
    };
    Pointer.prototype.initEvents = function () {
        var el = $(this.element);
        // click
        if (!this.settings.noclick) {
            el.click(function () {
                if (el.hasClass('open')) {
                    el.removeClass('open');
                }
                else {
                    el.addClass('open');
                }
            });
        }
        el.click(function () {
            el.pointer('open');
        });
        el.mouseenter(function () {
            el.pointer('open');
            setTimeout(function () {
                el.pointer('open');
            }, 2);
        });
        el.mouseleave(function () {
            setTimeout(function () {
                if (!el.hasClass('open') && $('.ac-wp-pointer.hover').length === 0) {
                    el.pointer('close');
                }
            }, 1);
        });
        el.on('close', function () {
            setTimeout(function () {
                if (!el.hasClass('open')) {
                    el.pointer('close');
                }
            });
        });
    };
    return Pointer;
}());

var AcPointers = /** @class */ (function () {
    function AcPointers() {
    }
    AcPointers.isInitialized = function (element) {
        return this.initElements.filter(function (el) { return el === element; }).length > 0;
    };
    AcPointers.add = function (element) {
        this.initElements.push(element);
    };
    AcPointers.initElements = [];
    return AcPointers;
}());
var initPointers = function (elements) {
    if (elements === void 0) { elements = null; }
    if (!elements) {
        elements = document.querySelectorAll('.ac-pointer');
    }
    elements.forEach(function (element) {
        new Pointer(element);
    });
    $('.ac-wp-pointer').hover(function () {
        $(this).addClass('hover');
    }, function () {
        $(this).removeClass('hover');
        $('.ac-pointer').trigger('close');
    }).on('click', '.close', function () {
        $('.ac-pointer').removeClass('open');
    });
    new _tooltips__WEBPACK_IMPORTED_MODULE_0__["default"]();
};


/***/ }),

/***/ "./js/modules/ac-services.ts":
/*!***********************************!*\
  !*** ./js/modules/ac-services.ts ***!
  \***********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(nanobus__WEBPACK_IMPORTED_MODULE_0__);

var AcServices = /** @class */ (function () {
    function AcServices() {
        this.services = {};
        this.events = new nanobus__WEBPACK_IMPORTED_MODULE_0___default.a();
    }
    AcServices.prototype.registerService = function (name, service) {
        this.services[name] = service;
        return this;
    };
    AcServices.prototype.getService = function (name) {
        return this.hasService(name) ? this.services[name] : null;
    };
    AcServices.prototype.hasService = function (name) {
        return this.services.hasOwnProperty(name);
    };
    AcServices.prototype.addListener = function (name, callback) {
        this.events.addListener(name, callback);
    };
    AcServices.prototype.emitEvent = function (name, args) {
        this.events.emit(name, args);
    };
    return AcServices;
}());
/* harmony default export */ __webpack_exports__["default"] = (AcServices);


/***/ }),

/***/ "./js/modules/modal.ts":
/*!*****************************!*\
  !*** ./js/modules/modal.ts ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Modal = /** @class */ (function () {
    function Modal(el) {
        if (!el) {
            return;
        }
        this.el = el;
        this.dialog = el.querySelector('.ac-modal__dialog');
        this.initEvents();
    }
    Modal.prototype.getElement = function () {
        return this.el;
    };
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
        var _this = this;
        //short delay in order to allow bubbling events to bind before opening
        setTimeout(function () {
            _this.onOpen();
            _this.el.removeAttribute('style');
            _this.el.classList.add('-active');
        });
    };
    Modal.prototype.destroy = function () {
        this.el.remove();
    };
    Modal.prototype.onClose = function () {
    };
    Modal.prototype.onOpen = function () {
    };
    return Modal;
}());
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

var Modals = /** @class */ (function () {
    function Modals() {
        this.modals = {};
        this.number = 0;
        this.defaults = {
            modal: _modal__WEBPACK_IMPORTED_MODULE_0__["default"]
        };
        this.initGlobalEvents();
    }
    Modals.prototype.register = function (modal, key) {
        if (key === void 0) { key = ''; }
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
}());
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
var ToggleBoxLink = /** @class */ (function () {
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
        }
        else {
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
            AC_SERVICES.getService('Tooltips').init();
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
}());
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

var Tooltips = /** @class */ (function () {
    function Tooltips() {
        this.init();
    }
    Tooltips.prototype.init = function () {
        Object(_plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__["initAcTooltips"])();
    };
    return Tooltips;
}());
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
var ShowMore = /** @class */ (function () {
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
        }
        else {
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
}());
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
var Tooltip = /** @class */ (function () {
    function Tooltip(el, content) {
        if (content === void 0) { content = ''; }
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
            _this.tip.style.left = ((viewportOffset.left - bodyOffset.left) + _this.element.offsetWidth / 2) + 'px';
            _this.tip.style.top = ((viewportOffset.top - bodyOffset.top) + _this.element.offsetHeight) + 'px';
            _this.tip.classList.add('hover');
        });
        this.element.addEventListener('mouseleave', function () {
            _this.tip.classList.remove('hover');
        });
    };
    return Tooltip;
}());

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
/* harmony import */ var _modules_modals__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./modules/modals */ "./js/modules/modals.ts");
/* harmony import */ var _modules_ac_pointer__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./modules/ac-pointer */ "./js/modules/ac-pointer.ts");




// @ts-ignore








var AC_SERVICES = Object(_helpers_admin_columns__WEBPACK_IMPORTED_MODULE_9__["initAcServices"])();
AC_SERVICES.registerService('Modals', new _modules_modals__WEBPACK_IMPORTED_MODULE_10__["default"]());
jquery__WEBPACK_IMPORTED_MODULE_4___default()(document).ready(function () {
    var table = Object(_helpers_table__WEBPACK_IMPORTED_MODULE_8__["resolveTableBySelector"])(AC.table_id);
    Object(_modules_ac_pointer__WEBPACK_IMPORTED_MODULE_11__["initPointers"])();
    if (table) {
        var TableModule = (new _table_table__WEBPACK_IMPORTED_MODULE_0__["default"](table, AC_SERVICES)).init();
        AC_SERVICES.registerService('Table', TableModule);
        AC_SERVICES.registerService('ScreenOptionsColumns', new _table_screen_options_columns__WEBPACK_IMPORTED_MODULE_2__["default"](TableModule.Columns));
    }
    AC_SERVICES.registerService('Tooltips', new _modules_tooltips__WEBPACK_IMPORTED_MODULE_1__["default"]());
    document.querySelectorAll('.ac-toggle-box-link').forEach(function (el) {
        new _modules_toggle_box_link__WEBPACK_IMPORTED_MODULE_3__["default"](el);
    });
    jquery__WEBPACK_IMPORTED_MODULE_4___default()('.wp-list-table').on('updated', 'tr', function () {
        AC_SERVICES.getService('Table').addCellClasses();
        Object(_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__["auto_init_show_more"])();
    });
    // TODO use more global event name instead of IE
    jquery__WEBPACK_IMPORTED_MODULE_4___default()('.wp-list-table td').on('ACP_InlineEditing_After_SetValue', function () {
        Object(_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__["auto_init_show_more"])();
    });
});
AC_SERVICES.addListener(_constants__WEBPACK_IMPORTED_MODULE_7__["EventConstants"].TABLE.READY, function (event) {
    Object(_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__["auto_init_show_more"])();
    Object(_table_functions__WEBPACK_IMPORTED_MODULE_6__["init_actions_tooltips"])();
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(function (node) {
                if (node.tagName === 'TR' && node.classList.contains('iedit')) {
                    jquery__WEBPACK_IMPORTED_MODULE_4___default()(node).trigger('updated', { id: Object(_helpers_table__WEBPACK_IMPORTED_MODULE_8__["getIdFromTableRow"])(node), row: node });
                }
            });
        });
    });
    observer.observe(event.table.getElement(), { childList: true, subtree: true });
});


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
var Actions = /** @class */ (function () {
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
}());
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
var Cell = /** @class */ (function () {
    function Cell(id, name, el) {
        this.object_id = id;
        this.column_name = name;
        this.original_value = el.innerHTML;
        this.el = el;
        this.services = {};
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
    Cell.prototype.setElement = function (element) {
        this.el = element;
    };
    Cell.prototype.getRow = function () {
        return this.el.parentElement;
    };
    Cell.prototype.getSettings = function () {
        return AC_SERVICES.getService('Table').Columns.get(this.getName());
    };
    Cell.prototype.hasChanged = function (content) {
        return this.original_value !== content;
    };
    Cell.prototype.setValue = function (value) {
        this.original_value = value;
        this.el.innerHTML = value;
        return this;
    };
    Cell.prototype.setService = function (name, service) {
        this.services[name] = service;
    };
    Cell.prototype.getService = function (name) {
        return this.hasService(name) ? this.services[name] : null;
    };
    Cell.prototype.hasService = function (name) {
        return this.services.hasOwnProperty(name);
    };
    return Cell;
}());
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
var Cells = /** @class */ (function () {
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
        Object.keys(cells).forEach(function (name) { return result.push(cells[name]); });
        return result;
    };
    Cells.prototype.getAll = function () {
        var _this = this;
        var results = [];
        Object.keys(this.cells).forEach(function (id) {
            var cells = _this.cells[id];
            Object.keys(cells).forEach(function (name) { return results.push(cells[name]); });
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
}());
/* harmony default export */ __webpack_exports__["default"] = (Cells);


/***/ }),

/***/ "./js/table/columns.ts":
/*!*****************************!*\
  !*** ./js/table/columns.ts ***!
  \*****************************/
/*! exports provided: default, ColumnTableSettings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ColumnTableSettings", function() { return ColumnTableSettings; });
var Columns = /** @class */ (function () {
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
            self.columns[headers[i].id] = new ColumnTableSettings(headerName, AC.column_types[headerName], this.sanitizeLabel(headers[i]));
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
        var label = header.innerHTML;
        // If it contains a link, we presume that it is because of sorting
        if (link) {
            var elements = link.getElementsByTagName('span');
            if (elements.length > 0) {
                label = elements[0].innerHTML;
            }
        }
        return label;
    };
    return Columns;
}());
/* harmony default export */ __webpack_exports__["default"] = (Columns);
var ColumnTableSettings = /** @class */ (function () {
    function ColumnTableSettings(name, type, label) {
        this.name = name;
        this.type = type;
        this.label = label;
        this.services = {};
    }
    ColumnTableSettings.prototype.setService = function (name, service) {
        this.services[name] = service;
    };
    ColumnTableSettings.prototype.getService = function (name) {
        return this.hasService(name) ? this.services[name] : null;
    };
    ColumnTableSettings.prototype.hasService = function (name) {
        return this.services.hasOwnProperty(name);
    };
    return ColumnTableSettings;
}());



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
var RowSelection = /** @class */ (function () {
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
}());
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
var ScreenOptionsColumns = /** @class */ (function () {
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
}());
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







var Table = /** @class */ (function () {
    function Table(el, services) {
        this.el = el;
        this.Services = services;
        this.Columns = new _columns__WEBPACK_IMPORTED_MODULE_2__["default"](el);
        this.Cells = new _cells__WEBPACK_IMPORTED_MODULE_1__["default"]();
        this.Actions = document.getElementById('ac-table-actions') ? new _actions__WEBPACK_IMPORTED_MODULE_0__["default"](document.getElementById('ac-table-actions')) : null;
        this.Selection = new _row_selection__WEBPACK_IMPORTED_MODULE_4__["default"](this);
    }
    Table.prototype.getElement = function () {
        return this.el;
    };
    Table.prototype.getIdsFromTable = function () {
        var result = [];
        this.el.getElementsByTagName('tbody')[0].querySelectorAll('tr').forEach(function (row) {
            result.push(Object(_helpers_table__WEBPACK_IMPORTED_MODULE_5__["getIdFromTableRow"])(row));
        });
        return result;
    };
    Table.prototype.init = function () {
        this.initTable();
        this.addCellClasses();
        document.dispatchEvent(new CustomEvent('AC_Table_Ready', { detail: { table: this } }));
        this.Services.emitEvent(_constants__WEBPACK_IMPORTED_MODULE_6__["EventConstants"].TABLE.READY, { table: this });
        return this;
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
        var _this = this;
        this.el.getElementsByTagName('tbody')[0].querySelectorAll('tr').forEach(function (row) {
            _this.updateRow(row);
        });
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
     * @deprecated use Helper function instead
     * TODO remove once IE uses the helper
     */
    Table.prototype.getRowCellByName = function (row, column_name) {
        return Object(_helpers_table__WEBPACK_IMPORTED_MODULE_5__["getRowCellByName"])(row, column_name);
    };
    return Table;
}());
/* harmony default export */ __webpack_exports__["default"] = (Table);


/***/ }),

/***/ "./node_modules/nanoassert/index.js":
/*!******************************************!*\
  !*** ./node_modules/nanoassert/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

assert.notEqual = notEqual;
assert.notOk = notOk;
assert.equal = equal;
assert.ok = assert;
module.exports = assert;
function equal(a, b, m) {
    assert(a == b, m); // eslint-disable-line eqeqeq
}
function notEqual(a, b, m) {
    assert(a != b, m); // eslint-disable-line eqeqeq
}
function notOk(t, m) {
    assert(!t, m);
}
function assert(t, m) {
    if (!t)
        throw new Error(m || 'AssertionError');
}


/***/ }),

/***/ "./node_modules/nanobus/index.js":
/*!***************************************!*\
  !*** ./node_modules/nanobus/index.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var splice = __webpack_require__(/*! remove-array-items */ "./node_modules/remove-array-items/index.js");
var nanotiming = __webpack_require__(/*! nanotiming */ "./node_modules/nanotiming/browser.js");
var assert = __webpack_require__(/*! assert */ "./node_modules/nanoassert/index.js");
module.exports = Nanobus;
function Nanobus(name) {
    if (!(this instanceof Nanobus))
        return new Nanobus(name);
    this._name = name || 'nanobus';
    this._starListeners = [];
    this._listeners = {};
}
Nanobus.prototype.emit = function (eventName) {
    assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.emit: eventName should be type string or symbol');
    var data = [];
    for (var i = 1, len = arguments.length; i < len; i++) {
        data.push(arguments[i]);
    }
    var emitTiming = nanotiming(this._name + "('" + eventName.toString() + "')");
    var listeners = this._listeners[eventName];
    if (listeners && listeners.length > 0) {
        this._emit(this._listeners[eventName], data);
    }
    if (this._starListeners.length > 0) {
        this._emit(this._starListeners, eventName, data, emitTiming.uuid);
    }
    emitTiming();
    return this;
};
Nanobus.prototype.on = Nanobus.prototype.addListener = function (eventName, listener) {
    assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.on: eventName should be type string or symbol');
    assert.equal(typeof listener, 'function', 'nanobus.on: listener should be type function');
    if (eventName === '*') {
        this._starListeners.push(listener);
    }
    else {
        if (!this._listeners[eventName])
            this._listeners[eventName] = [];
        this._listeners[eventName].push(listener);
    }
    return this;
};
Nanobus.prototype.prependListener = function (eventName, listener) {
    assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.prependListener: eventName should be type string or symbol');
    assert.equal(typeof listener, 'function', 'nanobus.prependListener: listener should be type function');
    if (eventName === '*') {
        this._starListeners.unshift(listener);
    }
    else {
        if (!this._listeners[eventName])
            this._listeners[eventName] = [];
        this._listeners[eventName].unshift(listener);
    }
    return this;
};
Nanobus.prototype.once = function (eventName, listener) {
    assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.once: eventName should be type string or symbol');
    assert.equal(typeof listener, 'function', 'nanobus.once: listener should be type function');
    var self = this;
    this.on(eventName, once);
    function once() {
        listener.apply(self, arguments);
        self.removeListener(eventName, once);
    }
    return this;
};
Nanobus.prototype.prependOnceListener = function (eventName, listener) {
    assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.prependOnceListener: eventName should be type string or symbol');
    assert.equal(typeof listener, 'function', 'nanobus.prependOnceListener: listener should be type function');
    var self = this;
    this.prependListener(eventName, once);
    function once() {
        listener.apply(self, arguments);
        self.removeListener(eventName, once);
    }
    return this;
};
Nanobus.prototype.removeListener = function (eventName, listener) {
    assert.ok(typeof eventName === 'string' || typeof eventName === 'symbol', 'nanobus.removeListener: eventName should be type string or symbol');
    assert.equal(typeof listener, 'function', 'nanobus.removeListener: listener should be type function');
    if (eventName === '*') {
        this._starListeners = this._starListeners.slice();
        return remove(this._starListeners, listener);
    }
    else {
        if (typeof this._listeners[eventName] !== 'undefined') {
            this._listeners[eventName] = this._listeners[eventName].slice();
        }
        return remove(this._listeners[eventName], listener);
    }
    function remove(arr, listener) {
        if (!arr)
            return;
        var index = arr.indexOf(listener);
        if (index !== -1) {
            splice(arr, index, 1);
            return true;
        }
    }
};
Nanobus.prototype.removeAllListeners = function (eventName) {
    if (eventName) {
        if (eventName === '*') {
            this._starListeners = [];
        }
        else {
            this._listeners[eventName] = [];
        }
    }
    else {
        this._starListeners = [];
        this._listeners = {};
    }
    return this;
};
Nanobus.prototype.listeners = function (eventName) {
    var listeners = eventName !== '*'
        ? this._listeners[eventName]
        : this._starListeners;
    var ret = [];
    if (listeners) {
        var ilength = listeners.length;
        for (var i = 0; i < ilength; i++)
            ret.push(listeners[i]);
    }
    return ret;
};
Nanobus.prototype._emit = function (arr, eventName, data, uuid) {
    if (typeof arr === 'undefined')
        return;
    if (arr.length === 0)
        return;
    if (data === undefined) {
        data = eventName;
        eventName = null;
    }
    if (eventName) {
        if (uuid !== undefined) {
            data = [eventName].concat(data, uuid);
        }
        else {
            data = [eventName].concat(data);
        }
    }
    var length = arr.length;
    for (var i = 0; i < length; i++) {
        var listener = arr[i];
        listener.apply(listener, data);
    }
};


/***/ }),

/***/ "./node_modules/nanoscheduler/index.js":
/*!*********************************************!*\
  !*** ./node_modules/nanoscheduler/index.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var assert = __webpack_require__(/*! assert */ "./node_modules/nanoassert/index.js");
var hasWindow = typeof window !== 'undefined';
function createScheduler() {
    var scheduler;
    if (hasWindow) {
        if (!window._nanoScheduler)
            window._nanoScheduler = new NanoScheduler(true);
        scheduler = window._nanoScheduler;
    }
    else {
        scheduler = new NanoScheduler();
    }
    return scheduler;
}
function NanoScheduler(hasWindow) {
    this.hasWindow = hasWindow;
    this.hasIdle = this.hasWindow && window.requestIdleCallback;
    this.method = this.hasIdle ? window.requestIdleCallback.bind(window) : this.setTimeout;
    this.scheduled = false;
    this.queue = [];
}
NanoScheduler.prototype.push = function (cb) {
    assert.equal(typeof cb, 'function', 'nanoscheduler.push: cb should be type function');
    this.queue.push(cb);
    this.schedule();
};
NanoScheduler.prototype.schedule = function () {
    if (this.scheduled)
        return;
    this.scheduled = true;
    var self = this;
    this.method(function (idleDeadline) {
        var cb;
        while (self.queue.length && idleDeadline.timeRemaining() > 0) {
            cb = self.queue.shift();
            cb(idleDeadline);
        }
        self.scheduled = false;
        if (self.queue.length)
            self.schedule();
    });
};
NanoScheduler.prototype.setTimeout = function (cb) {
    setTimeout(cb, 0, {
        timeRemaining: function () {
            return 1;
        }
    });
};
module.exports = createScheduler;


/***/ }),

/***/ "./node_modules/nanotiming/browser.js":
/*!********************************************!*\
  !*** ./node_modules/nanotiming/browser.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var scheduler = __webpack_require__(/*! nanoscheduler */ "./node_modules/nanoscheduler/index.js")();
var assert = __webpack_require__(/*! assert */ "./node_modules/nanoassert/index.js");
var perf;
nanotiming.disabled = true;
try {
    perf = window.performance;
    nanotiming.disabled = window.localStorage.DISABLE_NANOTIMING === 'true' || !perf.mark;
}
catch (e) { }
module.exports = nanotiming;
function nanotiming(name) {
    assert.equal(typeof name, 'string', 'nanotiming: name should be type string');
    if (nanotiming.disabled)
        return noop;
    var uuid = (perf.now() * 10000).toFixed() % Number.MAX_SAFE_INTEGER;
    var startName = 'start-' + uuid + '-' + name;
    perf.mark(startName);
    function end(cb) {
        var endName = 'end-' + uuid + '-' + name;
        perf.mark(endName);
        scheduler.push(function () {
            var err = null;
            try {
                var measureName = name + ' [' + uuid + ']';
                perf.measure(measureName, startName, endName);
                perf.clearMarks(startName);
                perf.clearMarks(endName);
            }
            catch (e) {
                err = e;
            }
            if (cb)
                cb(err, name);
        });
    }
    end.uuid = uuid;
    return end;
}
function noop(cb) {
    if (cb) {
        scheduler.push(function () {
            cb(new Error('nanotiming: performance API unavailable'));
        });
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
module.exports = function removeItems(arr, startIdx, removeCount) {
    var i, length = arr.length;
    if (startIdx >= length || removeCount === 0) {
        return;
    }
    removeCount = (startIdx + removeCount > length ? length - startIdx : removeCount);
    var len = length - removeCount;
    for (i = startIdx; i < len; ++i) {
        arr[i] = arr[i + removeCount];
    }
    arr.length = len;
};


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