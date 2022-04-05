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
    column.getElement().querySelectorAll('[data-indicator-toggle]').forEach((toggleElement) => {
        let relatedSettings = column.getElement().querySelectorAll(`.ac-column-setting[data-setting='${toggleElement.dataset.setting}'] .col-input .ac-setting-input:first-child input[type=radio]`);
        toggleElement.addEventListener('click', () => {
            switchTo(!toggleElement.classList.contains('on'), relatedSettings);
        });
        relatedSettings.forEach(element => {
            element.addEventListener('change', () => {
                element.value === 'off'
                    ? toggleElement.classList.remove('on')
                    : toggleElement.classList.add('on');
            });
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
        this.element = element;
        this.inputs = element.querySelectorAll('.ac-setting-input input[type="radio"]');
        this.subFields = element.querySelectorAll('.ac-column-setting');
        this.initState();
        this.initEvents();
    }
    initEvents() {
        this.inputs.forEach(el => {
            el.addEventListener('change', () => this.initState());
        });
    }
    initState() {
        this.isOptionEnabled()
            ? this.subFields.forEach(el => el.style.display = 'table')
            : this.subFields.forEach(el => el.style.display = 'none');
    }
    isOptionEnabled() {
        let checked = Array.from(this.inputs).filter(input => {
            return input.checked;
        });
        return checked.length ? checked[0].value === 'on' : false;
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
        $(this.element).mouseenter(() => {
            $(this.element).pointer({
                content: this.getRelatedHTML(),
                position: this.settings.position,
                pointerWidth: this.settings.width,
                pointerClass: this.getPointerClass()
            });
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
        var _a, _b, _c;
        return (_c = (_b = document.getElementById((_a = this.element.getAttribute('rel')) !== null && _a !== void 0 ? _a : '')) === null || _b === void 0 ? void 0 : _b.innerHTML) !== null && _c !== void 0 ? _c : '';
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