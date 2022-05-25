/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./js/select2/excludegroup.matcher.js":
/*!********************************************!*\
  !*** ./js/select2/excludegroup.matcher.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ excludeGroupsMather)
/* harmony export */ });
function excludeGroupsMather( params, data ) {
	if ( jQuery.trim( params.term ) === '' ) {
		return data;
	}

	if ( typeof data.children === 'undefined' ) {
		return null;
	}

	var filteredChildren = [];
	jQuery.each( data.children, function( idx, child ) {
		if ( child.text.toUpperCase().indexOf( params.term.toUpperCase() ) > -1 ) {
			filteredChildren.push( child );
		}
	} );

	if ( filteredChildren.length ) {
		var modifiedData = jQuery.extend( {}, data, true );
		modifiedData.children = filteredChildren;

		return modifiedData;
	}

	return null;
}

/***/ }),

/***/ "./node_modules/axios/index.js":
/*!*************************************!*\
  !*** ./node_modules/axios/index.js ***!
  \*************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(/*! ./lib/axios */ "./node_modules/axios/lib/axios.js");

/***/ }),

/***/ "./node_modules/axios/lib/adapters/xhr.js":
/*!************************************************!*\
  !*** ./node_modules/axios/lib/adapters/xhr.js ***!
  \************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");
var settle = __webpack_require__(/*! ./../core/settle */ "./node_modules/axios/lib/core/settle.js");
var cookies = __webpack_require__(/*! ./../helpers/cookies */ "./node_modules/axios/lib/helpers/cookies.js");
var buildURL = __webpack_require__(/*! ./../helpers/buildURL */ "./node_modules/axios/lib/helpers/buildURL.js");
var buildFullPath = __webpack_require__(/*! ../core/buildFullPath */ "./node_modules/axios/lib/core/buildFullPath.js");
var parseHeaders = __webpack_require__(/*! ./../helpers/parseHeaders */ "./node_modules/axios/lib/helpers/parseHeaders.js");
var isURLSameOrigin = __webpack_require__(/*! ./../helpers/isURLSameOrigin */ "./node_modules/axios/lib/helpers/isURLSameOrigin.js");
var createError = __webpack_require__(/*! ../core/createError */ "./node_modules/axios/lib/core/createError.js");

module.exports = function xhrAdapter(config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    var requestData = config.data;
    var requestHeaders = config.headers;

    if (utils.isFormData(requestData)) {
      delete requestHeaders['Content-Type']; // Let the browser set it
    }

    var request = new XMLHttpRequest();

    // HTTP basic authentication
    if (config.auth) {
      var username = config.auth.username || '';
      var password = config.auth.password ? unescape(encodeURIComponent(config.auth.password)) : '';
      requestHeaders.Authorization = 'Basic ' + btoa(username + ':' + password);
    }

    var fullPath = buildFullPath(config.baseURL, config.url);
    request.open(config.method.toUpperCase(), buildURL(fullPath, config.params, config.paramsSerializer), true);

    // Set the request timeout in MS
    request.timeout = config.timeout;

    // Listen for ready state
    request.onreadystatechange = function handleLoad() {
      if (!request || request.readyState !== 4) {
        return;
      }

      // The request errored out and we didn't get a response, this will be
      // handled by onerror instead
      // With one exception: request that using file: protocol, most browsers
      // will return status as 0 even though it's a successful request
      if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
        return;
      }

      // Prepare the response
      var responseHeaders = 'getAllResponseHeaders' in request ? parseHeaders(request.getAllResponseHeaders()) : null;
      var responseData = !config.responseType || config.responseType === 'text' ? request.responseText : request.response;
      var response = {
        data: responseData,
        status: request.status,
        statusText: request.statusText,
        headers: responseHeaders,
        config: config,
        request: request
      };

      settle(resolve, reject, response);

      // Clean up request
      request = null;
    };

    // Handle browser request cancellation (as opposed to a manual cancellation)
    request.onabort = function handleAbort() {
      if (!request) {
        return;
      }

      reject(createError('Request aborted', config, 'ECONNABORTED', request));

      // Clean up request
      request = null;
    };

    // Handle low level network errors
    request.onerror = function handleError() {
      // Real errors are hidden from us by the browser
      // onerror should only fire if it's a network error
      reject(createError('Network Error', config, null, request));

      // Clean up request
      request = null;
    };

    // Handle timeout
    request.ontimeout = function handleTimeout() {
      var timeoutErrorMessage = 'timeout of ' + config.timeout + 'ms exceeded';
      if (config.timeoutErrorMessage) {
        timeoutErrorMessage = config.timeoutErrorMessage;
      }
      reject(createError(timeoutErrorMessage, config, 'ECONNABORTED',
        request));

      // Clean up request
      request = null;
    };

    // Add xsrf header
    // This is only done if running in a standard browser environment.
    // Specifically not if we're in a web worker, or react-native.
    if (utils.isStandardBrowserEnv()) {
      // Add xsrf header
      var xsrfValue = (config.withCredentials || isURLSameOrigin(fullPath)) && config.xsrfCookieName ?
        cookies.read(config.xsrfCookieName) :
        undefined;

      if (xsrfValue) {
        requestHeaders[config.xsrfHeaderName] = xsrfValue;
      }
    }

    // Add headers to the request
    if ('setRequestHeader' in request) {
      utils.forEach(requestHeaders, function setRequestHeader(val, key) {
        if (typeof requestData === 'undefined' && key.toLowerCase() === 'content-type') {
          // Remove Content-Type if data is undefined
          delete requestHeaders[key];
        } else {
          // Otherwise add header to the request
          request.setRequestHeader(key, val);
        }
      });
    }

    // Add withCredentials to request if needed
    if (!utils.isUndefined(config.withCredentials)) {
      request.withCredentials = !!config.withCredentials;
    }

    // Add responseType to request if needed
    if (config.responseType) {
      try {
        request.responseType = config.responseType;
      } catch (e) {
        // Expected DOMException thrown by browsers not compatible XMLHttpRequest Level 2.
        // But, this can be suppressed for 'json' type as it can be parsed by default 'transformResponse' function.
        if (config.responseType !== 'json') {
          throw e;
        }
      }
    }

    // Handle progress if needed
    if (typeof config.onDownloadProgress === 'function') {
      request.addEventListener('progress', config.onDownloadProgress);
    }

    // Not all browsers support upload events
    if (typeof config.onUploadProgress === 'function' && request.upload) {
      request.upload.addEventListener('progress', config.onUploadProgress);
    }

    if (config.cancelToken) {
      // Handle cancellation
      config.cancelToken.promise.then(function onCanceled(cancel) {
        if (!request) {
          return;
        }

        request.abort();
        reject(cancel);
        // Clean up request
        request = null;
      });
    }

    if (!requestData) {
      requestData = null;
    }

    // Send the request
    request.send(requestData);
  });
};


/***/ }),

/***/ "./node_modules/axios/lib/axios.js":
/*!*****************************************!*\
  !*** ./node_modules/axios/lib/axios.js ***!
  \*****************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./utils */ "./node_modules/axios/lib/utils.js");
var bind = __webpack_require__(/*! ./helpers/bind */ "./node_modules/axios/lib/helpers/bind.js");
var Axios = __webpack_require__(/*! ./core/Axios */ "./node_modules/axios/lib/core/Axios.js");
var mergeConfig = __webpack_require__(/*! ./core/mergeConfig */ "./node_modules/axios/lib/core/mergeConfig.js");
var defaults = __webpack_require__(/*! ./defaults */ "./node_modules/axios/lib/defaults.js");

/**
 * Create an instance of Axios
 *
 * @param {Object} defaultConfig The default config for the instance
 * @return {Axios} A new instance of Axios
 */
function createInstance(defaultConfig) {
  var context = new Axios(defaultConfig);
  var instance = bind(Axios.prototype.request, context);

  // Copy axios.prototype to instance
  utils.extend(instance, Axios.prototype, context);

  // Copy context to instance
  utils.extend(instance, context);

  return instance;
}

// Create the default instance to be exported
var axios = createInstance(defaults);

// Expose Axios class to allow class inheritance
axios.Axios = Axios;

// Factory for creating new instances
axios.create = function create(instanceConfig) {
  return createInstance(mergeConfig(axios.defaults, instanceConfig));
};

// Expose Cancel & CancelToken
axios.Cancel = __webpack_require__(/*! ./cancel/Cancel */ "./node_modules/axios/lib/cancel/Cancel.js");
axios.CancelToken = __webpack_require__(/*! ./cancel/CancelToken */ "./node_modules/axios/lib/cancel/CancelToken.js");
axios.isCancel = __webpack_require__(/*! ./cancel/isCancel */ "./node_modules/axios/lib/cancel/isCancel.js");

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};
axios.spread = __webpack_require__(/*! ./helpers/spread */ "./node_modules/axios/lib/helpers/spread.js");

// Expose isAxiosError
axios.isAxiosError = __webpack_require__(/*! ./helpers/isAxiosError */ "./node_modules/axios/lib/helpers/isAxiosError.js");

module.exports = axios;

// Allow use of default import syntax in TypeScript
module.exports["default"] = axios;


/***/ }),

/***/ "./node_modules/axios/lib/cancel/Cancel.js":
/*!*************************************************!*\
  !*** ./node_modules/axios/lib/cancel/Cancel.js ***!
  \*************************************************/
/***/ ((module) => {

"use strict";


/**
 * A `Cancel` is an object that is thrown when an operation is canceled.
 *
 * @class
 * @param {string=} message The message.
 */
function Cancel(message) {
  this.message = message;
}

Cancel.prototype.toString = function toString() {
  return 'Cancel' + (this.message ? ': ' + this.message : '');
};

Cancel.prototype.__CANCEL__ = true;

module.exports = Cancel;


/***/ }),

/***/ "./node_modules/axios/lib/cancel/CancelToken.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/cancel/CancelToken.js ***!
  \******************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var Cancel = __webpack_require__(/*! ./Cancel */ "./node_modules/axios/lib/cancel/Cancel.js");

/**
 * A `CancelToken` is an object that can be used to request cancellation of an operation.
 *
 * @class
 * @param {Function} executor The executor function.
 */
function CancelToken(executor) {
  if (typeof executor !== 'function') {
    throw new TypeError('executor must be a function.');
  }

  var resolvePromise;
  this.promise = new Promise(function promiseExecutor(resolve) {
    resolvePromise = resolve;
  });

  var token = this;
  executor(function cancel(message) {
    if (token.reason) {
      // Cancellation has already been requested
      return;
    }

    token.reason = new Cancel(message);
    resolvePromise(token.reason);
  });
}

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
CancelToken.prototype.throwIfRequested = function throwIfRequested() {
  if (this.reason) {
    throw this.reason;
  }
};

/**
 * Returns an object that contains a new `CancelToken` and a function that, when called,
 * cancels the `CancelToken`.
 */
CancelToken.source = function source() {
  var cancel;
  var token = new CancelToken(function executor(c) {
    cancel = c;
  });
  return {
    token: token,
    cancel: cancel
  };
};

module.exports = CancelToken;


/***/ }),

/***/ "./node_modules/axios/lib/cancel/isCancel.js":
/*!***************************************************!*\
  !*** ./node_modules/axios/lib/cancel/isCancel.js ***!
  \***************************************************/
/***/ ((module) => {

"use strict";


module.exports = function isCancel(value) {
  return !!(value && value.__CANCEL__);
};


/***/ }),

/***/ "./node_modules/axios/lib/core/Axios.js":
/*!**********************************************!*\
  !*** ./node_modules/axios/lib/core/Axios.js ***!
  \**********************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");
var buildURL = __webpack_require__(/*! ../helpers/buildURL */ "./node_modules/axios/lib/helpers/buildURL.js");
var InterceptorManager = __webpack_require__(/*! ./InterceptorManager */ "./node_modules/axios/lib/core/InterceptorManager.js");
var dispatchRequest = __webpack_require__(/*! ./dispatchRequest */ "./node_modules/axios/lib/core/dispatchRequest.js");
var mergeConfig = __webpack_require__(/*! ./mergeConfig */ "./node_modules/axios/lib/core/mergeConfig.js");

/**
 * Create a new instance of Axios
 *
 * @param {Object} instanceConfig The default config for the instance
 */
function Axios(instanceConfig) {
  this.defaults = instanceConfig;
  this.interceptors = {
    request: new InterceptorManager(),
    response: new InterceptorManager()
  };
}

/**
 * Dispatch a request
 *
 * @param {Object} config The config specific for this request (merged with this.defaults)
 */
Axios.prototype.request = function request(config) {
  /*eslint no-param-reassign:0*/
  // Allow for axios('example/url'[, config]) a la fetch API
  if (typeof config === 'string') {
    config = arguments[1] || {};
    config.url = arguments[0];
  } else {
    config = config || {};
  }

  config = mergeConfig(this.defaults, config);

  // Set config.method
  if (config.method) {
    config.method = config.method.toLowerCase();
  } else if (this.defaults.method) {
    config.method = this.defaults.method.toLowerCase();
  } else {
    config.method = 'get';
  }

  // Hook up interceptors middleware
  var chain = [dispatchRequest, undefined];
  var promise = Promise.resolve(config);

  this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
    chain.unshift(interceptor.fulfilled, interceptor.rejected);
  });

  this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
    chain.push(interceptor.fulfilled, interceptor.rejected);
  });

  while (chain.length) {
    promise = promise.then(chain.shift(), chain.shift());
  }

  return promise;
};

Axios.prototype.getUri = function getUri(config) {
  config = mergeConfig(this.defaults, config);
  return buildURL(config.url, config.params, config.paramsSerializer).replace(/^\?/, '');
};

// Provide aliases for supported request methods
utils.forEach(['delete', 'get', 'head', 'options'], function forEachMethodNoData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, config) {
    return this.request(mergeConfig(config || {}, {
      method: method,
      url: url,
      data: (config || {}).data
    }));
  };
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, data, config) {
    return this.request(mergeConfig(config || {}, {
      method: method,
      url: url,
      data: data
    }));
  };
});

module.exports = Axios;


/***/ }),

/***/ "./node_modules/axios/lib/core/InterceptorManager.js":
/*!***********************************************************!*\
  !*** ./node_modules/axios/lib/core/InterceptorManager.js ***!
  \***********************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

function InterceptorManager() {
  this.handlers = [];
}

/**
 * Add a new interceptor to the stack
 *
 * @param {Function} fulfilled The function to handle `then` for a `Promise`
 * @param {Function} rejected The function to handle `reject` for a `Promise`
 *
 * @return {Number} An ID used to remove interceptor later
 */
InterceptorManager.prototype.use = function use(fulfilled, rejected) {
  this.handlers.push({
    fulfilled: fulfilled,
    rejected: rejected
  });
  return this.handlers.length - 1;
};

/**
 * Remove an interceptor from the stack
 *
 * @param {Number} id The ID that was returned by `use`
 */
InterceptorManager.prototype.eject = function eject(id) {
  if (this.handlers[id]) {
    this.handlers[id] = null;
  }
};

/**
 * Iterate over all the registered interceptors
 *
 * This method is particularly useful for skipping over any
 * interceptors that may have become `null` calling `eject`.
 *
 * @param {Function} fn The function to call for each interceptor
 */
InterceptorManager.prototype.forEach = function forEach(fn) {
  utils.forEach(this.handlers, function forEachHandler(h) {
    if (h !== null) {
      fn(h);
    }
  });
};

module.exports = InterceptorManager;


/***/ }),

/***/ "./node_modules/axios/lib/core/buildFullPath.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/core/buildFullPath.js ***!
  \******************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var isAbsoluteURL = __webpack_require__(/*! ../helpers/isAbsoluteURL */ "./node_modules/axios/lib/helpers/isAbsoluteURL.js");
var combineURLs = __webpack_require__(/*! ../helpers/combineURLs */ "./node_modules/axios/lib/helpers/combineURLs.js");

/**
 * Creates a new URL by combining the baseURL with the requestedURL,
 * only when the requestedURL is not already an absolute URL.
 * If the requestURL is absolute, this function returns the requestedURL untouched.
 *
 * @param {string} baseURL The base URL
 * @param {string} requestedURL Absolute or relative URL to combine
 * @returns {string} The combined full path
 */
module.exports = function buildFullPath(baseURL, requestedURL) {
  if (baseURL && !isAbsoluteURL(requestedURL)) {
    return combineURLs(baseURL, requestedURL);
  }
  return requestedURL;
};


/***/ }),

/***/ "./node_modules/axios/lib/core/createError.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/core/createError.js ***!
  \****************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var enhanceError = __webpack_require__(/*! ./enhanceError */ "./node_modules/axios/lib/core/enhanceError.js");

/**
 * Create an Error with the specified message, config, error code, request and response.
 *
 * @param {string} message The error message.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 * @returns {Error} The created error.
 */
module.exports = function createError(message, config, code, request, response) {
  var error = new Error(message);
  return enhanceError(error, config, code, request, response);
};


/***/ }),

/***/ "./node_modules/axios/lib/core/dispatchRequest.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/core/dispatchRequest.js ***!
  \********************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");
var transformData = __webpack_require__(/*! ./transformData */ "./node_modules/axios/lib/core/transformData.js");
var isCancel = __webpack_require__(/*! ../cancel/isCancel */ "./node_modules/axios/lib/cancel/isCancel.js");
var defaults = __webpack_require__(/*! ../defaults */ "./node_modules/axios/lib/defaults.js");

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }
}

/**
 * Dispatch a request to the server using the configured adapter.
 *
 * @param {object} config The config that is to be used for the request
 * @returns {Promise} The Promise to be fulfilled
 */
module.exports = function dispatchRequest(config) {
  throwIfCancellationRequested(config);

  // Ensure headers exist
  config.headers = config.headers || {};

  // Transform request data
  config.data = transformData(
    config.data,
    config.headers,
    config.transformRequest
  );

  // Flatten headers
  config.headers = utils.merge(
    config.headers.common || {},
    config.headers[config.method] || {},
    config.headers
  );

  utils.forEach(
    ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
    function cleanHeaderConfig(method) {
      delete config.headers[method];
    }
  );

  var adapter = config.adapter || defaults.adapter;

  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);

    // Transform response data
    response.data = transformData(
      response.data,
      response.headers,
      config.transformResponse
    );

    return response;
  }, function onAdapterRejection(reason) {
    if (!isCancel(reason)) {
      throwIfCancellationRequested(config);

      // Transform response data
      if (reason && reason.response) {
        reason.response.data = transformData(
          reason.response.data,
          reason.response.headers,
          config.transformResponse
        );
      }
    }

    return Promise.reject(reason);
  });
};


/***/ }),

/***/ "./node_modules/axios/lib/core/enhanceError.js":
/*!*****************************************************!*\
  !*** ./node_modules/axios/lib/core/enhanceError.js ***!
  \*****************************************************/
/***/ ((module) => {

"use strict";


/**
 * Update an Error with the specified config, error code, and response.
 *
 * @param {Error} error The error to update.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 * @returns {Error} The error.
 */
module.exports = function enhanceError(error, config, code, request, response) {
  error.config = config;
  if (code) {
    error.code = code;
  }

  error.request = request;
  error.response = response;
  error.isAxiosError = true;

  error.toJSON = function toJSON() {
    return {
      // Standard
      message: this.message,
      name: this.name,
      // Microsoft
      description: this.description,
      number: this.number,
      // Mozilla
      fileName: this.fileName,
      lineNumber: this.lineNumber,
      columnNumber: this.columnNumber,
      stack: this.stack,
      // Axios
      config: this.config,
      code: this.code
    };
  };
  return error;
};


/***/ }),

/***/ "./node_modules/axios/lib/core/mergeConfig.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/core/mergeConfig.js ***!
  \****************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ../utils */ "./node_modules/axios/lib/utils.js");

/**
 * Config-specific merge-function which creates a new config-object
 * by merging two configuration objects together.
 *
 * @param {Object} config1
 * @param {Object} config2
 * @returns {Object} New object resulting from merging config2 to config1
 */
module.exports = function mergeConfig(config1, config2) {
  // eslint-disable-next-line no-param-reassign
  config2 = config2 || {};
  var config = {};

  var valueFromConfig2Keys = ['url', 'method', 'data'];
  var mergeDeepPropertiesKeys = ['headers', 'auth', 'proxy', 'params'];
  var defaultToConfig2Keys = [
    'baseURL', 'transformRequest', 'transformResponse', 'paramsSerializer',
    'timeout', 'timeoutMessage', 'withCredentials', 'adapter', 'responseType', 'xsrfCookieName',
    'xsrfHeaderName', 'onUploadProgress', 'onDownloadProgress', 'decompress',
    'maxContentLength', 'maxBodyLength', 'maxRedirects', 'transport', 'httpAgent',
    'httpsAgent', 'cancelToken', 'socketPath', 'responseEncoding'
  ];
  var directMergeKeys = ['validateStatus'];

  function getMergedValue(target, source) {
    if (utils.isPlainObject(target) && utils.isPlainObject(source)) {
      return utils.merge(target, source);
    } else if (utils.isPlainObject(source)) {
      return utils.merge({}, source);
    } else if (utils.isArray(source)) {
      return source.slice();
    }
    return source;
  }

  function mergeDeepProperties(prop) {
    if (!utils.isUndefined(config2[prop])) {
      config[prop] = getMergedValue(config1[prop], config2[prop]);
    } else if (!utils.isUndefined(config1[prop])) {
      config[prop] = getMergedValue(undefined, config1[prop]);
    }
  }

  utils.forEach(valueFromConfig2Keys, function valueFromConfig2(prop) {
    if (!utils.isUndefined(config2[prop])) {
      config[prop] = getMergedValue(undefined, config2[prop]);
    }
  });

  utils.forEach(mergeDeepPropertiesKeys, mergeDeepProperties);

  utils.forEach(defaultToConfig2Keys, function defaultToConfig2(prop) {
    if (!utils.isUndefined(config2[prop])) {
      config[prop] = getMergedValue(undefined, config2[prop]);
    } else if (!utils.isUndefined(config1[prop])) {
      config[prop] = getMergedValue(undefined, config1[prop]);
    }
  });

  utils.forEach(directMergeKeys, function merge(prop) {
    if (prop in config2) {
      config[prop] = getMergedValue(config1[prop], config2[prop]);
    } else if (prop in config1) {
      config[prop] = getMergedValue(undefined, config1[prop]);
    }
  });

  var axiosKeys = valueFromConfig2Keys
    .concat(mergeDeepPropertiesKeys)
    .concat(defaultToConfig2Keys)
    .concat(directMergeKeys);

  var otherKeys = Object
    .keys(config1)
    .concat(Object.keys(config2))
    .filter(function filterAxiosKeys(key) {
      return axiosKeys.indexOf(key) === -1;
    });

  utils.forEach(otherKeys, mergeDeepProperties);

  return config;
};


/***/ }),

/***/ "./node_modules/axios/lib/core/settle.js":
/*!***********************************************!*\
  !*** ./node_modules/axios/lib/core/settle.js ***!
  \***********************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var createError = __webpack_require__(/*! ./createError */ "./node_modules/axios/lib/core/createError.js");

/**
 * Resolve or reject a Promise based on response status.
 *
 * @param {Function} resolve A function that resolves the promise.
 * @param {Function} reject A function that rejects the promise.
 * @param {object} response The response.
 */
module.exports = function settle(resolve, reject, response) {
  var validateStatus = response.config.validateStatus;
  if (!response.status || !validateStatus || validateStatus(response.status)) {
    resolve(response);
  } else {
    reject(createError(
      'Request failed with status code ' + response.status,
      response.config,
      null,
      response.request,
      response
    ));
  }
};


/***/ }),

/***/ "./node_modules/axios/lib/core/transformData.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/core/transformData.js ***!
  \******************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

/**
 * Transform the data for a request or a response
 *
 * @param {Object|String} data The data to be transformed
 * @param {Array} headers The headers for the request or response
 * @param {Array|Function} fns A single function or Array of functions
 * @returns {*} The resulting transformed data
 */
module.exports = function transformData(data, headers, fns) {
  /*eslint no-param-reassign:0*/
  utils.forEach(fns, function transform(fn) {
    data = fn(data, headers);
  });

  return data;
};


/***/ }),

/***/ "./node_modules/axios/lib/defaults.js":
/*!********************************************!*\
  !*** ./node_modules/axios/lib/defaults.js ***!
  \********************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./utils */ "./node_modules/axios/lib/utils.js");
var normalizeHeaderName = __webpack_require__(/*! ./helpers/normalizeHeaderName */ "./node_modules/axios/lib/helpers/normalizeHeaderName.js");

var DEFAULT_CONTENT_TYPE = {
  'Content-Type': 'application/x-www-form-urlencoded'
};

function setContentTypeIfUnset(headers, value) {
  if (!utils.isUndefined(headers) && utils.isUndefined(headers['Content-Type'])) {
    headers['Content-Type'] = value;
  }
}

function getDefaultAdapter() {
  var adapter;
  if (typeof XMLHttpRequest !== 'undefined') {
    // For browsers use XHR adapter
    adapter = __webpack_require__(/*! ./adapters/xhr */ "./node_modules/axios/lib/adapters/xhr.js");
  } else if (typeof process !== 'undefined' && Object.prototype.toString.call(process) === '[object process]') {
    // For node use HTTP adapter
    adapter = __webpack_require__(/*! ./adapters/http */ "./node_modules/axios/lib/adapters/xhr.js");
  }
  return adapter;
}

var defaults = {
  adapter: getDefaultAdapter(),

  transformRequest: [function transformRequest(data, headers) {
    normalizeHeaderName(headers, 'Accept');
    normalizeHeaderName(headers, 'Content-Type');
    if (utils.isFormData(data) ||
      utils.isArrayBuffer(data) ||
      utils.isBuffer(data) ||
      utils.isStream(data) ||
      utils.isFile(data) ||
      utils.isBlob(data)
    ) {
      return data;
    }
    if (utils.isArrayBufferView(data)) {
      return data.buffer;
    }
    if (utils.isURLSearchParams(data)) {
      setContentTypeIfUnset(headers, 'application/x-www-form-urlencoded;charset=utf-8');
      return data.toString();
    }
    if (utils.isObject(data)) {
      setContentTypeIfUnset(headers, 'application/json;charset=utf-8');
      return JSON.stringify(data);
    }
    return data;
  }],

  transformResponse: [function transformResponse(data) {
    /*eslint no-param-reassign:0*/
    if (typeof data === 'string') {
      try {
        data = JSON.parse(data);
      } catch (e) { /* Ignore */ }
    }
    return data;
  }],

  /**
   * A timeout in milliseconds to abort a request. If set to 0 (default) a
   * timeout is not created.
   */
  timeout: 0,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  maxContentLength: -1,
  maxBodyLength: -1,

  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  }
};

defaults.headers = {
  common: {
    'Accept': 'application/json, text/plain, */*'
  }
};

utils.forEach(['delete', 'get', 'head'], function forEachMethodNoData(method) {
  defaults.headers[method] = {};
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  defaults.headers[method] = utils.merge(DEFAULT_CONTENT_TYPE);
});

module.exports = defaults;


/***/ }),

/***/ "./node_modules/axios/lib/helpers/bind.js":
/*!************************************************!*\
  !*** ./node_modules/axios/lib/helpers/bind.js ***!
  \************************************************/
/***/ ((module) => {

"use strict";


module.exports = function bind(fn, thisArg) {
  return function wrap() {
    var args = new Array(arguments.length);
    for (var i = 0; i < args.length; i++) {
      args[i] = arguments[i];
    }
    return fn.apply(thisArg, args);
  };
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/buildURL.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/helpers/buildURL.js ***!
  \****************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

function encode(val) {
  return encodeURIComponent(val).
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, '+').
    replace(/%5B/gi, '[').
    replace(/%5D/gi, ']');
}

/**
 * Build a URL by appending params to the end
 *
 * @param {string} url The base of the url (e.g., http://www.google.com)
 * @param {object} [params] The params to be appended
 * @returns {string} The formatted url
 */
module.exports = function buildURL(url, params, paramsSerializer) {
  /*eslint no-param-reassign:0*/
  if (!params) {
    return url;
  }

  var serializedParams;
  if (paramsSerializer) {
    serializedParams = paramsSerializer(params);
  } else if (utils.isURLSearchParams(params)) {
    serializedParams = params.toString();
  } else {
    var parts = [];

    utils.forEach(params, function serialize(val, key) {
      if (val === null || typeof val === 'undefined') {
        return;
      }

      if (utils.isArray(val)) {
        key = key + '[]';
      } else {
        val = [val];
      }

      utils.forEach(val, function parseValue(v) {
        if (utils.isDate(v)) {
          v = v.toISOString();
        } else if (utils.isObject(v)) {
          v = JSON.stringify(v);
        }
        parts.push(encode(key) + '=' + encode(v));
      });
    });

    serializedParams = parts.join('&');
  }

  if (serializedParams) {
    var hashmarkIndex = url.indexOf('#');
    if (hashmarkIndex !== -1) {
      url = url.slice(0, hashmarkIndex);
    }

    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
  }

  return url;
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/combineURLs.js":
/*!*******************************************************!*\
  !*** ./node_modules/axios/lib/helpers/combineURLs.js ***!
  \*******************************************************/
/***/ ((module) => {

"use strict";


/**
 * Creates a new URL by combining the specified URLs
 *
 * @param {string} baseURL The base URL
 * @param {string} relativeURL The relative URL
 * @returns {string} The combined URL
 */
module.exports = function combineURLs(baseURL, relativeURL) {
  return relativeURL
    ? baseURL.replace(/\/+$/, '') + '/' + relativeURL.replace(/^\/+/, '')
    : baseURL;
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/cookies.js":
/*!***************************************************!*\
  !*** ./node_modules/axios/lib/helpers/cookies.js ***!
  \***************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs support document.cookie
    (function standardBrowserEnv() {
      return {
        write: function write(name, value, expires, path, domain, secure) {
          var cookie = [];
          cookie.push(name + '=' + encodeURIComponent(value));

          if (utils.isNumber(expires)) {
            cookie.push('expires=' + new Date(expires).toGMTString());
          }

          if (utils.isString(path)) {
            cookie.push('path=' + path);
          }

          if (utils.isString(domain)) {
            cookie.push('domain=' + domain);
          }

          if (secure === true) {
            cookie.push('secure');
          }

          document.cookie = cookie.join('; ');
        },

        read: function read(name) {
          var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
          return (match ? decodeURIComponent(match[3]) : null);
        },

        remove: function remove(name) {
          this.write(name, '', Date.now() - 86400000);
        }
      };
    })() :

  // Non standard browser env (web workers, react-native) lack needed support.
    (function nonStandardBrowserEnv() {
      return {
        write: function write() {},
        read: function read() { return null; },
        remove: function remove() {}
      };
    })()
);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isAbsoluteURL.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isAbsoluteURL.js ***!
  \*********************************************************/
/***/ ((module) => {

"use strict";


/**
 * Determines whether the specified URL is absolute
 *
 * @param {string} url The URL to test
 * @returns {boolean} True if the specified URL is absolute, otherwise false
 */
module.exports = function isAbsoluteURL(url) {
  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
  // by any combination of letters, digits, plus, period, or hyphen.
  return /^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(url);
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isAxiosError.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isAxiosError.js ***!
  \********************************************************/
/***/ ((module) => {

"use strict";


/**
 * Determines whether the payload is an error thrown by Axios
 *
 * @param {*} payload The value to test
 * @returns {boolean} True if the payload is an error thrown by Axios, otherwise false
 */
module.exports = function isAxiosError(payload) {
  return (typeof payload === 'object') && (payload.isAxiosError === true);
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isURLSameOrigin.js":
/*!***********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isURLSameOrigin.js ***!
  \***********************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs have full support of the APIs needed to test
  // whether the request URL is of the same origin as current location.
    (function standardBrowserEnv() {
      var msie = /(msie|trident)/i.test(navigator.userAgent);
      var urlParsingNode = document.createElement('a');
      var originURL;

      /**
    * Parse a URL to discover it's components
    *
    * @param {String} url The URL to be parsed
    * @returns {Object}
    */
      function resolveURL(url) {
        var href = url;

        if (msie) {
        // IE needs attribute set twice to normalize properties
          urlParsingNode.setAttribute('href', href);
          href = urlParsingNode.href;
        }

        urlParsingNode.setAttribute('href', href);

        // urlParsingNode provides the UrlUtils interface - http://url.spec.whatwg.org/#urlutils
        return {
          href: urlParsingNode.href,
          protocol: urlParsingNode.protocol ? urlParsingNode.protocol.replace(/:$/, '') : '',
          host: urlParsingNode.host,
          search: urlParsingNode.search ? urlParsingNode.search.replace(/^\?/, '') : '',
          hash: urlParsingNode.hash ? urlParsingNode.hash.replace(/^#/, '') : '',
          hostname: urlParsingNode.hostname,
          port: urlParsingNode.port,
          pathname: (urlParsingNode.pathname.charAt(0) === '/') ?
            urlParsingNode.pathname :
            '/' + urlParsingNode.pathname
        };
      }

      originURL = resolveURL(window.location.href);

      /**
    * Determine if a URL shares the same origin as the current location
    *
    * @param {String} requestURL The URL to test
    * @returns {boolean} True if URL shares the same origin, otherwise false
    */
      return function isURLSameOrigin(requestURL) {
        var parsed = (utils.isString(requestURL)) ? resolveURL(requestURL) : requestURL;
        return (parsed.protocol === originURL.protocol &&
            parsed.host === originURL.host);
      };
    })() :

  // Non standard browser envs (web workers, react-native) lack needed support.
    (function nonStandardBrowserEnv() {
      return function isURLSameOrigin() {
        return true;
      };
    })()
);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/normalizeHeaderName.js":
/*!***************************************************************!*\
  !*** ./node_modules/axios/lib/helpers/normalizeHeaderName.js ***!
  \***************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ../utils */ "./node_modules/axios/lib/utils.js");

module.exports = function normalizeHeaderName(headers, normalizedName) {
  utils.forEach(headers, function processHeader(value, name) {
    if (name !== normalizedName && name.toUpperCase() === normalizedName.toUpperCase()) {
      headers[normalizedName] = value;
      delete headers[name];
    }
  });
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/parseHeaders.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/parseHeaders.js ***!
  \********************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(/*! ./../utils */ "./node_modules/axios/lib/utils.js");

// Headers whose duplicates are ignored by node
// c.f. https://nodejs.org/api/http.html#http_message_headers
var ignoreDuplicateOf = [
  'age', 'authorization', 'content-length', 'content-type', 'etag',
  'expires', 'from', 'host', 'if-modified-since', 'if-unmodified-since',
  'last-modified', 'location', 'max-forwards', 'proxy-authorization',
  'referer', 'retry-after', 'user-agent'
];

/**
 * Parse headers into an object
 *
 * ```
 * Date: Wed, 27 Aug 2014 08:58:49 GMT
 * Content-Type: application/json
 * Connection: keep-alive
 * Transfer-Encoding: chunked
 * ```
 *
 * @param {String} headers Headers needing to be parsed
 * @returns {Object} Headers parsed into an object
 */
module.exports = function parseHeaders(headers) {
  var parsed = {};
  var key;
  var val;
  var i;

  if (!headers) { return parsed; }

  utils.forEach(headers.split('\n'), function parser(line) {
    i = line.indexOf(':');
    key = utils.trim(line.substr(0, i)).toLowerCase();
    val = utils.trim(line.substr(i + 1));

    if (key) {
      if (parsed[key] && ignoreDuplicateOf.indexOf(key) >= 0) {
        return;
      }
      if (key === 'set-cookie') {
        parsed[key] = (parsed[key] ? parsed[key] : []).concat([val]);
      } else {
        parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
      }
    }
  });

  return parsed;
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/spread.js":
/*!**************************************************!*\
  !*** ./node_modules/axios/lib/helpers/spread.js ***!
  \**************************************************/
/***/ ((module) => {

"use strict";


/**
 * Syntactic sugar for invoking a function and expanding an array for arguments.
 *
 * Common use case would be to use `Function.prototype.apply`.
 *
 *  ```js
 *  function f(x, y, z) {}
 *  var args = [1, 2, 3];
 *  f.apply(null, args);
 *  ```
 *
 * With `spread` this example can be re-written.
 *
 *  ```js
 *  spread(function(x, y, z) {})([1, 2, 3]);
 *  ```
 *
 * @param {Function} callback
 * @returns {Function}
 */
module.exports = function spread(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
};


/***/ }),

/***/ "./node_modules/axios/lib/utils.js":
/*!*****************************************!*\
  !*** ./node_modules/axios/lib/utils.js ***!
  \*****************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var bind = __webpack_require__(/*! ./helpers/bind */ "./node_modules/axios/lib/helpers/bind.js");

/*global toString:true*/

// utils is a library of generic helper functions non-specific to axios

var toString = Object.prototype.toString;

/**
 * Determine if a value is an Array
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Array, otherwise false
 */
function isArray(val) {
  return toString.call(val) === '[object Array]';
}

/**
 * Determine if a value is undefined
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if the value is undefined, otherwise false
 */
function isUndefined(val) {
  return typeof val === 'undefined';
}

/**
 * Determine if a value is a Buffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Buffer, otherwise false
 */
function isBuffer(val) {
  return val !== null && !isUndefined(val) && val.constructor !== null && !isUndefined(val.constructor)
    && typeof val.constructor.isBuffer === 'function' && val.constructor.isBuffer(val);
}

/**
 * Determine if a value is an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
 */
function isArrayBuffer(val) {
  return toString.call(val) === '[object ArrayBuffer]';
}

/**
 * Determine if a value is a FormData
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an FormData, otherwise false
 */
function isFormData(val) {
  return (typeof FormData !== 'undefined') && (val instanceof FormData);
}

/**
 * Determine if a value is a view on an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
 */
function isArrayBufferView(val) {
  var result;
  if ((typeof ArrayBuffer !== 'undefined') && (ArrayBuffer.isView)) {
    result = ArrayBuffer.isView(val);
  } else {
    result = (val) && (val.buffer) && (val.buffer instanceof ArrayBuffer);
  }
  return result;
}

/**
 * Determine if a value is a String
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a String, otherwise false
 */
function isString(val) {
  return typeof val === 'string';
}

/**
 * Determine if a value is a Number
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Number, otherwise false
 */
function isNumber(val) {
  return typeof val === 'number';
}

/**
 * Determine if a value is an Object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Object, otherwise false
 */
function isObject(val) {
  return val !== null && typeof val === 'object';
}

/**
 * Determine if a value is a plain Object
 *
 * @param {Object} val The value to test
 * @return {boolean} True if value is a plain Object, otherwise false
 */
function isPlainObject(val) {
  if (toString.call(val) !== '[object Object]') {
    return false;
  }

  var prototype = Object.getPrototypeOf(val);
  return prototype === null || prototype === Object.prototype;
}

/**
 * Determine if a value is a Date
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Date, otherwise false
 */
function isDate(val) {
  return toString.call(val) === '[object Date]';
}

/**
 * Determine if a value is a File
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a File, otherwise false
 */
function isFile(val) {
  return toString.call(val) === '[object File]';
}

/**
 * Determine if a value is a Blob
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Blob, otherwise false
 */
function isBlob(val) {
  return toString.call(val) === '[object Blob]';
}

/**
 * Determine if a value is a Function
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Function, otherwise false
 */
function isFunction(val) {
  return toString.call(val) === '[object Function]';
}

/**
 * Determine if a value is a Stream
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Stream, otherwise false
 */
function isStream(val) {
  return isObject(val) && isFunction(val.pipe);
}

/**
 * Determine if a value is a URLSearchParams object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
 */
function isURLSearchParams(val) {
  return typeof URLSearchParams !== 'undefined' && val instanceof URLSearchParams;
}

/**
 * Trim excess whitespace off the beginning and end of a string
 *
 * @param {String} str The String to trim
 * @returns {String} The String freed of excess whitespace
 */
function trim(str) {
  return str.replace(/^\s*/, '').replace(/\s*$/, '');
}

/**
 * Determine if we're running in a standard browser environment
 *
 * This allows axios to run in a web worker, and react-native.
 * Both environments support XMLHttpRequest, but not fully standard globals.
 *
 * web workers:
 *  typeof window -> undefined
 *  typeof document -> undefined
 *
 * react-native:
 *  navigator.product -> 'ReactNative'
 * nativescript
 *  navigator.product -> 'NativeScript' or 'NS'
 */
function isStandardBrowserEnv() {
  if (typeof navigator !== 'undefined' && (navigator.product === 'ReactNative' ||
                                           navigator.product === 'NativeScript' ||
                                           navigator.product === 'NS')) {
    return false;
  }
  return (
    typeof window !== 'undefined' &&
    typeof document !== 'undefined'
  );
}

/**
 * Iterate over an Array or an Object invoking a function for each item.
 *
 * If `obj` is an Array callback will be called passing
 * the value, index, and complete array for each item.
 *
 * If 'obj' is an Object callback will be called passing
 * the value, key, and complete object for each property.
 *
 * @param {Object|Array} obj The object to iterate
 * @param {Function} fn The callback to invoke for each item
 */
function forEach(obj, fn) {
  // Don't bother if no value provided
  if (obj === null || typeof obj === 'undefined') {
    return;
  }

  // Force an array if not already something iterable
  if (typeof obj !== 'object') {
    /*eslint no-param-reassign:0*/
    obj = [obj];
  }

  if (isArray(obj)) {
    // Iterate over array values
    for (var i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // Iterate over object keys
    for (var key in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, key)) {
        fn.call(null, obj[key], key, obj);
      }
    }
  }
}

/**
 * Accepts varargs expecting each argument to be an object, then
 * immutably merges the properties of each object and returns result.
 *
 * When multiple objects contain the same key the later object in
 * the arguments list will take precedence.
 *
 * Example:
 *
 * ```js
 * var result = merge({foo: 123}, {foo: 456});
 * console.log(result.foo); // outputs 456
 * ```
 *
 * @param {Object} obj1 Object to merge
 * @returns {Object} Result of all merge properties
 */
function merge(/* obj1, obj2, obj3, ... */) {
  var result = {};
  function assignValue(val, key) {
    if (isPlainObject(result[key]) && isPlainObject(val)) {
      result[key] = merge(result[key], val);
    } else if (isPlainObject(val)) {
      result[key] = merge({}, val);
    } else if (isArray(val)) {
      result[key] = val.slice();
    } else {
      result[key] = val;
    }
  }

  for (var i = 0, l = arguments.length; i < l; i++) {
    forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Extends object a by mutably adding to it the properties of object b.
 *
 * @param {Object} a The object to be extended
 * @param {Object} b The object to copy properties from
 * @param {Object} thisArg The object to bind function to
 * @return {Object} The resulting value of object a
 */
function extend(a, b, thisArg) {
  forEach(b, function assignValue(val, key) {
    if (thisArg && typeof val === 'function') {
      a[key] = bind(val, thisArg);
    } else {
      a[key] = val;
    }
  });
  return a;
}

/**
 * Remove byte order marker. This catches EF BB BF (the UTF-8 BOM)
 *
 * @param {string} content with BOM
 * @return {string} content value without BOM
 */
function stripBOM(content) {
  if (content.charCodeAt(0) === 0xFEFF) {
    content = content.slice(1);
  }
  return content;
}

module.exports = {
  isArray: isArray,
  isArrayBuffer: isArrayBuffer,
  isBuffer: isBuffer,
  isFormData: isFormData,
  isArrayBufferView: isArrayBufferView,
  isString: isString,
  isNumber: isNumber,
  isObject: isObject,
  isPlainObject: isPlainObject,
  isUndefined: isUndefined,
  isDate: isDate,
  isFile: isFile,
  isBlob: isBlob,
  isFunction: isFunction,
  isStream: isStream,
  isURLSearchParams: isURLSearchParams,
  isStandardBrowserEnv: isStandardBrowserEnv,
  forEach: forEach,
  merge: merge,
  extend: extend,
  trim: trim,
  stripBOM: stripBOM
};


/***/ }),

/***/ "./js/components/PointerModal.svelte":
/*!*******************************************!*\
  !*** ./js/components/PointerModal.svelte ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* js/components/PointerModal.svelte generated by Svelte v3.44.1 */


function create_fragment(ctx) {
  let div2;
  let div0;
  let t;
  let div1;
  let div2_class_value;
  let mounted;
  let dispose;
  return {
    c() {
      div2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      div0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      div1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div0, "class", "ac-pointer-modal__arrow");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div0, "data-pos",
      /*position*/
      ctx[1]);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div1, "class", "ac-pointer-modal__content");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div2, "class", div2_class_value = "ac-pointer-modal -" +
      /*position*/
      ctx[1] + "");
    },

    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div2, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div2, div0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div2, t);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div2, div1);
      div1.innerHTML =
      /*content*/
      ctx[0];

      if (!mounted) {
        dispose = [(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div2, "mouseenter",
        /*mouseenter_handler*/
        ctx[6]), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div2, "mouseleave",
        /*mouseleave_handler*/
        ctx[7])];
        mounted = true;
      }
    },

    p(ctx, [dirty]) {
      if (dirty &
      /*position*/
      2) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div0, "data-pos",
        /*position*/
        ctx[1]);
      }

      if (dirty &
      /*content*/
      1) div1.innerHTML =
      /*content*/
      ctx[0];
      ;

      if (dirty &
      /*position*/
      2 && div2_class_value !== (div2_class_value = "ac-pointer-modal -" +
      /*position*/
      ctx[1] + "")) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div2, "class", div2_class_value);
      }
    },

    i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,

    d(detaching) {
      if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div2);
      mounted = false;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
    }

  };
}

function instance($$self, $$props, $$invalidate) {
  let {
    content = ''
  } = $$props;
  let {
    position = ''
  } = $$props;
  let onElement = false;
  let {
    closeHandler
  } = $$props;

  const isOnElement = () => {
    return onElement;
  };

  const checkLeave = () => {
    $$invalidate(2, onElement = false);
    closeHandler();
  };

  const mouseenter_handler = () => $$invalidate(2, onElement = true);

  const mouseleave_handler = () => checkLeave();

  $$self.$$set = $$props => {
    if ('content' in $$props) $$invalidate(0, content = $$props.content);
    if ('position' in $$props) $$invalidate(1, position = $$props.position);
    if ('closeHandler' in $$props) $$invalidate(4, closeHandler = $$props.closeHandler);
  };

  return [content, position, onElement, checkLeave, closeHandler, isOnElement, mouseenter_handler, mouseleave_handler];
}

class PointerModal extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
      content: 0,
      position: 1,
      closeHandler: 4,
      isOnElement: 5
    });
  }

  get isOnElement() {
    return this.$$.ctx[5];
  }

}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PointerModal);

/***/ }),

/***/ "./node_modules/nanoassert/index.js":
/*!******************************************!*\
  !*** ./node_modules/nanoassert/index.js ***!
  \******************************************/
/***/ ((module) => {

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
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

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
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

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
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

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
/***/ ((module) => {

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

/***/ "./js/admin/columns/ajax.ts":
/*!**********************************!*\
  !*** ./js/admin/columns/ajax.ts ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "submitColumnSettings": () => (/* binding */ submitColumnSettings),
/* harmony export */   "switchColumnType": () => (/* binding */ switchColumnType),
/* harmony export */   "refreshColumn": () => (/* binding */ refreshColumn)
/* harmony export */ });
const axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
const mapDataToFormData = (data, formData = new FormData()) => {
    Object.keys(data).forEach(key => {
        formData.append(key, data[key]);
    });
    return formData;
};
const submitColumnSettings = (data) => {
    return axios.post(ajaxurl, mapDataToFormData({
        action: 'ac-columns',
        id: 'save',
        _ajax_nonce: AC._ajax_nonce,
        data: JSON.stringify(data)
    }));
};
const switchColumnType = (type, list_screen = AC.list_screen) => {
    var _a;
    return axios.post(ajaxurl, mapDataToFormData({
        _ajax_nonce: AC._ajax_nonce,
        action: 'ac-columns',
        current_original_columns: JSON.stringify((_a = AC_SERVICES.getService('Form')) === null || _a === void 0 ? void 0 : _a.getOriginalColumns().map((e) => e.getName())),
        id: 'select',
        list_screen: list_screen,
        type: type,
    }));
};
const refreshColumn = (name, data, list_screen = AC.list_screen) => {
    return axios.post(ajaxurl, mapDataToFormData({
        _ajax_nonce: AC._ajax_nonce,
        action: 'ac-columns',
        column_name: name,
        data: data,
        id: 'refresh',
        list_screen: list_screen,
    }));
};


/***/ }),

/***/ "./js/admin/columns/column-configurator.ts":
/*!*************************************************!*\
  !*** ./js/admin/columns/column-configurator.ts ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ ColumnConfigurator)
/* harmony export */ });
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../constants */ "./js/constants.ts");
/* harmony import */ var _events_toggle__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./events/toggle */ "./js/admin/columns/events/toggle.ts");
/* harmony import */ var _events_indicator__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./events/indicator */ "./js/admin/columns/events/indicator.ts");
/* harmony import */ var _events_type_selector__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./events/type-selector */ "./js/admin/columns/events/type-selector.ts");
/* harmony import */ var _events_refresh__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./events/refresh */ "./js/admin/columns/events/refresh.ts");
/* harmony import */ var _events_remove__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./events/remove */ "./js/admin/columns/events/remove.ts");
/* harmony import */ var _events_clone__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./events/clone */ "./js/admin/columns/events/clone.ts");
/* harmony import */ var _events_label__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./events/label */ "./js/admin/columns/events/label.ts");
/* harmony import */ var _settings_label__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./settings/label */ "./js/admin/columns/settings/label.ts");
/* harmony import */ var _settings_image_size__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./settings/image-size */ "./js/admin/columns/settings/image-size.ts");
/* harmony import */ var _settings_number_format__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./settings/number-format */ "./js/admin/columns/settings/number-format.ts");
/* harmony import */ var _settings_type__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./settings/type */ "./js/admin/columns/settings/type.ts");
/* harmony import */ var _settings_width__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./settings/width */ "./js/admin/columns/settings/width.ts");
/* harmony import */ var _settings_date__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./settings/date */ "./js/admin/columns/settings/date.ts");
/* harmony import */ var _settings_pro__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./settings/pro */ "./js/admin/columns/settings/pro.ts");
/* harmony import */ var _settings_custom_field__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./settings/custom-field */ "./js/admin/columns/settings/custom-field.ts");
/* harmony import */ var _settings_sub_setting_toggle__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./settings/sub-setting-toggle */ "./js/admin/columns/settings/sub-setting-toggle.ts");
/* harmony import */ var _settings_multi_select__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./settings/multi-select */ "./js/admin/columns/settings/multi-select.ts");


















class ColumnConfigurator {
    constructor(Services) {
        Services.addListener(_constants__WEBPACK_IMPORTED_MODULE_0__.EventConstants.SETTINGS.COLUMN.INIT, (column) => {
            (0,_events_toggle__WEBPACK_IMPORTED_MODULE_1__.initToggle)(column);
            (0,_events_indicator__WEBPACK_IMPORTED_MODULE_2__.initIndicator)(column);
            (0,_events_type_selector__WEBPACK_IMPORTED_MODULE_3__.initTypeSelector)(column);
            (0,_events_remove__WEBPACK_IMPORTED_MODULE_5__.initRemoveColumn)(column);
            (0,_events_clone__WEBPACK_IMPORTED_MODULE_6__.initClone)(column);
            (0,_events_label__WEBPACK_IMPORTED_MODULE_7__.initLabelSettingEvents)(column);
            (0,_events_label__WEBPACK_IMPORTED_MODULE_7__.initLabelTooltipsEvent)(column);
            (0,_events_label__WEBPACK_IMPORTED_MODULE_7__.initLabel)(column);
            (0,_events_refresh__WEBPACK_IMPORTED_MODULE_4__.initColumnRefresh)(column);
            (0,_settings_multi_select__WEBPACK_IMPORTED_MODULE_17__.initMultiSelectFields)(column);
            (0,_settings_label__WEBPACK_IMPORTED_MODULE_8__.initLabelSetting)(column);
            (0,_settings_image_size__WEBPACK_IMPORTED_MODULE_9__.initImageSizeSetting)(column);
            (0,_settings_number_format__WEBPACK_IMPORTED_MODULE_10__.initNumberFormatSetting)(column);
            (0,_settings_type__WEBPACK_IMPORTED_MODULE_11__.initColumnTypeSelectorSetting)(column);
            (0,_settings_width__WEBPACK_IMPORTED_MODULE_12__.initWidthSetting)(column);
            (0,_settings_date__WEBPACK_IMPORTED_MODULE_13__.initDateSetting)(column);
            (0,_settings_pro__WEBPACK_IMPORTED_MODULE_14__.initProSetting)(column);
            (0,_settings_custom_field__WEBPACK_IMPORTED_MODULE_15__.initCustomFieldSelector)(column);
            (0,_settings_sub_setting_toggle__WEBPACK_IMPORTED_MODULE_16__.initSubSettings)(column);
        });
    }
}


/***/ }),

/***/ "./js/admin/columns/column.ts":
/*!************************************!*\
  !*** ./js/admin/columns/column.ts ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "COLUMN_EVENTS": () => (/* binding */ COLUMN_EVENTS),
/* harmony export */   "Column": () => (/* binding */ Column)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../constants */ "./js/constants.ts");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(nanobus__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _ajax__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ajax */ "./js/admin/columns/ajax.ts");
/* harmony import */ var _helpers_elements__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../helpers/elements */ "./js/helpers/elements.ts");
/* harmony import */ var _helpers_animations__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../helpers/animations */ "./js/helpers/animations.ts");
/* harmony import */ var _helpers_string__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../helpers/string */ "./js/helpers/string.ts");
// @ts-ignore







const STATES = {
    CLOSED: 'closed',
    OPEN: 'open'
};
const COLUMN_EVENTS = {
    REMOVE: 'remove',
    CLONE: 'clone',
};
class Column {
    constructor(element, name, services) {
        this.events = new (nanobus__WEBPACK_IMPORTED_MODULE_2___default())();
        this.name = name;
        this.element = element;
        this.state = STATES.CLOSED;
        this.services = services;
        this.setPropertiesByElement(element);
        this.init();
    }
    setPropertiesByElement(element) {
        this.type = element.dataset.type;
        this.original = element.dataset.original === '1';
        this.disabled = element.classList.contains('disabled');
        element.dataset.columnName = this.name;
        return this;
    }
    getName() {
        return this.name;
    }
    getType() {
        return this.type;
    }
    isOriginal() {
        return this.original;
    }
    getElement() {
        return this.element;
    }
    isDisabled() {
        return this.element.classList.contains('disabled');
    }
    disable() {
        this.element.classList.add('disabled');
        return this;
    }
    setLoading(enabled) {
        enabled
            ? this.getElement().classList.add('loading')
            : this.getElement().classList.remove('loading');
        return this;
    }
    enable() {
        this.element.classList.remove('disabled');
        return this;
    }
    init() {
        this.services.emitEvent(_constants__WEBPACK_IMPORTED_MODULE_1__.EventConstants.SETTINGS.COLUMN.INIT, this);
        return this;
    }
    destroy() {
        this.element.remove();
    }
    remove(duration = 350) {
        this.events.emit(COLUMN_EVENTS.REMOVE, this);
        (0,_helpers_animations__WEBPACK_IMPORTED_MODULE_5__.fadeOut)(this.getElement(), duration, () => {
            this.destroy();
        });
    }
    getState() {
        return this.state;
    }
    toggle(duration = 150) {
        this.getState() === STATES.OPEN
            ? this.close(duration)
            : this.open(duration);
    }
    close(duration = 0) {
        this.getElement().classList.remove('opened');
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(this.getElement()).find('.ac-column-body').slideUp(duration);
        this.state = STATES.CLOSED;
    }
    open(duration = 0) {
        this.getElement().classList.add('opened');
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(this.getElement()).find('.ac-column-body').slideDown(duration);
        this.state = STATES.OPEN;
    }
    isOpen() {
        return this.state === STATES.OPEN;
    }
    showMessage(message) {
        let msgElement = this.getElement().querySelector('.ac-column-setting--type .msg');
        if (msgElement) {
            msgElement.innerHTML = message;
            msgElement.style.display = 'block';
        }
    }
    getJson() {
        let formData = new FormData(this.getElement());
        formData.set('name', this.getName());
        var obj = {};
        // @ts-ignore
        for (var entry of formData.entries()) {
            let key = entry[0];
            let value = entry[1];
            if (this.fieldSupportsMultipleValues(key)) {
                let _value = obj.hasOwnProperty(key) ? obj[key] : [];
                _value.push(value);
                obj[key] = _value;
            }
            else {
                obj[key] = value;
            }
        }
        return obj;
    }
    switchToType(type) {
        this.setLoading(true);
        (0,_ajax__WEBPACK_IMPORTED_MODULE_3__.switchColumnType)(type).then((response) => {
            if (response.data.success) {
                let element = (0,_helpers_elements__WEBPACK_IMPORTED_MODULE_4__.createElementFromString)(response.data.data.trim()).firstChild;
                this.name = (0,_helpers_string__WEBPACK_IMPORTED_MODULE_6__.uniqid)();
                this.reinitColumnFromElement(element).open();
            }
            else {
                this.showMessage(response.data.data.error);
            }
        }).catch(() => {
            this.showMessage(AC.i18n.errors.loading_column);
        }).finally(() => this.setLoading(false));
    }
    refresh() {
        this.setLoading(true);
        (0,_ajax__WEBPACK_IMPORTED_MODULE_3__.refreshColumn)(this.getName(), JSON.stringify(this.getJson())).then((response) => {
            if (response.data.success) {
                this.reinitColumnFromElement((0,_helpers_elements__WEBPACK_IMPORTED_MODULE_4__.createElementFromString)(response.data.data.trim()).firstChild);
                this.services.emitEvent(_constants__WEBPACK_IMPORTED_MODULE_1__.EventConstants.SETTINGS.COLUMN.REFRESHED, this);
                if (this.isOpen()) {
                    this.open();
                }
            }
            else {
                this.showMessage(AC.i18n.errors.loading_column);
            }
        }).finally(() => this.setLoading(false));
    }
    fieldSupportsMultipleValues(key) {
        let element = this.getElement().elements[key];
        return (element && element.tagName === 'SELECT' && element.hasAttribute('multiple'));
    }
    reinitColumnFromElement(element) {
        var _a;
        (_a = this.getElement().parentNode) === null || _a === void 0 ? void 0 : _a.replaceChild(element, this.getElement());
        this.element = element;
        this.setPropertiesByElement(element).init();
        return this;
    }
}


/***/ }),

/***/ "./js/admin/columns/events/clone.ts":
/*!******************************************!*\
  !*** ./js/admin/columns/events/clone.ts ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initClone": () => (/* binding */ initClone)
/* harmony export */ });
/* harmony import */ var _column__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../column */ "./js/admin/columns/column.ts");
/*
* Column: bind clone events
*
* @since 2.0
*/

const initClone = (column) => {
    column.getElement().querySelectorAll('.clone-button').forEach(element => {
        element.addEventListener('click', e => {
            e.preventDefault();
            if (!column.isOriginal()) {
                column.events.emit(_column__WEBPACK_IMPORTED_MODULE_0__.COLUMN_EVENTS.CLONE);
            }
        });
    });
};


/***/ }),

/***/ "./js/admin/columns/events/indicator.ts":
/*!**********************************************!*\
  !*** ./js/admin/columns/events/indicator.ts ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initIndicator": () => (/* binding */ initIndicator)
/* harmony export */ });
const initIndicator = (column) => {
    if (column.isDisabled())
        return;
    column.getElement().querySelectorAll('[data-indicator-toggle]').forEach((indicatorElement) => {
        var _a;
        let settingName = (_a = indicatorElement.dataset.setting) !== null && _a !== void 0 ? _a : '';
        let radioInputs = column.getElement().querySelectorAll(`.ac-column-setting[data-setting='${indicatorElement.dataset.setting}'] .col-input .ac-setting-input:first-child input[type=radio]`);
        if (radioInputs.length) {
            initRadioRelations(column, indicatorElement, settingName);
        }
        let toggleInput = column.getElement().querySelector(`.ac-column-setting[data-setting='${indicatorElement.dataset.setting}'] .col-input .ac-setting-input:first-child .ac-toggle-v2`);
        if (toggleInput) {
            initToggleRelation(column, indicatorElement, settingName, toggleInput);
        }
    });
};
const initToggleRelation = (column, indicatorElement, setting, toggleSetting) => {
    var _a;
    let checkBox = (_a = toggleSetting.querySelector('input[type=checkbox]')) !== null && _a !== void 0 ? _a : document.createElement('input');
    indicatorElement.addEventListener('click', () => {
        checkBox.checked = !checkBox.checked;
        checkBox.dispatchEvent(new Event('input'));
    });
    checkBox.addEventListener('input', () => checkBox.checked ? indicatorElement.classList.add('on') : indicatorElement.classList.remove('on'));
};
const initRadioRelations = (column, indicatorElement, setting) => {
    let relatedSettings = column.getElement().querySelectorAll(`.ac-column-setting[data-setting='${setting}'] .col-input .ac-setting-input:first-child input[type=radio]`);
    indicatorElement.addEventListener('click', () => {
        switchTo(!indicatorElement.classList.contains('on'), relatedSettings);
    });
    relatedSettings.forEach(element => {
        element.addEventListener('change', () => {
            element.value === 'off'
                ? indicatorElement.classList.remove('on')
                : indicatorElement.classList.add('on');
        });
    });
};
const switchTo = (checked, elements) => {
    let checkvalue = checked ? 'on' : 'off';
    elements.forEach(el => {
        if (el.value === checkvalue) {
            el.checked = true;
            el.dispatchEvent(new Event('change'));
            el.dispatchEvent(new Event('click'));
        }
    });
};


/***/ }),

/***/ "./js/admin/columns/events/label.ts":
/*!******************************************!*\
  !*** ./js/admin/columns/events/label.ts ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initLabel": () => (/* binding */ initLabel),
/* harmony export */   "initLabelSettingEvents": () => (/* binding */ initLabelSettingEvents),
/* harmony export */   "initLabelTooltipsEvent": () => (/* binding */ initLabelTooltipsEvent)
/* harmony export */ });
/* harmony import */ var _helpers_events__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../helpers/events */ "./js/helpers/events.ts");

const initLabel = (column) => {
    column.getElement().querySelectorAll('select[data-label="update"]').forEach((select) => {
        select.addEventListener('change', () => {
            let labelSetting = column.getElement().querySelector('input.ac-setting-input_label');
            let option = select.selectedOptions.length > 0 ? select.selectedOptions[0] : null;
            if (labelSetting && option) {
                labelSetting.value = option.innerHTML;
                labelSetting.dispatchEvent(new Event('change'));
            }
        });
    });
    setTimeout(() => {
        column.getElement().querySelectorAll('[data-column-label]').forEach(el => {
            if (el.offsetWidth < 10) {
                el.innerText = column.getType();
            }
        });
    }, 50);
};
const initLabelSettingEvents = (column) => {
    let labelInput = column.getElement().querySelector('.ac-column-setting--label input[type=text]');
    if (labelInput) {
        (0,_helpers_events__WEBPACK_IMPORTED_MODULE_0__.addEventListeners)(labelInput, ['change', 'keyup'], () => changeLabel(labelInput, column));
    }
};
const initLabelTooltipsEvent = (column) => {
    column.getElement().querySelectorAll('.col-label .label').forEach(label => {
        (0,_helpers_events__WEBPACK_IMPORTED_MODULE_0__.onHover)(label, () => hoverTooltip(label, 'block'), () => hoverTooltip(label, 'none'));
    });
};
const hoverTooltip = (label, display) => {
    var _a;
    let related = (_a = label.closest('.col-label')) === null || _a === void 0 ? void 0 : _a.querySelector('div.tooltip');
    if (!!related) {
        related.style.display = display;
    }
};
const changeLabel = (labelInput, column) => {
    column.getElement().querySelectorAll('[data-column-label]').forEach(el => el.innerHTML = labelInput.value);
};


/***/ }),

/***/ "./js/admin/columns/events/refresh.ts":
/*!********************************************!*\
  !*** ./js/admin/columns/events/refresh.ts ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initColumnRefresh": () => (/* binding */ initColumnRefresh)
/* harmony export */ });
const initColumnRefresh = (column) => {
    column.getElement().querySelectorAll('[data-refresh="column"], [data-refresh="column"] input').forEach(element => {
        element.addEventListener('change', () => {
            // Allow other settings to do their thing first so all changes are refreshed correctly
            setTimeout(() => column.refresh(), 50);
        });
    });
};


/***/ }),

/***/ "./js/admin/columns/events/remove.ts":
/*!*******************************************!*\
  !*** ./js/admin/columns/events/remove.ts ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initRemoveColumn": () => (/* binding */ initRemoveColumn)
/* harmony export */ });
const initRemoveColumn = (column) => {
    column.getElement().querySelectorAll('[data-remove-column]').forEach((element) => {
        element.addEventListener('click', e => {
            e.preventDefault();
            column.remove();
        });
    });
};


/***/ }),

/***/ "./js/admin/columns/events/toggle.ts":
/*!*******************************************!*\
  !*** ./js/admin/columns/events/toggle.ts ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initToggle": () => (/* binding */ initToggle)
/* harmony export */ });
const initToggle = (column) => {
    column.getElement().querySelectorAll('[data-toggle="column"]').forEach((el) => {
        el.addEventListener('click', e => column.toggle());
        el.style.cursor = 'pointer';
    });
};


/***/ }),

/***/ "./js/admin/columns/events/type-selector.ts":
/*!**************************************************!*\
  !*** ./js/admin/columns/events/type-selector.ts ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initTypeSelector": () => (/* binding */ initTypeSelector)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
// @ts-ignore

const initTypeSelector = (column) => {
    column.getElement().querySelectorAll('select.ac-setting-input_type').forEach(select => {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(select).on('change', () => column.switchToType(select.value));
    });
};


/***/ }),

/***/ "./js/admin/columns/feedback.ts":
/*!**************************************!*\
  !*** ./js/admin/columns/feedback.ts ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _helpers_animations__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../helpers/animations */ "./js/helpers/animations.ts");

class Feedback {
    constructor(element) {
        this.element = element;
        this.init();
    }
    init() {
        var _a, _b;
        (_a = this.element.querySelector('a.no')) === null || _a === void 0 ? void 0 : _a.addEventListener('click', (e) => {
            e.preventDefault();
            (0,_helpers_animations__WEBPACK_IMPORTED_MODULE_0__.fadeOut)(this.element.querySelector('#feedback-choice'), 300, () => {
                (0,_helpers_animations__WEBPACK_IMPORTED_MODULE_0__.fadeIn)(this.element.querySelector('#feedback-support'), 300);
            });
        });
        (_b = this.element.querySelector('a.yes')) === null || _b === void 0 ? void 0 : _b.addEventListener('click', (e) => {
            e.preventDefault();
            (0,_helpers_animations__WEBPACK_IMPORTED_MODULE_0__.fadeOut)(this.element.querySelector('#feedback-choice'), 300, () => {
                (0,_helpers_animations__WEBPACK_IMPORTED_MODULE_0__.fadeIn)(this.element.querySelector('#feedback-rate'), 300);
            });
        });
    }
    ;
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Feedback);


/***/ }),

/***/ "./js/admin/columns/form.ts":
/*!**********************************!*\
  !*** ./js/admin/columns/form.ts ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Form": () => (/* binding */ Form)
/* harmony export */ });
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../constants */ "./js/constants.ts");
/* harmony import */ var _column__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./column */ "./js/admin/columns/column.ts");
/* harmony import */ var _ajax__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ajax */ "./js/admin/columns/ajax.ts");
/* harmony import */ var _helpers_animations__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../helpers/animations */ "./js/helpers/animations.ts");
/* harmony import */ var _helpers_elements__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../helpers/elements */ "./js/helpers/elements.ts");
/* harmony import */ var _helpers_string__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../helpers/string */ "./js/helpers/string.ts");
/* harmony import */ var _helpers_html_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../helpers/html-element */ "./js/helpers/html-element.ts");







class Form {
    constructor(element, services) {
        this.form = element;
        this.services = services;
        this.columns = [];
        this.services.emitEvent(_constants__WEBPACK_IMPORTED_MODULE_0__.EventConstants.SETTINGS.FORM.LOADED, this);
        this.init();
    }
    init() {
        this.initColumns();
        if (this.isDisabled()) {
            this.disableFields();
            this.disableColumns();
        }
        this.services.emitEvent(_constants__WEBPACK_IMPORTED_MODULE_0__.EventConstants.SETTINGS.FORM.READY, this);
    }
    getElement() {
        return this.form;
    }
    getColumns() {
        return this.columns;
    }
    getSortedColumns() {
        let result = [];
        this.getElement().querySelectorAll('form.ac-column').forEach(column => {
            let c = this.columns.find(c => c.getName() === column.dataset.columnName);
            if (!!c) {
                result.push(c);
            }
        });
        return result;
    }
    placeColumn(column, after = null) {
        var _a;
        if (after) {
            (0,_helpers_elements__WEBPACK_IMPORTED_MODULE_4__.insertAfter)(column.getElement(), after);
        }
        else {
            (_a = this.getElement().querySelector('.ac-columns')) === null || _a === void 0 ? void 0 : _a.append(column.getElement());
        }
        setTimeout(() => {
            (0,_helpers_animations__WEBPACK_IMPORTED_MODULE_3__.scrollToElement)(column.getElement(), 300, { offset: -18 });
        }, 300);
        return this;
    }
    createNewColumn() {
        let column = createColumnFromTemplate(this.services);
        this.columns.push(column);
        this.placeColumn(column);
        this.bindColumnEvents(column);
        column.open(300);
        return column;
    }
    isDisabled() {
        return this.form.classList.contains('-disabled');
    }
    getOriginalColumns() {
        return this.columns.filter(column => column.isOriginal());
    }
    disableColumns() {
        this.columns.forEach(col => col.disable());
    }
    initColumns() {
        this.getElement().querySelectorAll('.ac-column').forEach((element) => {
            var _a;
            let column = new _column__WEBPACK_IMPORTED_MODULE_1__.Column(element, (_a = element.dataset.columnName) !== null && _a !== void 0 ? _a : '', this.services);
            this.columns.push(column);
            this.bindColumnEvents(column);
        });
    }
    bindColumnEvents(column) {
        column.events.addListener(_column__WEBPACK_IMPORTED_MODULE_1__.COLUMN_EVENTS.REMOVE, () => {
            this.removeColumn(column.getName());
        });
        column.events.addListener(_column__WEBPACK_IMPORTED_MODULE_1__.COLUMN_EVENTS.CLONE, () => {
            let cloneColumn = new _column__WEBPACK_IMPORTED_MODULE_1__.Column(column.getElement().cloneNode(true), (0,_helpers_string__WEBPACK_IMPORTED_MODULE_5__.uniqid)(), this.services);
            this.columns.push(cloneColumn);
            this.placeColumn(cloneColumn, column.getElement()).bindColumnEvents(cloneColumn);
            column.isOpen() ? cloneColumn.open() : cloneColumn.close();
            (0,_helpers_animations__WEBPACK_IMPORTED_MODULE_3__.fadeIn)(cloneColumn.getElement(), 300);
        });
    }
    resetColumns() {
        this.columns.forEach((column) => {
            column.destroy();
        });
        this.columns = [];
    }
    getFormData() {
        let columnData = {};
        let titleElement = this.getElement().querySelector('input[name=title]');
        this.getSortedColumns().forEach(column => {
            columnData[column.getName()] = column.getJson();
        });
        return {
            title: titleElement ? titleElement.value : '',
            list_screen: AC.list_screen,
            list_screen_id: AC.layout,
            columns: columnData,
            settings: this.getPreferences()
        };
    }
    disableFields() {
        this.getElement().querySelectorAll('input, select, button').forEach(el => {
            el.setAttribute('readonly', 'readonly');
            el.setAttribute('disabled', 'disabled');
        });
    }
    submitForm() {
        this.services.emitEvent(_constants__WEBPACK_IMPORTED_MODULE_0__.EventConstants.SETTINGS.FORM.SAVING, this);
        (0,_ajax__WEBPACK_IMPORTED_MODULE_2__.submitColumnSettings)(this.getFormData()).then((response) => {
            if (response.data.success) {
                this.showMessage(response.data.data.message, 'updated');
                AC.layout = response.data.data.list_id;
            }
            else if (response.data) {
                let error = response.data;
                this.showMessage(error.data.message, 'notice notice-warning');
            }
        }).catch(() => {
            this.showMessage(AC.i18n.error.save_settings);
        }).finally(() => {
            this.services.emitEvent(_constants__WEBPACK_IMPORTED_MODULE_0__.EventConstants.SETTINGS.FORM.SAVED, this);
        });
    }
    showMessage(message, className = 'updated') {
        let element = _helpers_html_element__WEBPACK_IMPORTED_MODULE_6__["default"].create('div').addClass('ac-message').addClasses(...className.split(' ')).addHtml(`<p>${message}</p>`).getElement();
        let messageContainer = document.querySelector('.ac-admin__main');
        if (messageContainer) {
            messageContainer.querySelectorAll('.ac-message').forEach((el) => el.remove());
            messageContainer.insertAdjacentElement('afterbegin', element);
        }
        (0,_helpers_animations__WEBPACK_IMPORTED_MODULE_3__.fadeIn)(element, 600);
    }
    removeColumn(name) {
        this.columns.forEach((c, i) => {
            if (name === c.getName()) {
                this.columns.splice(i, 1);
            }
        });
    }
    getPreferences() {
        let data = {};
        document.querySelectorAll('form[data-form-part=preferences]').forEach(el => {
            let fData = new FormData(el);
            // @ts-ignore
            for (let entry of fData.entries()) {
                let key = entry[0];
                let value = entry[1];
                // @ts-ignore
                let element = el.elements[key];
                data[key] = element.tagName === 'SELECT' && element.hasAttribute('multiple')
                    ? fData.getAll(key)
                    : data[key] = value;
            }
        });
        return data;
    }
}
const createColumnFromTemplate = (services) => {
    var _a;
    let columnElement = (_a = document.querySelector('#add-new-column-template .ac-column')) === null || _a === void 0 ? void 0 : _a.cloneNode(true);
    const newColumnName = (0,_helpers_string__WEBPACK_IMPORTED_MODULE_5__.uniqid)();
    columnElement.querySelectorAll('label[for]').forEach(label => {
        let relatedId = label.getAttribute('for');
        if (relatedId) {
            let relatedElement = columnElement.querySelector(`#${relatedId}`);
            if (relatedElement) {
                const newID = relatedId + newColumnName;
                label.setAttribute('for', newID);
                relatedElement.id = newID;
            }
        }
    });
    return new _column__WEBPACK_IMPORTED_MODULE_1__.Column(columnElement, newColumnName, services);
};


/***/ }),

/***/ "./js/admin/columns/listscreen-initialize.ts":
/*!***************************************************!*\
  !*** ./js/admin/columns/listscreen-initialize.ts ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initUninitializedListScreens": () => (/* binding */ initUninitializedListScreens)
/* harmony export */ });
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(nanobus__WEBPACK_IMPORTED_MODULE_0__);

const axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
class ListScreenInitializer {
    constructor(list_screens) {
        this.listScreens = list_screens;
        this.processed = [];
        this.errors = [];
        this.success = [];
        this.events = new (nanobus__WEBPACK_IMPORTED_MODULE_0___default())();
        this.run();
    }
    doAjaxCall(listScreen) {
        return axios.get(listScreen.screen_link);
    }
    run() {
        Object.values(this.listScreens).forEach((l) => this.processListScreen(l));
    }
    onFinish() {
        if (this.success.length === Object.keys(this.listScreens).length) {
            this.events.emit('success');
        }
        if (this.errors.length > 0) {
            this.events.emit('error');
        }
    }
    checkFinish() {
        if (this.processed.length === Object.keys(this.listScreens).length) {
            this.onFinish();
        }
    }
    processListScreen(listScreen) {
        this.doAjaxCall(listScreen).then((response) => {
            response.data === 'ac_success'
                ? this.success.push(listScreen)
                : this.errors.push(listScreen);
        }).catch(() => {
            this.errors.push(listScreen);
        }).finally(() => {
            this.processed.push(listScreen);
            this.checkFinish();
        });
    }
}
const initUninitializedListScreens = (listScreens) => {
    if (Object.keys(listScreens).length > 0) {
        // Only load main screen first if unitialized, otherwise do the rest in background
        if (listScreens.hasOwnProperty(AC.list_screen)) {
            const main_initializer = new ListScreenInitializer({ [AC.list_screen]: listScreens[AC.list_screen] });
            main_initializer.events.on('error', () => {
                document.querySelectorAll('.ac-loading-msg-wrapper').forEach(el => el.remove());
                document.querySelectorAll('.menu').forEach(el => el.classList.remove('hidden'));
            });
            main_initializer.events.on('success', () => {
                window.location.href = `${location.href}&t=${Date.now()}`;
            });
        }
        else {
            new ListScreenInitializer(listScreens);
        }
    }
};


/***/ }),

/***/ "./js/admin/columns/screen-options.ts":
/*!********************************************!*\
  !*** ./js/admin/columns/screen-options.ts ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ InfoScreenOption)
/* harmony export */ });
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _helpers_global__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../helpers/global */ "./js/helpers/global.ts");


class InfoScreenOption {
    constructor(name, input, toggleClass, container) {
        this.name = name;
        this.input = input;
        this.toggleClass = toggleClass;
        this.container = container;
        this.initEvents();
    }
    initEvents() {
        this.input.addEventListener('change', () => {
            this.input.checked
                ? this.container.classList.add(this.toggleClass)
                : this.container.classList.remove(this.toggleClass);
            this.persist();
        });
    }
    persist() {
        axios__WEBPACK_IMPORTED_MODULE_0___default().post(ajaxurl, (0,_helpers_global__WEBPACK_IMPORTED_MODULE_1__.mapDataToFormData)({
            action: 'ac_admin_screen_options',
            _ajax_nonce: AC._ajax_nonce,
            option_name: this.name,
            option_value: this.input.checked ? 1 : 0
        }));
    }
}


/***/ }),

/***/ "./js/admin/columns/settings/custom-field.ts":
/*!***************************************************!*\
  !*** ./js/admin/columns/settings/custom-field.ts ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initCustomFieldSelector": () => (/* binding */ initCustomFieldSelector)
/* harmony export */ });
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(nanobus__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_1__);

// @ts-ignore

const axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
const initCustomFieldSelector = (column) => {
    column.getElement().querySelectorAll('[data-setting=custom_field]').forEach(setting => new CustomField(column, setting));
};
class CustomField {
    constructor(column, setting) {
        this.column = column;
        this.setting = setting;
        this.select = setting.querySelector('.custom_field');
        this.bindEvents();
    }
    bindEvents() {
        var _a, _b;
        const request = loadSingleRequestManager((_a = this.select.dataset.type) !== null && _a !== void 0 ? _a : '', (_b = this.select.dataset.post_type) !== null && _b !== void 0 ? _b : '');
        const editingAvailable = this.column.getElement().querySelectorAll('[data-setting="edit"][data-indicator-toggle]').length > 0;
        // Ensure you won't get any duplicates on clone
        this.select.querySelectorAll('optgroup').forEach(el => {
            el.remove();
        });
        this.select.removeAttribute('data-select2-id');
        this.setting.querySelectorAll('.select2').forEach(el => {
            el.remove();
        });
        request.getOptions().then((data) => {
            jquery__WEBPACK_IMPORTED_MODULE_1___default()(this.select).ac_select2({
                theme: 'acs2',
                width: '100%',
                tags: editingAvailable,
                dropdownCssClass: '-customfields',
                data: data
            });
        });
    }
}
class SingleCustomFieldRequestManager {
    constructor(metaType, postType) {
        this.metaType = metaType;
        this.postType = postType;
        this.loading = false;
        this.data = null;
        this.events = new (nanobus__WEBPACK_IMPORTED_MODULE_0___default())();
    }
    retrieveOptions() {
        this.loading = true;
        let formData = new FormData();
        formData.set('action', 'ac_custom_field_options');
        formData.set('post_type', this.postType);
        formData.set('meta_type', this.metaType);
        formData.set('_ajax_nonce', AC._ajax_nonce);
        return axios.post(ajaxurl, formData);
    }
    getOptions() {
        return new Promise((resolve, reject) => {
            if (this.data) {
                resolve(this.data);
            }
            else if (this.loading) {
                this.events.on('loaded', () => {
                    resolve(this.data);
                });
            }
            else {
                this.retrieveOptions().then((response) => {
                    if (!response.data.success) {
                        reject();
                    }
                    this.data = response.data.data.results;
                    this.events.emit('loaded');
                    resolve(this.data);
                });
            }
        });
    }
}
const loadSingleRequestManager = (metaType, postType) => {
    const key = `custom_field_${metaType}_${postType}`;
    if (typeof window.AC_Requests === 'undefined') {
        window.AC_Requests = {};
    }
    if (!window.AC_Requests.hasOwnProperty(key)) {
        window.AC_Requests[key] = new SingleCustomFieldRequestManager(metaType, postType);
    }
    return window.AC_Requests[key];
};


/***/ }),

/***/ "./js/admin/columns/settings/date.ts":
/*!*******************************************!*\
  !*** ./js/admin/columns/settings/date.ts ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initDateSetting": () => (/* binding */ initDateSetting)
/* harmony export */ });
const axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
const initDateSetting = (column) => {
    column.getElement().querySelectorAll('[data-setting=date]').forEach(setting => new DateSetting(column, setting));
};
class DateSetting {
    constructor(column, setting) {
        this.column = column;
        this.setting = setting;
        this.options = this.setting.querySelectorAll('.radio-labels input[type=radio]');
        this.valueInput = this.setting.querySelector('[data-value-input]');
        let defaultElement = this.setting.querySelector('.radio-labels code');
        this.defaultFormat = defaultElement ? defaultElement.textContent : 'Y-m-d';
        // @ts-ignore
        let customInput = [...this.options].filter(radio => typeof radio.dataset.custom !== 'undefined');
        this.customOption = new CustomOption(customInput[0], this.setting.querySelector('[data-custom-date]'), this.setting.querySelector('.ac-setting-input-date__example'), this.valueInput);
        this.initEvents();
    }
    getOptionsAsArray() {
        return Array.from(this.options);
    }
    getSelectionOption() {
        let selected = this.getOptionsAsArray().filter(option => option.checked);
        return selected ? selected[0] : null;
    }
    initEvents() {
        this.options.forEach(radio => {
            radio.addEventListener('change', () => this.handleUpdate(radio));
        });
        this.setSelected();
        this.customOption.updateExample();
    }
    setSelected() {
        let selected = this.getOptionsAsArray().find(option => option.value === this.getCurrentValue());
        if (selected) {
            selected.checked = true;
            selected.dispatchEvent(new Event('change'));
        }
    }
    handleUpdate(input) {
        var _a, _b, _c;
        this.valueInput.value = input.value;
        this.customOption.toggle(typeof input.dataset.custom !== 'undefined');
        let helpText = (_c = (_b = (_a = input.closest('label')) === null || _a === void 0 ? void 0 : _a.querySelector('[data-help]')) === null || _b === void 0 ? void 0 : _b.innerHTML) !== null && _c !== void 0 ? _c : '';
        this.setHelpText(helpText);
        if (typeof input.dataset.custom !== 'undefined') {
            return;
        }
        switch (this.valueInput.value) {
            case 'custom':
                break;
            case 'wp_default':
            case 'diff':
                this.customOption.setExample('');
                break;
            default:
                this.customOption.setExample(this.valueInput.value);
        }
        this.customOption.updateExample();
    }
    getCurrentValue() {
        return this.valueInput.value;
    }
    setHelpText(text) {
        let element = this.setting.querySelector('.help-msg');
        if (element) {
            element.innerHTML = text;
            element.style.display = 'block';
        }
    }
}
class CustomOption {
    constructor(radio, input, example, valueElement) {
        this.radio = radio;
        this.input = input;
        this.example = example;
        this.valueElement = valueElement;
        this.timeout = null;
        this.input.addEventListener('change', () => {
            this.updateExample();
            if (radio.checked) {
                this.valueElement.value = this.input.value;
            }
        });
        this.input.addEventListener('keyup', () => {
            if (radio.checked) {
                this.valueElement.value = this.input.value;
            }
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => this.updateExample(), 500);
        });
    }
    setExample(example) {
        this.input.value = example;
    }
    updateExample() {
        this.getExample().then((response) => {
            this.example.innerHTML = response.data;
        });
    }
    toggle(enable = true) {
        enable
            ? this.input.removeAttribute('disabled')
            : this.input.setAttribute('disabled', 'disabled');
    }
    getExample() {
        let data = new FormData();
        data.set('action', 'date_format');
        data.set('date', this.input.value);
        return axios.post(ajaxurl, data, {});
    }
}


/***/ }),

/***/ "./js/admin/columns/settings/image-size.ts":
/*!*************************************************!*\
  !*** ./js/admin/columns/settings/image-size.ts ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initImageSizeSetting": () => (/* binding */ initImageSizeSetting),
/* harmony export */   "ImageSizeSetting": () => (/* binding */ ImageSizeSetting)
/* harmony export */ });
const initImageSizeSetting = (column) => {
    let setting = column.getElement().querySelector('.ac-column-setting--image');
    if (setting) {
        new ImageSizeSetting(column, setting);
    }
};
class ImageSizeSetting {
    constructor(column, setting) {
        this.column = column;
        this.setting = setting;
        this.field = this.setting.querySelector('.ac-setting-input select');
        this.initState();
        this.bindEvents();
    }
    getValue() {
        var _a;
        return (_a = this.field) === null || _a === void 0 ? void 0 : _a.value;
    }
    bindEvents() {
        var _a;
        (_a = this.field) === null || _a === void 0 ? void 0 : _a.addEventListener('change', () => this.initState());
    }
    initState() {
        this.toggleSubSettings('cpac-custom' === this.getValue());
    }
    toggleSubSettings(show = true) {
        this.setting.querySelectorAll('.ac-column-setting').forEach(setting => setting.style.display = show ? 'table' : 'none');
    }
}


/***/ }),

/***/ "./js/admin/columns/settings/label.ts":
/*!********************************************!*\
  !*** ./js/admin/columns/settings/label.ts ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initLabelSetting": () => (/* binding */ initLabelSetting)
/* harmony export */ });
/* harmony import */ var _modules_modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../modules/modal */ "./js/modules/modal.ts");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(nanobus__WEBPACK_IMPORTED_MODULE_1__);


const initLabelSetting = (column) => {
    let setting = column.getElement().querySelector('.ac-column-setting--label');
    if (setting) {
        new LabelSetting(column, setting);
    }
};
class LabelSetting {
    constructor(column, setting) {
        this.column = column;
        this.setting = setting;
        this.column = column;
        this.setting = setting;
        this.field = this.setting.querySelector('.ac-setting-input_label');
        let iconPicker = column.getElement().querySelector('.-iconpicker');
        if (iconPicker) {
            this.modal = new IconPickerModal(iconPicker);
            this.modal.setIconSelection(this.getDashIconFromValue());
            this.initEvents();
        }
    }
    initEvents() {
        this.column.getElement().querySelectorAll('.ac-setting-label-icon').forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                this.modal.open();
            });
        });
        this.modal.onSubmit(() => {
            this.setLabel(this.modal.getDashIconMarkup());
            this.modal.close();
        });
    }
    getDashIconFromValue() {
        let html = document.createRange().createContextualFragment(this.getValue());
        let dashicon = html.querySelector('.dashicons');
        let value = '';
        if (!dashicon) {
            return value;
        }
        dashicon.classList.forEach(cls => {
            if (cls.indexOf('dashicons-') === 0) {
                value = cls.replace('dashicons-', '');
            }
        });
        return value;
    }
    getValue() {
        var _a, _b;
        return (_b = (_a = this.field) === null || _a === void 0 ? void 0 : _a.value) !== null && _b !== void 0 ? _b : '';
    }
    setLabel(label) {
        if (this.field) {
            this.field.value = label;
            this.field.dispatchEvent(new Event('change'));
        }
    }
}
class IconPickerModal extends _modules_modal__WEBPACK_IMPORTED_MODULE_0__["default"] {
    constructor(element) {
        super(element);
        this.events = new (nanobus__WEBPACK_IMPORTED_MODULE_1___default())();
        this.dashIcon = '';
        this.search = new IconSearch(this);
    }
    initEvents() {
        super.initEvents();
        this.getElement().querySelectorAll('[data-action="submit"]').forEach((element) => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                this.events.emit('submit');
            });
        });
        this.getElement().querySelectorAll('input[type=search]').forEach((element) => {
            ['keyup', 'search'].forEach(event => {
                element.addEventListener(event, (e) => {
                    e.preventDefault();
                    this.search.searchFor(element.value);
                });
            });
        });
        this.getIconElements().forEach(icon => {
            icon.addEventListener('click', (e) => {
                var _a;
                e.preventDefault();
                this.setIconSelection((_a = icon.dataset.dashicon) !== null && _a !== void 0 ? _a : '');
                this.getIconElements().forEach(el => el.classList.remove('active'));
                icon.classList.add('active');
            });
        });
    }
    getIconElements() {
        return this.getElement().querySelectorAll('.ac-ipicker__icon');
    }
    onSubmit(cb) {
        this.events.on('submit', cb);
    }
    getDashIconMarkup() {
        return `<span class="dashicons dashicons-${this.dashIcon}"></span>`;
    }
    setIconSelection(dashicon) {
        let selection = this.getElement().querySelector('.ac-ipicker__selection');
        this.dashIcon = dashicon;
        if (selection) {
            selection.innerHTML = this.getDashIconMarkup();
            selection.style.visibility = 'visible';
        }
    }
}
class IconSearch {
    constructor(modal) {
        this.modal = modal;
    }
    searchFor(query) {
        this.modal.getElement().querySelectorAll('[data-dashicon]').forEach(el => {
            var _a;
            if (((_a = el.dataset.dashicon) === null || _a === void 0 ? void 0 : _a.indexOf(query.toLowerCase())) !== -1) {
                el.style.display = 'inline-block';
            }
            else {
                el.style.display = 'none';
            }
        });
        this.determineVisibilityGroups();
    }
    determineVisibilityGroups() {
        this.modal.getElement().querySelectorAll('.ac-ipicker__group').forEach(group => {
            let icons = group.querySelectorAll('[data-dashicon]');
            let hiddenItems = Array.from(icons).filter(el => {
                return (el.offsetParent === null);
            });
            let header = group.querySelector('h3');
            if (header) {
                header.style.display = icons.length == hiddenItems.length ? 'none' : 'block';
            }
        });
    }
}


/***/ }),

/***/ "./js/admin/columns/settings/multi-select.ts":
/*!***************************************************!*\
  !*** ./js/admin/columns/settings/multi-select.ts ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initMultiSelectFields": () => (/* binding */ initMultiSelectFields)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
// @ts-ignore

const initMultiSelectFields = (column) => {
    column.getElement().querySelectorAll('select[multiple]').forEach(select => {
        new MultiSelect(column, select);
    });
};
class MultiSelect {
    constructor(column, select) {
        this.column = column;
        this.select = select;
        this.bindEvents();
    }
    bindEvents() {
        var _a;
        // First remove all cloned Select2 elements
        this.select.removeAttribute('data-select2-id');
        (_a = this.select.parentElement) === null || _a === void 0 ? void 0 : _a.querySelectorAll('.select2').forEach(el => {
            el.remove();
        });
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(this.select).ac_select2({
            theme: 'acs2',
            width: '100%',
            escapeMarkup: function (text) {
                return text;
            },
        });
    }
}


/***/ }),

/***/ "./js/admin/columns/settings/number-format.ts":
/*!****************************************************!*\
  !*** ./js/admin/columns/settings/number-format.ts ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initNumberFormatSetting": () => (/* binding */ initNumberFormatSetting)
/* harmony export */ });
const axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
const initNumberFormatSetting = (column) => {
    column.getElement().querySelectorAll('.ac-column-setting--number_format').forEach(setting => {
        new NumberFormat(column, setting);
    });
};
class NumberFormat {
    constructor(column, setting) {
        this.column = column;
        this.setting = setting;
        this.bindEvents();
    }
    bindEvents() {
        this.refreshPreview();
        this.setting.querySelectorAll('input').forEach(el => {
            el.addEventListener('change', () => this.refreshPreview());
        });
    }
    refreshPreview() {
        this.getExampleRequest().then((response) => {
            this.setting.querySelectorAll('[data-preview]').forEach(el => el.textContent = response.data.data);
        });
    }
    getValue() {
        let decimals = this.setting.querySelector('.ac-setting-input_number_decimals');
        let decimal_point = this.setting.querySelector('.ac-setting-input_number_decimal_point');
        let thousands_point = this.setting.querySelector('.ac-setting-input_number_thousands_separator');
        return {
            decimals: decimals ? decimals.value : '',
            decimal_point: decimal_point ? decimal_point.value : '',
            thousands_point: thousands_point ? thousands_point.value : '',
        };
    }
    getExampleRequest() {
        const value = this.getValue();
        let data = new FormData();
        data.set('action', 'ac_number_format');
        data.set('number', '7500');
        data.set('decimals', value.decimals);
        data.set('decimal_point', value.decimal_point);
        data.set('thousands_sep', value.thousands_point);
        return axios.post(ajaxurl, data, {});
    }
}


/***/ }),

/***/ "./js/admin/columns/settings/pro.ts":
/*!******************************************!*\
  !*** ./js/admin/columns/settings/pro.ts ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initProSetting": () => (/* binding */ initProSetting)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
// @ts-ignore

const initProSetting = (column) => {
    column.getElement().querySelectorAll('.ac-column-setting--pro').forEach(setting => {
        setting.querySelectorAll('input').forEach(input => {
            input.addEventListener('click', () => jquery__WEBPACK_IMPORTED_MODULE_0___default()(setting).find('[data-ac-modal]').trigger('click'));
        });
    });
};


/***/ }),

/***/ "./js/admin/columns/settings/sub-setting-toggle.ts":
/*!*********************************************************!*\
  !*** ./js/admin/columns/settings/sub-setting-toggle.ts ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initSubSettings": () => (/* binding */ initSubSettings)
/* harmony export */ });
const initSubSettings = (column) => {
    column.getElement().querySelectorAll('.ac-column-setting--filter,.ac-column-setting--sort,.ac-column-setting--edit').forEach(setting => {
        new SubsettingSetting(setting);
    });
};
class SubsettingSetting {
    constructor(element) {
        var _a;
        this.element = element;
        this.input = element.querySelector('.ac-setting-input input[type="checkbox"]');
        this.subFields = element.querySelectorAll('.ac-column-setting');
        this.initState();
        (_a = this.input) === null || _a === void 0 ? void 0 : _a.addEventListener('input', () => this.initState());
    }
    initState() {
        this.isOptionEnabled()
            ? this.subFields.forEach(el => el.style.display = 'table')
            : this.subFields.forEach(el => el.style.display = 'none');
    }
    isOptionEnabled() {
        var _a, _b;
        return (_b = (_a = this.input) === null || _a === void 0 ? void 0 : _a.checked) !== null && _b !== void 0 ? _b : false;
    }
}


/***/ }),

/***/ "./js/admin/columns/settings/type.ts":
/*!*******************************************!*\
  !*** ./js/admin/columns/settings/type.ts ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initColumnTypeSelectorSetting": () => (/* binding */ initColumnTypeSelectorSetting)
/* harmony export */ });
/* harmony import */ var _select2_excludegroup_matcher__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../select2/excludegroup.matcher */ "./js/select2/excludegroup.matcher.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _helpers_admin_columns__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../helpers/admin-columns */ "./js/helpers/admin-columns.ts");

// @ts-ignore


const initColumnTypeSelectorSetting = (column) => {
    column.getElement().querySelectorAll('[data-setting="type"]').forEach(setting => {
        new TypeSelector(column, setting);
    });
};
class TypeSelector {
    constructor(column, setting) {
        this.column = column;
        this.setting = setting;
        this.bindEvents();
    }
    bindEvents() {
        const select = this.setting.querySelector('.ac-setting-input_type');
        const setting = this.setting;
        const column = this.column;
        if (select) {
            select.removeAttribute('data-select2-id');
            this.setting.querySelectorAll('.select2').forEach(el => {
                el.remove();
            });
            jquery__WEBPACK_IMPORTED_MODULE_1___default()(select).ac_select2({
                theme: 'acs2',
                width: '100%',
                dropdownCssClass: '-type-selector',
                escapeMarkup: function (text) {
                    return text;
                },
                templateResult: function (result) {
                    let text = result.text;
                    if (result.hasOwnProperty('id') && result.id.includes('placeholder-')) {
                        text += `<span style="background-color:#FE3D6C; color:#fff; font-size: 10px; margin-top: -1px; padding: 1px 5px; border-radius: 2px; text-transform: uppercase;float: right; margin-right 10px;">PRO</span>`;
                    }
                    return (0,_helpers_admin_columns__WEBPACK_IMPORTED_MODULE_2__.initAcServices)().filters.applyFilters('column_type_templates', text, { setting: setting, column: column, result: result });
                },
                matcher: _select2_excludegroup_matcher__WEBPACK_IMPORTED_MODULE_0__["default"]
            });
        }
    }
}


/***/ }),

/***/ "./js/admin/columns/settings/width.ts":
/*!********************************************!*\
  !*** ./js/admin/columns/settings/width.ts ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initWidthSetting": () => (/* binding */ initWidthSetting)
/* harmony export */ });
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(nanobus__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_1__);

// @ts-ignore

const initWidthSetting = (column) => {
    column.getElement().querySelectorAll('table[data-setting="width"]').forEach(setting => new WidthSetting(column, setting));
};
class WidthSetting {
    constructor(column, setting) {
        this.column = column;
        this.setting = setting;
        this.events = new (nanobus__WEBPACK_IMPORTED_MODULE_0___default())();
        this.indicator = new WidthIndicator(column.getElement().querySelector('.ac-column-heading-setting--width'));
        this.widthInput = this.setting.querySelector('[data-width-input]');
        this.unitInput = this.setting.querySelectorAll('[data-unit-input] input');
        this.init();
    }
    getWidth() {
        var _a, _b;
        let widthValue = (_b = (_a = this.widthInput) === null || _a === void 0 ? void 0 : _a.value) !== null && _b !== void 0 ? _b : 0;
        return +widthValue;
    }
    setWidth(width) {
        if (this.widthInput) {
            this.widthInput.value = width ? width.toString() : '';
        }
        this.updateIndicator();
    }
    updateUnit() {
        this.setting.querySelector('.description .unit').innerHTML = this.getUnit();
    }
    getUnit() {
        var _a, _b;
        return (_b = (_a = this.setting.querySelector('[data-unit-input] input:checked')) === null || _a === void 0 ? void 0 : _a.value) !== null && _b !== void 0 ? _b : '0';
    }
    getValue() {
        return {
            width: this.getWidth(),
            unit: this.getUnit()
        };
    }
    validate() {
        let width = this.getWidth();
        if (width === 0 || width < 0) {
            this.setWidth('');
        }
        if (this.getUnit() === '%') {
            if (width > 100) {
                this.setWidth(100);
            }
        }
    }
    init() {
        var _a;
        (_a = this.widthInput) === null || _a === void 0 ? void 0 : _a.addEventListener('keyup', () => {
            this.updateIndicator();
            this.initSlider();
            this.validate();
        });
        this.unitInput.forEach(el => {
            el.addEventListener('change', () => {
                this.initSlider();
                this.updateIndicator();
                this.updateUnit();
                this.validate();
            });
        });
        this.initSlider();
    }
    updateIndicator() {
        this.indicator.setValue(this.getWidth(), this.getUnit());
    }
    initSlider() {
        this.column.getElement().querySelectorAll('.width-slider').forEach(el => {
            jquery__WEBPACK_IMPORTED_MODULE_1___default()(el).slider({
                range: 'min',
                min: 0,
                max: '%' === this.getUnit() ? 100 : 500,
                value: this.getWidth(),
                slide: (event, ui) => {
                    this.setWidth(ui.value);
                }
            });
        });
    }
}
class WidthIndicator {
    constructor(element) {
        this.element = element;
        this.events = new (nanobus__WEBPACK_IMPORTED_MODULE_0___default())();
    }
    setValue(width, unit) {
        if (width === null) {
            return this.element.innerText = '';
        }
        this.element.innerText = `${width.toString()}${unit}`;
    }
}


/***/ }),

/***/ "./js/constants.ts":
/*!*************************!*\
  !*** ./js/constants.ts ***!
  \*************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "EventConstants": () => (/* binding */ EventConstants)
/* harmony export */ });
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
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initAcServices": () => (/* binding */ initAcServices)
/* harmony export */ });
/* harmony import */ var _modules_ac_services__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modules/ac-services */ "./js/modules/ac-services.ts");

const initAcServices = () => {
    if (!window.AC_SERVICES) {
        window.AC_SERVICES = new _modules_ac_services__WEBPACK_IMPORTED_MODULE_0__["default"]();
    }
    return window.AC_SERVICES;
};


/***/ }),

/***/ "./js/helpers/animations.ts":
/*!**********************************!*\
  !*** ./js/helpers/animations.ts ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "fadeIn": () => (/* binding */ fadeIn),
/* harmony export */   "fadeOut": () => (/* binding */ fadeOut),
/* harmony export */   "scrollToElement": () => (/* binding */ scrollToElement)
/* harmony export */ });
const fadeIn = (element, ms = 100, cb = null, display = 'block') => {
    element.style.display = display;
    element.style.transition = `opacity ${ms}ms`;
    element.style.opacity = '0';
    setTimeout(() => {
        element.style.opacity = '1';
    }, 100);
    if (cb) {
        element.addEventListener('transitionend', () => {
            cb.call(undefined);
        }, { once: true });
    }
};
const fadeOut = (element, ms = 100, cb = null, display = 'none') => {
    element.style.transition = `opacity ${ms}ms`;
    element.style.opacity = '1';
    setTimeout(() => {
        element.style.opacity = '0';
    }, 100);
    element.addEventListener('transitionend', () => {
        element.style.display = display;
        if (cb) {
            cb.call(undefined);
        }
    }, { once: true });
};
const scrollToElement = (element, ms, options = {}) => {
    var _a;
    let defaults = {
        offset: 0
    };
    let settings = Object.assign({}, defaults, options);
    const elementY = element.offsetTop + ((_a = settings.offset) !== null && _a !== void 0 ? _a : 0);
    const startingY = window.pageYOffset;
    const diff = elementY - startingY;
    let start;
    // Bootstrap our animation - it will get called right before next frame shall be rendered.
    window.requestAnimationFrame(function step(timestamp) {
        if (!start) {
            start = timestamp;
        }
        let time = timestamp - start;
        let percent = Math.min(time / ms, 1);
        window.scrollTo(0, startingY + diff * percent);
        if (time < ms) {
            window.requestAnimationFrame(step);
        }
    });
};


/***/ }),

/***/ "./js/helpers/elements.ts":
/*!********************************!*\
  !*** ./js/helpers/elements.ts ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "insertAfter": () => (/* binding */ insertAfter),
/* harmony export */   "insertBefore": () => (/* binding */ insertBefore),
/* harmony export */   "createElementFromString": () => (/* binding */ createElementFromString)
/* harmony export */ });
/* harmony import */ var _html_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./html-element */ "./js/helpers/html-element.ts");

const insertAfter = (newNode, referenceNode) => {
    var _a;
    (_a = referenceNode === null || referenceNode === void 0 ? void 0 : referenceNode.parentNode) === null || _a === void 0 ? void 0 : _a.insertBefore(newNode, referenceNode.nextSibling);
};
const insertBefore = (newNode, referenceNode) => {
    var _a;
    (_a = referenceNode === null || referenceNode === void 0 ? void 0 : referenceNode.parentNode) === null || _a === void 0 ? void 0 : _a.insertBefore(newNode, referenceNode);
};
const createElementFromString = (content, baseElement = 'div') => {
    return _html_element__WEBPACK_IMPORTED_MODULE_0__["default"].create(baseElement).addHtml(content).getElement();
};


/***/ }),

/***/ "./js/helpers/events.ts":
/*!******************************!*\
  !*** ./js/helpers/events.ts ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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

/***/ "./js/helpers/global.ts":
/*!******************************!*\
  !*** ./js/helpers/global.ts ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getParamFromUrl": () => (/* binding */ getParamFromUrl),
/* harmony export */   "mapDataToFormData": () => (/* binding */ mapDataToFormData),
/* harmony export */   "appendObjectToFormData": () => (/* binding */ appendObjectToFormData)
/* harmony export */ });
const getParamFromUrl = (param, url) => {
    if (!url.includes('?')) {
        return null;
    }
    const params = new URLSearchParams(url.split('?')[1]);
    return params.get(param);
};
const mapDataToFormData = (data, formData = null) => {
    let fData = formData !== null && formData !== void 0 ? formData : new FormData();
    Object.keys(data).forEach(key => {
        appendObjectToFormData(fData, data[key], key);
    });
    return fData;
};
const appendObjectToFormData = (formData, data, parentKey = '') => {
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
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ AcHtmlElement)
/* harmony export */ });
class AcHtmlElement {
    constructor(element) {
        this.element = element;
    }
    static find(selector) {
        let element = document.querySelector(selector);
        return element === null ? null : new AcHtmlElement(element);
    }
    static create(el) {
        return new AcHtmlElement(document.createElement(el));
    }
    getElement() {
        return this.element;
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
    setAttribute(name, value) {
        this.element.setAttribute(name, value);
        return this;
    }
    setAttributes(attributes) {
        Object.keys(attributes).forEach(k => this.setAttribute(k, attributes[k]));
        return this;
    }
    addHtml(html) {
        this.element.innerHTML = html;
        return this;
    }
    append(element) {
        this.element.appendChild(element);
        return this;
    }
    appendSelfTo(element) {
        element.append(this.element);
        return this;
    }
    css(property, value) {
        this.element.style[property] = value;
        return this;
    }
    insertAfter(insertedElement) {
        var _a;
        try {
            (_a = this.element.parentElement) === null || _a === void 0 ? void 0 : _a.insertBefore(insertedElement, this.element.nextElementSibling);
        }
        catch (e) {
            console.error("Not able to insert element after current node", this.element);
        }
    }
    insertSelfBefore(referenceNode) {
        var _a;
        try {
            (_a = referenceNode.parentElement) === null || _a === void 0 ? void 0 : _a.insertBefore(this.element, referenceNode);
        }
        catch (e) {
            console.error("Not able to insert element before current node", this.element);
        }
        return this;
    }
    insertBefore(insertedElement) {
        var _a;
        try {
            (_a = this.element.parentElement) === null || _a === void 0 ? void 0 : _a.insertBefore(insertedElement, this.element);
        }
        catch (e) {
            console.error("Not able to insert element before current node", this.element);
        }
        return this;
    }
    addEventListener(event, listener) {
        this.element.addEventListener(event, listener);
        return this;
    }
    addEventListeners(events, listener) {
        events.forEach(e => this.addEventListener(e, listener));
        return this;
    }
}


/***/ }),

/***/ "./js/helpers/string.ts":
/*!******************************!*\
  !*** ./js/helpers/string.ts ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "uniqid": () => (/* binding */ uniqid),
/* harmony export */   "stripHtml": () => (/* binding */ stripHtml)
/* harmony export */ });
const uniqid = (prefix = "", moreEntropy = false) => {
    const c = Date.now() / 1000;
    let d = c.toString(16).split(".").join("");
    while (d.length < 14)
        d += "0";
    let e = "";
    if (moreEntropy) {
        e = ".";
        e += Math.round(Math.random() * 100000000);
    }
    return prefix + d + e;
};
const stripHtml = (originalString) => {
    return originalString ? originalString.replace(/(<([^>]+)>)/gi, "") : '';
};


/***/ }),

/***/ "./js/modules/ac-hookable-filters.ts":
/*!*******************************************!*\
  !*** ./js/modules/ac-hookable-filters.ts ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ AcHookableFilters)
/* harmony export */ });
class AcHookableFilters {
    constructor() {
        this.filters = {};
    }
    addFilter(name, callback, priority = 10) {
        if (!this.filters.hasOwnProperty(name)) {
            this.filters[name] = {};
        }
        if (!this.filters[name].hasOwnProperty(priority)) {
            this.filters[name][priority] = [];
        }
        this.filters[name][priority].push(callback);
    }
    applyFilters(name, value, payload = {}) {
        if (!this.filters.hasOwnProperty(name)) {
            return value;
        }
        Object.keys(this.filters[name]).forEach((priority) => {
            this.filters[name][parseInt(priority)].forEach(cb => {
                value = cb(value, payload);
            });
        });
        return value;
    }
}


/***/ }),

/***/ "./js/modules/ac-pointer.ts":
/*!**********************************!*\
  !*** ./js/modules/ac-pointer.ts ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Pointer": () => (/* binding */ Pointer),
/* harmony export */   "initPointers": () => (/* binding */ initPointers)
/* harmony export */ });
/* harmony import */ var _tooltips__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tooltips */ "./js/modules/tooltips.ts");
/* harmony import */ var _components_PointerModal_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/PointerModal.svelte */ "./js/components/PointerModal.svelte");


class Pointer {
    constructor(element, target) {
        var _a, _b;
        this.element = element;
        this.target = target;
        this.width = (_a = element.dataset.width) !== null && _a !== void 0 ? _a : null;
        this.position = (_b = element.dataset.pos) !== null && _b !== void 0 ? _b : 'right';
        this.initEvents();
    }
    initEvents() {
        if (this.element.dataset.acTooltipInit === '1') {
            return;
        }
        this.container = createPointerElement();
        if (this.width) {
            this.container.style.width = `${this.width}px`;
        }
        this.attachModel();
        this.element.dataset.acTooltipInit = '1';
        this.element.addEventListener('mouseenter', () => {
            if (this.onScreen) {
                return;
            }
            this.onScreen = true;
            document.body.appendChild(this.container);
            this.container.style.width = `${this.width}px`;
            this.setPosition();
            setTimeout(() => {
                this.setPosition();
            }, 100);
        });
        this.element.addEventListener('mouseleave', () => {
            this.checkClose();
        });
    }
    setPosition() {
        const bodyOffset = document.body.getBoundingClientRect();
        const viewportOffset = this.element.getBoundingClientRect();
        this.container.style.top = ((viewportOffset.top - bodyOffset.top) + this.element.offsetHeight - (this.container.offsetHeight / 2) + 20) + 'px';
        switch (this.position) {
            case 'right_bottom':
                this.container.style.top = ((viewportOffset.top - bodyOffset.top) - this.container.offsetHeight + 100) + 'px';
                this.container.style.left = ((viewportOffset.left - bodyOffset.left) + this.element.offsetWidth + 14) + 'px';
                break;
            case 'left':
                this.container.style.left = ((viewportOffset.left - bodyOffset.left) - this.container.offsetWidth - 12) + 'px';
                break;
            default:
                this.container.style.left = ((viewportOffset.left - bodyOffset.left) + this.element.offsetWidth + 10) + 'px';
        }
    }
    checkClose() {
        setTimeout(() => {
            if (!this.component.isOnElement()) {
                this.closeHandler();
            }
        }, 50);
    }
    closeHandler() {
        if (this.onScreen) {
            document.body.removeChild(this.container);
            this.onScreen = false;
        }
    }
    destroyComponent() {
        if (this.component !== null) {
            this.component.$destroy();
        }
    }
    attachModel() {
        this.component = new _components_PointerModal_svelte__WEBPACK_IMPORTED_MODULE_1__["default"]({
            target: this.container,
            props: {
                content: this.target.innerHTML,
                position: this.position,
                closeHandler: () => this.checkClose(),
                destroyHandler: () => this.destroyComponent()
            }
        });
    }
}
const createPointerElement = () => {
    const element = document.createElement('div');
    element.classList.add('ac-pointer-modal-container');
    element.style.position = 'absolute';
    return element;
};
const initPointers = (elements = null) => {
    if (!elements) {
        elements = document.querySelectorAll('.ac-pointer');
    }
    elements.forEach(element => {
        var _a;
        let relElement = document.querySelector((_a = `#${element.getAttribute('rel')}`) !== null && _a !== void 0 ? _a : '#n');
        if (relElement) {
            new Pointer(element, relElement);
        }
    });
    new _tooltips__WEBPACK_IMPORTED_MODULE_0__["default"]();
};


/***/ }),

/***/ "./js/modules/ac-services.ts":
/*!***********************************!*\
  !*** ./js/modules/ac-services.ts ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ AcServices)
/* harmony export */ });
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(nanobus__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _helpers_html_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers/html-element */ "./js/helpers/html-element.ts");
/* harmony import */ var _ac_hookable_filters__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ac-hookable-filters */ "./js/modules/ac-hookable-filters.ts");



class AcServices {
    constructor() {
        this.services = {};
        this.events = new (nanobus__WEBPACK_IMPORTED_MODULE_0___default())();
        this.filters = new _ac_hookable_filters__WEBPACK_IMPORTED_MODULE_2__["default"]();
        this.$ = _helpers_html_element__WEBPACK_IMPORTED_MODULE_1__["default"];
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
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Modal)
/* harmony export */ });
class Modal {
    constructor(el) {
        var _a;
        this.el = el;
        this.dialog = (_a = el.querySelector('.ac-modal__dialogs')) !== null && _a !== void 0 ? _a : document.createElement('div');
        this.initEvents();
    }
    getElement() {
        return this.el;
    }
    initEvents() {
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
                    this.close();
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
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Modals)
/* harmony export */ });
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
        var _a;
        (_a = this.get(key)) === null || _a === void 0 ? void 0 : _a.open();
    }
    close(key) {
        var _a;
        (_a = this.get(key)) === null || _a === void 0 ? void 0 : _a.close();
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

/***/ "./js/modules/tooltips.ts":
/*!********************************!*\
  !*** ./js/modules/tooltips.ts ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../plugin/tooltip */ "./js/plugin/tooltip.ts");

class Tooltips {
    constructor() {
        this.init();
    }
    init() {
        (0,_plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__.initAcTooltips)();
    }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Tooltips);


/***/ }),

/***/ "./js/plugin/tooltip.ts":
/*!******************************!*\
  !*** ./js/plugin/tooltip.ts ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initAcTooltips": () => (/* binding */ initAcTooltips),
/* harmony export */   "Tooltip": () => (/* binding */ Tooltip)
/* harmony export */ });
const initAcTooltips = () => {
    document.querySelectorAll('.ac-tooltip').forEach(el => el.remove());
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
        this.element.addEventListener('mouseenter', () => {
            const bodyOffset = document.body.getBoundingClientRect();
            const viewportOffset = this.element.getBoundingClientRect();
            document.body.appendChild(this.tip);
            this.tip.style.left = ((viewportOffset.left - bodyOffset.left) + this.element.offsetWidth / 2) + 'px';
            this.tip.style.top = ((viewportOffset.top - bodyOffset.top) + this.element.offsetHeight) + 'px';
            this.tip.classList.add('hover');
        });
        this.element.addEventListener('mouseleave', () => {
            this.tip.classList.remove('hover');
            document.body.removeChild(this.tip);
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

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ ((module) => {

"use strict";
module.exports = jQuery;

/***/ }),

/***/ "./node_modules/svelte/internal/index.mjs":
/*!************************************************!*\
  !*** ./node_modules/svelte/internal/index.mjs ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "HtmlTag": () => (/* binding */ HtmlTag),
/* harmony export */   "HtmlTagHydration": () => (/* binding */ HtmlTagHydration),
/* harmony export */   "SvelteComponent": () => (/* binding */ SvelteComponent),
/* harmony export */   "SvelteComponentDev": () => (/* binding */ SvelteComponentDev),
/* harmony export */   "SvelteComponentTyped": () => (/* binding */ SvelteComponentTyped),
/* harmony export */   "SvelteElement": () => (/* binding */ SvelteElement),
/* harmony export */   "action_destroyer": () => (/* binding */ action_destroyer),
/* harmony export */   "add_attribute": () => (/* binding */ add_attribute),
/* harmony export */   "add_classes": () => (/* binding */ add_classes),
/* harmony export */   "add_flush_callback": () => (/* binding */ add_flush_callback),
/* harmony export */   "add_location": () => (/* binding */ add_location),
/* harmony export */   "add_render_callback": () => (/* binding */ add_render_callback),
/* harmony export */   "add_resize_listener": () => (/* binding */ add_resize_listener),
/* harmony export */   "add_transform": () => (/* binding */ add_transform),
/* harmony export */   "afterUpdate": () => (/* binding */ afterUpdate),
/* harmony export */   "append": () => (/* binding */ append),
/* harmony export */   "append_dev": () => (/* binding */ append_dev),
/* harmony export */   "append_empty_stylesheet": () => (/* binding */ append_empty_stylesheet),
/* harmony export */   "append_hydration": () => (/* binding */ append_hydration),
/* harmony export */   "append_hydration_dev": () => (/* binding */ append_hydration_dev),
/* harmony export */   "append_styles": () => (/* binding */ append_styles),
/* harmony export */   "assign": () => (/* binding */ assign),
/* harmony export */   "attr": () => (/* binding */ attr),
/* harmony export */   "attr_dev": () => (/* binding */ attr_dev),
/* harmony export */   "attribute_to_object": () => (/* binding */ attribute_to_object),
/* harmony export */   "beforeUpdate": () => (/* binding */ beforeUpdate),
/* harmony export */   "bind": () => (/* binding */ bind),
/* harmony export */   "binding_callbacks": () => (/* binding */ binding_callbacks),
/* harmony export */   "blank_object": () => (/* binding */ blank_object),
/* harmony export */   "bubble": () => (/* binding */ bubble),
/* harmony export */   "check_outros": () => (/* binding */ check_outros),
/* harmony export */   "children": () => (/* binding */ children),
/* harmony export */   "claim_component": () => (/* binding */ claim_component),
/* harmony export */   "claim_element": () => (/* binding */ claim_element),
/* harmony export */   "claim_html_tag": () => (/* binding */ claim_html_tag),
/* harmony export */   "claim_space": () => (/* binding */ claim_space),
/* harmony export */   "claim_svg_element": () => (/* binding */ claim_svg_element),
/* harmony export */   "claim_text": () => (/* binding */ claim_text),
/* harmony export */   "clear_loops": () => (/* binding */ clear_loops),
/* harmony export */   "component_subscribe": () => (/* binding */ component_subscribe),
/* harmony export */   "compute_rest_props": () => (/* binding */ compute_rest_props),
/* harmony export */   "compute_slots": () => (/* binding */ compute_slots),
/* harmony export */   "createEventDispatcher": () => (/* binding */ createEventDispatcher),
/* harmony export */   "create_animation": () => (/* binding */ create_animation),
/* harmony export */   "create_bidirectional_transition": () => (/* binding */ create_bidirectional_transition),
/* harmony export */   "create_component": () => (/* binding */ create_component),
/* harmony export */   "create_in_transition": () => (/* binding */ create_in_transition),
/* harmony export */   "create_out_transition": () => (/* binding */ create_out_transition),
/* harmony export */   "create_slot": () => (/* binding */ create_slot),
/* harmony export */   "create_ssr_component": () => (/* binding */ create_ssr_component),
/* harmony export */   "current_component": () => (/* binding */ current_component),
/* harmony export */   "custom_event": () => (/* binding */ custom_event),
/* harmony export */   "dataset_dev": () => (/* binding */ dataset_dev),
/* harmony export */   "debug": () => (/* binding */ debug),
/* harmony export */   "destroy_block": () => (/* binding */ destroy_block),
/* harmony export */   "destroy_component": () => (/* binding */ destroy_component),
/* harmony export */   "destroy_each": () => (/* binding */ destroy_each),
/* harmony export */   "detach": () => (/* binding */ detach),
/* harmony export */   "detach_after_dev": () => (/* binding */ detach_after_dev),
/* harmony export */   "detach_before_dev": () => (/* binding */ detach_before_dev),
/* harmony export */   "detach_between_dev": () => (/* binding */ detach_between_dev),
/* harmony export */   "detach_dev": () => (/* binding */ detach_dev),
/* harmony export */   "dirty_components": () => (/* binding */ dirty_components),
/* harmony export */   "dispatch_dev": () => (/* binding */ dispatch_dev),
/* harmony export */   "each": () => (/* binding */ each),
/* harmony export */   "element": () => (/* binding */ element),
/* harmony export */   "element_is": () => (/* binding */ element_is),
/* harmony export */   "empty": () => (/* binding */ empty),
/* harmony export */   "end_hydrating": () => (/* binding */ end_hydrating),
/* harmony export */   "escape": () => (/* binding */ escape),
/* harmony export */   "escape_attribute_value": () => (/* binding */ escape_attribute_value),
/* harmony export */   "escape_object": () => (/* binding */ escape_object),
/* harmony export */   "escaped": () => (/* binding */ escaped),
/* harmony export */   "exclude_internal_props": () => (/* binding */ exclude_internal_props),
/* harmony export */   "fix_and_destroy_block": () => (/* binding */ fix_and_destroy_block),
/* harmony export */   "fix_and_outro_and_destroy_block": () => (/* binding */ fix_and_outro_and_destroy_block),
/* harmony export */   "fix_position": () => (/* binding */ fix_position),
/* harmony export */   "flush": () => (/* binding */ flush),
/* harmony export */   "getAllContexts": () => (/* binding */ getAllContexts),
/* harmony export */   "getContext": () => (/* binding */ getContext),
/* harmony export */   "get_all_dirty_from_scope": () => (/* binding */ get_all_dirty_from_scope),
/* harmony export */   "get_binding_group_value": () => (/* binding */ get_binding_group_value),
/* harmony export */   "get_current_component": () => (/* binding */ get_current_component),
/* harmony export */   "get_custom_elements_slots": () => (/* binding */ get_custom_elements_slots),
/* harmony export */   "get_root_for_style": () => (/* binding */ get_root_for_style),
/* harmony export */   "get_slot_changes": () => (/* binding */ get_slot_changes),
/* harmony export */   "get_spread_object": () => (/* binding */ get_spread_object),
/* harmony export */   "get_spread_update": () => (/* binding */ get_spread_update),
/* harmony export */   "get_store_value": () => (/* binding */ get_store_value),
/* harmony export */   "globals": () => (/* binding */ globals),
/* harmony export */   "group_outros": () => (/* binding */ group_outros),
/* harmony export */   "handle_promise": () => (/* binding */ handle_promise),
/* harmony export */   "hasContext": () => (/* binding */ hasContext),
/* harmony export */   "has_prop": () => (/* binding */ has_prop),
/* harmony export */   "identity": () => (/* binding */ identity),
/* harmony export */   "init": () => (/* binding */ init),
/* harmony export */   "insert": () => (/* binding */ insert),
/* harmony export */   "insert_dev": () => (/* binding */ insert_dev),
/* harmony export */   "insert_hydration": () => (/* binding */ insert_hydration),
/* harmony export */   "insert_hydration_dev": () => (/* binding */ insert_hydration_dev),
/* harmony export */   "intros": () => (/* binding */ intros),
/* harmony export */   "invalid_attribute_name_character": () => (/* binding */ invalid_attribute_name_character),
/* harmony export */   "is_client": () => (/* binding */ is_client),
/* harmony export */   "is_crossorigin": () => (/* binding */ is_crossorigin),
/* harmony export */   "is_empty": () => (/* binding */ is_empty),
/* harmony export */   "is_function": () => (/* binding */ is_function),
/* harmony export */   "is_promise": () => (/* binding */ is_promise),
/* harmony export */   "listen": () => (/* binding */ listen),
/* harmony export */   "listen_dev": () => (/* binding */ listen_dev),
/* harmony export */   "loop": () => (/* binding */ loop),
/* harmony export */   "loop_guard": () => (/* binding */ loop_guard),
/* harmony export */   "missing_component": () => (/* binding */ missing_component),
/* harmony export */   "mount_component": () => (/* binding */ mount_component),
/* harmony export */   "noop": () => (/* binding */ noop),
/* harmony export */   "not_equal": () => (/* binding */ not_equal),
/* harmony export */   "now": () => (/* binding */ now),
/* harmony export */   "null_to_empty": () => (/* binding */ null_to_empty),
/* harmony export */   "object_without_properties": () => (/* binding */ object_without_properties),
/* harmony export */   "onDestroy": () => (/* binding */ onDestroy),
/* harmony export */   "onMount": () => (/* binding */ onMount),
/* harmony export */   "once": () => (/* binding */ once),
/* harmony export */   "outro_and_destroy_block": () => (/* binding */ outro_and_destroy_block),
/* harmony export */   "prevent_default": () => (/* binding */ prevent_default),
/* harmony export */   "prop_dev": () => (/* binding */ prop_dev),
/* harmony export */   "query_selector_all": () => (/* binding */ query_selector_all),
/* harmony export */   "raf": () => (/* binding */ raf),
/* harmony export */   "run": () => (/* binding */ run),
/* harmony export */   "run_all": () => (/* binding */ run_all),
/* harmony export */   "safe_not_equal": () => (/* binding */ safe_not_equal),
/* harmony export */   "schedule_update": () => (/* binding */ schedule_update),
/* harmony export */   "select_multiple_value": () => (/* binding */ select_multiple_value),
/* harmony export */   "select_option": () => (/* binding */ select_option),
/* harmony export */   "select_options": () => (/* binding */ select_options),
/* harmony export */   "select_value": () => (/* binding */ select_value),
/* harmony export */   "self": () => (/* binding */ self),
/* harmony export */   "setContext": () => (/* binding */ setContext),
/* harmony export */   "set_attributes": () => (/* binding */ set_attributes),
/* harmony export */   "set_current_component": () => (/* binding */ set_current_component),
/* harmony export */   "set_custom_element_data": () => (/* binding */ set_custom_element_data),
/* harmony export */   "set_data": () => (/* binding */ set_data),
/* harmony export */   "set_data_dev": () => (/* binding */ set_data_dev),
/* harmony export */   "set_input_type": () => (/* binding */ set_input_type),
/* harmony export */   "set_input_value": () => (/* binding */ set_input_value),
/* harmony export */   "set_now": () => (/* binding */ set_now),
/* harmony export */   "set_raf": () => (/* binding */ set_raf),
/* harmony export */   "set_store_value": () => (/* binding */ set_store_value),
/* harmony export */   "set_style": () => (/* binding */ set_style),
/* harmony export */   "set_svg_attributes": () => (/* binding */ set_svg_attributes),
/* harmony export */   "space": () => (/* binding */ space),
/* harmony export */   "spread": () => (/* binding */ spread),
/* harmony export */   "src_url_equal": () => (/* binding */ src_url_equal),
/* harmony export */   "start_hydrating": () => (/* binding */ start_hydrating),
/* harmony export */   "stop_propagation": () => (/* binding */ stop_propagation),
/* harmony export */   "subscribe": () => (/* binding */ subscribe),
/* harmony export */   "svg_element": () => (/* binding */ svg_element),
/* harmony export */   "text": () => (/* binding */ text),
/* harmony export */   "tick": () => (/* binding */ tick),
/* harmony export */   "time_ranges_to_array": () => (/* binding */ time_ranges_to_array),
/* harmony export */   "to_number": () => (/* binding */ to_number),
/* harmony export */   "toggle_class": () => (/* binding */ toggle_class),
/* harmony export */   "transition_in": () => (/* binding */ transition_in),
/* harmony export */   "transition_out": () => (/* binding */ transition_out),
/* harmony export */   "trusted": () => (/* binding */ trusted),
/* harmony export */   "update_await_block_branch": () => (/* binding */ update_await_block_branch),
/* harmony export */   "update_keyed_each": () => (/* binding */ update_keyed_each),
/* harmony export */   "update_slot": () => (/* binding */ update_slot),
/* harmony export */   "update_slot_base": () => (/* binding */ update_slot_base),
/* harmony export */   "validate_component": () => (/* binding */ validate_component),
/* harmony export */   "validate_each_argument": () => (/* binding */ validate_each_argument),
/* harmony export */   "validate_each_keys": () => (/* binding */ validate_each_keys),
/* harmony export */   "validate_slots": () => (/* binding */ validate_slots),
/* harmony export */   "validate_store": () => (/* binding */ validate_store),
/* harmony export */   "xlink_attr": () => (/* binding */ xlink_attr)
/* harmony export */ });
function noop() { }
const identity = x => x;
function assign(tar, src) {
    // @ts-ignore
    for (const k in src)
        tar[k] = src[k];
    return tar;
}
function is_promise(value) {
    return value && typeof value === 'object' && typeof value.then === 'function';
}
function add_location(element, file, line, column, char) {
    element.__svelte_meta = {
        loc: { file, line, column, char }
    };
}
function run(fn) {
    return fn();
}
function blank_object() {
    return Object.create(null);
}
function run_all(fns) {
    fns.forEach(run);
}
function is_function(thing) {
    return typeof thing === 'function';
}
function safe_not_equal(a, b) {
    return a != a ? b == b : a !== b || ((a && typeof a === 'object') || typeof a === 'function');
}
let src_url_equal_anchor;
function src_url_equal(element_src, url) {
    if (!src_url_equal_anchor) {
        src_url_equal_anchor = document.createElement('a');
    }
    src_url_equal_anchor.href = url;
    return element_src === src_url_equal_anchor.href;
}
function not_equal(a, b) {
    return a != a ? b == b : a !== b;
}
function is_empty(obj) {
    return Object.keys(obj).length === 0;
}
function validate_store(store, name) {
    if (store != null && typeof store.subscribe !== 'function') {
        throw new Error(`'${name}' is not a store with a 'subscribe' method`);
    }
}
function subscribe(store, ...callbacks) {
    if (store == null) {
        return noop;
    }
    const unsub = store.subscribe(...callbacks);
    return unsub.unsubscribe ? () => unsub.unsubscribe() : unsub;
}
function get_store_value(store) {
    let value;
    subscribe(store, _ => value = _)();
    return value;
}
function component_subscribe(component, store, callback) {
    component.$$.on_destroy.push(subscribe(store, callback));
}
function create_slot(definition, ctx, $$scope, fn) {
    if (definition) {
        const slot_ctx = get_slot_context(definition, ctx, $$scope, fn);
        return definition[0](slot_ctx);
    }
}
function get_slot_context(definition, ctx, $$scope, fn) {
    return definition[1] && fn
        ? assign($$scope.ctx.slice(), definition[1](fn(ctx)))
        : $$scope.ctx;
}
function get_slot_changes(definition, $$scope, dirty, fn) {
    if (definition[2] && fn) {
        const lets = definition[2](fn(dirty));
        if ($$scope.dirty === undefined) {
            return lets;
        }
        if (typeof lets === 'object') {
            const merged = [];
            const len = Math.max($$scope.dirty.length, lets.length);
            for (let i = 0; i < len; i += 1) {
                merged[i] = $$scope.dirty[i] | lets[i];
            }
            return merged;
        }
        return $$scope.dirty | lets;
    }
    return $$scope.dirty;
}
function update_slot_base(slot, slot_definition, ctx, $$scope, slot_changes, get_slot_context_fn) {
    if (slot_changes) {
        const slot_context = get_slot_context(slot_definition, ctx, $$scope, get_slot_context_fn);
        slot.p(slot_context, slot_changes);
    }
}
function update_slot(slot, slot_definition, ctx, $$scope, dirty, get_slot_changes_fn, get_slot_context_fn) {
    const slot_changes = get_slot_changes(slot_definition, $$scope, dirty, get_slot_changes_fn);
    update_slot_base(slot, slot_definition, ctx, $$scope, slot_changes, get_slot_context_fn);
}
function get_all_dirty_from_scope($$scope) {
    if ($$scope.ctx.length > 32) {
        const dirty = [];
        const length = $$scope.ctx.length / 32;
        for (let i = 0; i < length; i++) {
            dirty[i] = -1;
        }
        return dirty;
    }
    return -1;
}
function exclude_internal_props(props) {
    const result = {};
    for (const k in props)
        if (k[0] !== '$')
            result[k] = props[k];
    return result;
}
function compute_rest_props(props, keys) {
    const rest = {};
    keys = new Set(keys);
    for (const k in props)
        if (!keys.has(k) && k[0] !== '$')
            rest[k] = props[k];
    return rest;
}
function compute_slots(slots) {
    const result = {};
    for (const key in slots) {
        result[key] = true;
    }
    return result;
}
function once(fn) {
    let ran = false;
    return function (...args) {
        if (ran)
            return;
        ran = true;
        fn.call(this, ...args);
    };
}
function null_to_empty(value) {
    return value == null ? '' : value;
}
function set_store_value(store, ret, value) {
    store.set(value);
    return ret;
}
const has_prop = (obj, prop) => Object.prototype.hasOwnProperty.call(obj, prop);
function action_destroyer(action_result) {
    return action_result && is_function(action_result.destroy) ? action_result.destroy : noop;
}

const is_client = typeof window !== 'undefined';
let now = is_client
    ? () => window.performance.now()
    : () => Date.now();
let raf = is_client ? cb => requestAnimationFrame(cb) : noop;
// used internally for testing
function set_now(fn) {
    now = fn;
}
function set_raf(fn) {
    raf = fn;
}

const tasks = new Set();
function run_tasks(now) {
    tasks.forEach(task => {
        if (!task.c(now)) {
            tasks.delete(task);
            task.f();
        }
    });
    if (tasks.size !== 0)
        raf(run_tasks);
}
/**
 * For testing purposes only!
 */
function clear_loops() {
    tasks.clear();
}
/**
 * Creates a new task that runs on each raf frame
 * until it returns a falsy value or is aborted
 */
function loop(callback) {
    let task;
    if (tasks.size === 0)
        raf(run_tasks);
    return {
        promise: new Promise(fulfill => {
            tasks.add(task = { c: callback, f: fulfill });
        }),
        abort() {
            tasks.delete(task);
        }
    };
}

// Track which nodes are claimed during hydration. Unclaimed nodes can then be removed from the DOM
// at the end of hydration without touching the remaining nodes.
let is_hydrating = false;
function start_hydrating() {
    is_hydrating = true;
}
function end_hydrating() {
    is_hydrating = false;
}
function upper_bound(low, high, key, value) {
    // Return first index of value larger than input value in the range [low, high)
    while (low < high) {
        const mid = low + ((high - low) >> 1);
        if (key(mid) <= value) {
            low = mid + 1;
        }
        else {
            high = mid;
        }
    }
    return low;
}
function init_hydrate(target) {
    if (target.hydrate_init)
        return;
    target.hydrate_init = true;
    // We know that all children have claim_order values since the unclaimed have been detached if target is not <head>
    let children = target.childNodes;
    // If target is <head>, there may be children without claim_order
    if (target.nodeName === 'HEAD') {
        const myChildren = [];
        for (let i = 0; i < children.length; i++) {
            const node = children[i];
            if (node.claim_order !== undefined) {
                myChildren.push(node);
            }
        }
        children = myChildren;
    }
    /*
    * Reorder claimed children optimally.
    * We can reorder claimed children optimally by finding the longest subsequence of
    * nodes that are already claimed in order and only moving the rest. The longest
    * subsequence subsequence of nodes that are claimed in order can be found by
    * computing the longest increasing subsequence of .claim_order values.
    *
    * This algorithm is optimal in generating the least amount of reorder operations
    * possible.
    *
    * Proof:
    * We know that, given a set of reordering operations, the nodes that do not move
    * always form an increasing subsequence, since they do not move among each other
    * meaning that they must be already ordered among each other. Thus, the maximal
    * set of nodes that do not move form a longest increasing subsequence.
    */
    // Compute longest increasing subsequence
    // m: subsequence length j => index k of smallest value that ends an increasing subsequence of length j
    const m = new Int32Array(children.length + 1);
    // Predecessor indices + 1
    const p = new Int32Array(children.length);
    m[0] = -1;
    let longest = 0;
    for (let i = 0; i < children.length; i++) {
        const current = children[i].claim_order;
        // Find the largest subsequence length such that it ends in a value less than our current value
        // upper_bound returns first greater value, so we subtract one
        // with fast path for when we are on the current longest subsequence
        const seqLen = ((longest > 0 && children[m[longest]].claim_order <= current) ? longest + 1 : upper_bound(1, longest, idx => children[m[idx]].claim_order, current)) - 1;
        p[i] = m[seqLen] + 1;
        const newLen = seqLen + 1;
        // We can guarantee that current is the smallest value. Otherwise, we would have generated a longer sequence.
        m[newLen] = i;
        longest = Math.max(newLen, longest);
    }
    // The longest increasing subsequence of nodes (initially reversed)
    const lis = [];
    // The rest of the nodes, nodes that will be moved
    const toMove = [];
    let last = children.length - 1;
    for (let cur = m[longest] + 1; cur != 0; cur = p[cur - 1]) {
        lis.push(children[cur - 1]);
        for (; last >= cur; last--) {
            toMove.push(children[last]);
        }
        last--;
    }
    for (; last >= 0; last--) {
        toMove.push(children[last]);
    }
    lis.reverse();
    // We sort the nodes being moved to guarantee that their insertion order matches the claim order
    toMove.sort((a, b) => a.claim_order - b.claim_order);
    // Finally, we move the nodes
    for (let i = 0, j = 0; i < toMove.length; i++) {
        while (j < lis.length && toMove[i].claim_order >= lis[j].claim_order) {
            j++;
        }
        const anchor = j < lis.length ? lis[j] : null;
        target.insertBefore(toMove[i], anchor);
    }
}
function append(target, node) {
    target.appendChild(node);
}
function append_styles(target, style_sheet_id, styles) {
    const append_styles_to = get_root_for_style(target);
    if (!append_styles_to.getElementById(style_sheet_id)) {
        const style = element('style');
        style.id = style_sheet_id;
        style.textContent = styles;
        append_stylesheet(append_styles_to, style);
    }
}
function get_root_for_style(node) {
    if (!node)
        return document;
    const root = node.getRootNode ? node.getRootNode() : node.ownerDocument;
    if (root && root.host) {
        return root;
    }
    return node.ownerDocument;
}
function append_empty_stylesheet(node) {
    const style_element = element('style');
    append_stylesheet(get_root_for_style(node), style_element);
    return style_element;
}
function append_stylesheet(node, style) {
    append(node.head || node, style);
}
function append_hydration(target, node) {
    if (is_hydrating) {
        init_hydrate(target);
        if ((target.actual_end_child === undefined) || ((target.actual_end_child !== null) && (target.actual_end_child.parentElement !== target))) {
            target.actual_end_child = target.firstChild;
        }
        // Skip nodes of undefined ordering
        while ((target.actual_end_child !== null) && (target.actual_end_child.claim_order === undefined)) {
            target.actual_end_child = target.actual_end_child.nextSibling;
        }
        if (node !== target.actual_end_child) {
            // We only insert if the ordering of this node should be modified or the parent node is not target
            if (node.claim_order !== undefined || node.parentNode !== target) {
                target.insertBefore(node, target.actual_end_child);
            }
        }
        else {
            target.actual_end_child = node.nextSibling;
        }
    }
    else if (node.parentNode !== target || node.nextSibling !== null) {
        target.appendChild(node);
    }
}
function insert(target, node, anchor) {
    target.insertBefore(node, anchor || null);
}
function insert_hydration(target, node, anchor) {
    if (is_hydrating && !anchor) {
        append_hydration(target, node);
    }
    else if (node.parentNode !== target || node.nextSibling != anchor) {
        target.insertBefore(node, anchor || null);
    }
}
function detach(node) {
    node.parentNode.removeChild(node);
}
function destroy_each(iterations, detaching) {
    for (let i = 0; i < iterations.length; i += 1) {
        if (iterations[i])
            iterations[i].d(detaching);
    }
}
function element(name) {
    return document.createElement(name);
}
function element_is(name, is) {
    return document.createElement(name, { is });
}
function object_without_properties(obj, exclude) {
    const target = {};
    for (const k in obj) {
        if (has_prop(obj, k)
            // @ts-ignore
            && exclude.indexOf(k) === -1) {
            // @ts-ignore
            target[k] = obj[k];
        }
    }
    return target;
}
function svg_element(name) {
    return document.createElementNS('http://www.w3.org/2000/svg', name);
}
function text(data) {
    return document.createTextNode(data);
}
function space() {
    return text(' ');
}
function empty() {
    return text('');
}
function listen(node, event, handler, options) {
    node.addEventListener(event, handler, options);
    return () => node.removeEventListener(event, handler, options);
}
function prevent_default(fn) {
    return function (event) {
        event.preventDefault();
        // @ts-ignore
        return fn.call(this, event);
    };
}
function stop_propagation(fn) {
    return function (event) {
        event.stopPropagation();
        // @ts-ignore
        return fn.call(this, event);
    };
}
function self(fn) {
    return function (event) {
        // @ts-ignore
        if (event.target === this)
            fn.call(this, event);
    };
}
function trusted(fn) {
    return function (event) {
        // @ts-ignore
        if (event.isTrusted)
            fn.call(this, event);
    };
}
function attr(node, attribute, value) {
    if (value == null)
        node.removeAttribute(attribute);
    else if (node.getAttribute(attribute) !== value)
        node.setAttribute(attribute, value);
}
function set_attributes(node, attributes) {
    // @ts-ignore
    const descriptors = Object.getOwnPropertyDescriptors(node.__proto__);
    for (const key in attributes) {
        if (attributes[key] == null) {
            node.removeAttribute(key);
        }
        else if (key === 'style') {
            node.style.cssText = attributes[key];
        }
        else if (key === '__value') {
            node.value = node[key] = attributes[key];
        }
        else if (descriptors[key] && descriptors[key].set) {
            node[key] = attributes[key];
        }
        else {
            attr(node, key, attributes[key]);
        }
    }
}
function set_svg_attributes(node, attributes) {
    for (const key in attributes) {
        attr(node, key, attributes[key]);
    }
}
function set_custom_element_data(node, prop, value) {
    if (prop in node) {
        node[prop] = typeof node[prop] === 'boolean' && value === '' ? true : value;
    }
    else {
        attr(node, prop, value);
    }
}
function xlink_attr(node, attribute, value) {
    node.setAttributeNS('http://www.w3.org/1999/xlink', attribute, value);
}
function get_binding_group_value(group, __value, checked) {
    const value = new Set();
    for (let i = 0; i < group.length; i += 1) {
        if (group[i].checked)
            value.add(group[i].__value);
    }
    if (!checked) {
        value.delete(__value);
    }
    return Array.from(value);
}
function to_number(value) {
    return value === '' ? null : +value;
}
function time_ranges_to_array(ranges) {
    const array = [];
    for (let i = 0; i < ranges.length; i += 1) {
        array.push({ start: ranges.start(i), end: ranges.end(i) });
    }
    return array;
}
function children(element) {
    return Array.from(element.childNodes);
}
function init_claim_info(nodes) {
    if (nodes.claim_info === undefined) {
        nodes.claim_info = { last_index: 0, total_claimed: 0 };
    }
}
function claim_node(nodes, predicate, processNode, createNode, dontUpdateLastIndex = false) {
    // Try to find nodes in an order such that we lengthen the longest increasing subsequence
    init_claim_info(nodes);
    const resultNode = (() => {
        // We first try to find an element after the previous one
        for (let i = nodes.claim_info.last_index; i < nodes.length; i++) {
            const node = nodes[i];
            if (predicate(node)) {
                const replacement = processNode(node);
                if (replacement === undefined) {
                    nodes.splice(i, 1);
                }
                else {
                    nodes[i] = replacement;
                }
                if (!dontUpdateLastIndex) {
                    nodes.claim_info.last_index = i;
                }
                return node;
            }
        }
        // Otherwise, we try to find one before
        // We iterate in reverse so that we don't go too far back
        for (let i = nodes.claim_info.last_index - 1; i >= 0; i--) {
            const node = nodes[i];
            if (predicate(node)) {
                const replacement = processNode(node);
                if (replacement === undefined) {
                    nodes.splice(i, 1);
                }
                else {
                    nodes[i] = replacement;
                }
                if (!dontUpdateLastIndex) {
                    nodes.claim_info.last_index = i;
                }
                else if (replacement === undefined) {
                    // Since we spliced before the last_index, we decrease it
                    nodes.claim_info.last_index--;
                }
                return node;
            }
        }
        // If we can't find any matching node, we create a new one
        return createNode();
    })();
    resultNode.claim_order = nodes.claim_info.total_claimed;
    nodes.claim_info.total_claimed += 1;
    return resultNode;
}
function claim_element_base(nodes, name, attributes, create_element) {
    return claim_node(nodes, (node) => node.nodeName === name, (node) => {
        const remove = [];
        for (let j = 0; j < node.attributes.length; j++) {
            const attribute = node.attributes[j];
            if (!attributes[attribute.name]) {
                remove.push(attribute.name);
            }
        }
        remove.forEach(v => node.removeAttribute(v));
        return undefined;
    }, () => create_element(name));
}
function claim_element(nodes, name, attributes) {
    return claim_element_base(nodes, name, attributes, element);
}
function claim_svg_element(nodes, name, attributes) {
    return claim_element_base(nodes, name, attributes, svg_element);
}
function claim_text(nodes, data) {
    return claim_node(nodes, (node) => node.nodeType === 3, (node) => {
        const dataStr = '' + data;
        if (node.data.startsWith(dataStr)) {
            if (node.data.length !== dataStr.length) {
                return node.splitText(dataStr.length);
            }
        }
        else {
            node.data = dataStr;
        }
    }, () => text(data), true // Text nodes should not update last index since it is likely not worth it to eliminate an increasing subsequence of actual elements
    );
}
function claim_space(nodes) {
    return claim_text(nodes, ' ');
}
function find_comment(nodes, text, start) {
    for (let i = start; i < nodes.length; i += 1) {
        const node = nodes[i];
        if (node.nodeType === 8 /* comment node */ && node.textContent.trim() === text) {
            return i;
        }
    }
    return nodes.length;
}
function claim_html_tag(nodes) {
    // find html opening tag
    const start_index = find_comment(nodes, 'HTML_TAG_START', 0);
    const end_index = find_comment(nodes, 'HTML_TAG_END', start_index);
    if (start_index === end_index) {
        return new HtmlTagHydration();
    }
    init_claim_info(nodes);
    const html_tag_nodes = nodes.splice(start_index, end_index + 1);
    detach(html_tag_nodes[0]);
    detach(html_tag_nodes[html_tag_nodes.length - 1]);
    const claimed_nodes = html_tag_nodes.slice(1, html_tag_nodes.length - 1);
    for (const n of claimed_nodes) {
        n.claim_order = nodes.claim_info.total_claimed;
        nodes.claim_info.total_claimed += 1;
    }
    return new HtmlTagHydration(claimed_nodes);
}
function set_data(text, data) {
    data = '' + data;
    if (text.wholeText !== data)
        text.data = data;
}
function set_input_value(input, value) {
    input.value = value == null ? '' : value;
}
function set_input_type(input, type) {
    try {
        input.type = type;
    }
    catch (e) {
        // do nothing
    }
}
function set_style(node, key, value, important) {
    node.style.setProperty(key, value, important ? 'important' : '');
}
function select_option(select, value) {
    for (let i = 0; i < select.options.length; i += 1) {
        const option = select.options[i];
        if (option.__value === value) {
            option.selected = true;
            return;
        }
    }
    select.selectedIndex = -1; // no option should be selected
}
function select_options(select, value) {
    for (let i = 0; i < select.options.length; i += 1) {
        const option = select.options[i];
        option.selected = ~value.indexOf(option.__value);
    }
}
function select_value(select) {
    const selected_option = select.querySelector(':checked') || select.options[0];
    return selected_option && selected_option.__value;
}
function select_multiple_value(select) {
    return [].map.call(select.querySelectorAll(':checked'), option => option.__value);
}
// unfortunately this can't be a constant as that wouldn't be tree-shakeable
// so we cache the result instead
let crossorigin;
function is_crossorigin() {
    if (crossorigin === undefined) {
        crossorigin = false;
        try {
            if (typeof window !== 'undefined' && window.parent) {
                void window.parent.document;
            }
        }
        catch (error) {
            crossorigin = true;
        }
    }
    return crossorigin;
}
function add_resize_listener(node, fn) {
    const computed_style = getComputedStyle(node);
    if (computed_style.position === 'static') {
        node.style.position = 'relative';
    }
    const iframe = element('iframe');
    iframe.setAttribute('style', 'display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%; ' +
        'overflow: hidden; border: 0; opacity: 0; pointer-events: none; z-index: -1;');
    iframe.setAttribute('aria-hidden', 'true');
    iframe.tabIndex = -1;
    const crossorigin = is_crossorigin();
    let unsubscribe;
    if (crossorigin) {
        iframe.src = "data:text/html,<script>onresize=function(){parent.postMessage(0,'*')}</script>";
        unsubscribe = listen(window, 'message', (event) => {
            if (event.source === iframe.contentWindow)
                fn();
        });
    }
    else {
        iframe.src = 'about:blank';
        iframe.onload = () => {
            unsubscribe = listen(iframe.contentWindow, 'resize', fn);
        };
    }
    append(node, iframe);
    return () => {
        if (crossorigin) {
            unsubscribe();
        }
        else if (unsubscribe && iframe.contentWindow) {
            unsubscribe();
        }
        detach(iframe);
    };
}
function toggle_class(element, name, toggle) {
    element.classList[toggle ? 'add' : 'remove'](name);
}
function custom_event(type, detail, bubbles = false) {
    const e = document.createEvent('CustomEvent');
    e.initCustomEvent(type, bubbles, false, detail);
    return e;
}
function query_selector_all(selector, parent = document.body) {
    return Array.from(parent.querySelectorAll(selector));
}
class HtmlTag {
    constructor() {
        this.e = this.n = null;
    }
    c(html) {
        this.h(html);
    }
    m(html, target, anchor = null) {
        if (!this.e) {
            this.e = element(target.nodeName);
            this.t = target;
            this.c(html);
        }
        this.i(anchor);
    }
    h(html) {
        this.e.innerHTML = html;
        this.n = Array.from(this.e.childNodes);
    }
    i(anchor) {
        for (let i = 0; i < this.n.length; i += 1) {
            insert(this.t, this.n[i], anchor);
        }
    }
    p(html) {
        this.d();
        this.h(html);
        this.i(this.a);
    }
    d() {
        this.n.forEach(detach);
    }
}
class HtmlTagHydration extends HtmlTag {
    constructor(claimed_nodes) {
        super();
        this.e = this.n = null;
        this.l = claimed_nodes;
    }
    c(html) {
        if (this.l) {
            this.n = this.l;
        }
        else {
            super.c(html);
        }
    }
    i(anchor) {
        for (let i = 0; i < this.n.length; i += 1) {
            insert_hydration(this.t, this.n[i], anchor);
        }
    }
}
function attribute_to_object(attributes) {
    const result = {};
    for (const attribute of attributes) {
        result[attribute.name] = attribute.value;
    }
    return result;
}
function get_custom_elements_slots(element) {
    const result = {};
    element.childNodes.forEach((node) => {
        result[node.slot || 'default'] = true;
    });
    return result;
}

const active_docs = new Set();
let active = 0;
// https://github.com/darkskyapp/string-hash/blob/master/index.js
function hash(str) {
    let hash = 5381;
    let i = str.length;
    while (i--)
        hash = ((hash << 5) - hash) ^ str.charCodeAt(i);
    return hash >>> 0;
}
function create_rule(node, a, b, duration, delay, ease, fn, uid = 0) {
    const step = 16.666 / duration;
    let keyframes = '{\n';
    for (let p = 0; p <= 1; p += step) {
        const t = a + (b - a) * ease(p);
        keyframes += p * 100 + `%{${fn(t, 1 - t)}}\n`;
    }
    const rule = keyframes + `100% {${fn(b, 1 - b)}}\n}`;
    const name = `__svelte_${hash(rule)}_${uid}`;
    const doc = get_root_for_style(node);
    active_docs.add(doc);
    const stylesheet = doc.__svelte_stylesheet || (doc.__svelte_stylesheet = append_empty_stylesheet(node).sheet);
    const current_rules = doc.__svelte_rules || (doc.__svelte_rules = {});
    if (!current_rules[name]) {
        current_rules[name] = true;
        stylesheet.insertRule(`@keyframes ${name} ${rule}`, stylesheet.cssRules.length);
    }
    const animation = node.style.animation || '';
    node.style.animation = `${animation ? `${animation}, ` : ''}${name} ${duration}ms linear ${delay}ms 1 both`;
    active += 1;
    return name;
}
function delete_rule(node, name) {
    const previous = (node.style.animation || '').split(', ');
    const next = previous.filter(name
        ? anim => anim.indexOf(name) < 0 // remove specific animation
        : anim => anim.indexOf('__svelte') === -1 // remove all Svelte animations
    );
    const deleted = previous.length - next.length;
    if (deleted) {
        node.style.animation = next.join(', ');
        active -= deleted;
        if (!active)
            clear_rules();
    }
}
function clear_rules() {
    raf(() => {
        if (active)
            return;
        active_docs.forEach(doc => {
            const stylesheet = doc.__svelte_stylesheet;
            let i = stylesheet.cssRules.length;
            while (i--)
                stylesheet.deleteRule(i);
            doc.__svelte_rules = {};
        });
        active_docs.clear();
    });
}

function create_animation(node, from, fn, params) {
    if (!from)
        return noop;
    const to = node.getBoundingClientRect();
    if (from.left === to.left && from.right === to.right && from.top === to.top && from.bottom === to.bottom)
        return noop;
    const { delay = 0, duration = 300, easing = identity, 
    // @ts-ignore todo: should this be separated from destructuring? Or start/end added to public api and documentation?
    start: start_time = now() + delay, 
    // @ts-ignore todo:
    end = start_time + duration, tick = noop, css } = fn(node, { from, to }, params);
    let running = true;
    let started = false;
    let name;
    function start() {
        if (css) {
            name = create_rule(node, 0, 1, duration, delay, easing, css);
        }
        if (!delay) {
            started = true;
        }
    }
    function stop() {
        if (css)
            delete_rule(node, name);
        running = false;
    }
    loop(now => {
        if (!started && now >= start_time) {
            started = true;
        }
        if (started && now >= end) {
            tick(1, 0);
            stop();
        }
        if (!running) {
            return false;
        }
        if (started) {
            const p = now - start_time;
            const t = 0 + 1 * easing(p / duration);
            tick(t, 1 - t);
        }
        return true;
    });
    start();
    tick(0, 1);
    return stop;
}
function fix_position(node) {
    const style = getComputedStyle(node);
    if (style.position !== 'absolute' && style.position !== 'fixed') {
        const { width, height } = style;
        const a = node.getBoundingClientRect();
        node.style.position = 'absolute';
        node.style.width = width;
        node.style.height = height;
        add_transform(node, a);
    }
}
function add_transform(node, a) {
    const b = node.getBoundingClientRect();
    if (a.left !== b.left || a.top !== b.top) {
        const style = getComputedStyle(node);
        const transform = style.transform === 'none' ? '' : style.transform;
        node.style.transform = `${transform} translate(${a.left - b.left}px, ${a.top - b.top}px)`;
    }
}

let current_component;
function set_current_component(component) {
    current_component = component;
}
function get_current_component() {
    if (!current_component)
        throw new Error('Function called outside component initialization');
    return current_component;
}
function beforeUpdate(fn) {
    get_current_component().$$.before_update.push(fn);
}
function onMount(fn) {
    get_current_component().$$.on_mount.push(fn);
}
function afterUpdate(fn) {
    get_current_component().$$.after_update.push(fn);
}
function onDestroy(fn) {
    get_current_component().$$.on_destroy.push(fn);
}
function createEventDispatcher() {
    const component = get_current_component();
    return (type, detail) => {
        const callbacks = component.$$.callbacks[type];
        if (callbacks) {
            // TODO are there situations where events could be dispatched
            // in a server (non-DOM) environment?
            const event = custom_event(type, detail);
            callbacks.slice().forEach(fn => {
                fn.call(component, event);
            });
        }
    };
}
function setContext(key, context) {
    get_current_component().$$.context.set(key, context);
}
function getContext(key) {
    return get_current_component().$$.context.get(key);
}
function getAllContexts() {
    return get_current_component().$$.context;
}
function hasContext(key) {
    return get_current_component().$$.context.has(key);
}
// TODO figure out if we still want to support
// shorthand events, or if we want to implement
// a real bubbling mechanism
function bubble(component, event) {
    const callbacks = component.$$.callbacks[event.type];
    if (callbacks) {
        // @ts-ignore
        callbacks.slice().forEach(fn => fn.call(this, event));
    }
}

const dirty_components = [];
const intros = { enabled: false };
const binding_callbacks = [];
const render_callbacks = [];
const flush_callbacks = [];
const resolved_promise = Promise.resolve();
let update_scheduled = false;
function schedule_update() {
    if (!update_scheduled) {
        update_scheduled = true;
        resolved_promise.then(flush);
    }
}
function tick() {
    schedule_update();
    return resolved_promise;
}
function add_render_callback(fn) {
    render_callbacks.push(fn);
}
function add_flush_callback(fn) {
    flush_callbacks.push(fn);
}
let flushing = false;
const seen_callbacks = new Set();
function flush() {
    if (flushing)
        return;
    flushing = true;
    do {
        // first, call beforeUpdate functions
        // and update components
        for (let i = 0; i < dirty_components.length; i += 1) {
            const component = dirty_components[i];
            set_current_component(component);
            update(component.$$);
        }
        set_current_component(null);
        dirty_components.length = 0;
        while (binding_callbacks.length)
            binding_callbacks.pop()();
        // then, once components are updated, call
        // afterUpdate functions. This may cause
        // subsequent updates...
        for (let i = 0; i < render_callbacks.length; i += 1) {
            const callback = render_callbacks[i];
            if (!seen_callbacks.has(callback)) {
                // ...so guard against infinite loops
                seen_callbacks.add(callback);
                callback();
            }
        }
        render_callbacks.length = 0;
    } while (dirty_components.length);
    while (flush_callbacks.length) {
        flush_callbacks.pop()();
    }
    update_scheduled = false;
    flushing = false;
    seen_callbacks.clear();
}
function update($$) {
    if ($$.fragment !== null) {
        $$.update();
        run_all($$.before_update);
        const dirty = $$.dirty;
        $$.dirty = [-1];
        $$.fragment && $$.fragment.p($$.ctx, dirty);
        $$.after_update.forEach(add_render_callback);
    }
}

let promise;
function wait() {
    if (!promise) {
        promise = Promise.resolve();
        promise.then(() => {
            promise = null;
        });
    }
    return promise;
}
function dispatch(node, direction, kind) {
    node.dispatchEvent(custom_event(`${direction ? 'intro' : 'outro'}${kind}`));
}
const outroing = new Set();
let outros;
function group_outros() {
    outros = {
        r: 0,
        c: [],
        p: outros // parent group
    };
}
function check_outros() {
    if (!outros.r) {
        run_all(outros.c);
    }
    outros = outros.p;
}
function transition_in(block, local) {
    if (block && block.i) {
        outroing.delete(block);
        block.i(local);
    }
}
function transition_out(block, local, detach, callback) {
    if (block && block.o) {
        if (outroing.has(block))
            return;
        outroing.add(block);
        outros.c.push(() => {
            outroing.delete(block);
            if (callback) {
                if (detach)
                    block.d(1);
                callback();
            }
        });
        block.o(local);
    }
}
const null_transition = { duration: 0 };
function create_in_transition(node, fn, params) {
    let config = fn(node, params);
    let running = false;
    let animation_name;
    let task;
    let uid = 0;
    function cleanup() {
        if (animation_name)
            delete_rule(node, animation_name);
    }
    function go() {
        const { delay = 0, duration = 300, easing = identity, tick = noop, css } = config || null_transition;
        if (css)
            animation_name = create_rule(node, 0, 1, duration, delay, easing, css, uid++);
        tick(0, 1);
        const start_time = now() + delay;
        const end_time = start_time + duration;
        if (task)
            task.abort();
        running = true;
        add_render_callback(() => dispatch(node, true, 'start'));
        task = loop(now => {
            if (running) {
                if (now >= end_time) {
                    tick(1, 0);
                    dispatch(node, true, 'end');
                    cleanup();
                    return running = false;
                }
                if (now >= start_time) {
                    const t = easing((now - start_time) / duration);
                    tick(t, 1 - t);
                }
            }
            return running;
        });
    }
    let started = false;
    return {
        start() {
            if (started)
                return;
            started = true;
            delete_rule(node);
            if (is_function(config)) {
                config = config();
                wait().then(go);
            }
            else {
                go();
            }
        },
        invalidate() {
            started = false;
        },
        end() {
            if (running) {
                cleanup();
                running = false;
            }
        }
    };
}
function create_out_transition(node, fn, params) {
    let config = fn(node, params);
    let running = true;
    let animation_name;
    const group = outros;
    group.r += 1;
    function go() {
        const { delay = 0, duration = 300, easing = identity, tick = noop, css } = config || null_transition;
        if (css)
            animation_name = create_rule(node, 1, 0, duration, delay, easing, css);
        const start_time = now() + delay;
        const end_time = start_time + duration;
        add_render_callback(() => dispatch(node, false, 'start'));
        loop(now => {
            if (running) {
                if (now >= end_time) {
                    tick(0, 1);
                    dispatch(node, false, 'end');
                    if (!--group.r) {
                        // this will result in `end()` being called,
                        // so we don't need to clean up here
                        run_all(group.c);
                    }
                    return false;
                }
                if (now >= start_time) {
                    const t = easing((now - start_time) / duration);
                    tick(1 - t, t);
                }
            }
            return running;
        });
    }
    if (is_function(config)) {
        wait().then(() => {
            // @ts-ignore
            config = config();
            go();
        });
    }
    else {
        go();
    }
    return {
        end(reset) {
            if (reset && config.tick) {
                config.tick(1, 0);
            }
            if (running) {
                if (animation_name)
                    delete_rule(node, animation_name);
                running = false;
            }
        }
    };
}
function create_bidirectional_transition(node, fn, params, intro) {
    let config = fn(node, params);
    let t = intro ? 0 : 1;
    let running_program = null;
    let pending_program = null;
    let animation_name = null;
    function clear_animation() {
        if (animation_name)
            delete_rule(node, animation_name);
    }
    function init(program, duration) {
        const d = (program.b - t);
        duration *= Math.abs(d);
        return {
            a: t,
            b: program.b,
            d,
            duration,
            start: program.start,
            end: program.start + duration,
            group: program.group
        };
    }
    function go(b) {
        const { delay = 0, duration = 300, easing = identity, tick = noop, css } = config || null_transition;
        const program = {
            start: now() + delay,
            b
        };
        if (!b) {
            // @ts-ignore todo: improve typings
            program.group = outros;
            outros.r += 1;
        }
        if (running_program || pending_program) {
            pending_program = program;
        }
        else {
            // if this is an intro, and there's a delay, we need to do
            // an initial tick and/or apply CSS animation immediately
            if (css) {
                clear_animation();
                animation_name = create_rule(node, t, b, duration, delay, easing, css);
            }
            if (b)
                tick(0, 1);
            running_program = init(program, duration);
            add_render_callback(() => dispatch(node, b, 'start'));
            loop(now => {
                if (pending_program && now > pending_program.start) {
                    running_program = init(pending_program, duration);
                    pending_program = null;
                    dispatch(node, running_program.b, 'start');
                    if (css) {
                        clear_animation();
                        animation_name = create_rule(node, t, running_program.b, running_program.duration, 0, easing, config.css);
                    }
                }
                if (running_program) {
                    if (now >= running_program.end) {
                        tick(t = running_program.b, 1 - t);
                        dispatch(node, running_program.b, 'end');
                        if (!pending_program) {
                            // we're done
                            if (running_program.b) {
                                // intro — we can tidy up immediately
                                clear_animation();
                            }
                            else {
                                // outro — needs to be coordinated
                                if (!--running_program.group.r)
                                    run_all(running_program.group.c);
                            }
                        }
                        running_program = null;
                    }
                    else if (now >= running_program.start) {
                        const p = now - running_program.start;
                        t = running_program.a + running_program.d * easing(p / running_program.duration);
                        tick(t, 1 - t);
                    }
                }
                return !!(running_program || pending_program);
            });
        }
    }
    return {
        run(b) {
            if (is_function(config)) {
                wait().then(() => {
                    // @ts-ignore
                    config = config();
                    go(b);
                });
            }
            else {
                go(b);
            }
        },
        end() {
            clear_animation();
            running_program = pending_program = null;
        }
    };
}

function handle_promise(promise, info) {
    const token = info.token = {};
    function update(type, index, key, value) {
        if (info.token !== token)
            return;
        info.resolved = value;
        let child_ctx = info.ctx;
        if (key !== undefined) {
            child_ctx = child_ctx.slice();
            child_ctx[key] = value;
        }
        const block = type && (info.current = type)(child_ctx);
        let needs_flush = false;
        if (info.block) {
            if (info.blocks) {
                info.blocks.forEach((block, i) => {
                    if (i !== index && block) {
                        group_outros();
                        transition_out(block, 1, 1, () => {
                            if (info.blocks[i] === block) {
                                info.blocks[i] = null;
                            }
                        });
                        check_outros();
                    }
                });
            }
            else {
                info.block.d(1);
            }
            block.c();
            transition_in(block, 1);
            block.m(info.mount(), info.anchor);
            needs_flush = true;
        }
        info.block = block;
        if (info.blocks)
            info.blocks[index] = block;
        if (needs_flush) {
            flush();
        }
    }
    if (is_promise(promise)) {
        const current_component = get_current_component();
        promise.then(value => {
            set_current_component(current_component);
            update(info.then, 1, info.value, value);
            set_current_component(null);
        }, error => {
            set_current_component(current_component);
            update(info.catch, 2, info.error, error);
            set_current_component(null);
            if (!info.hasCatch) {
                throw error;
            }
        });
        // if we previously had a then/catch block, destroy it
        if (info.current !== info.pending) {
            update(info.pending, 0);
            return true;
        }
    }
    else {
        if (info.current !== info.then) {
            update(info.then, 1, info.value, promise);
            return true;
        }
        info.resolved = promise;
    }
}
function update_await_block_branch(info, ctx, dirty) {
    const child_ctx = ctx.slice();
    const { resolved } = info;
    if (info.current === info.then) {
        child_ctx[info.value] = resolved;
    }
    if (info.current === info.catch) {
        child_ctx[info.error] = resolved;
    }
    info.block.p(child_ctx, dirty);
}

const globals = (typeof window !== 'undefined'
    ? window
    : typeof globalThis !== 'undefined'
        ? globalThis
        : global);

function destroy_block(block, lookup) {
    block.d(1);
    lookup.delete(block.key);
}
function outro_and_destroy_block(block, lookup) {
    transition_out(block, 1, 1, () => {
        lookup.delete(block.key);
    });
}
function fix_and_destroy_block(block, lookup) {
    block.f();
    destroy_block(block, lookup);
}
function fix_and_outro_and_destroy_block(block, lookup) {
    block.f();
    outro_and_destroy_block(block, lookup);
}
function update_keyed_each(old_blocks, dirty, get_key, dynamic, ctx, list, lookup, node, destroy, create_each_block, next, get_context) {
    let o = old_blocks.length;
    let n = list.length;
    let i = o;
    const old_indexes = {};
    while (i--)
        old_indexes[old_blocks[i].key] = i;
    const new_blocks = [];
    const new_lookup = new Map();
    const deltas = new Map();
    i = n;
    while (i--) {
        const child_ctx = get_context(ctx, list, i);
        const key = get_key(child_ctx);
        let block = lookup.get(key);
        if (!block) {
            block = create_each_block(key, child_ctx);
            block.c();
        }
        else if (dynamic) {
            block.p(child_ctx, dirty);
        }
        new_lookup.set(key, new_blocks[i] = block);
        if (key in old_indexes)
            deltas.set(key, Math.abs(i - old_indexes[key]));
    }
    const will_move = new Set();
    const did_move = new Set();
    function insert(block) {
        transition_in(block, 1);
        block.m(node, next);
        lookup.set(block.key, block);
        next = block.first;
        n--;
    }
    while (o && n) {
        const new_block = new_blocks[n - 1];
        const old_block = old_blocks[o - 1];
        const new_key = new_block.key;
        const old_key = old_block.key;
        if (new_block === old_block) {
            // do nothing
            next = new_block.first;
            o--;
            n--;
        }
        else if (!new_lookup.has(old_key)) {
            // remove old block
            destroy(old_block, lookup);
            o--;
        }
        else if (!lookup.has(new_key) || will_move.has(new_key)) {
            insert(new_block);
        }
        else if (did_move.has(old_key)) {
            o--;
        }
        else if (deltas.get(new_key) > deltas.get(old_key)) {
            did_move.add(new_key);
            insert(new_block);
        }
        else {
            will_move.add(old_key);
            o--;
        }
    }
    while (o--) {
        const old_block = old_blocks[o];
        if (!new_lookup.has(old_block.key))
            destroy(old_block, lookup);
    }
    while (n)
        insert(new_blocks[n - 1]);
    return new_blocks;
}
function validate_each_keys(ctx, list, get_context, get_key) {
    const keys = new Set();
    for (let i = 0; i < list.length; i++) {
        const key = get_key(get_context(ctx, list, i));
        if (keys.has(key)) {
            throw new Error('Cannot have duplicate keys in a keyed each');
        }
        keys.add(key);
    }
}

function get_spread_update(levels, updates) {
    const update = {};
    const to_null_out = {};
    const accounted_for = { $$scope: 1 };
    let i = levels.length;
    while (i--) {
        const o = levels[i];
        const n = updates[i];
        if (n) {
            for (const key in o) {
                if (!(key in n))
                    to_null_out[key] = 1;
            }
            for (const key in n) {
                if (!accounted_for[key]) {
                    update[key] = n[key];
                    accounted_for[key] = 1;
                }
            }
            levels[i] = n;
        }
        else {
            for (const key in o) {
                accounted_for[key] = 1;
            }
        }
    }
    for (const key in to_null_out) {
        if (!(key in update))
            update[key] = undefined;
    }
    return update;
}
function get_spread_object(spread_props) {
    return typeof spread_props === 'object' && spread_props !== null ? spread_props : {};
}

// source: https://html.spec.whatwg.org/multipage/indices.html
const boolean_attributes = new Set([
    'allowfullscreen',
    'allowpaymentrequest',
    'async',
    'autofocus',
    'autoplay',
    'checked',
    'controls',
    'default',
    'defer',
    'disabled',
    'formnovalidate',
    'hidden',
    'ismap',
    'loop',
    'multiple',
    'muted',
    'nomodule',
    'novalidate',
    'open',
    'playsinline',
    'readonly',
    'required',
    'reversed',
    'selected'
]);

const invalid_attribute_name_character = /[\s'">/=\u{FDD0}-\u{FDEF}\u{FFFE}\u{FFFF}\u{1FFFE}\u{1FFFF}\u{2FFFE}\u{2FFFF}\u{3FFFE}\u{3FFFF}\u{4FFFE}\u{4FFFF}\u{5FFFE}\u{5FFFF}\u{6FFFE}\u{6FFFF}\u{7FFFE}\u{7FFFF}\u{8FFFE}\u{8FFFF}\u{9FFFE}\u{9FFFF}\u{AFFFE}\u{AFFFF}\u{BFFFE}\u{BFFFF}\u{CFFFE}\u{CFFFF}\u{DFFFE}\u{DFFFF}\u{EFFFE}\u{EFFFF}\u{FFFFE}\u{FFFFF}\u{10FFFE}\u{10FFFF}]/u;
// https://html.spec.whatwg.org/multipage/syntax.html#attributes-2
// https://infra.spec.whatwg.org/#noncharacter
function spread(args, classes_to_add) {
    const attributes = Object.assign({}, ...args);
    if (classes_to_add) {
        if (attributes.class == null) {
            attributes.class = classes_to_add;
        }
        else {
            attributes.class += ' ' + classes_to_add;
        }
    }
    let str = '';
    Object.keys(attributes).forEach(name => {
        if (invalid_attribute_name_character.test(name))
            return;
        const value = attributes[name];
        if (value === true)
            str += ' ' + name;
        else if (boolean_attributes.has(name.toLowerCase())) {
            if (value)
                str += ' ' + name;
        }
        else if (value != null) {
            str += ` ${name}="${value}"`;
        }
    });
    return str;
}
const escaped = {
    '"': '&quot;',
    "'": '&#39;',
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;'
};
function escape(html) {
    return String(html).replace(/["'&<>]/g, match => escaped[match]);
}
function escape_attribute_value(value) {
    return typeof value === 'string' ? escape(value) : value;
}
function escape_object(obj) {
    const result = {};
    for (const key in obj) {
        result[key] = escape_attribute_value(obj[key]);
    }
    return result;
}
function each(items, fn) {
    let str = '';
    for (let i = 0; i < items.length; i += 1) {
        str += fn(items[i], i);
    }
    return str;
}
const missing_component = {
    $$render: () => ''
};
function validate_component(component, name) {
    if (!component || !component.$$render) {
        if (name === 'svelte:component')
            name += ' this={...}';
        throw new Error(`<${name}> is not a valid SSR component. You may need to review your build config to ensure that dependencies are compiled, rather than imported as pre-compiled modules`);
    }
    return component;
}
function debug(file, line, column, values) {
    console.log(`{@debug} ${file ? file + ' ' : ''}(${line}:${column})`); // eslint-disable-line no-console
    console.log(values); // eslint-disable-line no-console
    return '';
}
let on_destroy;
function create_ssr_component(fn) {
    function $$render(result, props, bindings, slots, context) {
        const parent_component = current_component;
        const $$ = {
            on_destroy,
            context: new Map(context || (parent_component ? parent_component.$$.context : [])),
            // these will be immediately discarded
            on_mount: [],
            before_update: [],
            after_update: [],
            callbacks: blank_object()
        };
        set_current_component({ $$ });
        const html = fn(result, props, bindings, slots);
        set_current_component(parent_component);
        return html;
    }
    return {
        render: (props = {}, { $$slots = {}, context = new Map() } = {}) => {
            on_destroy = [];
            const result = { title: '', head: '', css: new Set() };
            const html = $$render(result, props, {}, $$slots, context);
            run_all(on_destroy);
            return {
                html,
                css: {
                    code: Array.from(result.css).map(css => css.code).join('\n'),
                    map: null // TODO
                },
                head: result.title + result.head
            };
        },
        $$render
    };
}
function add_attribute(name, value, boolean) {
    if (value == null || (boolean && !value))
        return '';
    return ` ${name}${value === true ? '' : `=${typeof value === 'string' ? JSON.stringify(escape(value)) : `"${value}"`}`}`;
}
function add_classes(classes) {
    return classes ? ` class="${classes}"` : '';
}

function bind(component, name, callback) {
    const index = component.$$.props[name];
    if (index !== undefined) {
        component.$$.bound[index] = callback;
        callback(component.$$.ctx[index]);
    }
}
function create_component(block) {
    block && block.c();
}
function claim_component(block, parent_nodes) {
    block && block.l(parent_nodes);
}
function mount_component(component, target, anchor, customElement) {
    const { fragment, on_mount, on_destroy, after_update } = component.$$;
    fragment && fragment.m(target, anchor);
    if (!customElement) {
        // onMount happens before the initial afterUpdate
        add_render_callback(() => {
            const new_on_destroy = on_mount.map(run).filter(is_function);
            if (on_destroy) {
                on_destroy.push(...new_on_destroy);
            }
            else {
                // Edge case - component was destroyed immediately,
                // most likely as a result of a binding initialising
                run_all(new_on_destroy);
            }
            component.$$.on_mount = [];
        });
    }
    after_update.forEach(add_render_callback);
}
function destroy_component(component, detaching) {
    const $$ = component.$$;
    if ($$.fragment !== null) {
        run_all($$.on_destroy);
        $$.fragment && $$.fragment.d(detaching);
        // TODO null out other refs, including component.$$ (but need to
        // preserve final state?)
        $$.on_destroy = $$.fragment = null;
        $$.ctx = [];
    }
}
function make_dirty(component, i) {
    if (component.$$.dirty[0] === -1) {
        dirty_components.push(component);
        schedule_update();
        component.$$.dirty.fill(0);
    }
    component.$$.dirty[(i / 31) | 0] |= (1 << (i % 31));
}
function init(component, options, instance, create_fragment, not_equal, props, append_styles, dirty = [-1]) {
    const parent_component = current_component;
    set_current_component(component);
    const $$ = component.$$ = {
        fragment: null,
        ctx: null,
        // state
        props,
        update: noop,
        not_equal,
        bound: blank_object(),
        // lifecycle
        on_mount: [],
        on_destroy: [],
        on_disconnect: [],
        before_update: [],
        after_update: [],
        context: new Map(options.context || (parent_component ? parent_component.$$.context : [])),
        // everything else
        callbacks: blank_object(),
        dirty,
        skip_bound: false,
        root: options.target || parent_component.$$.root
    };
    append_styles && append_styles($$.root);
    let ready = false;
    $$.ctx = instance
        ? instance(component, options.props || {}, (i, ret, ...rest) => {
            const value = rest.length ? rest[0] : ret;
            if ($$.ctx && not_equal($$.ctx[i], $$.ctx[i] = value)) {
                if (!$$.skip_bound && $$.bound[i])
                    $$.bound[i](value);
                if (ready)
                    make_dirty(component, i);
            }
            return ret;
        })
        : [];
    $$.update();
    ready = true;
    run_all($$.before_update);
    // `false` as a special case of no DOM component
    $$.fragment = create_fragment ? create_fragment($$.ctx) : false;
    if (options.target) {
        if (options.hydrate) {
            start_hydrating();
            const nodes = children(options.target);
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            $$.fragment && $$.fragment.l(nodes);
            nodes.forEach(detach);
        }
        else {
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            $$.fragment && $$.fragment.c();
        }
        if (options.intro)
            transition_in(component.$$.fragment);
        mount_component(component, options.target, options.anchor, options.customElement);
        end_hydrating();
        flush();
    }
    set_current_component(parent_component);
}
let SvelteElement;
if (typeof HTMLElement === 'function') {
    SvelteElement = class extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({ mode: 'open' });
        }
        connectedCallback() {
            const { on_mount } = this.$$;
            this.$$.on_disconnect = on_mount.map(run).filter(is_function);
            // @ts-ignore todo: improve typings
            for (const key in this.$$.slotted) {
                // @ts-ignore todo: improve typings
                this.appendChild(this.$$.slotted[key]);
            }
        }
        attributeChangedCallback(attr, _oldValue, newValue) {
            this[attr] = newValue;
        }
        disconnectedCallback() {
            run_all(this.$$.on_disconnect);
        }
        $destroy() {
            destroy_component(this, 1);
            this.$destroy = noop;
        }
        $on(type, callback) {
            // TODO should this delegate to addEventListener?
            const callbacks = (this.$$.callbacks[type] || (this.$$.callbacks[type] = []));
            callbacks.push(callback);
            return () => {
                const index = callbacks.indexOf(callback);
                if (index !== -1)
                    callbacks.splice(index, 1);
            };
        }
        $set($$props) {
            if (this.$$set && !is_empty($$props)) {
                this.$$.skip_bound = true;
                this.$$set($$props);
                this.$$.skip_bound = false;
            }
        }
    };
}
/**
 * Base class for Svelte components. Used when dev=false.
 */
class SvelteComponent {
    $destroy() {
        destroy_component(this, 1);
        this.$destroy = noop;
    }
    $on(type, callback) {
        const callbacks = (this.$$.callbacks[type] || (this.$$.callbacks[type] = []));
        callbacks.push(callback);
        return () => {
            const index = callbacks.indexOf(callback);
            if (index !== -1)
                callbacks.splice(index, 1);
        };
    }
    $set($$props) {
        if (this.$$set && !is_empty($$props)) {
            this.$$.skip_bound = true;
            this.$$set($$props);
            this.$$.skip_bound = false;
        }
    }
}

function dispatch_dev(type, detail) {
    document.dispatchEvent(custom_event(type, Object.assign({ version: '3.44.1' }, detail), true));
}
function append_dev(target, node) {
    dispatch_dev('SvelteDOMInsert', { target, node });
    append(target, node);
}
function append_hydration_dev(target, node) {
    dispatch_dev('SvelteDOMInsert', { target, node });
    append_hydration(target, node);
}
function insert_dev(target, node, anchor) {
    dispatch_dev('SvelteDOMInsert', { target, node, anchor });
    insert(target, node, anchor);
}
function insert_hydration_dev(target, node, anchor) {
    dispatch_dev('SvelteDOMInsert', { target, node, anchor });
    insert_hydration(target, node, anchor);
}
function detach_dev(node) {
    dispatch_dev('SvelteDOMRemove', { node });
    detach(node);
}
function detach_between_dev(before, after) {
    while (before.nextSibling && before.nextSibling !== after) {
        detach_dev(before.nextSibling);
    }
}
function detach_before_dev(after) {
    while (after.previousSibling) {
        detach_dev(after.previousSibling);
    }
}
function detach_after_dev(before) {
    while (before.nextSibling) {
        detach_dev(before.nextSibling);
    }
}
function listen_dev(node, event, handler, options, has_prevent_default, has_stop_propagation) {
    const modifiers = options === true ? ['capture'] : options ? Array.from(Object.keys(options)) : [];
    if (has_prevent_default)
        modifiers.push('preventDefault');
    if (has_stop_propagation)
        modifiers.push('stopPropagation');
    dispatch_dev('SvelteDOMAddEventListener', { node, event, handler, modifiers });
    const dispose = listen(node, event, handler, options);
    return () => {
        dispatch_dev('SvelteDOMRemoveEventListener', { node, event, handler, modifiers });
        dispose();
    };
}
function attr_dev(node, attribute, value) {
    attr(node, attribute, value);
    if (value == null)
        dispatch_dev('SvelteDOMRemoveAttribute', { node, attribute });
    else
        dispatch_dev('SvelteDOMSetAttribute', { node, attribute, value });
}
function prop_dev(node, property, value) {
    node[property] = value;
    dispatch_dev('SvelteDOMSetProperty', { node, property, value });
}
function dataset_dev(node, property, value) {
    node.dataset[property] = value;
    dispatch_dev('SvelteDOMSetDataset', { node, property, value });
}
function set_data_dev(text, data) {
    data = '' + data;
    if (text.wholeText === data)
        return;
    dispatch_dev('SvelteDOMSetData', { node: text, data });
    text.data = data;
}
function validate_each_argument(arg) {
    if (typeof arg !== 'string' && !(arg && typeof arg === 'object' && 'length' in arg)) {
        let msg = '{#each} only iterates over array-like objects.';
        if (typeof Symbol === 'function' && arg && Symbol.iterator in arg) {
            msg += ' You can use a spread to convert this iterable into an array.';
        }
        throw new Error(msg);
    }
}
function validate_slots(name, slot, keys) {
    for (const slot_key of Object.keys(slot)) {
        if (!~keys.indexOf(slot_key)) {
            console.warn(`<${name}> received an unexpected slot "${slot_key}".`);
        }
    }
}
/**
 * Base class for Svelte components with some minor dev-enhancements. Used when dev=true.
 */
class SvelteComponentDev extends SvelteComponent {
    constructor(options) {
        if (!options || (!options.target && !options.$$inline)) {
            throw new Error("'target' is a required option");
        }
        super();
    }
    $destroy() {
        super.$destroy();
        this.$destroy = () => {
            console.warn('Component was already destroyed'); // eslint-disable-line no-console
        };
    }
    $capture_state() { }
    $inject_state() { }
}
/**
 * Base class to create strongly typed Svelte components.
 * This only exists for typing purposes and should be used in `.d.ts` files.
 *
 * ### Example:
 *
 * You have component library on npm called `component-library`, from which
 * you export a component called `MyComponent`. For Svelte+TypeScript users,
 * you want to provide typings. Therefore you create a `index.d.ts`:
 * ```ts
 * import { SvelteComponentTyped } from "svelte";
 * export class MyComponent extends SvelteComponentTyped<{foo: string}> {}
 * ```
 * Typing this makes it possible for IDEs like VS Code with the Svelte extension
 * to provide intellisense and to use the component like this in a Svelte file
 * with TypeScript:
 * ```svelte
 * <script lang="ts">
 * 	import { MyComponent } from "component-library";
 * </script>
 * <MyComponent foo={'bar'} />
 * ```
 *
 * #### Why not make this part of `SvelteComponent(Dev)`?
 * Because
 * ```ts
 * class ASubclassOfSvelteComponent extends SvelteComponent<{foo: string}> {}
 * const component: typeof SvelteComponent = ASubclassOfSvelteComponent;
 * ```
 * will throw a type error, so we need to separate the more strictly typed class.
 */
class SvelteComponentTyped extends SvelteComponentDev {
    constructor(options) {
        super(options);
    }
}
function loop_guard(timeout) {
    const start = Date.now();
    return () => {
        if (Date.now() - start > timeout) {
            throw new Error('Infinite loop detected');
        }
    };
}




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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
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
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!**********************************!*\
  !*** ./js/admin-page-columns.ts ***!
  \**********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _admin_columns_form__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./admin/columns/form */ "./js/admin/columns/form.ts");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./constants */ "./js/constants.ts");
/* harmony import */ var _helpers_admin_columns__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./helpers/admin-columns */ "./js/helpers/admin-columns.ts");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _admin_columns_column_configurator__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./admin/columns/column-configurator */ "./js/admin/columns/column-configurator.ts");
/* harmony import */ var _modules_modal__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./modules/modal */ "./js/modules/modal.ts");
/* harmony import */ var _admin_columns_feedback__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./admin/columns/feedback */ "./js/admin/columns/feedback.ts");
/* harmony import */ var _admin_columns_screen_options__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./admin/columns/screen-options */ "./js/admin/columns/screen-options.ts");
/* harmony import */ var _plugin_tooltip__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./plugin/tooltip */ "./js/plugin/tooltip.ts");
/* harmony import */ var _modules_ac_pointer__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./modules/ac-pointer */ "./js/modules/ac-pointer.ts");
/* harmony import */ var _admin_columns_listscreen_initialize__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./admin/columns/listscreen-initialize */ "./js/admin/columns/listscreen-initialize.ts");
/* harmony import */ var _modules_modals__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./modules/modals */ "./js/modules/modals.ts");












let AcServices = (0,_helpers_admin_columns__WEBPACK_IMPORTED_MODULE_2__.initAcServices)();
AcServices.registerService('Modals', new _modules_modals__WEBPACK_IMPORTED_MODULE_11__["default"]());
new _admin_columns_column_configurator__WEBPACK_IMPORTED_MODULE_4__["default"](AcServices);
document.addEventListener('DOMContentLoaded', () => {
    initSaveHandlers();
    // Init the form
    document.querySelectorAll('#listscreen_settings').forEach((formElement) => {
        AcServices.registerService('Form', new _admin_columns_form__WEBPACK_IMPORTED_MODULE_0__.Form(formElement, AcServices));
    });
    // Init the Pro promotion Modal
    if (AcServices.hasService('Modals')) {
        document.querySelectorAll('#ac-modal-pro').forEach(proModal => {
            var _a;
            (_a = AcServices.getService('Modals')) === null || _a === void 0 ? void 0 : _a.register(new _modules_modal__WEBPACK_IMPORTED_MODULE_5__["default"](proModal), 'pro');
        });
    }
    document.querySelectorAll('#ac_list_screen').forEach(select => {
        select.addEventListener('change', () => {
            var _a;
            document.querySelectorAll('.view-link').forEach(link => link.style.display = 'none');
            (_a = select.closest('form')) === null || _a === void 0 ? void 0 : _a.submit();
            select.disabled = true;
            select.nextElementSibling.style.display = 'inline-block';
        });
    });
    document.querySelectorAll('#direct-feedback').forEach(feedbackElement => new _admin_columns_feedback__WEBPACK_IMPORTED_MODULE_6__["default"](feedbackElement));
    if (AC.hasOwnProperty('uninitialized_list_screens')) {
        (0,_admin_columns_listscreen_initialize__WEBPACK_IMPORTED_MODULE_10__.initUninitializedListScreens)(AC.uninitialized_list_screens);
    }
    // Screen Options
    document.querySelectorAll('[data-ac-screen-option="show_column_id"] input').forEach(el => new _admin_columns_screen_options__WEBPACK_IMPORTED_MODULE_7__["default"]('show_column_id', el, 'show-column-id', document.querySelector('.ac-boxes')));
    document.querySelectorAll('[data-ac-screen-option="show_column_type"] input').forEach(el => new _admin_columns_screen_options__WEBPACK_IMPORTED_MODULE_7__["default"]('show_column_type', el, 'show-column-type', document.querySelector('.ac-boxes')));
    document.querySelectorAll('[data-ac-screen-option="show_list_screen_id"] input').forEach(el => new _admin_columns_screen_options__WEBPACK_IMPORTED_MODULE_7__["default"]('show_list_screen_id', el, 'show-list-screen-id', document.querySelector('.ac-admin')));
    document.querySelectorAll('[data-ac-screen-option="show_list_screen_type"] input').forEach(el => new _admin_columns_screen_options__WEBPACK_IMPORTED_MODULE_7__["default"]('show_list_screen_type', el, 'show-list-screen-type', document.querySelector('.ac-admin')));
});
AcServices.addListener(_constants__WEBPACK_IMPORTED_MODULE_1__.EventConstants.SETTINGS.FORM.LOADED, (form) => {
    document.querySelectorAll('.add_column').forEach(el => el.addEventListener('click', () => form.createNewColumn()));
    document.querySelectorAll('a[data-clear-columns]').forEach(el => el.addEventListener('click', () => form.resetColumns()));
    // Make column settings sortable
    let $form = jquery__WEBPACK_IMPORTED_MODULE_3___default()(form.getElement());
    $form.hasClass('ui-sortable')
        ? $form.sortable('refresh')
        : $form.sortable({ items: '.ac-column', handle: '[data-sort-handle]' });
});
AcServices.addListener(_constants__WEBPACK_IMPORTED_MODULE_1__.EventConstants.SETTINGS.FORM.SAVING, () => {
    var _a;
    (_a = document.querySelector('#cpac .ac-admin')) === null || _a === void 0 ? void 0 : _a.classList.add('saving');
});
AcServices.addListener(_constants__WEBPACK_IMPORTED_MODULE_1__.EventConstants.SETTINGS.FORM.SAVED, () => {
    var _a, _b;
    (_a = document.querySelector('#cpac .ac-admin')) === null || _a === void 0 ? void 0 : _a.classList.remove('saving');
    (_b = document.querySelector('#cpac .ac-admin')) === null || _b === void 0 ? void 0 : _b.classList.add('stored');
});
AcServices.addListener(_constants__WEBPACK_IMPORTED_MODULE_1__.EventConstants.SETTINGS.COLUMN.INIT, (column) => {
    (0,_plugin_tooltip__WEBPACK_IMPORTED_MODULE_8__.initAcTooltips)();
    (0,_modules_ac_pointer__WEBPACK_IMPORTED_MODULE_9__.initPointers)(column.getElement().querySelectorAll('.ac-pointer'));
});
const initSaveHandlers = () => {
    const elements = document.querySelectorAll('.sidebox a.submit, .column-footer a.submit');
    AcServices.addListener(_constants__WEBPACK_IMPORTED_MODULE_1__.EventConstants.SETTINGS.FORM.READY, (form) => {
        elements.forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                elements.forEach(el => el.setAttribute('disabled', 'disabled'));
                form.submitForm();
            });
        });
    });
    AcServices.addListener(_constants__WEBPACK_IMPORTED_MODULE_1__.EventConstants.SETTINGS.FORM.SAVED, () => elements.forEach(el => el.removeAttribute('disabled')));
};

})();

/******/ })()
;
//# sourceMappingURL=admin-page-columns.js.map