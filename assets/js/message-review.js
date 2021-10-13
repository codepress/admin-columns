/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./js/helpers/events.ts":
/*!******************************!*\
  !*** ./js/helpers/events.ts ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "addEventListenerLive": () => (/* binding */ addEventListenerLive),
/* harmony export */   "onHover": () => (/* binding */ onHover),
/* harmony export */   "addEventListeners": () => (/* binding */ addEventListeners)
/* harmony export */ });
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

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ ((module) => {

module.exports = jQuery;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!******************************!*\
  !*** ./js/message-review.ts ***!
  \******************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helpers_events__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helpers/events */ "./js/helpers/events.ts");

const $ = __webpack_require__(/*! jquery */ "jquery");
document.addEventListener('DOMContentLoaded', () => {
    (0,_helpers_events__WEBPACK_IMPORTED_MODULE_0__.addEventListenerLive)('click', 'a.hide-review-notice-soft', (e) => {
        e.preventDefault();
        let notice = e.target.closest('.ac-notice');
        notice.querySelector('.info').remove();
        notice.querySelector('.help').style.display = 'block';
        $.post(ajaxurl, JSON.parse(notice.dataset.dismissibleCallback));
    });
    (0,_helpers_events__WEBPACK_IMPORTED_MODULE_0__.addEventListenerLive)('click', 'a.hide-review-notice', (e) => {
        e.preventDefault();
        e.target.closest('.ac-notice').querySelector('.notice-dismiss').dispatchEvent(new Event('click'));
    });
});

})();

/******/ })()
;
//# sourceMappingURL=message-review.js.map