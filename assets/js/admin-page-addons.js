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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/admin-page-addons.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/admin-page-addons.js":
/*!*********************************!*\
  !*** ./js/admin-page-addons.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(global) {

var _notice = _interopRequireDefault(__webpack_require__(/*! ./modules/notice */ "./js/modules/notice.js"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

__webpack_require__(/*! admin-columns-js/polyfill/nodelist */ "./node_modules/admin-columns-js/polyfill/nodelist.js");

var AddonDownload =
/*#__PURE__*/
function () {
  function AddonDownload(el, slug) {
    _classCallCheck(this, AddonDownload);

    this.element = el;
    this.slug = slug;
    this.loadingState = false;
    this.initEvents();
  }

  _createClass(AddonDownload, [{
    key: "getDownloadButton",
    value: function getDownloadButton() {
      return this.element.querySelector('[data-install]');
    }
  }, {
    key: "setLoadingState",
    value: function setLoadingState() {
      var button = this.getDownloadButton();

      if (button) {
        button.insertAdjacentHTML('afterend', '<span class="spinner" style="visibility: visible;"></span>');
        button.classList.add('button-disabled');
      }

      this.loadingState = true;
    }
  }, {
    key: "removeLoadingState",
    value: function removeLoadingState() {
      var button = this.getDownloadButton();
      var spinner = this.element.querySelector('.spinner');

      if (spinner) {
        spinner.remove();
      }

      if (button) {
        button.classList.remove('button-disabled');
      }

      this.loadingState = false;
    }
  }, {
    key: "initEvents",
    value: function initEvents() {
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
    }
  }, {
    key: "success",
    value: function success(status) {
      var button = this.getDownloadButton();
      var title = this.element.querySelector('h3');
      var notice = new _notice.default();
      notice.setMessage("<p>The Add-on <strong>".concat(title.innerHTML, "</strong> is installed.</p>")).makeDismissable().addClass('updated');
      document.querySelector('.ac-addons').insertAdjacentElement('beforebegin', notice.render());

      if (button) {
        button.insertAdjacentHTML('beforebegin', "<span class=\"active\">".concat(status, "</span>"));
        button.remove();
      }
    }
  }, {
    key: "failure",
    value: function failure(message) {
      var title = this.element.querySelector('h3');
      var notice = new _notice.default();
      notice.setMessage("<p><strong>".concat(title.innerHTML, "</strong>: ").concat(message, "</p>")).makeDismissable().addClass('notice-error');
      document.querySelector('.ac-addons').insertAdjacentElement('beforebegin', notice.render());
      AddonDownload.scrollToTop(200);
    }
  }, {
    key: "download",
    value: function download() {
      var _this2 = this;

      var request = this.request();
      request.done(function (response) {
        _this2.removeLoadingState();

        if (response.success) {
          _this2.success(response.data.status);
        } else {
          _this2.failure(response.data);
        }
      });
    }
  }, {
    key: "request",
    value: function request() {
      var data = {
        action: 'acp-install-addon',
        plugin_name: this.slug,
        _ajax_nonce: AC.ajax_nonce
      };
      return jQuery.ajax({
        url: ajaxurl,
        method: 'post',
        data: data
      });
    }
  }], [{
    key: "scrollToTop",
    value: function scrollToTop(ms) {
      jQuery('html, body').animate({
        scrollTop: 0
      }, ms);
    }
  }]);

  return AddonDownload;
}();

document.addEventListener("DOMContentLoaded", function () {
  global.AC_Addons = [];
  document.querySelectorAll('.ac-addon').forEach(function (element) {
    AC_Addons[element.dataset.slug] = new AddonDownload(element, element.dataset.slug);
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./js/modules/notice.js":
/*!******************************!*\
  !*** ./js/modules/notice.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Notice =
/*#__PURE__*/
function () {
  function Notice() {
    _classCallCheck(this, Notice);

    this.element = document.createElement('div');
    this.element.classList.add('notice');
  }

  _createClass(Notice, [{
    key: "setMessage",
    value: function setMessage(message) {
      this.message = message;
      return this;
    }
  }, {
    key: "renderDimiss",
    value: function renderDimiss() {
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
    }
  }, {
    key: "renderContent",
    value: function renderContent() {
      this.element.insertAdjacentHTML('afterbegin', this.message);
    }
  }, {
    key: "makeDismissable",
    value: function makeDismissable() {
      this.dismissible = true;
      return this;
    }
  }, {
    key: "addClass",
    value: function addClass(className) {
      this.element.classList.add(className);
      return this;
    }
  }, {
    key: "render",
    value: function render() {
      this.element.innerHTML = '';
      this.renderContent();

      if (this.dismissible) {
        this.renderDimiss();
      }

      return this.element;
    }
  }]);

  return Notice;
}();

exports.default = Notice;

/***/ }),

/***/ "./node_modules/admin-columns-js/polyfill/nodelist.js":
/*!************************************************************!*\
  !*** ./node_modules/admin-columns-js/polyfill/nodelist.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

if ( window.NodeList && !NodeList.prototype.forEach ) {
	NodeList.prototype.forEach = Array.prototype.forEach;
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
	g = g || Function("return this")() || (1, eval)("this");
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
//# sourceMappingURL=admin-page-addons.js.map