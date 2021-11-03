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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/notice-dismissible.ts");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/helpers/events.ts":
/*!******************************!*\
  !*** ./js/helpers/events.ts ***!
  \******************************/
/*! exports provided: addEventListenerLive, onHover, addEventListeners */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addEventListenerLive", function() { return addEventListenerLive; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "onHover", function() { return onHover; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addEventListeners", function() { return addEventListeners; });
const addEventListenerLive = (eventType, elementQuerySelector, cb, rootElement = null) => {
    let element = rootElement ? rootElement : document;
    element.addEventListener(eventType, (event) => {
        let qs = document.querySelectorAll(elementQuerySelector);
        if (qs) {
            var element = event.target, index = -1;
            while (element && ((index = Array.prototype.indexOf.call(qs, element)) === -1)) {
                element = element.parentElement;
            }
            if (index > -1) {
                cb.call(element, event);
            }
        }
    });
};
const onHover = (el, cbOver, cbLeave) => {
    el.addEventListener('mouseenter', cbOver);
    el.addEventListener('mouseleave', cbLeave);
};
const addEventListeners = (el, events, callback) => {
    events.forEach(event => el.addEventListener(event, callback));
};


/***/ }),

/***/ "./js/notice-dismissible.ts":
/*!**********************************!*\
  !*** ./js/notice-dismissible.ts ***!
  \**********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _plugin_dismissible_notice__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./plugin/dismissible-notice */ "./js/plugin/dismissible-notice.ts");

document.addEventListener('DOMContentLoaded', () => {
    Object(_plugin_dismissible_notice__WEBPACK_IMPORTED_MODULE_0__["initDismissibleNotices"])();
});


/***/ }),

/***/ "./js/plugin/dismissible-notice.ts":
/*!*****************************************!*\
  !*** ./js/plugin/dismissible-notice.ts ***!
  \*****************************************/
/*! exports provided: dismissNotice, initDismissibleNotices */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "dismissNotice", function() { return dismissNotice; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "initDismissibleNotices", function() { return initDismissibleNotices; });
/* harmony import */ var _helpers_events__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/events */ "./js/helpers/events.ts");

const $ = __webpack_require__(/*! jquery */ "jquery");
const dismissNotice = (selector) => {
    document.querySelectorAll(selector).forEach((el) => {
        Object(_helpers_events__WEBPACK_IMPORTED_MODULE_0__["addEventListenerLive"])('click', '.ac-notice__dismiss, [data-dismiss], .notice-dismiss', (e) => {
            e.preventDefault();
            let data = el.dataset.dismissibleCallback ? JSON.parse(el.dataset.dismissibleCallback) : null;
            if (data) {
                $.post(ajaxurl, data);
            }
        }, el);
    });
};
const initDismissibleNotices = () => {
    dismissNotice('.ac-notice');
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
//# sourceMappingURL=notice-dismissible.js.map