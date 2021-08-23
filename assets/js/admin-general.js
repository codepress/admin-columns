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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/admin-general.ts");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/admin-general.ts":
/*!*****************************!*\
  !*** ./js/admin-general.ts ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_ac_section__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/ac-section */ "./js/modules/ac-section.ts");
/* harmony import */ var _modules_ac_pointer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/ac-pointer */ "./js/modules/ac-pointer.ts");


const $ = __webpack_require__(/*! jquery */ "jquery");
$(document).ready(() => {
    Object(_modules_ac_pointer__WEBPACK_IMPORTED_MODULE_1__["initPointers"])();
    document.querySelectorAll('.ac-section').forEach(el => {
        new _modules_ac_section__WEBPACK_IMPORTED_MODULE_0__["default"](el);
    });
});


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

/***/ "./js/modules/ac-section.ts":
/*!**********************************!*\
  !*** ./js/modules/ac-section.ts ***!
  \**********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return AcSection; });
let Cookies = __webpack_require__(/*! js-cookie */ "./node_modules/js-cookie/src/js.cookie.js");
class AcSection {
    constructor(el) {
        this.element = el;
        this.init();
    }
    init() {
        if (this.element.classList.contains('-closable')) {
            const header = this.element.querySelector('.ac-section__header');
            if (header) {
                header.addEventListener('click', () => {
                    this.toggle();
                });
            }
            if (this.isStorable()) {
                let setting = Cookies.get(this.getCookieKey());
                if (setting !== undefined) {
                    (parseInt(setting) === 1) ? this.open : this.close();
                }
            }
        }
    }
    getCookieKey() {
        return `ac-section_${this.getSectionId()}`;
    }
    getSectionId() {
        return this.element.dataset.section;
    }
    isStorable() {
        return typeof this.element.dataset.section !== 'undefined';
    }
    toggle() {
        this.isOpen() ? this.close() : this.open();
    }
    isOpen() {
        return !this.element.classList.contains('-closed');
    }
    open() {
        this.element.classList.remove('-closed');
        if (this.isStorable()) {
            Cookies.set(this.getCookieKey(), 1);
        }
    }
    close() {
        this.element.classList.add('-closed');
        if (this.isStorable()) {
            Cookies.set(this.getCookieKey(), 0);
        }
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

/***/ "./node_modules/js-cookie/src/js.cookie.js":
/*!*************************************************!*\
  !*** ./node_modules/js-cookie/src/js.cookie.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * JavaScript Cookie v2.2.1
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
;
(function (factory) {
    var registeredInModuleLoader;
    if (true) {
        !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
        registeredInModuleLoader = true;
    }
    if (true) {
        module.exports = factory();
        registeredInModuleLoader = true;
    }
    if (!registeredInModuleLoader) {
        var OldCookies = window.Cookies;
        var api = window.Cookies = factory();
        api.noConflict = function () {
            window.Cookies = OldCookies;
            return api;
        };
    }
}(function () {
    function extend() {
        var i = 0;
        var result = {};
        for (; i < arguments.length; i++) {
            var attributes = arguments[i];
            for (var key in attributes) {
                result[key] = attributes[key];
            }
        }
        return result;
    }
    function decode(s) {
        return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
    }
    function init(converter) {
        function api() { }
        function set(key, value, attributes) {
            if (typeof document === 'undefined') {
                return;
            }
            attributes = extend({
                path: '/'
            }, api.defaults, attributes);
            if (typeof attributes.expires === 'number') {
                attributes.expires = new Date(new Date() * 1 + attributes.expires * 864e+5);
            }
            // We're using "expires" because "max-age" is not supported by IE
            attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';
            try {
                var result = JSON.stringify(value);
                if (/^[\{\[]/.test(result)) {
                    value = result;
                }
            }
            catch (e) { }
            value = converter.write ?
                converter.write(value, key) :
                encodeURIComponent(String(value))
                    .replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);
            key = encodeURIComponent(String(key))
                .replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
                .replace(/[\(\)]/g, escape);
            var stringifiedAttributes = '';
            for (var attributeName in attributes) {
                if (!attributes[attributeName]) {
                    continue;
                }
                stringifiedAttributes += '; ' + attributeName;
                if (attributes[attributeName] === true) {
                    continue;
                }
                // Considers RFC 6265 section 5.2:
                // ...
                // 3.  If the remaining unparsed-attributes contains a %x3B (";")
                //     character:
                // Consume the characters of the unparsed-attributes up to,
                // not including, the first %x3B (";") character.
                // ...
                stringifiedAttributes += '=' + attributes[attributeName].split(';')[0];
            }
            return (document.cookie = key + '=' + value + stringifiedAttributes);
        }
        function get(key, json) {
            if (typeof document === 'undefined') {
                return;
            }
            var jar = {};
            // To prevent the for loop in the first place assign an empty array
            // in case there are no cookies at all.
            var cookies = document.cookie ? document.cookie.split('; ') : [];
            var i = 0;
            for (; i < cookies.length; i++) {
                var parts = cookies[i].split('=');
                var cookie = parts.slice(1).join('=');
                if (!json && cookie.charAt(0) === '"') {
                    cookie = cookie.slice(1, -1);
                }
                try {
                    var name = decode(parts[0]);
                    cookie = (converter.read || converter)(cookie, name) ||
                        decode(cookie);
                    if (json) {
                        try {
                            cookie = JSON.parse(cookie);
                        }
                        catch (e) { }
                    }
                    jar[name] = cookie;
                    if (key === name) {
                        break;
                    }
                }
                catch (e) { }
            }
            return key ? jar[key] : jar;
        }
        api.set = set;
        api.get = function (key) {
            return get(key, false /* read as raw */);
        };
        api.getJSON = function (key) {
            return get(key, true /* read as json */);
        };
        api.remove = function (key, attributes) {
            set(key, '', extend(attributes, {
                expires: -1
            }));
        };
        api.defaults = {};
        api.withConverter = init;
        return api;
    }
    return init(function () { });
}));


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
//# sourceMappingURL=admin-general.js.map