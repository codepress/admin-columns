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
const EventConstants = {
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

const initAcServices = () => {
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
/* harmony import */ var _html_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./html-element */ "./js/helpers/html-element.ts");

const insertAfter = (newNode, referenceNode) => {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
};
const insertBefore = (newNode, referenceNode) => {
    referenceNode.parentNode.insertBefore(newNode, referenceNode);
};
const createElementFromString = (content, baseElement = 'div') => {
    return _html_element__WEBPACK_IMPORTED_MODULE_0__["default"].create(baseElement).addHtml(content).element;
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
/*! exports provided: getParamFromUrl, mapDataToFormData, appendObjectToFormData */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getParamFromUrl", function() { return getParamFromUrl; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "mapDataToFormData", function() { return mapDataToFormData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "appendObjectToFormData", function() { return appendObjectToFormData; });
const getParamFromUrl = (param, url) => {
    if (!url.includes('?')) {
        return null;
    }
    const params = new URLSearchParams(url.split('?')[1]);
    return params.get(param);
};
const mapDataToFormData = (data, formData = null) => {
    if (!formData) {
        formData = new FormData();
    }
    Object.keys(data).forEach(key => {
        appendObjectToFormData(formData, data[key], key);
    });
    return formData;
};
const appendObjectToFormData = (formData, data, parentKey = null) => {
    if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
        Object.keys(data).forEach(key => {
            appendObjectToFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
        });
    }
    else {
        const value = data == null ? '' : data;
        formData.append(parentKey, value);
    }
};


/***/ }),

/***/ "./js/helpers/html-element.ts":
/*!************************************!*\
  !*** ./js/helpers/html-element.ts ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return AcHtmlElement; });
class AcHtmlElement {
    constructor(el) {
        this.element = el instanceof HTMLElement ? el : document.createElement(el);
    }
    static create(el) {
        return new AcHtmlElement(el);
    }
    addId(id) {
        this.element.id = id;
        return this;
    }
    addClass(className) {
        this.element.classList.add(className);
        return this;
    }
    addClasses(...classNames) {
        classNames.forEach(className => this.addClass(className));
        return this;
    }
    addHtml(html) {
        this.element.innerHTML = html;
        return this;
    }
    css(property, value) {
        this.element.style[property] = value;
        return this;
    }
}


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

const getIdFromTableRow = (row) => {
    if (row.classList.contains('no-items')) {
        return 0;
    }
    let item_id = getIdFromString(row.id);
    if (!item_id) {
        let input = row.querySelector('.check-column input[type=checkbox]');
        if (input) {
            item_id = getIdFromString(input.id);
        }
    }
    // Try to get the ID from the edit URL (MS Sites)
    if (!item_id) {
        let link = row.parentElement.querySelector('.edit a');
        if (link) {
            let href = link.getAttribute('href');
            if (href) {
                item_id = parseInt(Object(_global__WEBPACK_IMPORTED_MODULE_0__["getParamFromUrl"])('id', href));
            }
        }
    }
    row.dataset.id = item_id.toString();
    return item_id;
};
const getIdFromString = (value) => {
    let id_parts = value.split(/[_,\-]+/);
    return parseInt(id_parts[id_parts.length - 1]);
};
const getRowCellByName = (row, column_name) => {
    return row.querySelector(`td.column-${column_name}`);
};
const resolveTableBySelector = (selector) => {
    let table = document.querySelector(selector);
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

const $ = __webpack_require__(/*! jquery */ "jquery");
class Pointer {
    constructor(el) {
        this.element = el;
        this.settings = this.getDefaults();
        this.init();
        this.setInitialized();
    }
    setInitialized() {
        AcPointers.add(this.element);
    }
    getDefaults() {
        return {
            width: this.element.getAttribute('data-width') ? this.element.getAttribute('data-width') : 250,
            noclick: this.element.getAttribute('data-noclick') ? this.element.getAttribute('data-noclick') : false,
            position: this.getPosition()
        };
    }
    isInitialized() {
        return AcPointers.isInitialized(this.element);
    }
    init() {
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
    }
    getPosition() {
        let position = {
            at: 'left top',
            my: 'right top',
            edge: 'right', // position of arrow
        };
        let pos = this.element.getAttribute('data-pos');
        let edge = this.element.getAttribute('data-pos_edge');
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
    }
    getPointerClass() {
        let classes = [
            'ac-wp-pointer',
            'wp-pointer',
            'wp-pointer-' + this.settings.position.edge
        ];
        if (this.settings.noclick) {
            classes.push('noclick');
        }
        return classes.join(' ');
    }
    getRelatedHTML() {
        let related_element = document.getElementById(this.element.getAttribute('rel'));
        return related_element ? related_element.innerHTML : '';
    }
    initEvents() {
        let el = $(this.element);
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
            setTimeout(() => {
                el.pointer('open');
            }, 2);
        });
        el.mouseleave(function () {
            setTimeout(() => {
                if (!el.hasClass('open') && $('.ac-wp-pointer.hover').length === 0) {
                    el.pointer('close');
                }
            }, 1);
        });
        el.on('close', () => {
            setTimeout(() => {
                if (!el.hasClass('open')) {
                    el.pointer('close');
                }
            });
        });
    }
}
class AcPointers {
    static isInitialized(element) {
        return this.initElements.filter(el => el === element).length > 0;
    }
    static add(element) {
        this.initElements.push(element);
    }
}
AcPointers.initElements = [];
const initPointers = (elements = null) => {
    if (!elements) {
        elements = document.querySelectorAll('.ac-pointer');
    }
    elements.forEach(element => {
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
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return AcServices; });
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(nanobus__WEBPACK_IMPORTED_MODULE_0__);

class AcServices {
    constructor() {
        this.services = {};
        this.events = new nanobus__WEBPACK_IMPORTED_MODULE_0___default.a();
    }
    registerService(name, service) {
        this.services[name] = service;
        return this;
    }
    getService(name) {
        return this.hasService(name) ? this.services[name] : null;
    }
    hasService(name) {
        return this.services.hasOwnProperty(name);
    }
    addListener(name, callback) {
        this.events.addListener(name, callback);
    }
    emitEvent(name, args) {
        this.events.emit(name, args);
    }
}


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
    getElement() {
        return this.el;
    }
    initEvents() {
        let self = this;
        document.addEventListener('keydown', (e) => {
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
            dismissButtons.forEach((b) => {
                b.addEventListener('click', (e) => {
                    e.preventDefault();
                    self.close();
                });
            });
        }
        this.el.addEventListener('click', (e) => {
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
    onClose() {
    }
    onOpen() {
    }
}


/***/ }),

/***/ "./js/modules/modals.ts":
/*!******************************!*\
  !*** ./js/modules/modals.ts ***!
  \******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Modals; });
/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modal */ "./js/modules/modal.ts");

class Modals {
    constructor() {
        this.modals = {};
        this.number = 0;
        this.defaults = {
            modal: _modal__WEBPACK_IMPORTED_MODULE_0__["default"]
        };
        this.initGlobalEvents();
    }
    register(modal, key = '') {
        if (!key) {
            key = 'm' + this.number;
        }
        this.modals[key] = modal;
        this.number++;
        return modal;
    }
    get(key) {
        return this.modals.hasOwnProperty(key) ? this.modals[key] : null;
    }
    open(key) {
        if (this.get(key)) {
            this.get(key).open();
        }
    }
    close(key) {
        if (this.get(key)) {
            this.get(key).close();
        }
    }
    closeAll() {
        for (let key in this.modals) {
            this.close(key);
        }
    }
    initGlobalEvents() {
        document.addEventListener('click', (e) => {
            let target = e.target;
            if (target.dataset.acModal) {
                e.preventDefault();
                this.open(target.dataset.acModal);
            }
        });
    }
}


/***/ }),

/***/ "./js/modules/service-container.ts":
/*!*****************************************!*\
  !*** ./js/modules/service-container.ts ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ServiceContainer; });
class ServiceContainer {
    constructor() {
        this.services = {};
    }
    setService(name, service) {
        this.services[name] = service;
    }
    getService(name) {
        return this.hasService(name) ? this.services[name] : null;
    }
    hasService(name) {
        return this.services.hasOwnProperty(name);
    }
}


/***/ }),

/***/ "./js/modules/toggle-box-link.ts":
/*!***************************************!*\
  !*** ./js/modules/toggle-box-link.ts ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ToggleBoxLink; });
/* harmony import */ var _helpers_elements__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/elements */ "./js/helpers/elements.ts");

const $ = __webpack_require__(/*! jquery */ "jquery");
class ToggleBoxLink {
    constructor(element) {
        this.element = element;
        this.initEvents();
        this.contentBox = this.element.parentElement.querySelector('.ac-toggle-box-contents');
        if (!this.contentBox) {
            this.createContenBox();
        }
    }
    isAjax() {
        return parseInt(this.element.dataset.ajaxPopulate) === 1;
    }
    isInited() {
        return this.element.dataset.toggleBoxInit;
    }
    createContenBox() {
        let contentBox = document.createElement('div');
        contentBox.classList.add('ac-toggle-box-contents');
        Object(_helpers_elements__WEBPACK_IMPORTED_MODULE_0__["insertAfter"])(contentBox, this.element);
        this.contentBox = contentBox;
        return this.contentBox;
    }
    initEvents() {
        if (this.isInited()) {
            return;
        }
        this.element.addEventListener('click', (e) => {
            e.preventDefault();
            if (this.isAjax() && !this.hasContent()) {
                this.manageAjaxValue();
            }
            this.toggleContentBox();
        });
        this.element.dataset.toggleBoxInit = 'true';
    }
    hasContent() {
        return this.getContentBox().innerHTML.length > 0;
    }
    setContent(content) {
        this.getContentBox().innerHTML = content;
    }
    getContentBox() {
        if (!this.contentBox) {
            return this.createContenBox();
        }
        return this.contentBox;
    }
    setLabel(open) {
        let label = this.element.dataset.label;
        if (open && this.element.dataset.labelClose) {
            label = this.element.dataset.labelClose;
        }
        this.element.innerHTML = label + '<span class="spinner"></span>';
    }
    toggleContentBox() {
        if (this.getContentBox().classList.contains('-open')) {
            this.getContentBox().classList.remove('-open');
            this.setLabel(false);
        }
        else {
            this.getContentBox().classList.add('-open');
            this.setLabel(true);
        }
    }
    manageAjaxValue() {
        this.element.classList.add('loading');
        this.retrieveAjaxValue().done((response) => {
            this.setContent(response);
            $(this.element.parentElement).trigger('ajax_column_value_ready');
            AC_SERVICES.getService('Tooltips').init();
        }).always(() => {
            this.element.classList.remove('loading');
        });
    }
    retrieveAjaxValue() {
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

/***/ "./js/plugin/show-more.ts":
/*!********************************!*\
  !*** ./js/plugin/show-more.ts ***!
  \********************************/
/*! exports provided: auto_init_show_more, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "auto_init_show_more", function() { return auto_init_show_more; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ShowMore; });
const auto_init_show_more = () => {
    document.querySelectorAll('.ac-show-more').forEach((el) => {
        new ShowMore(el);
    });
};
class ShowMore {
    constructor(el) {
        this.element = el;
        this.initEvents();
    }
    initEvents() {
        if (this.isInited()) {
            return;
        }
        if (this.getToggler()) {
            this.getToggler().addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();
                this.toggle();
            });
        }
        this.element.dataset.showMoreInit = 'true';
    }
    getToggler() {
        return this.element.querySelector('.ac-show-more__toggle');
    }
    isInited() {
        return this.element.dataset.showMoreInit === 'true';
    }
    toggle() {
        if (this.element.classList.contains('-on')) {
            this.hide();
        }
        else {
            this.show();
        }
    }
    show() {
        this.element.classList.add('-on');
        this.getToggler().innerHTML = this.getToggler().dataset.less;
    }
    hide() {
        this.element.classList.remove('-on');
        this.getToggler().innerHTML = this.getToggler().dataset.more;
    }
}


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
    document.querySelectorAll('[data-ac-tip]').forEach((element) => {
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
            this.tip.style.left = ((viewportOffset.left - bodyOffset.left) + this.element.offsetWidth / 2) + 'px';
            this.tip.style.top = ((viewportOffset.top - bodyOffset.top) + this.element.offsetHeight) + 'px';
            this.tip.classList.add('hover');
        });
        this.element.addEventListener('mouseleave', () => {
            this.tip.classList.remove('hover');
        });
    }
}
const createTooltip = (content) => {
    let tip = document.createElement('div');
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








let AC_SERVICES = Object(_helpers_admin_columns__WEBPACK_IMPORTED_MODULE_9__["initAcServices"])();
AC_SERVICES.registerService('Modals', new _modules_modals__WEBPACK_IMPORTED_MODULE_10__["default"]());
document.addEventListener('DOMContentLoaded', () => {
    let table = Object(_helpers_table__WEBPACK_IMPORTED_MODULE_8__["resolveTableBySelector"])(AC.table_id);
    Object(_modules_ac_pointer__WEBPACK_IMPORTED_MODULE_11__["initPointers"])();
    if (table) {
        const TableModule = new _table_table__WEBPACK_IMPORTED_MODULE_0__["default"](table, AC_SERVICES).init();
        AC_SERVICES.registerService('Table', TableModule);
        AC_SERVICES.registerService('ScreenOptionsColumns', new _table_screen_options_columns__WEBPACK_IMPORTED_MODULE_2__["default"](TableModule.Columns));
    }
    AC_SERVICES.registerService('Tooltips', new _modules_tooltips__WEBPACK_IMPORTED_MODULE_1__["default"]());
    document.querySelectorAll('.ac-toggle-box-link').forEach(el => {
        new _modules_toggle_box_link__WEBPACK_IMPORTED_MODULE_3__["default"](el);
    });
    jquery__WEBPACK_IMPORTED_MODULE_4___default()('.wp-list-table').on('updated', 'tr', function () {
        AC_SERVICES.getService('Table').addCellClasses();
        Object(_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__["auto_init_show_more"])();
    });
});
AC_SERVICES.addListener(_constants__WEBPACK_IMPORTED_MODULE_7__["EventConstants"].TABLE.READY, (event) => {
    Object(_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__["auto_init_show_more"])();
    Object(_table_functions__WEBPACK_IMPORTED_MODULE_6__["init_actions_tooltips"])();
    let observer = new MutationObserver(mutations => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.tagName === 'TR' && node.classList.contains('iedit')) {
                    jquery__WEBPACK_IMPORTED_MODULE_4___default()(node).trigger('updated', { id: Object(_helpers_table__WEBPACK_IMPORTED_MODULE_8__["getIdFromTableRow"])(node), row: node });
                }
            });
        });
    });
    observer.observe(event.table.getElement(), { childList: true, subtree: true });
    event.table.Cells.getAll().forEach(cell => {
        cell.events.addListener('setValue', () => {
            Object(_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__["auto_init_show_more"])();
        });
    });
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
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Actions; });
/* harmony import */ var _helpers_elements__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/elements */ "./js/helpers/elements.ts");

var nanobus = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
class Actions {
    constructor(element) {
        this.container = element;
        this.events = nanobus();
        this.init();
    }
    init() {
        this.container.addEventListener('update', () => {
            this.refresh();
        });
        let reference = document.querySelectorAll('.tablenav.top .actions');
        if (reference && reference.length) {
            Object(_helpers_elements__WEBPACK_IMPORTED_MODULE_0__["insertAfter"])(this.container, reference[reference.length - 1]);
            this.container.classList.add('-init');
            this.container.dispatchEvent(new CustomEvent('update'));
        }
    }
    refresh() {
        this.container.querySelectorAll('.ac-table-actions-buttons > a').forEach((element) => {
            element.classList.remove('last');
        });
        let buttons = [].slice.call(this.container.querySelectorAll('.ac-table-actions-buttons > a'), 0);
        buttons.reverse();
        for (var i = 0; i < buttons.length; i++) {
            if (buttons[i].offsetParent) {
                buttons[i].classList.add('last');
                break;
            }
        }
    }
}


/***/ }),

/***/ "./js/table/cell.ts":
/*!**************************!*\
  !*** ./js/table/cell.ts ***!
  \**************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Cell; });
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(nanobus__WEBPACK_IMPORTED_MODULE_0__);

class Cell {
    constructor(id, name, el) {
        this.object_id = id;
        this.column_name = name;
        this.original_value = el.innerHTML;
        this.el = el;
        this.services = {};
        this.events = new nanobus__WEBPACK_IMPORTED_MODULE_0___default.a();
    }
    getOriginalValue() {
        return this.original_value;
    }
    getObjectID() {
        return this.object_id;
    }
    getName() {
        return this.column_name;
    }
    getElement() {
        return this.el;
    }
    setElement(element) {
        this.el = element;
    }
    getRow() {
        return this.el.parentElement;
    }
    getSettings() {
        return AC_SERVICES.getService('Table').Columns.get(this.getName());
    }
    hasChanged(content) {
        return this.original_value !== content;
    }
    setValue(value) {
        let rowActions = this.el.querySelector('.row-actions');
        this.original_value = value;
        this.el.innerHTML = value;
        if (rowActions) {
            this.el.append(rowActions);
        }
        this.original_value = value;
        this.events.emit('setValue', this);
        return this;
    }
    setService(name, service) {
        this.services[name] = service;
    }
    getService(name) {
        return this.hasService(name) ? this.services[name] : null;
    }
    hasService(name) {
        return this.services.hasOwnProperty(name);
    }
}


/***/ }),

/***/ "./js/table/cells.ts":
/*!***************************!*\
  !*** ./js/table/cells.ts ***!
  \***************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Cells; });
class Cells {
    constructor() {
        this.cells = {};
    }
    add(id, cell) {
        if (!this.cells.hasOwnProperty(id)) {
            this.cells[id] = {};
        }
        this.cells[id][cell.getName()] = cell;
    }
    getByID(id) {
        let result = [];
        if (!this.cells.hasOwnProperty(id.toString())) {
            return result;
        }
        let cells = this.cells[id.toString()];
        Object.keys(cells).forEach(name => result.push(cells[name]));
        return result;
    }
    getAll() {
        let results = [];
        Object.keys(this.cells).forEach(id => {
            let cells = this.cells[id];
            Object.keys(cells).forEach(name => results.push(cells[name]));
        });
        return results;
    }
    getByName(name) {
        let results = [];
        Object.keys(this.cells).forEach(id => {
            let cells = this.cells[id];
            Object.keys(cells).forEach(column_name => {
                if (name === column_name) {
                    results.push(cells[column_name]);
                }
            });
        });
        return results;
    }
    get(id, name) {
        return this.cells.hasOwnProperty(id.toString()) ? this.cells[id][name] : null;
    }
}


/***/ }),

/***/ "./js/table/columns.ts":
/*!*****************************!*\
  !*** ./js/table/columns.ts ***!
  \*****************************/
/*! exports provided: default, ColumnTableSettings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Columns; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ColumnTableSettings", function() { return ColumnTableSettings; });
class Columns {
    constructor(table) {
        this.table = table;
        this.columns = {};
        this.init();
    }
    init() {
        let self = this;
        let thead = this.table.querySelector('thead');
        let headers = thead.querySelectorAll('th');
        for (let i = 0; i < headers.length; i++) {
            let headerName = headers[i].id;
            self.columns[headers[i].id] = new ColumnTableSettings(headerName, AC.column_types[headerName], this.sanitizeLabel(headers[i]));
        }
    }
    getColumns() {
        return this.columns;
    }
    getColumnsMap() {
        let map = new Map();
        let columns = this.getColumns();
        Object.keys(columns).forEach((k) => {
            map.set(k, columns[k]);
        });
        return map;
    }
    getColumnNames() {
        return Object.keys(this.columns);
    }
    get(column_name) {
        return this.columns.hasOwnProperty(column_name) ? this.columns[column_name] : null;
    }
    sanitizeLabel(header) {
        let link = header.querySelector('a');
        let label = header.innerHTML;
        // If it contains a link, we presume that it is because of sorting
        if (link) {
            let elements = link.getElementsByTagName('span');
            if (elements.length > 0) {
                label = elements[0].innerHTML;
            }
        }
        return label;
    }
}
class ColumnTableSettings {
    constructor(name, type, label) {
        this.name = name;
        this.type = type;
        this.label = label;
        this.services = {};
    }
    setService(name, service) {
        this.services[name] = service;
    }
    getService(name) {
        return this.hasService(name) ? this.services[name] : null;
    }
    hasService(name) {
        return this.services.hasOwnProperty(name);
    }
}


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

const init_actions_tooltips = () => {
    document.querySelectorAll('.cpac_use_icons').forEach((el) => {
        el.parentElement.querySelectorAll('.row-actions a').forEach((el) => {
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
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return RowSelection; });
class RowSelection {
    constructor(table) {
        this.Table = table;
    }
    getIDs() {
        let ids = [];
        let checked = this.Table.getElement().querySelectorAll('tbody th.check-column input[type=checkbox]:checked');
        if (checked.length === 0) {
            return ids;
        }
        for (let i = 0; i < checked.length; i++) {
            ids.push(parseInt(checked[i].value));
        }
        return ids;
    }
    /**
     * Get selected cells for specific column
     */
    getSelectedCells(name) {
        let ids = this.getIDs();
        if (ids.length === 0) {
            return null;
        }
        let cells = [];
        ids.forEach((id) => {
            let cell = this.Table.Cells.get(id, name);
            if (cell) {
                cells.push(cell);
            }
        });
        return cells;
    }
    getCount() {
        return this.getIDs().length;
    }
    isAllSelected() {
        return !!this.Table.getElement().querySelector('thead #cb input:checked');
    }
}


/***/ }),

/***/ "./js/table/screen-options-columns.ts":
/*!********************************************!*\
  !*** ./js/table/screen-options-columns.ts ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return ScreenOptionsColumns; });
/* harmony import */ var _helpers_html_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/html-element */ "./js/helpers/html-element.ts");

class ScreenOptionsColumns {
    constructor(columns) {
        this.columns = columns;
        columns.getColumnNames().forEach((column_name) => {
            let column = columns.get(column_name);
            let input = ScreenOptionsColumns.getInputByName(column.name);
            if (input && input.parentElement.textContent.length === 0) {
                input.parentElement.appendChild(_helpers_html_element__WEBPACK_IMPORTED_MODULE_0__["default"].create('span').addHtml(column.label).element);
            }
        });
    }
    static getInputByName(name) {
        let input = document.querySelector(`input[name='${name}-hide']`);
        return input ? input : false;
    }
}


/***/ }),

/***/ "./js/table/table.ts":
/*!***************************!*\
  !*** ./js/table/table.ts ***!
  \***************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Table; });
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./actions */ "./js/table/actions.ts");
/* harmony import */ var _cells__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cells */ "./js/table/cells.ts");
/* harmony import */ var _columns__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./columns */ "./js/table/columns.ts");
/* harmony import */ var _cell__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./cell */ "./js/table/cell.ts");
/* harmony import */ var _row_selection__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./row-selection */ "./js/table/row-selection.ts");
/* harmony import */ var _helpers_table__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../helpers/table */ "./js/helpers/table.ts");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../constants */ "./js/constants.ts");
/* harmony import */ var _modules_service_container__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../modules/service-container */ "./js/modules/service-container.ts");








class Table {
    constructor(el, services) {
        this.el = el;
        this.AcServices = services;
        this.Services = new _modules_service_container__WEBPACK_IMPORTED_MODULE_7__["default"]();
        this.Columns = new _columns__WEBPACK_IMPORTED_MODULE_2__["default"](el);
        this.Cells = new _cells__WEBPACK_IMPORTED_MODULE_1__["default"]();
        this.Actions = document.getElementById('ac-table-actions') ? new _actions__WEBPACK_IMPORTED_MODULE_0__["default"](document.getElementById('ac-table-actions')) : null;
        this.Selection = new _row_selection__WEBPACK_IMPORTED_MODULE_4__["default"](this);
    }
    getElement() {
        return this.el;
    }
    getIdsFromTable() {
        let result = [];
        this.el.getElementsByTagName('tbody')[0].querySelectorAll('tr').forEach(row => {
            result.push(Object(_helpers_table__WEBPACK_IMPORTED_MODULE_5__["getIdFromTableRow"])(row));
        });
        return result;
    }
    init() {
        this.initTable();
        this.addCellClasses();
        document.dispatchEvent(new CustomEvent('AC_Table_Ready', { detail: { table: this } }));
        this.AcServices.emitEvent(_constants__WEBPACK_IMPORTED_MODULE_6__["EventConstants"].TABLE.READY, { table: this });
        return this;
    }
    addCellClasses() {
        this.Columns.getColumnNames().forEach((name) => {
            let type = this.Columns.get(name).type;
            let cells = this.Cells.getByName(name);
            cells.forEach((cell) => {
                cell.getElement().classList.add(type);
            });
        });
    }
    initTable() {
        this.el.getElementsByTagName('tbody')[0].querySelectorAll('tr').forEach(row => {
            this.updateRow(row);
        });
    }
    updateRow(row) {
        let id = Object(_helpers_table__WEBPACK_IMPORTED_MODULE_5__["getIdFromTableRow"])(row);
        row.dataset.id = id.toString();
        this.setCellsForRow(row);
    }
    setCellsForRow(row) {
        let id = Object(_helpers_table__WEBPACK_IMPORTED_MODULE_5__["getIdFromTableRow"])(row);
        this.Columns.getColumnNames().forEach((name) => {
            let selector = name.replace(/\./g, '\\.');
            let td = row.querySelector("td.column-" + selector);
            if (td) {
                let cell = new _cell__WEBPACK_IMPORTED_MODULE_3__["default"](id, name, td);
                this.Cells.add(id, cell);
            }
        });
    }
}


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