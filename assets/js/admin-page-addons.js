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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/admin-page-addons.ts");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/admin-page-addons.ts":
/*!*********************************!*\
  !*** ./js/admin-page-addons.ts ***!
  \*********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_addon_download__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/addon-download */ "./js/modules/addon-download.ts");

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.ac-addon').forEach(function (element) {
        new _modules_addon_download__WEBPACK_IMPORTED_MODULE_0__["AddonDownload"](element, element.dataset.slug);
    });
});


/***/ }),

/***/ "./js/modules/addon-download.ts":
/*!**************************************!*\
  !*** ./js/modules/addon-download.ts ***!
  \**************************************/
/*! exports provided: AddonDownload */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "AddonDownload", function() { return AddonDownload; });
/* harmony import */ var _notice__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./notice */ "./js/modules/notice.ts");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_1__);

// @ts-ignore

var AddonDownload = /** @class */ (function () {
    function AddonDownload(el, slug) {
        this.element = el;
        this.slug = slug;
        this.loadingState = false;
        this.initEvents();
    }
    AddonDownload.prototype.getDownloadButton = function () {
        return this.element.querySelector('[data-install]');
    };
    AddonDownload.prototype.setLoadingState = function () {
        var button = this.getDownloadButton();
        if (button) {
            button.insertAdjacentHTML('afterend', '<span class="spinner" style="visibility: visible;"></span>');
            button.classList.add('button-disabled');
        }
        this.loadingState = true;
    };
    AddonDownload.prototype.removeLoadingState = function () {
        var button = this.getDownloadButton();
        var spinner = this.element.querySelector('.spinner');
        if (spinner) {
            spinner.remove();
        }
        if (button) {
            button.classList.remove('button-disabled');
        }
        this.loadingState = false;
    };
    AddonDownload.prototype.initEvents = function () {
        var _this = this;
        var button = this.getDownloadButton();
        if (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                if (_this.loadingState) {
                    return;
                }
                _this.setLoadingState();
                _this.download();
            });
        }
    };
    AddonDownload.prototype.success = function (status) {
        var button = this.getDownloadButton();
        var title = this.element.querySelector('h3');
        var notice = new _notice__WEBPACK_IMPORTED_MODULE_0__["default"]();
        notice.setMessage("<p>The Add-on <strong>" + title.innerHTML + "</strong> is installed.</p>")
            .makeDismissable()
            .addClass('updated');
        document.querySelector('.ac-addons').insertAdjacentElement('beforebegin', notice.render());
        if (button) {
            button.insertAdjacentHTML('beforebegin', "<span class=\"active\">" + status + "</span>");
            button.remove();
        }
    };
    AddonDownload.scrollToTop = function (ms) {
        jquery__WEBPACK_IMPORTED_MODULE_1___default()('html, body').animate({
            scrollTop: 0
        }, ms);
    };
    AddonDownload.prototype.failure = function (message) {
        var title = this.element.querySelector('h3');
        var notice = new _notice__WEBPACK_IMPORTED_MODULE_0__["default"]();
        notice.setMessage("<p><strong>" + title.innerHTML + "</strong>: " + message + "</p>")
            .makeDismissable()
            .addClass('notice-error');
        document.querySelector('.ac-addons').insertAdjacentElement('beforebegin', notice.render());
        AddonDownload.scrollToTop(200);
    };
    AddonDownload.prototype.download = function () {
        var _this = this;
        var request = this.request();
        request.done(function (response) {
            _this.removeLoadingState();
            if (response.success) {
                _this.success(response.data.status);
            }
            else {
                _this.failure(response.data);
            }
        });
    };
    AddonDownload.prototype.request = function () {
        var data = {
            action: 'acp-install-addon',
            plugin_name: this.slug,
            _ajax_nonce: AC._ajax_nonce
        };
        return jquery__WEBPACK_IMPORTED_MODULE_1___default.a.ajax({
            url: ajaxurl,
            method: 'post',
            data: data
        });
    };
    return AddonDownload;
}());



/***/ }),

/***/ "./js/modules/notice.ts":
/*!******************************!*\
  !*** ./js/modules/notice.ts ***!
  \******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Notice = /** @class */ (function () {
    function Notice() {
        this.element = document.createElement('div');
        this.element.classList.add('notice');
        this.dismissible = false;
    }
    Notice.prototype.setMessage = function (message) {
        this.message = message;
        return this;
    };
    Notice.prototype.renderDismiss = function () {
        var _this = this;
        var button = document.createElement('button');
        button.classList.add('notice-dismiss');
        button.setAttribute('type', 'button');
        button.insertAdjacentHTML('beforeend', "<span class=\"screen-reader-text\">Dismiss this notice.</span>");
        button.addEventListener('click', function (e) {
            e.preventDefault();
            _this.element.remove();
        });
        this.element.classList.add('is-dismissible');
        this.element.insertAdjacentElement('beforeend', button);
    };
    Notice.prototype.renderContent = function () {
        this.element.insertAdjacentHTML('afterbegin', this.message);
    };
    Notice.prototype.makeDismissable = function () {
        this.dismissible = true;
        return this;
    };
    Notice.prototype.addClass = function (className) {
        this.element.classList.add(className);
        return this;
    };
    Notice.prototype.render = function () {
        this.element.innerHTML = '';
        this.renderContent();
        if (this.dismissible) {
            this.renderDismiss();
        }
        return this.element;
    };
    return Notice;
}());
/* harmony default export */ __webpack_exports__["default"] = (Notice);


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
//# sourceMappingURL=admin-page-addons.js.map