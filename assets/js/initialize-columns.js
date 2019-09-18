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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/initialize-columns.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/initialize-columns.js":
/*!**********************************!*\
  !*** ./js/initialize-columns.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var InitializeColumns =
/*#__PURE__*/
function () {
  function InitializeColumns(list_screens) {
    _classCallCheck(this, InitializeColumns);

    this.list_screens = list_screens;
    this.treads = 6;
  }

  _createClass(InitializeColumns, [{
    key: "initListScreen",
    value: function initListScreen(list_screen) {
      return jQuery.ajax({
        //url : list_screen.screen_link,
        url: 'https://firstvisit.test',
        method: 'get'
      });
    }
  }, {
    key: "run",
    value: function run() {
      for (var i = 0; i < this.treads; i++) {
        this.processNext();
      }
    }
  }, {
    key: "getNextItem",
    value: function getNextItem() {
      return this.list_screens.shift();
    }
  }, {
    key: "processNext",
    value: function processNext() {
      var _this = this;

      var list_screen = this.getNextItem();

      if (!list_screen) {
        return;
      }

      this.initListScreen(list_screen).done(function (d) {
        _this.processNext();
      });
    }
  }]);

  return InitializeColumns;
}();

var Modal =
/*#__PURE__*/
function () {
  function Modal() {
    _classCallCheck(this, Modal);
  }

  _createClass(Modal, [{
    key: "getMarkup",
    value: function getMarkup() {
      "<div class=\"ac-modal -active\">\n\t\t\t<div class=\"ac-modal__dialog\">\n\t\t\t\t<div class=\"ac-modal__dialog__content\">\n\t\t\t\t\t<p class=\"ac-modal__dialog__content__lead\">\n\t\t\t\t\t\tUpgrade to PRO, and take Admin Columns to the next level:\t\t\t</p>\n\t\t\t\t\t<ul class=\"ac-modal__dialog__list\">\n\t\t\t\t\t\t<li>Sort &amp; Filter on all your content</li>\n\t\t\t\t\t\t<li>Directly edit your content from the overview</li>\n\t\t\t\t\t\t<li>Export all column data to CSV</li>\n\t\t\t\t\t\t<li>Create multiple column groups per overview</li>\n\t\t\t\t\t\t<li>Get add-ons for ACF, WooCommerce and many more</li>\n\t\t\t\t\t</ul>\n\t\t\t\t</div>\n\t\t\t\n\t\t\t</div>\n\t\t</div>";
    }
  }, {
    key: "place",
    value: function place() {
      document.createElement();
    }
  }, {
    key: "remove",
    value: function remove() {}
  }]);

  return Modal;
}();

jQuery(document).ready(function () {
  var initializer = new InitializeColumns(AC_INIT_LISTSCREENS);
  initializer.run();
  var overlay = document.createElement('div');
  overlay.classList.add('ac-modal', '-active', '-dblocking'); //document.getElementById('wpcontent' ).insertAdjacentElement( 'beforebegin', overlay );
  //document.body.appendChild( overlay );
});

/***/ })

/******/ });
//# sourceMappingURL=initialize-columns.js.map