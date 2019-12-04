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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/admin-general.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/admin-general.js":
/*!*****************************!*\
  !*** ./js/admin-general.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(global) {

var _tooltips = _interopRequireDefault(__webpack_require__(/*! ./modules/tooltips */ "./js/modules/tooltips.js"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

global.AdminColumns = typeof AdminColumns !== "undefined" ? AdminColumns : {};
jQuery(document).ready(function ($) {
  if ($('#cpac').length === 0) {
    return false;
  }

  ac_pointers($);
  ac_help($);
});
/*
 * WP Pointer
 *
 */

global.ac_pointers = function ($) {
  $('.ac-pointer').each(function () {
    ac_pointer($(this));
  });
  $('.ac-wp-pointer').hover(function () {
    $(this).addClass('hover');
  }, function () {
    $(this).removeClass('hover');
    $('.ac-pointer').trigger('close');
  });
  new _tooltips.default();
};

global.ac_pointer = function ($el) {
  var el = $el,
      $ = jQuery,
      html = el.attr('rel'),
      pos = el.attr('data-pos'),
      w = el.attr('data-width'),
      noclick = el.attr('data-noclick');
  var position = {
    at: 'left top',
    // position of wp-pointer relative to the element which triggers the pointer event
    my: 'right top',
    // position of wp-pointer relative to the at-coordinates
    edge: 'right' // position of arrow

  };
  var width = w ? w : 250;

  if ('right' === pos) {
    position = {
      at: 'right middle',
      my: 'left middle',
      edge: 'left'
    };
  }

  if ('left' === pos) {
    position = {
      at: 'left middle',
      my: 'right middle',
      edge: 'right'
    };
  } // create pointer


  el.pointer({
    content: $('#' + html).html(),
    position: position,
    pointerWidth: width,
    // bug fix. with an arrow on the right side the position of wp-pointer is incorrect. it does not take
    // into account the padding of the arrow. adding "wp-pointer-' + position.edge"  will fix that.
    pointerClass: 'ac-wp-pointer wp-pointer wp-pointer-' + position.edge + (noclick ? ' noclick' : '')
  }); // click

  if (!noclick) {
    el.click(function () {
      if (el.hasClass('open')) {
        el.removeClass('open');
      } else {
        el.addClass('open');
      }
    });
  } // show on hover


  el.hover(function () {
    $(this).pointer('open');
  }, function () {
    var el = $(this);
    setTimeout(function () {
      if (!el.hasClass('open') && $('.ac-wp-pointer.hover').length == 0) {
        el.pointer('close');
      }
    }, 100);
  }).on('close', function () {
    if (!el.hasClass('open') && $('.ac-wp-pointer.hover').length == 0) {
      el.pointer('close');
    }
  });
};
/*
 * Help
 *
 * usage: <a href="javascript:;" class="help" data-help="tab-2"></a>
 */


function ac_help($) {
  $('a.help').click(function (e) {
    e.preventDefault();
    var panel = $('#contextual-help-wrap');
    panel.parent().show();
    $('a[href="#tab-panel-cpac-' + $(this).attr('data-help') + '"]', panel).trigger('click');
    panel.slideDown('fast', function () {
      panel.focus();
    });
  });
}
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./js/modules/tooltips.js":
/*!********************************!*\
  !*** ./js/modules/tooltips.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Tooltips =
/*#__PURE__*/
function () {
  function Tooltips() {
    _classCallCheck(this, Tooltips);

    this.isEnabled = typeof jQuery.fn.qtip !== 'undefined';
    this.init();
  }

  _createClass(Tooltips, [{
    key: "init",
    value: function init() {
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
    }
  }]);

  return Tooltips;
}();

module.exports = Tooltips;

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
//# sourceMappingURL=admin-general.js.map