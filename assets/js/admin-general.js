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
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(global) {/* harmony import */ var _modules_tooltips__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/tooltips */ "./js/modules/tooltips.js");
/* harmony import */ var _modules_ac_section__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/ac-section */ "./js/modules/ac-section.js");
/* harmony import */ var _modules_ac_pointer__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/ac-pointer */ "./js/modules/ac-pointer.js");
/* harmony import */ var _plugin_dismissible_notice__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./plugin/dismissible-notice */ "./js/plugin/dismissible-notice.ts");





var nanobus = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");

global.AdminColumns = typeof AdminColumns !== "undefined" ? AdminColumns : {};
AdminColumns.events = nanobus();
jQuery(document).ready(function ($) {
  Object(_plugin_dismissible_notice__WEBPACK_IMPORTED_MODULE_3__["initDismissibleNotices"])();

  if ($('#cpac').length === 0) {
    return false;
  }

  ac_pointers();
  document.querySelectorAll('.ac-section').forEach(function (el) {
    new _modules_ac_section__WEBPACK_IMPORTED_MODULE_1__["default"](el);
  });
});
/*
 * WP Pointer
 *
 */

global.ac_pointers = function () {
  var $ = jQuery;
  document.querySelectorAll('.ac-pointer').forEach(function (element) {
    new _modules_ac_pointer__WEBPACK_IMPORTED_MODULE_2__["default"](element);
  });
  $('.ac-wp-pointer').hover(function () {
    $(this).addClass('hover');
  }, function () {
    $(this).removeClass('hover');
    $('.ac-pointer').trigger('close');
  }).on('click', '.close', function () {
    $('.ac-pointer').removeClass('open');
  });
  new _modules_tooltips__WEBPACK_IMPORTED_MODULE_0__["default"]();
};

global.ac_pointer = function (el) {
  new _modules_ac_pointer__WEBPACK_IMPORTED_MODULE_2__["default"](el);
};
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./js/helpers/events.ts":
/*!******************************!*\
  !*** ./js/helpers/events.ts ***!
  \******************************/
/*! exports provided: addEventListenerLive */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addEventListenerLive", function() { return addEventListenerLive; });
var addEventListenerLive = function addEventListenerLive(eventType, elementQuerySelector, cb, rootElement) {
  if (rootElement === void 0) {
    rootElement = null;
  }

  var element = rootElement ? rootElement : document;
  element.addEventListener(eventType, function (event) {
    var qs = document.querySelectorAll(elementQuerySelector);

    if (qs) {
      var element = event.target,
          index = -1;

      while (element && (index = Array.prototype.indexOf.call(qs, element)) === -1) {
        element = element.parentElement;
      }

      if (index > -1) {
        cb.call(element, event);
      }
    }
  });
};

/***/ }),

/***/ "./js/modules/ac-pointer.js":
/*!**********************************!*\
  !*** ./js/modules/ac-pointer.js ***!
  \**********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Pointer =
/** @class */
function () {
  function Pointer(el) {
    this.el = el;
    this.settings = this.getDefaults();
    this.init();
    this.setInitialized();
  }

  Pointer.prototype.setInitialized = function () {
    this.el.dataset.ac_pointer_initialized = 1;
  };

  Pointer.prototype.getDefaults = function () {
    return {
      width: this.el.getAttribute('data-width') ? this.el.getAttribute('data-width') : 250,
      noclick: this.el.getAttribute('data-noclick') ? this.el.getAttribute('data-noclick') : false,
      position: this.getPosition()
    };
  };

  Pointer.prototype.isInitialized = function () {
    return this.el.dataset.hasOwnProperty('ac_pointer_initialized');
  };

  Pointer.prototype.init = function () {
    if (this.isInitialized()) {
      return;
    } // create pointer


    jQuery(this.el).pointer({
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
      edge: 'right'
    };
    var pos = this.el.getAttribute('data-pos');
    var edge = this.el.getAttribute('data-pos_edge');

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
    var classes = ['ac-wp-pointer', 'wp-pointer', 'wp-pointer-' + this.settings.position.edge];

    if (this.settings.noclick) {
      classes.push('noclick');
    }

    return classes.join(' ');
  };

  Pointer.prototype.getRelatedHTML = function () {
    var related_element = document.getElementById(this.el.getAttribute('rel'));
    return related_element ? related_element.innerHTML : '';
  };

  Pointer.prototype.initEvents = function () {
    var el = jQuery(this.el); // click

    if (!this.settings.noclick) {
      el.click(function () {
        if (el.hasClass('open')) {
          el.removeClass('open');
        } else {
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
        if (!el.hasClass('open') && jQuery('.ac-wp-pointer.hover').length === 0) {
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
}();

/* harmony default export */ __webpack_exports__["default"] = (Pointer);

/***/ }),

/***/ "./js/modules/ac-section.js":
/*!**********************************!*\
  !*** ./js/modules/ac-section.js ***!
  \**********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Cookies = __webpack_require__(/*! js-cookie */ "./node_modules/js-cookie/src/js.cookie.js");

var AcSection =
/** @class */
function () {
  function AcSection(el) {
    this.element = el;
    this.init();
  }

  AcSection.prototype.init = function () {
    var _this = this;

    if (this.element.classList.contains('-closable')) {
      var header = this.element.querySelector('.ac-section__header');

      if (header) {
        header.addEventListener('click', function () {
          _this.toggle();
        });
      }

      if (this.isStorable()) {
        var setting = Cookies.get(this.getCookieKey());

        if (setting !== undefined) {
          parseInt(setting) === 1 ? this.open : this.close();
        }
      }
    }
  };

  AcSection.prototype.getCookieKey = function () {
    return "ac-section_" + this.getSectionId();
  };

  AcSection.prototype.getSectionId = function () {
    return this.element.dataset.section;
  };

  AcSection.prototype.isStorable = function () {
    return typeof this.element.dataset.section !== 'undefined';
  };

  AcSection.prototype.toggle = function () {
    this.isOpen() ? this.close() : this.open();
  };

  AcSection.prototype.isOpen = function () {
    return !this.element.classList.contains('-closed');
  };

  AcSection.prototype.open = function () {
    this.element.classList.remove('-closed');

    if (this.isStorable()) {
      Cookies.set(this.getCookieKey(), 1);
    }
  };

  AcSection.prototype.close = function () {
    this.element.classList.add('-closed');

    if (this.isStorable()) {
      Cookies.set(this.getCookieKey(), 0);
    }
  };

  return AcSection;
}();

/* harmony default export */ __webpack_exports__["default"] = (AcSection);

/***/ }),

/***/ "./js/modules/tooltips.js":
/*!********************************!*\
  !*** ./js/modules/tooltips.js ***!
  \********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Tooltips =
/** @class */
function () {
  function Tooltips() {
    this.isEnabled = typeof jQuery.fn.qtip !== 'undefined';
    this.init();
  }

  Tooltips.prototype.init = function () {
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
  };

  return Tooltips;
}();

/* harmony default export */ __webpack_exports__["default"] = (Tooltips);

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


var $ = __webpack_require__(/*! jquery */ "jquery");

var dismissNotice = function dismissNotice(selector) {
  document.querySelectorAll(selector).forEach(function (el) {
    Object(_helpers_events__WEBPACK_IMPORTED_MODULE_0__["addEventListenerLive"])('click', '.ac-notice__dismiss, [data-dismiss], .notice-dismiss', function (e) {
      e.preventDefault();
      var data = el.dataset.dismissibleCallback ? JSON.parse(el.dataset.dismissibleCallback) : null;

      if (data) {
        $.post(ajaxurl, data);
      }
    }, el);
  });
};
var initDismissibleNotices = function initDismissibleNotices() {
  dismissNotice('.ac-notice');
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
;(function (factory) {
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
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function decode (s) {
		return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
	}

	function init (converter) {
		function api() {}

		function set (key, value, attributes) {
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
			} catch (e) {}

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

		function get (key, json) {
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
						} catch (e) {}
					}

					jar[name] = cookie;

					if (key === name) {
						break;
					}
				} catch (e) {}
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

	return init(function () {});
}));


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
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


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