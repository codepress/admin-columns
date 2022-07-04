/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

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

/***/ "./js/components/JsonValue.svelte":
/*!****************************************!*\
  !*** ./js/components/JsonValue.svelte ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte_json_tree__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte-json-tree */ "./node_modules/svelte-json-tree/dist/es/index.js");
/* js/components/JsonValue.svelte generated by Svelte v3.44.1 */



function create_fragment(ctx) {
  let jsontree;
  let current;
  jsontree = new svelte_json_tree__WEBPACK_IMPORTED_MODULE_1__["default"]({
    props: {
      value:
      /*value*/
      ctx[0],
      defaultExpandedLevel:
      /*level*/
      ctx[1]
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsontree.$$.fragment);
    },

    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsontree, target, anchor);
      current = true;
    },

    p(ctx, [dirty]) {
      const jsontree_changes = {};
      if (dirty &
      /*value*/
      1) jsontree_changes.value =
      /*value*/
      ctx[0];
      if (dirty &
      /*level*/
      2) jsontree_changes.defaultExpandedLevel =
      /*level*/
      ctx[1];
      jsontree.$set(jsontree_changes);
    },

    i(local) {
      if (current) return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsontree.$$.fragment, local);
      current = true;
    },

    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsontree.$$.fragment, local);
      current = false;
    },

    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsontree, detaching);
    }

  };
}

function instance($$self, $$props, $$invalidate) {
  let {
    value
  } = $$props;
  let {
    level
  } = $$props;

  $$self.$$set = $$props => {
    if ('value' in $$props) $$invalidate(0, value = $$props.value);
    if ('level' in $$props) $$invalidate(1, level = $$props.level);
  };

  return [value, level];
}

class JsonValue extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
      value: 0,
      level: 1
    });
  }

}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (JsonValue);

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

/***/ "./js/components/ValueModal.svelte":
/*!*****************************************!*\
  !*** ./js/components/ValueModal.svelte ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _types_table__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../types/table */ "./js/types/table.ts");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _types_admin_columns__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../types/admin-columns */ "./js/types/admin-columns.ts");
/* harmony import */ var _helpers_translations__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../helpers/translations */ "./js/helpers/translations.ts");
/* js/components/ValueModal.svelte generated by Svelte v3.44.1 */







function create_if_block_3(ctx) {
  let h2;
  let t;
  return {
    c() {
      h2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("h2");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(
      /*title*/
      ctx[4]);
    },

    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, h2, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(h2, t);
    },

    p(ctx, dirty) {
      if (dirty &
      /*title*/
      16) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t,
      /*title*/
      ctx[4]);
    },

    d(detaching) {
      if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(h2);
    }

  };
} // (117:5) {#if editLink }


function create_if_block_2(ctx) {
  let a;
  let t_value =
  /*translation*/
  ctx[8].edit + "";
  let t;
  return {
    c() {
      a = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("a");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(a, "class", "edit btn button");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(a, "href",
      /*editLink*/
      ctx[6]);
    },

    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, a, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(a, t);
    },

    p(ctx, dirty) {
      if (dirty &
      /*editLink*/
      64) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(a, "href",
        /*editLink*/
        ctx[6]);
      }
    },

    d(detaching) {
      if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(a);
    }

  };
} // (120:5) {#if downloadLink }


function create_if_block_1(ctx) {
  let a;
  let t_value =
  /*translation*/
  ctx[8].download + "";
  let t;
  return {
    c() {
      a = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("a");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(a, "class", "edit btn button");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(a, "href",
      /*downloadLink*/
      ctx[7]);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(a, "download", "");
    },

    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, a, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(a, t);
    },

    p(ctx, dirty) {
      if (dirty &
      /*downloadLink*/
      128) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(a, "href",
        /*downloadLink*/
        ctx[7]);
      }
    },

    d(detaching) {
      if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(a);
    }

  };
} // (124:4) {#if items.length > 1 }


function create_if_block(ctx) {
  let div;
  let button0;
  let t;
  let button1;
  let mounted;
  let dispose;
  return {
    c() {
      div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      button0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("button");
      button0.innerHTML = `<span class="dashicons dashicons-arrow-left-alt2"></span>`;
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      button1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("button");
      button1.innerHTML = `<span class="dashicons dashicons-arrow-right-alt2"></span>`;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(button0, "title", "Previous");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(button0, "class", "btn");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(button1, "title", "Next");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(button1, "class", "btn");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "ac-value-modal__navigation");
    },

    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, button0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, button1);

      if (!mounted) {
        dispose = [(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(button0, "click", (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.prevent_default)(
        /*prevItem*/
        ctx[11])), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(button1, "click", (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.prevent_default)(
        /*nextItem*/
        ctx[10]))];
        mounted = true;
      }
    },

    p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,

    d(detaching) {
      if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
      mounted = false;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
    }

  };
}

function create_fragment(ctx) {
  let div9;
  let div0;
  let t0;
  let div8;
  let div7;
  let div3;
  let div1;
  let t1;
  let span0;
  let t2;
  let t3;
  let t4;
  let div2;
  let button;
  let t5;
  let div4;
  let t6;
  let div6;
  let div5;
  let t7;
  let t8;
  let div9_class_value;
  let mounted;
  let dispose;
  let if_block0 =
  /*title*/
  ctx[4] && create_if_block_3(ctx);
  let if_block1 =
  /*editLink*/
  ctx[6] && create_if_block_2(ctx);
  let if_block2 =
  /*downloadLink*/
  ctx[7] && create_if_block_1(ctx);
  let if_block3 =
  /*items*/
  ctx[1].length > 1 && create_if_block(ctx);
  return {
    c() {
      div9 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      div0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      div8 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      div7 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      div3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      div1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      if (if_block0) if_block0.c();
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      span0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("#");
      t3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(
      /*objectId*/
      ctx[0]);
      t4 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      div2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      button = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("button");
      button.innerHTML = `<span class="dashicons dashicons-no-alt"></span>`;
      t5 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      div4 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      t6 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      div6 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      div5 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
      if (if_block1) if_block1.c();
      t7 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      if (if_block2) if_block2.c();
      t8 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      if (if_block3) if_block3.c();
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div0, "class", "ac-value-modal-background");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span0, "class", "ac-badge");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div1, "class", "ac-value-modal-title");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div2, "class", "ac-value-modal-actions");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div3, "class", "ac-value-modal-panel__header");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div4, "class", "ac-value-modal-panel__body");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div5, "class", "ac-value-modal__edit");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div6, "class", "ac-value-modal-panel__footer");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div7, "class", "ac-value-modal-panel");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div8, "class", "ac-value-modal-container");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div9, "class", div9_class_value = "ac-value-modal " +
      /*modalClass*/
      ctx[2]);
    },

    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div9, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div9, div0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div9, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div9, div8);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div8, div7);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div7, div3);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div3, div1);
      if (if_block0) if_block0.m(div1, null);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div1, t1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div1, span0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span0, t2);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span0, t3);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div3, t4);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div3, div2);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div2, button);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div7, t5);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div7, div4);
      div4.innerHTML =
      /*content*/
      ctx[5];
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div7, t6);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div7, div6);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div6, div5);
      if (if_block1) if_block1.m(div5, null);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div5, t7);
      if (if_block2) if_block2.m(div5, null);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div6, t8);
      if (if_block3) if_block3.m(div6, null);
      /*div9_binding*/

      ctx[14](div9);

      if (!mounted) {
        dispose = [(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div0, "click",
        /*close*/
        ctx[9]), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(button, "click",
        /*close*/
        ctx[9]), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div7, "click", (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.stop_propagation)(
        /*click_handler*/
        ctx[13])), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div8, "click",
        /*close*/
        ctx[9])];
        mounted = true;
      }
    },

    p(ctx, [dirty]) {
      if (
      /*title*/
      ctx[4]) {
        if (if_block0) {
          if_block0.p(ctx, dirty);
        } else {
          if_block0 = create_if_block_3(ctx);
          if_block0.c();
          if_block0.m(div1, t1);
        }
      } else if (if_block0) {
        if_block0.d(1);
        if_block0 = null;
      }

      if (dirty &
      /*objectId*/
      1) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t3,
      /*objectId*/
      ctx[0]);
      if (dirty &
      /*content*/
      32) div4.innerHTML =
      /*content*/
      ctx[5];
      ;

      if (
      /*editLink*/
      ctx[6]) {
        if (if_block1) {
          if_block1.p(ctx, dirty);
        } else {
          if_block1 = create_if_block_2(ctx);
          if_block1.c();
          if_block1.m(div5, t7);
        }
      } else if (if_block1) {
        if_block1.d(1);
        if_block1 = null;
      }

      if (
      /*downloadLink*/
      ctx[7]) {
        if (if_block2) {
          if_block2.p(ctx, dirty);
        } else {
          if_block2 = create_if_block_1(ctx);
          if_block2.c();
          if_block2.m(div5, null);
        }
      } else if (if_block2) {
        if_block2.d(1);
        if_block2 = null;
      }

      if (
      /*items*/
      ctx[1].length > 1) {
        if (if_block3) {
          if_block3.p(ctx, dirty);
        } else {
          if_block3 = create_if_block(ctx);
          if_block3.c();
          if_block3.m(div6, null);
        }
      } else if (if_block3) {
        if_block3.d(1);
        if_block3 = null;
      }

      if (dirty &
      /*modalClass*/
      4 && div9_class_value !== (div9_class_value = "ac-value-modal " +
      /*modalClass*/
      ctx[2])) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div9, "class", div9_class_value);
      }
    },

    i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,

    d(detaching) {
      if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div9);
      if (if_block0) if_block0.d();
      if (if_block1) if_block1.d();
      if (if_block2) if_block2.d();
      if (if_block3) if_block3.d();
      /*div9_binding*/

      ctx[14](null);
      mounted = false;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
    }

  };
}

function instance($$self, $$props, $$invalidate) {
  let {
    items
  } = $$props;
  let {
    objectId
  } = $$props;
  let {
    destroyHandler
  } = $$props;
  let modalClass = '';
  let columnTitle;
  let mainElement;
  let title;
  let content;
  let editLink;
  let downloadLink;
  let source;
  let translation = (0,_helpers_translations__WEBPACK_IMPORTED_MODULE_5__.getTableTranslation)();
  const CancelToken = (axios__WEBPACK_IMPORTED_MODULE_3___default().CancelToken);

  const close = () => {
    destroyHandler();
  };

  const initMouseDown = e => {
    if (e.key === 'Escape') {
      destroyHandler();
    }

    if (e.key === 'ArrowLeft') {
      prevItem();
      e.preventDefault();
    }

    if (e.key === 'ArrowRight') {
      nextItem();
      e.preventDefault();
    }
  };

  (0,svelte__WEBPACK_IMPORTED_MODULE_2__.onMount)(() => {
    var _a;

    let item = items.find(i => i.objectId === objectId);
    columnTitle = item.element.closest('td').dataset.colname;

    if (items.length > 1) {
      document.addEventListener('keydown', initMouseDown);
    }

    $$invalidate(2, modalClass = item.element.dataset.modalClass);
    $$invalidate(4, title = (_a = item.title) !== null && _a !== void 0 ? _a : `#${item.objectId}`);
    updateData(item);
  });
  (0,svelte__WEBPACK_IMPORTED_MODULE_2__.onDestroy)(() => {
    document.removeEventListener('keydown', initMouseDown);
  });

  const getTitle = item => {
    var _a;

    return (_a = item.title) !== null && _a !== void 0 ? _a : `${columnTitle} #${item.objectId}`;
  };

  const updateData = item => {
    $$invalidate(0, objectId = item.objectId);
    $$invalidate(4, title = translation.value_loading);
    $$invalidate(5, content = `<span class="loading">${translation.value_loading}</span>`);
    $$invalidate(6, editLink = item.editLink);
    $$invalidate(7, downloadLink = item.downloadLink);

    if (source) {
      source.cancel();
    }

    source = CancelToken.source();
    return axios__WEBPACK_IMPORTED_MODULE_3___default()({
      method: 'get',
      url: ajaxurl,
      cancelToken: source.token,
      params: {
        action: 'ac_get_column_modal_value',
        layout: AC.layout,
        column_name: item.columnName,
        object_id: item.objectId,
        _ajax_nonce: AC.ajax_nonce
      }
    }).then(response => {
      $$invalidate(5, content = response.data);
      $$invalidate(4, title = getTitle(item));
    });
  };

  const updateItem = index => {
    updateData(items[index]);
  };

  const nextItem = () => {
    let index = items.findIndex(item => item.objectId === objectId);
    let newIndex = index + 1;
    updateItem(newIndex >= items.length ? 0 : newIndex);
  };

  const prevItem = () => {
    let index = items.findIndex(item => item.objectId === objectId);
    let newIndex = index - 1;
    updateItem(newIndex < 0 ? items.length - 1 : newIndex);
  };

  function click_handler(event) {
    svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bubble.call(this, $$self, event);
  }

  function div9_binding($$value) {
    svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
      mainElement = $$value;
      $$invalidate(3, mainElement);
    });
  }

  $$self.$$set = $$props => {
    if ('items' in $$props) $$invalidate(1, items = $$props.items);
    if ('objectId' in $$props) $$invalidate(0, objectId = $$props.objectId);
    if ('destroyHandler' in $$props) $$invalidate(12, destroyHandler = $$props.destroyHandler);
  };

  return [objectId, items, modalClass, mainElement, title, content, editLink, downloadLink, translation, close, nextItem, prevItem, destroyHandler, click_handler, div9_binding];
}

class ValueModal extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
      items: 1,
      objectId: 0,
      destroyHandler: 12
    });
  }

}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ValueModal);

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

/***/ "./node_modules/svelte-json-tree/dist/es/index.js":
/*!********************************************************!*\
  !*** ./node_modules/svelte-json-tree/dist/es/index.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Root)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var svelte_store__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! svelte/store */ "./node_modules/svelte/store/index.mjs");
var __defProp = Object.defineProperty;
var __getOwnPropSymbols = Object.getOwnPropertySymbols;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __propIsEnum = Object.prototype.propertyIsEnumerable;
var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: true, configurable: true, writable: true, value }) : obj[key] = value;
var __spreadValues = (a, b) => {
  for (var prop in b || (b = {}))
    if (__hasOwnProp.call(b, prop))
      __defNormalProp(a, prop, b[prop]);
  if (__getOwnPropSymbols)
    for (var prop of __getOwnPropSymbols(b)) {
      if (__propIsEnum.call(b, prop))
        __defNormalProp(a, prop, b[prop]);
    }
  return a;
};



const STATE = {};
function useState(newState, opts) {
  const currentState = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)(STATE);
  const _newState = typeof newState === "function" ? newState(currentState) : newState;
  const nextState = __spreadValues(__spreadValues({}, currentState), _newState);
  if (opts == null ? void 0 : opts.expandable)
    nextState.isParentExpanded = nextState.expanded;
  (0,svelte__WEBPACK_IMPORTED_MODULE_1__.setContext)(STATE, nextState);
  return currentState;
}
function add_css$8(target) {
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append_styles)(target, "svelte-1qd6nto", ".container.svelte-1qd6nto{display:inline-block;transform:translate(calc(0px - var(--li-identation)), -50%);position:absolute;top:50%;padding-right:100%}.arrow.svelte-1qd6nto{transform-origin:25% 50%;position:relative;line-height:1.1em;font-size:0.75em;margin-left:0;transition:150ms;color:var(--arrow-color);user-select:none;font-family:'Courier New', Courier, monospace;display:block}.expanded.svelte-1qd6nto{transform:rotateZ(90deg) translateX(-3px)}");
}
function create_if_block$8(ctx) {
  let span1;
  let span0;
  let mounted;
  let dispose;
  return {
    c() {
      span1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span0.textContent = `${"\u25B6"}`;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span0, "class", "arrow svelte-1qd6nto");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(span0, "expanded", ctx[2]);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span1, "class", "container svelte-1qd6nto");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span1, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span1, span0);
      if (!mounted) {
        dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(span1, "click", ctx[4]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 4) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(span0, "expanded", ctx2[2]);
      }
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span1);
      mounted = false;
      dispose();
    }
  };
}
function create_fragment$i(ctx) {
  let if_block_anchor;
  let if_block = ctx[1] && create_if_block$8(ctx);
  return {
    c() {
      if (if_block)
        if_block.c();
      if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if (if_block)
        if_block.m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
    },
    p(ctx2, [dirty]) {
      if (ctx2[1]) {
        if (if_block) {
          if_block.p(ctx2, dirty);
        } else {
          if_block = create_if_block$8(ctx2);
          if_block.c();
          if_block.m(if_block_anchor.parentNode, if_block_anchor);
        }
      } else if (if_block) {
        if_block.d(1);
        if_block = null;
      }
    },
    i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    d(detaching) {
      if (if_block)
        if_block.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
    }
  };
}
function instance$i($$self, $$props, $$invalidate) {
  let $expandable;
  let $expanded, $$unsubscribe_expanded = svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop, $$subscribe_expanded = () => ($$unsubscribe_expanded(), $$unsubscribe_expanded = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.subscribe)(expanded, ($$value) => $$invalidate(2, $expanded = $$value)), expanded);
  $$self.$$.on_destroy.push(() => $$unsubscribe_expanded());
  const { expanded: _expanded, expandable } = useState();
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, expandable, (value) => $$invalidate(1, $expandable = value));
  let { expanded = _expanded } = $$props;
  $$subscribe_expanded();
  const click_handler = (event) => {
    event.stopPropagation();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(expanded, $expanded = !$expanded, $expanded);
  };
  $$self.$$set = ($$props2) => {
    if ("expanded" in $$props2)
      $$subscribe_expanded($$invalidate(0, expanded = $$props2.expanded));
  };
  return [expanded, $expandable, $expanded, expandable, click_handler];
}
class JSONArrow extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$i, create_fragment$i, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { expanded: 0 }, add_css$8);
  }
}
function create_fragment$h(ctx) {
  let current;
  const default_slot_template = ctx[1].default;
  const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, ctx[0], null);
  return {
    c() {
      if (default_slot)
        default_slot.c();
    },
    m(target, anchor) {
      if (default_slot) {
        default_slot.m(target, anchor);
      }
      current = true;
    },
    p(ctx2, [dirty]) {
      if (default_slot) {
        if (default_slot.p && (!current || dirty & 1)) {
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(default_slot, default_slot_template, ctx2, ctx2[0], !current ? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(ctx2[0]) : (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, ctx2[0], dirty, null), null);
        }
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
      current = false;
    },
    d(detaching) {
      if (default_slot)
        default_slot.d(detaching);
    }
  };
}
function instance$h($$self, $$props, $$invalidate) {
  let { $$slots: slots = {}, $$scope } = $$props;
  useState({ displayMode: "summary" });
  $$self.$$set = ($$props2) => {
    if ("$$scope" in $$props2)
      $$invalidate(0, $$scope = $$props2.$$scope);
  };
  return [$$scope, slots];
}
class Summary extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$h, create_fragment$h, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {});
  }
}
function create_fragment$g(ctx) {
  let current;
  const default_slot_template = ctx[3].default;
  const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, ctx[2], null);
  return {
    c() {
      if (default_slot)
        default_slot.c();
    },
    m(target, anchor) {
      if (default_slot) {
        default_slot.m(target, anchor);
      }
      current = true;
    },
    p(ctx2, [dirty]) {
      if (default_slot) {
        if (default_slot.p && (!current || dirty & 4)) {
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(default_slot, default_slot_template, ctx2, ctx2[2], !current ? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(ctx2[2]) : (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, ctx2[2], dirty, null), null);
        }
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
      current = false;
    },
    d(detaching) {
      if (default_slot)
        default_slot.d(detaching);
    }
  };
}
function instance$g($$self, $$props, $$invalidate) {
  let { $$slots: slots = {}, $$scope } = $$props;
  let { expanded } = $$props;
  let { key } = $$props;
  const expandable = (0,svelte_store__WEBPACK_IMPORTED_MODULE_2__.writable)(false);
  useState(({ keyPath, level }) => {
    if (key !== "[[Entries]]") {
      keyPath = [...keyPath, key];
      level = level + 1;
    }
    return { keyPath, level, expanded, expandable };
  });
  $$self.$$set = ($$props2) => {
    if ("expanded" in $$props2)
      $$invalidate(0, expanded = $$props2.expanded);
    if ("key" in $$props2)
      $$invalidate(1, key = $$props2.key);
    if ("$$scope" in $$props2)
      $$invalidate(2, $$scope = $$props2.$$scope);
  };
  return [expanded, key, $$scope, slots];
}
class Expandable extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$g, create_fragment$g, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { expanded: 0, key: 1 });
  }
}
function add_css$7(target) {
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append_styles)(target, "svelte-19drypg", ".root.svelte-19drypg{display:inline-block;position:relative}.indent.svelte-19drypg{padding-left:var(--li-identation)}.label.svelte-19drypg{position:relative}");
}
function get_each_context$2(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[19] = list[i];
  child_ctx[21] = i;
  return child_ctx;
}
const get_item_value_slot_changes = (dirty) => ({ key: dirty & 1 });
const get_item_value_slot_context = (ctx) => ({
  key: ctx[19],
  index: ctx[21]
});
const get_item_key_slot_changes = (dirty) => ({ key: dirty & 1 });
const get_item_key_slot_context = (ctx) => ({
  key: ctx[19],
  index: ctx[21]
});
const get_preview_slot_changes = (dirty) => ({});
const get_preview_slot_context = (ctx) => ({});
const get_summary_slot_changes = (dirty) => ({});
const get_summary_slot_context = (ctx) => ({});
function create_else_block$6(ctx) {
  let span;
  let t0;
  let summary;
  let t1;
  let if_block1_anchor;
  let current;
  let mounted;
  let dispose;
  let if_block0 = ctx[6] && create_if_block_3$2(ctx);
  summary = new Summary({
    props: {
      $$slots: { default: [create_default_slot_1] },
      $$scope: { ctx }
    }
  });
  let if_block1 = ctx[4] && create_if_block_1$2(ctx);
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      if (if_block0)
        if_block0.c();
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(summary.$$.fragment);
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      if (if_block1)
        if_block1.c();
      if_block1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "root svelte-19drypg");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      if (if_block0)
        if_block0.m(span, null);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(summary, span, null);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t1, anchor);
      if (if_block1)
        if_block1.m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block1_anchor, anchor);
      current = true;
      if (!mounted) {
        dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(span, "click", ctx[9]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (ctx2[6])
        if_block0.p(ctx2, dirty);
      const summary_changes = {};
      if (dirty & 8192) {
        summary_changes.$$scope = { dirty, ctx: ctx2 };
      }
      summary.$set(summary_changes);
      if (ctx2[4]) {
        if (if_block1) {
          if_block1.p(ctx2, dirty);
          if (dirty & 16) {
            (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
          }
        } else {
          if_block1 = create_if_block_1$2(ctx2);
          if_block1.c();
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
          if_block1.m(if_block1_anchor.parentNode, if_block1_anchor);
        }
      } else if (if_block1) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1, 1, 1, () => {
          if_block1 = null;
        });
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(summary.$$.fragment, local);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(summary.$$.fragment, local);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
      if (if_block0)
        if_block0.d();
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(summary);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t1);
      if (if_block1)
        if_block1.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block1_anchor);
      mounted = false;
      dispose();
    }
  };
}
function create_if_block$7(ctx) {
  let current;
  const summary_slot_template = ctx[11].summary;
  const summary_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(summary_slot_template, ctx, ctx[13], get_summary_slot_context);
  return {
    c() {
      if (summary_slot)
        summary_slot.c();
    },
    m(target, anchor) {
      if (summary_slot) {
        summary_slot.m(target, anchor);
      }
      current = true;
    },
    p(ctx2, dirty) {
      if (summary_slot) {
        if (summary_slot.p && (!current || dirty & 8192)) {
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(summary_slot, summary_slot_template, ctx2, ctx2[13], !current ? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(ctx2[13]) : (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(summary_slot_template, ctx2[13], dirty, get_summary_slot_changes), get_summary_slot_context);
        }
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(summary_slot, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(summary_slot, local);
      current = false;
    },
    d(detaching) {
      if (summary_slot)
        summary_slot.d(detaching);
    }
  };
}
function create_if_block_3$2(ctx) {
  let jsonarrow;
  let current;
  jsonarrow = new JSONArrow({ props: { expanded: ctx[7] } });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonarrow.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonarrow, target, anchor);
      current = true;
    },
    p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonarrow.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonarrow.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonarrow, detaching);
    }
  };
}
function create_default_slot_1(ctx) {
  let current;
  const preview_slot_template = ctx[11].preview;
  const preview_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(preview_slot_template, ctx, ctx[13], get_preview_slot_context);
  return {
    c() {
      if (preview_slot)
        preview_slot.c();
    },
    m(target, anchor) {
      if (preview_slot) {
        preview_slot.m(target, anchor);
      }
      current = true;
    },
    p(ctx2, dirty) {
      if (preview_slot) {
        if (preview_slot.p && (!current || dirty & 8192)) {
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(preview_slot, preview_slot_template, ctx2, ctx2[13], !current ? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(ctx2[13]) : (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(preview_slot_template, ctx2[13], dirty, get_preview_slot_changes), get_preview_slot_context);
        }
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(preview_slot, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(preview_slot, local);
      current = false;
    },
    d(detaching) {
      if (preview_slot)
        preview_slot.d(detaching);
    }
  };
}
function create_if_block_1$2(ctx) {
  let ul;
  let current;
  let mounted;
  let dispose;
  let each_value = ctx[0];
  let each_blocks = [];
  for (let i = 0; i < each_value.length; i += 1) {
    each_blocks[i] = create_each_block$2(get_each_context$2(ctx, each_value, i));
  }
  const out = (i) => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(each_blocks[i], 1, 1, () => {
    each_blocks[i] = null;
  });
  return {
    c() {
      ul = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("ul");
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, ul, anchor);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(ul, null);
      }
      current = true;
      if (!mounted) {
        dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(ul, "click", (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.stop_propagation)(ctx[9]));
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 8223) {
        each_value = ctx2[0];
        let i;
        for (i = 0; i < each_value.length; i += 1) {
          const child_ctx = get_each_context$2(ctx2, each_value, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
            (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i], 1);
          } else {
            each_blocks[i] = create_each_block$2(child_ctx);
            each_blocks[i].c();
            (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i], 1);
            each_blocks[i].m(ul, null);
          }
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
        for (i = each_value.length; i < each_blocks.length; i += 1) {
          out(i);
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
      }
    },
    i(local) {
      if (current)
        return;
      for (let i = 0; i < each_value.length; i += 1) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i]);
      }
      current = true;
    },
    o(local) {
      each_blocks = each_blocks.filter(Boolean);
      for (let i = 0; i < each_blocks.length; i += 1) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(each_blocks[i]);
      }
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(ul);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_each)(each_blocks, detaching);
      mounted = false;
      dispose();
    }
  };
}
function create_if_block_2$2(ctx) {
  let span;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span.textContent = ":";
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "operator");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_default_slot$1(ctx) {
  let span;
  let jsonarrow;
  let show_if = !ctx[1] || ctx[1](ctx[19]);
  let t;
  let current;
  let mounted;
  let dispose;
  jsonarrow = new JSONArrow({});
  const item_key_slot_template = ctx[11].item_key;
  const item_key_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(item_key_slot_template, ctx, ctx[13], get_item_key_slot_context);
  let if_block = show_if && create_if_block_2$2();
  function click_handler() {
    return ctx[12](ctx[21]);
  }
  const item_value_slot_template = ctx[11].item_value;
  const item_value_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(item_value_slot_template, ctx, ctx[13], get_item_value_slot_context);
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonarrow.$$.fragment);
      if (item_key_slot)
        item_key_slot.c();
      if (if_block)
        if_block.c();
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      if (item_value_slot)
        item_value_slot.c();
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "label svelte-19drypg");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonarrow, span, null);
      if (item_key_slot) {
        item_key_slot.m(span, null);
      }
      if (if_block)
        if_block.m(span, null);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
      if (item_value_slot) {
        item_value_slot.m(target, anchor);
      }
      current = true;
      if (!mounted) {
        dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(span, "click", click_handler);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (item_key_slot) {
        if (item_key_slot.p && (!current || dirty & 8193)) {
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(item_key_slot, item_key_slot_template, ctx, ctx[13], !current ? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(ctx[13]) : (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(item_key_slot_template, ctx[13], dirty, get_item_key_slot_changes), get_item_key_slot_context);
        }
      }
      if (dirty & 3)
        show_if = !ctx[1] || ctx[1](ctx[19]);
      if (show_if) {
        if (if_block)
          ;
        else {
          if_block = create_if_block_2$2();
          if_block.c();
          if_block.m(span, t);
        }
      } else if (if_block) {
        if_block.d(1);
        if_block = null;
      }
      if (item_value_slot) {
        if (item_value_slot.p && (!current || dirty & 8193)) {
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(item_value_slot, item_value_slot_template, ctx, ctx[13], !current ? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(ctx[13]) : (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(item_value_slot_template, ctx[13], dirty, get_item_value_slot_changes), get_item_value_slot_context);
        }
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonarrow.$$.fragment, local);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(item_key_slot, local);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(item_value_slot, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonarrow.$$.fragment, local);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(item_key_slot, local);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(item_value_slot, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonarrow);
      if (item_key_slot)
        item_key_slot.d(detaching);
      if (if_block)
        if_block.d();
      if (item_value_slot)
        item_value_slot.d(detaching);
      mounted = false;
      dispose();
    }
  };
}
function create_each_block$2(ctx) {
  let li;
  let expandable_1;
  let t;
  let current;
  let mounted;
  let dispose;
  expandable_1 = new Expandable({
    props: {
      key: ctx[2](ctx[19]),
      expanded: ctx[3][ctx[21]],
      $$slots: { default: [create_default_slot$1] },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      li = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("li");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(expandable_1.$$.fragment);
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(li, "class", "svelte-19drypg");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(li, "indent", ctx[4]);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, li, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(expandable_1, li, null);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(li, t);
      current = true;
      if (!mounted) {
        dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(li, "click", (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.stop_propagation)(click_handler_1));
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      const expandable_1_changes = {};
      if (dirty & 5)
        expandable_1_changes.key = ctx2[2](ctx2[19]);
      if (dirty & 8)
        expandable_1_changes.expanded = ctx2[3][ctx2[21]];
      if (dirty & 8203) {
        expandable_1_changes.$$scope = { dirty, ctx: ctx2 };
      }
      expandable_1.$set(expandable_1_changes);
      if (dirty & 16) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(li, "indent", ctx2[4]);
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(expandable_1.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(expandable_1.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(li);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(expandable_1);
      mounted = false;
      dispose();
    }
  };
}
function create_fragment$f(ctx) {
  let current_block_type_index;
  let if_block;
  let if_block_anchor;
  let current;
  const if_block_creators = [create_if_block$7, create_else_block$6];
  const if_blocks = [];
  function select_block_type(ctx2, dirty) {
    if (ctx2[5] === "summary")
      return 0;
    return 1;
  }
  current_block_type_index = select_block_type(ctx);
  if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
  return {
    c() {
      if_block.c();
      if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if_blocks[current_block_type_index].m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      if_block.p(ctx2, dirty);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
      current = false;
    },
    d(detaching) {
      if_blocks[current_block_type_index].d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
    }
  };
}
const click_handler_1 = () => {
};
function instance$f($$self, $$props, $$invalidate) {
  let child_expanded;
  let $expanded;
  let $expandable;
  let { $$slots: slots = {}, $$scope } = $$props;
  let { keys } = $$props;
  let { shouldShowColon = void 0 } = $$props;
  let { expandKey = (key) => key } = $$props;
  let { defaultExpanded = false } = $$props;
  const { isParentExpanded, displayMode, root, expanded, expandable, keyPath, level, shouldExpandNode } = useState({ root: false }, { expandable: true });
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, expanded, (value) => $$invalidate(4, $expanded = value));
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, expandable, (value) => $$invalidate(14, $expandable = value));
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(expandable, $expandable = true, $expandable);
  if (displayMode !== "summary") {
    if (!defaultExpanded) {
      const controlled = shouldExpandNode({ keyPath, level });
      if (controlled !== void 0) {
        defaultExpanded = controlled;
      }
    }
    (0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
      return isParentExpanded.subscribe((value) => {
        if (!value)
          expanded.set(false);
        else
          expanded.set(defaultExpanded);
      });
    });
  }
  function toggleExpand() {
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(expanded, $expanded = !$expanded, $expanded);
  }
  const click_handler = (index) => child_expanded[index].update((value) => !value);
  $$self.$$set = ($$props2) => {
    if ("keys" in $$props2)
      $$invalidate(0, keys = $$props2.keys);
    if ("shouldShowColon" in $$props2)
      $$invalidate(1, shouldShowColon = $$props2.shouldShowColon);
    if ("expandKey" in $$props2)
      $$invalidate(2, expandKey = $$props2.expandKey);
    if ("defaultExpanded" in $$props2)
      $$invalidate(10, defaultExpanded = $$props2.defaultExpanded);
    if ("$$scope" in $$props2)
      $$invalidate(13, $$scope = $$props2.$$scope);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 1) {
      $$invalidate(3, child_expanded = keys.map(() => (0,svelte_store__WEBPACK_IMPORTED_MODULE_2__.writable)(false)));
    }
  };
  return [
    keys,
    shouldShowColon,
    expandKey,
    child_expanded,
    $expanded,
    displayMode,
    root,
    expanded,
    expandable,
    toggleExpand,
    defaultExpanded,
    slots,
    click_handler,
    $$scope
  ];
}
class JSONNested extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$f, create_fragment$f, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
      keys: 0,
      shouldShowColon: 1,
      expandKey: 2,
      defaultExpanded: 10
    }, add_css$7);
  }
}
function add_css$6(target) {
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append_styles)(target, "svelte-150ffaa", ".comma.svelte-150ffaa{margin-left:-0.5em;margin-right:0.5em}");
}
function get_each_context$1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[7] = list[i];
  child_ctx[9] = i;
  return child_ctx;
}
const get_item_slot_changes = (dirty) => ({ item: dirty & 1 });
const get_item_slot_context = (ctx) => ({
  item: ctx[7],
  index: ctx[9]
});
function create_if_block_3$1(ctx) {
  let span;
  let t;
  let if_block = ctx[2] && create_if_block_4(ctx);
  return {
    c() {
      if (if_block)
        if_block.c();
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(ctx[3]);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "operator");
    },
    m(target, anchor) {
      if (if_block)
        if_block.m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (ctx2[2]) {
        if (if_block) {
          if_block.p(ctx2, dirty);
        } else {
          if_block = create_if_block_4(ctx2);
          if_block.c();
          if_block.m(span.parentNode, span);
        }
      } else if (if_block) {
        if_block.d(1);
        if_block = null;
      }
      if (dirty & 8)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, ctx2[3]);
    },
    d(detaching) {
      if (if_block)
        if_block.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_if_block_4(ctx) {
  let span;
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(ctx[2]);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "label");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 4)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, ctx2[2]);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_if_block_2$1(ctx) {
  let span;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span.textContent = ",";
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "comma operator svelte-150ffaa");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_each_block$1(ctx) {
  let t;
  let if_block_anchor;
  let current;
  const item_slot_template = ctx[6].item;
  const item_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(item_slot_template, ctx, ctx[5], get_item_slot_context);
  let if_block = ctx[9] < ctx[0].length - 1 && create_if_block_2$1();
  return {
    c() {
      if (item_slot)
        item_slot.c();
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      if (if_block)
        if_block.c();
      if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if (item_slot) {
        item_slot.m(target, anchor);
      }
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
      if (if_block)
        if_block.m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      if (item_slot) {
        if (item_slot.p && (!current || dirty & 33)) {
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(item_slot, item_slot_template, ctx2, ctx2[5], !current ? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(ctx2[5]) : (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(item_slot_template, ctx2[5], dirty, get_item_slot_changes), get_item_slot_context);
        }
      }
      if (ctx2[9] < ctx2[0].length - 1) {
        if (if_block)
          ;
        else {
          if_block = create_if_block_2$1();
          if_block.c();
          if_block.m(if_block_anchor.parentNode, if_block_anchor);
        }
      } else if (if_block) {
        if_block.d(1);
        if_block = null;
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(item_slot, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(item_slot, local);
      current = false;
    },
    d(detaching) {
      if (item_slot)
        item_slot.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
      if (if_block)
        if_block.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
    }
  };
}
function create_if_block_1$1(ctx) {
  let span0;
  let t1;
  let span1;
  return {
    c() {
      span0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span0.textContent = ",";
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      span1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span1.textContent = "\u2026";
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span0, "class", "comma operator svelte-150ffaa");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span1, "class", "operator");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span0, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t1, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span1, anchor);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span0);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t1);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span1);
    }
  };
}
function create_if_block$6(ctx) {
  let span;
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(ctx[4]);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "operator");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 16)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, ctx2[4]);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_fragment$e(ctx) {
  let t0;
  let t1;
  let t2;
  let if_block2_anchor;
  let current;
  let if_block0 = ctx[3] && create_if_block_3$1(ctx);
  let each_value = ctx[0];
  let each_blocks = [];
  for (let i = 0; i < each_value.length; i += 1) {
    each_blocks[i] = create_each_block$1(get_each_context$1(ctx, each_value, i));
  }
  const out = (i) => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(each_blocks[i], 1, 1, () => {
    each_blocks[i] = null;
  });
  let if_block1 = ctx[1] && create_if_block_1$1();
  let if_block2 = ctx[4] && create_if_block$6(ctx);
  return {
    c() {
      if (if_block0)
        if_block0.c();
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      if (if_block1)
        if_block1.c();
      t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
      if (if_block2)
        if_block2.c();
      if_block2_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if (if_block0)
        if_block0.m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t0, anchor);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(target, anchor);
      }
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t1, anchor);
      if (if_block1)
        if_block1.m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t2, anchor);
      if (if_block2)
        if_block2.m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block2_anchor, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      if (ctx2[3]) {
        if (if_block0) {
          if_block0.p(ctx2, dirty);
        } else {
          if_block0 = create_if_block_3$1(ctx2);
          if_block0.c();
          if_block0.m(t0.parentNode, t0);
        }
      } else if (if_block0) {
        if_block0.d(1);
        if_block0 = null;
      }
      if (dirty & 33) {
        each_value = ctx2[0];
        let i;
        for (i = 0; i < each_value.length; i += 1) {
          const child_ctx = get_each_context$1(ctx2, each_value, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
            (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i], 1);
          } else {
            each_blocks[i] = create_each_block$1(child_ctx);
            each_blocks[i].c();
            (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i], 1);
            each_blocks[i].m(t1.parentNode, t1);
          }
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
        for (i = each_value.length; i < each_blocks.length; i += 1) {
          out(i);
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
      }
      if (ctx2[1]) {
        if (if_block1)
          ;
        else {
          if_block1 = create_if_block_1$1();
          if_block1.c();
          if_block1.m(t2.parentNode, t2);
        }
      } else if (if_block1) {
        if_block1.d(1);
        if_block1 = null;
      }
      if (ctx2[4]) {
        if (if_block2) {
          if_block2.p(ctx2, dirty);
        } else {
          if_block2 = create_if_block$6(ctx2);
          if_block2.c();
          if_block2.m(if_block2_anchor.parentNode, if_block2_anchor);
        }
      } else if (if_block2) {
        if_block2.d(1);
        if_block2 = null;
      }
    },
    i(local) {
      if (current)
        return;
      for (let i = 0; i < each_value.length; i += 1) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i]);
      }
      current = true;
    },
    o(local) {
      each_blocks = each_blocks.filter(Boolean);
      for (let i = 0; i < each_blocks.length; i += 1) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(each_blocks[i]);
      }
      current = false;
    },
    d(detaching) {
      if (if_block0)
        if_block0.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_each)(each_blocks, detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t1);
      if (if_block1)
        if_block1.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t2);
      if (if_block2)
        if_block2.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block2_anchor);
    }
  };
}
function instance$e($$self, $$props, $$invalidate) {
  let { $$slots: slots = {}, $$scope } = $$props;
  let { list } = $$props;
  let { hasMore } = $$props;
  let { label = void 0 } = $$props;
  let { prefix = void 0 } = $$props;
  let { postfix = void 0 } = $$props;
  $$self.$$set = ($$props2) => {
    if ("list" in $$props2)
      $$invalidate(0, list = $$props2.list);
    if ("hasMore" in $$props2)
      $$invalidate(1, hasMore = $$props2.hasMore);
    if ("label" in $$props2)
      $$invalidate(2, label = $$props2.label);
    if ("prefix" in $$props2)
      $$invalidate(3, prefix = $$props2.prefix);
    if ("postfix" in $$props2)
      $$invalidate(4, postfix = $$props2.postfix);
    if ("$$scope" in $$props2)
      $$invalidate(5, $$scope = $$props2.$$scope);
  };
  return [list, hasMore, label, prefix, postfix, $$scope, slots];
}
class PreviewList extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$e, create_fragment$e, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
      list: 0,
      hasMore: 1,
      label: 2,
      prefix: 3,
      postfix: 4
    }, add_css$6);
  }
}
function create_summary_slot$8(ctx) {
  let span;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span.textContent = `${"{\u2026}"}`;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "label");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
    },
    p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_slot$5(ctx) {
  let span0;
  let t0_value = ctx[4] + "";
  let t0;
  let span1;
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: { value: ctx[0][ctx[4]] }
  });
  return {
    c() {
      span0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t0_value);
      span1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span1.textContent = `${": "}`;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span0, "class", "property");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span1, "class", "operator");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span0, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span0, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span1, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      if ((!current || dirty & 16) && t0_value !== (t0_value = ctx2[4] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t0, t0_value);
      const jsonnode_changes = {};
      if (dirty & 17)
        jsonnode_changes.value = ctx2[0][ctx2[4]];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span0);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_preview_slot$8(ctx) {
  let previewlist;
  let current;
  previewlist = new PreviewList({
    props: {
      list: ctx[2],
      hasMore: ctx[2].length < ctx[1].length,
      prefix: "{",
      postfix: "}",
      $$slots: {
        item: [
          create_item_slot$5,
          ({ item }) => ({ 4: item }),
          ({ item }) => item ? 16 : 0
        ]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(previewlist.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(previewlist, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const previewlist_changes = {};
      if (dirty & 4)
        previewlist_changes.list = ctx2[2];
      if (dirty & 6)
        previewlist_changes.hasMore = ctx2[2].length < ctx2[1].length;
      if (dirty & 49) {
        previewlist_changes.$$scope = { dirty, ctx: ctx2 };
      }
      previewlist.$set(previewlist_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(previewlist.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(previewlist.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(previewlist, detaching);
    }
  };
}
function create_item_key_slot$8(ctx) {
  let span;
  let t_value = ctx[3] + "";
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 8 && t_value !== (t_value = ctx2[3] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_value_slot$8(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: { value: ctx[0][ctx[3]] }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 9)
        jsonnode_changes.value = ctx2[0][ctx2[3]];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_fragment$d(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: ctx[1],
      $$slots: {
        item_value: [
          create_item_value_slot$8,
          ({ key }) => ({ 3: key }),
          ({ key }) => key ? 8 : 0
        ],
        item_key: [
          create_item_key_slot$8,
          ({ key }) => ({ 3: key }),
          ({ key }) => key ? 8 : 0
        ],
        preview: [create_preview_slot$8],
        summary: [create_summary_slot$8]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      const jsonnested_changes = {};
      if (dirty & 2)
        jsonnested_changes.keys = ctx2[1];
      if (dirty & 47) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
function instance$d($$self, $$props, $$invalidate) {
  let keys;
  let previewKeys;
  let { value } = $$props;
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(0, value = $$props2.value);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 1) {
      $$invalidate(1, keys = Object.getOwnPropertyNames(value));
    }
    if ($$self.$$.dirty & 2) {
      $$invalidate(2, previewKeys = keys.slice(0, 5));
    }
  };
  return [value, keys, previewKeys];
}
class JSONObjectNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$d, create_fragment$d, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 0 });
  }
}
function create_summary_slot$7(ctx) {
  let span;
  let t0;
  let t1_value = ctx[0].length + "";
  let t1;
  let t2;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Array(");
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t1_value);
      t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(")");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "label");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t2);
    },
    p(ctx2, dirty) {
      if (dirty & 1 && t1_value !== (t1_value = ctx2[0].length + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_slot$4(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({ props: { value: ctx[4] } });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 16)
        jsonnode_changes.value = ctx2[4];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_preview_slot$7(ctx) {
  let previewlist;
  let current;
  previewlist = new PreviewList({
    props: {
      list: ctx[1],
      hasMore: ctx[1].length < ctx[0].length,
      label: "(" + ctx[0].length + ") ",
      prefix: "[",
      postfix: "]",
      $$slots: {
        item: [
          create_item_slot$4,
          ({ item }) => ({ 4: item }),
          ({ item }) => item ? 16 : 0
        ]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(previewlist.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(previewlist, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const previewlist_changes = {};
      if (dirty & 2)
        previewlist_changes.list = ctx2[1];
      if (dirty & 3)
        previewlist_changes.hasMore = ctx2[1].length < ctx2[0].length;
      if (dirty & 1)
        previewlist_changes.label = "(" + ctx2[0].length + ") ";
      if (dirty & 48) {
        previewlist_changes.$$scope = { dirty, ctx: ctx2 };
      }
      previewlist.$set(previewlist_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(previewlist.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(previewlist.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(previewlist, detaching);
    }
  };
}
function create_item_key_slot$7(ctx) {
  let span;
  let t_value = String(ctx[3]) + "";
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 8 && t_value !== (t_value = String(ctx2[3]) + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_value_slot$7(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: { value: ctx[0][ctx[3]] }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 9)
        jsonnode_changes.value = ctx2[0][ctx2[3]];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_fragment$c(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: ctx[2],
      $$slots: {
        item_value: [
          create_item_value_slot$7,
          ({ key }) => ({ 3: key }),
          ({ key }) => key ? 8 : 0
        ],
        item_key: [
          create_item_key_slot$7,
          ({ key }) => ({ 3: key }),
          ({ key }) => key ? 8 : 0
        ],
        preview: [create_preview_slot$7],
        summary: [create_summary_slot$7]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      const jsonnested_changes = {};
      if (dirty & 4)
        jsonnested_changes.keys = ctx2[2];
      if (dirty & 43) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
function instance$c($$self, $$props, $$invalidate) {
  let keys;
  let preview;
  let { value } = $$props;
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(0, value = $$props2.value);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 1) {
      $$invalidate(2, keys = Object.getOwnPropertyNames(value));
    }
    if ($$self.$$.dirty & 1) {
      $$invalidate(1, preview = value.slice(0, 5));
    }
  };
  return [value, preview, keys];
}
class JSONArrayNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$c, create_fragment$c, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 0 });
  }
}
function create_summary_slot$6(ctx) {
  let span;
  let t0;
  let t1;
  let t2_value = ctx[3].length + "";
  let t2;
  let t3;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(ctx[1]);
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("(");
      t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t2_value);
      t3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(")");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "label");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t2);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t3);
    },
    p(ctx2, dirty) {
      if (dirty & 2)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t0, ctx2[1]);
      if (dirty & 8 && t2_value !== (t2_value = ctx2[3].length + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t2, t2_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_slot$3(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({ props: { value: ctx[8] } });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 256)
        jsonnode_changes.value = ctx2[8];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_preview_slot$6(ctx) {
  let previewlist;
  let current;
  previewlist = new PreviewList({
    props: {
      list: ctx[4],
      hasMore: ctx[4].length < ctx[2].length,
      label: `${ctx[1]}(${ctx[3].length}) `,
      prefix: "{",
      postfix: "}",
      $$slots: {
        item: [
          create_item_slot$3,
          ({ item }) => ({ 8: item }),
          ({ item }) => item ? 256 : 0
        ]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(previewlist.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(previewlist, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const previewlist_changes = {};
      if (dirty & 16)
        previewlist_changes.list = ctx2[4];
      if (dirty & 20)
        previewlist_changes.hasMore = ctx2[4].length < ctx2[2].length;
      if (dirty & 10)
        previewlist_changes.label = `${ctx2[1]}(${ctx2[3].length}) `;
      if (dirty & 768) {
        previewlist_changes.$$scope = { dirty, ctx: ctx2 };
      }
      previewlist.$set(previewlist_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(previewlist.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(previewlist.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(previewlist, detaching);
    }
  };
}
function create_item_key_slot_1$1(ctx) {
  let span;
  let t_value = ctx[6] + "";
  let t;
  let span_class_value;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value = ctx[6] === ENTRIES$1 ? "internal" : "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 64 && t_value !== (t_value = ctx2[6] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
      if (dirty & 64 && span_class_value !== (span_class_value = ctx2[6] === ENTRIES$1 ? "internal" : "property")) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value);
      }
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_else_block$5(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: { value: ctx[0][ctx[6]] }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 65)
        jsonnode_changes.value = ctx2[0][ctx2[6]];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_if_block$5(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: ctx[3],
      defaultExpanded: true,
      $$slots: {
        item_value: [
          create_item_value_slot_1$1,
          ({ key: index }) => ({ 7: index }),
          ({ key: index }) => index ? 128 : 0
        ],
        item_key: [
          create_item_key_slot$6,
          ({ key: index }) => ({ 7: index }),
          ({ key: index }) => index ? 128 : 0
        ]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnested_changes = {};
      if (dirty & 8)
        jsonnested_changes.keys = ctx2[3];
      if (dirty & 644) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
function create_item_key_slot$6(ctx) {
  let span;
  let t_value = ctx[7] + "";
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 128 && t_value !== (t_value = ctx2[7] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_value_slot_1$1(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: {
      value: ctx[2][ctx[7]]
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 132)
        jsonnode_changes.value = ctx2[2][ctx2[7]];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_item_value_slot$6(ctx) {
  let current_block_type_index;
  let if_block;
  let if_block_anchor;
  let current;
  const if_block_creators = [create_if_block$5, create_else_block$5];
  const if_blocks = [];
  function select_block_type(ctx2, dirty) {
    if (ctx2[6] === ENTRIES$1)
      return 0;
    return 1;
  }
  current_block_type_index = select_block_type(ctx);
  if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
  return {
    c() {
      if_block.c();
      if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if_blocks[current_block_type_index].m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      let previous_block_index = current_block_type_index;
      current_block_type_index = select_block_type(ctx2);
      if (current_block_type_index === previous_block_index) {
        if_blocks[current_block_type_index].p(ctx2, dirty);
      } else {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
          if_blocks[previous_block_index] = null;
        });
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
        if_block = if_blocks[current_block_type_index];
        if (!if_block) {
          if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx2);
          if_block.c();
        } else {
          if_block.p(ctx2, dirty);
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
        if_block.m(if_block_anchor.parentNode, if_block_anchor);
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
      current = false;
    },
    d(detaching) {
      if_blocks[current_block_type_index].d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
    }
  };
}
function create_fragment$b(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: [ENTRIES$1, "size"],
      shouldShowColon: ctx[5],
      $$slots: {
        item_value: [
          create_item_value_slot$6,
          ({ key }) => ({ 6: key }),
          ({ key }) => key ? 64 : 0
        ],
        item_key: [
          create_item_key_slot_1$1,
          ({ key }) => ({ 6: key }),
          ({ key }) => key ? 64 : 0
        ],
        preview: [create_preview_slot$6],
        summary: [create_summary_slot$6]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      const jsonnested_changes = {};
      if (dirty & 607) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
const ENTRIES$1 = "[[Entries]]";
function instance$b($$self, $$props, $$invalidate) {
  let previewItems;
  let { value } = $$props;
  let { nodeType } = $$props;
  let indexes = [];
  let items = [];
  const func = (key) => key !== ENTRIES$1;
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(0, value = $$props2.value);
    if ("nodeType" in $$props2)
      $$invalidate(1, nodeType = $$props2.nodeType);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 1) {
      {
        let _indexes = [];
        let _items = [];
        let i = 0;
        for (const entry of value) {
          _indexes.push(i++);
          _items.push(entry);
        }
        $$invalidate(3, indexes = _indexes);
        $$invalidate(2, items = _items);
      }
    }
    if ($$self.$$.dirty & 4) {
      $$invalidate(4, previewItems = items.slice(0, 5));
    }
  };
  return [value, nodeType, items, indexes, previewItems, func];
}
class JSONIterableArrayNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$b, create_fragment$b, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 0, nodeType: 1 });
  }
}
function create_summary_slot$5(ctx) {
  let span;
  let t0;
  let t1_value = ctx[2].length + "";
  let t1;
  let t2;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Map(");
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t1_value);
      t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(")");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "color", "label");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t2);
    },
    p(ctx2, dirty) {
      if (dirty & 4 && t1_value !== (t1_value = ctx2[2].length + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_slot$2(ctx) {
  let jsonnode0;
  let span;
  let jsonnode1;
  let current;
  jsonnode0 = new JSONNode({ props: { value: ctx[10] } });
  jsonnode1 = new JSONNode({
    props: {
      value: ctx[0].get(ctx[10])
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode0.$$.fragment);
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span.textContent = `${" => "}`;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode1.$$.fragment);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "operator");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode0, target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode1, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode0_changes = {};
      if (dirty & 1024)
        jsonnode0_changes.value = ctx2[10];
      jsonnode0.$set(jsonnode0_changes);
      const jsonnode1_changes = {};
      if (dirty & 1025)
        jsonnode1_changes.value = ctx2[0].get(ctx2[10]);
      jsonnode1.$set(jsonnode1_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode0.$$.fragment, local);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode1.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode0.$$.fragment, local);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode1.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode0, detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode1, detaching);
    }
  };
}
function create_preview_slot_1(ctx) {
  let previewlist;
  let current;
  previewlist = new PreviewList({
    props: {
      list: ctx[4],
      hasMore: ctx[4].length < ctx[0].size,
      label: `Map(${ctx[2].length}) `,
      prefix: `{`,
      postfix: "}",
      $$slots: {
        item: [
          create_item_slot$2,
          ({ item }) => ({ 10: item }),
          ({ item }) => item ? 1024 : 0
        ]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(previewlist.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(previewlist, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const previewlist_changes = {};
      if (dirty & 16)
        previewlist_changes.list = ctx2[4];
      if (dirty & 17)
        previewlist_changes.hasMore = ctx2[4].length < ctx2[0].size;
      if (dirty & 4)
        previewlist_changes.label = `Map(${ctx2[2].length}) `;
      if (dirty & 3073) {
        previewlist_changes.$$scope = { dirty, ctx: ctx2 };
      }
      previewlist.$set(previewlist_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(previewlist.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(previewlist.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(previewlist, detaching);
    }
  };
}
function create_item_key_slot_2(ctx) {
  let span;
  let t_value = ctx[7] + "";
  let t;
  let span_class_value;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value = ctx[7] === ENTRIES ? "internal" : "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 128 && t_value !== (t_value = ctx2[7] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
      if (dirty & 128 && span_class_value !== (span_class_value = ctx2[7] === ENTRIES ? "internal" : "property")) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value);
      }
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_else_block$4(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: { value: ctx[0][ctx[7]] }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 129)
        jsonnode_changes.value = ctx2[0][ctx2[7]];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_if_block$4(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: ctx[1],
      expandKey: ctx[5],
      defaultExpanded: true,
      $$slots: {
        item_value: [
          create_item_value_slot_1,
          ({ key: index }) => ({ 8: index }),
          ({ key: index }) => index ? 256 : 0
        ],
        item_key: [
          create_item_key_slot_1,
          ({ key: index }) => ({ 8: index }),
          ({ key: index }) => index ? 256 : 0
        ]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnested_changes = {};
      if (dirty & 2)
        jsonnested_changes.keys = ctx2[1];
      if (dirty & 4)
        jsonnested_changes.expandKey = ctx2[5];
      if (dirty & 2316) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
function create_item_key_slot_1(ctx) {
  let span;
  let t_value = ctx[8] + "";
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 256 && t_value !== (t_value = ctx2[8] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_preview_slot$5(ctx) {
  let span0;
  let jsonnode0;
  let span1;
  let jsonnode1;
  let span2;
  let current;
  jsonnode0 = new JSONNode({
    props: { value: ctx[2][ctx[8]] }
  });
  jsonnode1 = new JSONNode({
    props: {
      value: ctx[3][ctx[8]]
    }
  });
  return {
    c() {
      span0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span0.textContent = `${"{ "}`;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode0.$$.fragment);
      span1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span1.textContent = `${" => "}`;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode1.$$.fragment);
      span2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span2.textContent = `${" }"}`;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span0, "class", "operator");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span1, "class", "operator");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span2, "class", "operator");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span0, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode0, target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span1, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode1, target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span2, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode0_changes = {};
      if (dirty & 260)
        jsonnode0_changes.value = ctx2[2][ctx2[8]];
      jsonnode0.$set(jsonnode0_changes);
      const jsonnode1_changes = {};
      if (dirty & 264)
        jsonnode1_changes.value = ctx2[3][ctx2[8]];
      jsonnode1.$set(jsonnode1_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode0.$$.fragment, local);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode1.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode0.$$.fragment, local);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode1.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode0, detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode1, detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span2);
    }
  };
}
function create_item_key_slot$5(ctx) {
  let span;
  let t_value = ctx[9] + "";
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 512 && t_value !== (t_value = ctx2[9] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_value_slot_2(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: {
      value: ctx[9] === "key" ? ctx[2][ctx[8]] : ctx[3][ctx[8]]
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 780)
        jsonnode_changes.value = ctx2[9] === "key" ? ctx2[2][ctx2[8]] : ctx2[3][ctx2[8]];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_item_value_slot_1(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: ["key", "value"],
      $$slots: {
        item_value: [
          create_item_value_slot_2,
          ({ key: name }) => ({ 9: name }),
          ({ key: name }) => name ? 512 : 0
        ],
        item_key: [
          create_item_key_slot$5,
          ({ key: name }) => ({ 9: name }),
          ({ key: name }) => name ? 512 : 0
        ],
        preview: [create_preview_slot$5]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnested_changes = {};
      if (dirty & 2828) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
function create_item_value_slot$5(ctx) {
  let current_block_type_index;
  let if_block;
  let if_block_anchor;
  let current;
  const if_block_creators = [create_if_block$4, create_else_block$4];
  const if_blocks = [];
  function select_block_type(ctx2, dirty) {
    if (ctx2[7] === ENTRIES)
      return 0;
    return 1;
  }
  current_block_type_index = select_block_type(ctx);
  if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
  return {
    c() {
      if_block.c();
      if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if_blocks[current_block_type_index].m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      let previous_block_index = current_block_type_index;
      current_block_type_index = select_block_type(ctx2);
      if (current_block_type_index === previous_block_index) {
        if_blocks[current_block_type_index].p(ctx2, dirty);
      } else {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
          if_blocks[previous_block_index] = null;
        });
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
        if_block = if_blocks[current_block_type_index];
        if (!if_block) {
          if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx2);
          if_block.c();
        } else {
          if_block.p(ctx2, dirty);
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
        if_block.m(if_block_anchor.parentNode, if_block_anchor);
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
      current = false;
    },
    d(detaching) {
      if_blocks[current_block_type_index].d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
    }
  };
}
function create_fragment$a(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: [ENTRIES, "size"],
      shouldShowColon: ctx[6],
      $$slots: {
        item_value: [
          create_item_value_slot$5,
          ({ key }) => ({ 7: key }),
          ({ key }) => key ? 128 : 0
        ],
        item_key: [
          create_item_key_slot_2,
          ({ key }) => ({ 7: key }),
          ({ key }) => key ? 128 : 0
        ],
        preview: [create_preview_slot_1],
        summary: [create_summary_slot$5]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      const jsonnested_changes = {};
      if (dirty & 2207) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
const ENTRIES = "[[Entries]]";
function instance$a($$self, $$props, $$invalidate) {
  let previewKeys;
  let { value } = $$props;
  useState();
  let indexes = [];
  let keys = [];
  let values = [];
  const func = (index) => keys[index];
  const func_1 = (key) => key !== ENTRIES;
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(0, value = $$props2.value);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 1) {
      {
        let _indexes = [];
        let _keys = [];
        let _values = [];
        let i = 0;
        for (const entry of value) {
          _indexes.push(i++);
          _keys.push(entry[0]);
          _values.push(entry[1]);
        }
        $$invalidate(1, indexes = _indexes);
        $$invalidate(2, keys = _keys);
        $$invalidate(3, values = _values);
      }
    }
    if ($$self.$$.dirty & 1) {
      $$invalidate(4, previewKeys = Array.from(value.keys()).slice(0, 5));
    }
  };
  return [value, indexes, keys, values, previewKeys, func, func_1];
}
class JSONIterableMapNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$a, create_fragment$a, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 0 });
  }
}
function add_css$5(target) {
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append_styles)(target, "svelte-l95iub", ".Date.svelte-l95iub{color:var(--date-color)}.BigInt.svelte-l95iub{color:var(--number-color)}.Number.svelte-l95iub{color:var(--number-color)}.Boolean.svelte-l95iub{color:var(--boolean-color)}.Null.svelte-l95iub{color:var(--null-color)}.Undefined.svelte-l95iub{color:var(--undefined-color)}.Symbol.svelte-l95iub{color:var(--symbol-color)}");
}
function create_fragment$9(ctx) {
  let span;
  let t;
  let span_class_value;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(ctx[0]);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.null_to_empty)(ctx[1]) + " svelte-l95iub");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, [dirty]) {
      if (dirty & 1)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, ctx2[0]);
      if (dirty & 2 && span_class_value !== (span_class_value = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.null_to_empty)(ctx2[1]) + " svelte-l95iub")) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value);
      }
    },
    i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function instance$9($$self, $$props, $$invalidate) {
  let { value, nodeType } = $$props;
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(0, value = $$props2.value);
    if ("nodeType" in $$props2)
      $$invalidate(1, nodeType = $$props2.nodeType);
  };
  return [value, nodeType];
}
class JSONValueNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$9, create_fragment$9, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 0, nodeType: 1 }, add_css$5);
  }
}
function add_css$4(target) {
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append_styles)(target, "svelte-1u08yw6", ".indent.svelte-1u08yw6{padding-left:var(--li-identation)}");
}
function get_each_context(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[6] = list[i];
  child_ctx[9] = i;
  const constants_0 = child_ctx[9] < child_ctx[0].length - 1;
  child_ctx[7] = constants_0;
  return child_ctx;
}
function create_else_block$3(ctx) {
  let span;
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: { value: ctx[0][0] + "\u2026" }
  });
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, span, null);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 1)
        jsonnode_changes.value = ctx2[0][0] + "\u2026";
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode);
    }
  };
}
function create_if_block$3(ctx) {
  let each_1_anchor;
  let current;
  let each_value = ctx[0];
  let each_blocks = [];
  for (let i = 0; i < each_value.length; i += 1) {
    each_blocks[i] = create_each_block(get_each_context(ctx, each_value, i));
  }
  const out = (i) => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(each_blocks[i], 1, 1, () => {
    each_blocks[i] = null;
  });
  return {
    c() {
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      each_1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(target, anchor);
      }
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, each_1_anchor, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      if (dirty & 1) {
        each_value = ctx2[0];
        let i;
        for (i = 0; i < each_value.length; i += 1) {
          const child_ctx = get_each_context(ctx2, each_value, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
            (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i], 1);
          } else {
            each_blocks[i] = create_each_block(child_ctx);
            each_blocks[i].c();
            (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i], 1);
            each_blocks[i].m(each_1_anchor.parentNode, each_1_anchor);
          }
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
        for (i = each_value.length; i < each_blocks.length; i += 1) {
          out(i);
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
      }
    },
    i(local) {
      if (current)
        return;
      for (let i = 0; i < each_value.length; i += 1) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i]);
      }
      current = true;
    },
    o(local) {
      each_blocks = each_blocks.filter(Boolean);
      for (let i = 0; i < each_blocks.length; i += 1) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(each_blocks[i]);
      }
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_each)(each_blocks, detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(each_1_anchor);
    }
  };
}
function create_each_block(ctx) {
  let span1;
  let jsonnode;
  let span0;
  let t_value = ctx[7] ? " +" : "";
  let t;
  let br;
  let current;
  jsonnode = new JSONNode({
    props: {
      value: ctx[6] + (ctx[7] ? "\\n" : "")
    }
  });
  return {
    c() {
      span1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
      span0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      br = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("br");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span0, "class", "operator");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span1, "class", "svelte-1u08yw6");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(span1, "indent", ctx[9] > 0);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span1, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, span1, null);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span1, span0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span0, t);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, br, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 1)
        jsonnode_changes.value = ctx2[6] + (ctx2[7] ? "\\n" : "");
      jsonnode.$set(jsonnode_changes);
      if ((!current || dirty & 1) && t_value !== (t_value = ctx2[7] ? " +" : ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(br);
    }
  };
}
function create_fragment$8(ctx) {
  let span;
  let current_block_type_index;
  let if_block;
  let current;
  let mounted;
  let dispose;
  const if_block_creators = [create_if_block$3, create_else_block$3];
  const if_blocks = [];
  function select_block_type(ctx2, dirty) {
    if (ctx2[1])
      return 0;
    return 1;
  }
  current_block_type_index = select_block_type(ctx);
  if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      if_block.c();
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      if_blocks[current_block_type_index].m(span, null);
      current = true;
      if (!mounted) {
        dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(span, "click", ctx[4]);
        mounted = true;
      }
    },
    p(ctx2, [dirty]) {
      let previous_block_index = current_block_type_index;
      current_block_type_index = select_block_type(ctx2);
      if (current_block_type_index === previous_block_index) {
        if_blocks[current_block_type_index].p(ctx2, dirty);
      } else {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
          if_blocks[previous_block_index] = null;
        });
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
        if_block = if_blocks[current_block_type_index];
        if (!if_block) {
          if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx2);
          if_block.c();
        } else {
          if_block.p(ctx2, dirty);
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
        if_block.m(span, null);
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
      if_blocks[current_block_type_index].d();
      mounted = false;
      dispose();
    }
  };
}
function instance$8($$self, $$props, $$invalidate) {
  let $expandable;
  let $expanded;
  let { stack } = $$props;
  const { expanded, expandable } = useState();
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, expanded, (value) => $$invalidate(1, $expanded = value));
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, expandable, (value) => $$invalidate(5, $expandable = value));
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(expandable, $expandable = true, $expandable);
  const click_handler = () => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(expanded, $expanded = !$expanded, $expanded);
  $$self.$$set = ($$props2) => {
    if ("stack" in $$props2)
      $$invalidate(0, stack = $$props2.stack);
  };
  return [stack, $expanded, expanded, expandable, click_handler];
}
class ErrorStack extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$8, create_fragment$8, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { stack: 0 }, add_css$4);
  }
}
function create_summary_slot$4(ctx) {
  let span;
  let t0;
  let t1_value = String(ctx[0].message) + "";
  let t1;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Error: ");
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t1_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "label");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t1);
    },
    p(ctx2, dirty) {
      if (dirty & 1 && t1_value !== (t1_value = String(ctx2[0].message) + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_preview_slot$4(ctx) {
  let span;
  let t0;
  let t1_value = String(ctx[0].message) + "";
  let t1;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Error: ");
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t1_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "label");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t1);
    },
    p(ctx2, dirty) {
      if (dirty & 1 && t1_value !== (t1_value = String(ctx2[0].message) + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_key_slot$4(ctx) {
  let span;
  let t_value = ctx[2] + "";
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 4 && t_value !== (t_value = ctx2[2] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_else_block$2(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: { value: ctx[0][ctx[2]] }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 5)
        jsonnode_changes.value = ctx2[0][ctx2[2]];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_if_block$2(ctx) {
  let errorstack;
  let current;
  errorstack = new ErrorStack({ props: { stack: ctx[1] } });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(errorstack.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(errorstack, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const errorstack_changes = {};
      if (dirty & 2)
        errorstack_changes.stack = ctx2[1];
      errorstack.$set(errorstack_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(errorstack.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(errorstack.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(errorstack, detaching);
    }
  };
}
function create_item_value_slot$4(ctx) {
  let current_block_type_index;
  let if_block;
  let if_block_anchor;
  let current;
  const if_block_creators = [create_if_block$2, create_else_block$2];
  const if_blocks = [];
  function select_block_type(ctx2, dirty) {
    if (ctx2[2] === "stack")
      return 0;
    return 1;
  }
  current_block_type_index = select_block_type(ctx);
  if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
  return {
    c() {
      if_block.c();
      if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if_blocks[current_block_type_index].m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      let previous_block_index = current_block_type_index;
      current_block_type_index = select_block_type(ctx2);
      if (current_block_type_index === previous_block_index) {
        if_blocks[current_block_type_index].p(ctx2, dirty);
      } else {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
          if_blocks[previous_block_index] = null;
        });
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
        if_block = if_blocks[current_block_type_index];
        if (!if_block) {
          if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx2);
          if_block.c();
        } else {
          if_block.p(ctx2, dirty);
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
        if_block.m(if_block_anchor.parentNode, if_block_anchor);
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
      current = false;
    },
    d(detaching) {
      if_blocks[current_block_type_index].d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
    }
  };
}
function create_fragment$7(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: ["message", "stack"],
      $$slots: {
        item_value: [
          create_item_value_slot$4,
          ({ key }) => ({ 2: key }),
          ({ key }) => key ? 4 : 0
        ],
        item_key: [
          create_item_key_slot$4,
          ({ key }) => ({ 2: key }),
          ({ key }) => key ? 4 : 0
        ],
        preview: [create_preview_slot$4],
        summary: [create_summary_slot$4]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      const jsonnested_changes = {};
      if (dirty & 15) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
function instance$7($$self, $$props, $$invalidate) {
  let stack;
  let { value } = $$props;
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(0, value = $$props2.value);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 1) {
      $$invalidate(1, stack = value.stack.split("\n"));
    }
  };
  return [value, stack];
}
class ErrorNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$7, create_fragment$7, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 0 });
  }
}
function objType(obj) {
  const type = Object.prototype.toString.call(obj).slice(8, -1);
  if (type === "Object") {
    if (typeof obj[Symbol.iterator] === "function") {
      return "Iterable";
    }
    return obj.constructor.name;
  }
  return type;
}
function add_css$3(target) {
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append_styles)(target, "svelte-1fvwa9c", "span.svelte-1fvwa9c{color:var(--string-color);word-break:break-all;word-wrap:break-word}");
}
function create_else_block$1(ctx) {
  let span;
  let t0;
  let t1;
  let t2;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)('"');
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(ctx[0]);
      t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)('"');
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "svelte-1fvwa9c");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t2);
    },
    p(ctx2, dirty) {
      if (dirty & 1)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, ctx2[0]);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_if_block$1(ctx) {
  let span;
  let t0;
  let t1_value = ctx[0].slice(0, 30) + (ctx[0].length > 30 ? "\u2026" : "");
  let t1;
  let t2;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)('"');
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t1_value);
      t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)('"');
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "svelte-1fvwa9c");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t2);
    },
    p(ctx2, dirty) {
      if (dirty & 1 && t1_value !== (t1_value = ctx2[0].slice(0, 30) + (ctx2[0].length > 30 ? "\u2026" : "")))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_fragment$6(ctx) {
  let if_block_anchor;
  function select_block_type(ctx2, dirty) {
    if (ctx2[1] === "summary")
      return create_if_block$1;
    return create_else_block$1;
  }
  let current_block_type = select_block_type(ctx);
  let if_block = current_block_type(ctx);
  return {
    c() {
      if_block.c();
      if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if_block.m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
    },
    p(ctx2, [dirty]) {
      if_block.p(ctx2, dirty);
    },
    i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    d(detaching) {
      if_block.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
    }
  };
}
function instance$6($$self, $$props, $$invalidate) {
  let serialised;
  let { value } = $$props;
  const map = { "\n": "\\n", "	": "\\t", "\r": "\\r" };
  const { displayMode } = useState();
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(2, value = $$props2.value);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 4) {
      $$invalidate(0, serialised = value.replace(/[\n\t\r]/g, (_) => map[_]));
    }
  };
  return [serialised, displayMode, value];
}
class JSONStringNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$6, create_fragment$6, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 2 }, add_css$3);
  }
}
function add_css$2(target) {
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append_styles)(target, "svelte-1eamqdt", ".i.svelte-1eamqdt{font-style:italic}.fn.svelte-1eamqdt,.i.svelte-1eamqdt{color:var(--function-color)}");
}
function create_summary_slot$3(ctx) {
  let span;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span.textContent = "\u0192";
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "i svelte-1eamqdt");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_if_block_3(ctx) {
  let span;
  let t_value = getPreview1(ctx[2]) + "";
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "fn i svelte-1eamqdt");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 4 && t_value !== (t_value = getPreview1(ctx2[2]) + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_if_block_2(ctx) {
  let span;
  let t_value = getPreview2(ctx[2]) + "";
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "i svelte-1eamqdt");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 4 && t_value !== (t_value = getPreview2(ctx2[2]) + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_preview_slot$3(ctx) {
  let if_block0_anchor;
  let if_block1_anchor;
  let if_block0 = !ctx[2].isArrow && create_if_block_3(ctx);
  let if_block1 = !ctx[2].isClass && create_if_block_2(ctx);
  return {
    c() {
      if (if_block0)
        if_block0.c();
      if_block0_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
      if (if_block1)
        if_block1.c();
      if_block1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if (if_block0)
        if_block0.m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block0_anchor, anchor);
      if (if_block1)
        if_block1.m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block1_anchor, anchor);
    },
    p(ctx2, dirty) {
      if (!ctx2[2].isArrow) {
        if (if_block0) {
          if_block0.p(ctx2, dirty);
        } else {
          if_block0 = create_if_block_3(ctx2);
          if_block0.c();
          if_block0.m(if_block0_anchor.parentNode, if_block0_anchor);
        }
      } else if (if_block0) {
        if_block0.d(1);
        if_block0 = null;
      }
      if (!ctx2[2].isClass) {
        if (if_block1) {
          if_block1.p(ctx2, dirty);
        } else {
          if_block1 = create_if_block_2(ctx2);
          if_block1.c();
          if_block1.m(if_block1_anchor.parentNode, if_block1_anchor);
        }
      } else if (if_block1) {
        if_block1.d(1);
        if_block1 = null;
      }
    },
    d(detaching) {
      if (if_block0)
        if_block0.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block0_anchor);
      if (if_block1)
        if_block1.d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block1_anchor);
    }
  };
}
function create_item_key_slot$3(ctx) {
  let span;
  let t_value = ctx[7] + "";
  let t;
  let span_class_value;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value = ctx[7] === FUNCTION || ctx[7] === PROTO ? "internal" : "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 128 && t_value !== (t_value = ctx2[7] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
      if (dirty & 128 && span_class_value !== (span_class_value = ctx2[7] === FUNCTION || ctx2[7] === PROTO ? "internal" : "property")) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value);
      }
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_else_block(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: {
      value: ctx[3](ctx[7])
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 128)
        jsonnode_changes.value = ctx2[3](ctx2[7]);
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_if_block_1(ctx) {
  let jsonobjectnode;
  let current;
  jsonobjectnode = new JSONObjectNode({
    props: {
      value: ctx[3](ctx[7])
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonobjectnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonobjectnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonobjectnode_changes = {};
      if (dirty & 128)
        jsonobjectnode_changes.value = ctx2[3](ctx2[7]);
      jsonobjectnode.$set(jsonobjectnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonobjectnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonobjectnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonobjectnode, detaching);
    }
  };
}
function create_if_block(ctx) {
  let span;
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(ctx[0]);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "i svelte-1eamqdt");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 1)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, ctx2[0]);
    },
    i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_value_slot$3(ctx) {
  let current_block_type_index;
  let if_block;
  let if_block_anchor;
  let current;
  const if_block_creators = [create_if_block, create_if_block_1, create_else_block];
  const if_blocks = [];
  function select_block_type(ctx2, dirty) {
    if (ctx2[7] === FUNCTION)
      return 0;
    if (ctx2[7] === "prototype")
      return 1;
    return 2;
  }
  current_block_type_index = select_block_type(ctx);
  if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
  return {
    c() {
      if_block.c();
      if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if_blocks[current_block_type_index].m(target, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      let previous_block_index = current_block_type_index;
      current_block_type_index = select_block_type(ctx2);
      if (current_block_type_index === previous_block_index) {
        if_blocks[current_block_type_index].p(ctx2, dirty);
      } else {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
          if_blocks[previous_block_index] = null;
        });
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
        if_block = if_blocks[current_block_type_index];
        if (!if_block) {
          if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx2);
          if_block.c();
        } else {
          if_block.p(ctx2, dirty);
        }
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
        if_block.m(if_block_anchor.parentNode, if_block_anchor);
      }
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
      current = false;
    },
    d(detaching) {
      if_blocks[current_block_type_index].d(detaching);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
    }
  };
}
function create_fragment$5(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: ctx[1],
      $$slots: {
        item_value: [
          create_item_value_slot$3,
          ({ key }) => ({ 7: key }),
          ({ key }) => key ? 128 : 0
        ],
        item_key: [
          create_item_key_slot$3,
          ({ key }) => ({ 7: key }),
          ({ key }) => key ? 128 : 0
        ],
        preview: [create_preview_slot$3],
        summary: [create_summary_slot$3]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      const jsonnested_changes = {};
      if (dirty & 2)
        jsonnested_changes.keys = ctx2[1];
      if (dirty & 389) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
const FUNCTION = "[[Function]]";
const PROTO = "[[Prototype]]";
function getPreview1({ isGenerator, isAsync, isClass }) {
  if (isClass)
    return `class ${isClass}`;
  return (isAsync ? "async " : "") + "\u0192" + (isGenerator ? "*" : "");
}
function getPreview2({ isAsync, isArrow, fnName, args }) {
  return (isArrow && isAsync ? "async" : "") + " " + (fnName != null ? fnName : "") + args + (isArrow ? " => \u2026" : "");
}
function toString(value) {
  try {
    return value.toString();
  } catch {
    switch (value.constructor.name) {
      case "AsyncFunction":
        return "async function () {}";
      case "AsyncGeneratorFunction":
        return "async function * () {}";
      case "GeneratorFunction:":
        return "function * () {}";
      default:
        return "function () {}";
    }
  }
}
function instance$5($$self, $$props, $$invalidate) {
  let str;
  let ctx;
  let keys;
  let { value } = $$props;
  function parseFunction(str2) {
    const match = str2.match(/^(?:(async)\s+)?(?:function)?(\*)?\s*([^(]+)?(\([^)]*\))\s*(=>)?/);
    const isAsync = match == null ? void 0 : match[1];
    const isGenerator = match == null ? void 0 : match[2];
    const fnName = match == null ? void 0 : match[3];
    const args = match == null ? void 0 : match[4];
    const isArrow = match == null ? void 0 : match[5];
    const classMatch = str2.match(/^class\s+([^\s]+)/);
    const isClass = classMatch == null ? void 0 : classMatch[1];
    return {
      args,
      isAsync,
      isGenerator,
      fnName,
      isArrow,
      isClass
    };
  }
  function getValue(key) {
    if (key === PROTO)
      return value.__proto__;
    return value[key];
  }
  function filterKeys(key) {
    if (key === FUNCTION)
      return true;
    return getValue(key);
  }
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(4, value = $$props2.value);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 16) {
      $$invalidate(0, str = toString(value));
    }
    if ($$self.$$.dirty & 1) {
      $$invalidate(2, ctx = parseFunction(str));
    }
  };
  $$invalidate(1, keys = ["length", "name", "prototype", FUNCTION, PROTO].filter(filterKeys));
  return [str, keys, ctx, getValue, value];
}
class JSONFunctionNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$5, create_fragment$5, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 4 }, add_css$2);
  }
}
function create_summary_slot$2(ctx) {
  let span;
  let t0_value = ctx[3] ? "writable(" : "readable(";
  let t0;
  let jsonnode;
  let t1_value = ")";
  let t1;
  let current;
  jsonnode = new JSONNode({ props: { value: ctx[2] } });
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t0_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t1_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "label");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, span, null);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t1);
      current = true;
    },
    p(ctx2, dirty) {
      if ((!current || dirty & 8) && t0_value !== (t0_value = ctx2[3] ? "writable(" : "readable("))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t0, t0_value);
      const jsonnode_changes = {};
      if (dirty & 4)
        jsonnode_changes.value = ctx2[2];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode);
    }
  };
}
function create_item_slot$1(ctx) {
  let span0;
  let t0_value = ctx[9] + "";
  let t0;
  let span1;
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: { value: ctx[0][ctx[9]] }
  });
  return {
    c() {
      span0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t0_value);
      span1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      span1.textContent = `${": "}`;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span0, "class", "property");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span1, "class", "operator");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span0, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span0, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span1, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      if ((!current || dirty & 512) && t0_value !== (t0_value = ctx2[9] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t0, t0_value);
      const jsonnode_changes = {};
      if (dirty & 513)
        jsonnode_changes.value = ctx2[0][ctx2[9]];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span0);
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_preview_slot$2(ctx) {
  let previewlist;
  let current;
  previewlist = new PreviewList({
    props: {
      list: ctx[4],
      hasMore: ctx[4].length < ctx[1].length,
      prefix: "{",
      postfix: "}",
      $$slots: {
        item: [
          create_item_slot$1,
          ({ item }) => ({ 9: item }),
          ({ item }) => item ? 512 : 0
        ]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(previewlist.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(previewlist, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const previewlist_changes = {};
      if (dirty & 16)
        previewlist_changes.list = ctx2[4];
      if (dirty & 18)
        previewlist_changes.hasMore = ctx2[4].length < ctx2[1].length;
      if (dirty & 1537) {
        previewlist_changes.$$scope = { dirty, ctx: ctx2 };
      }
      previewlist.$set(previewlist_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(previewlist.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(previewlist.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(previewlist, detaching);
    }
  };
}
function create_item_key_slot$2(ctx) {
  let span;
  let t_value = ctx[8] + "";
  let t;
  let span_class_value;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value = ctx[8] === STORE_VALUE ? "internal" : "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 256 && t_value !== (t_value = ctx2[8] + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
      if (dirty & 256 && span_class_value !== (span_class_value = ctx2[8] === STORE_VALUE ? "internal" : "property")) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value);
      }
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_value_slot$2(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: {
      value: ctx[6](ctx[8])
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 256)
        jsonnode_changes.value = ctx2[6](ctx2[8]);
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_fragment$4(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: ctx[5],
      $$slots: {
        item_value: [
          create_item_value_slot$2,
          ({ key }) => ({ 8: key }),
          ({ key }) => key ? 256 : 0
        ],
        item_key: [
          create_item_key_slot$2,
          ({ key }) => ({ 8: key }),
          ({ key }) => key ? 256 : 0
        ],
        preview: [create_preview_slot$2],
        summary: [create_summary_slot$2]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      const jsonnested_changes = {};
      if (dirty & 32)
        jsonnested_changes.keys = ctx2[5];
      if (dirty & 1311) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
const STORE_VALUE = "$value";
function instance$4($$self, $$props, $$invalidate) {
  let objectKeys;
  let keys;
  let previewKeys;
  let storeValue;
  let isWritableStore;
  let $value, $$unsubscribe_value = svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop, $$subscribe_value = () => ($$unsubscribe_value(), $$unsubscribe_value = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.subscribe)(value, ($$value) => $$invalidate(7, $value = $$value)), value);
  $$self.$$.on_destroy.push(() => $$unsubscribe_value());
  let { value } = $$props;
  $$subscribe_value();
  function getValue(key) {
    if (key === STORE_VALUE)
      return storeValue;
    return value[key];
  }
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$subscribe_value($$invalidate(0, value = $$props2.value));
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 1) {
      $$invalidate(1, objectKeys = Object.getOwnPropertyNames(value));
    }
    if ($$self.$$.dirty & 2) {
      $$invalidate(5, keys = [STORE_VALUE, ...objectKeys]);
    }
    if ($$self.$$.dirty & 2) {
      $$invalidate(4, previewKeys = objectKeys.slice(0, 5));
    }
    if ($$self.$$.dirty & 128) {
      $$invalidate(2, storeValue = $value);
    }
    if ($$self.$$.dirty & 1) {
      $$invalidate(3, isWritableStore = typeof value.set === "function");
    }
  };
  return [
    value,
    objectKeys,
    storeValue,
    isWritableStore,
    previewKeys,
    keys,
    getValue,
    $value
  ];
}
class JSONSvelteStoreNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$4, create_fragment$4, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 0 });
  }
}
function create_summary_slot$1(ctx) {
  let span;
  let t0;
  let t1;
  let t2_value = ctx[0].length + "";
  let t2;
  let t3;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(ctx[1]);
      t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("(");
      t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t2_value);
      t3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(")");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "label");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t0);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t1);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t2);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t3);
    },
    p(ctx2, dirty) {
      if (dirty & 2)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t0, ctx2[1]);
      if (dirty & 1 && t2_value !== (t2_value = ctx2[0].length + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t2, t2_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_slot(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({ props: { value: ctx[7] } });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 128)
        jsonnode_changes.value = ctx2[7];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_preview_slot$1(ctx) {
  let previewlist;
  let current;
  previewlist = new PreviewList({
    props: {
      list: ctx[2],
      hasMore: ctx[2].length < ctx[0].length,
      label: ctx[1] + "(" + ctx[0].length + ") ",
      prefix: "[",
      postfix: "]",
      $$slots: {
        item: [
          create_item_slot,
          ({ item }) => ({ 7: item }),
          ({ item }) => item ? 128 : 0
        ]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(previewlist.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(previewlist, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const previewlist_changes = {};
      if (dirty & 4)
        previewlist_changes.list = ctx2[2];
      if (dirty & 5)
        previewlist_changes.hasMore = ctx2[2].length < ctx2[0].length;
      if (dirty & 3)
        previewlist_changes.label = ctx2[1] + "(" + ctx2[0].length + ") ";
      if (dirty & 384) {
        previewlist_changes.$$scope = { dirty, ctx: ctx2 };
      }
      previewlist.$set(previewlist_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(previewlist.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(previewlist.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(previewlist, detaching);
    }
  };
}
function create_item_key_slot$1(ctx) {
  let span;
  let t_value = String(ctx[6]) + "";
  let t;
  let span_class_value;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value = ctx[4].includes(ctx[6]) ? "internal" : "property");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 64 && t_value !== (t_value = String(ctx2[6]) + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
      if (dirty & 64 && span_class_value !== (span_class_value = ctx2[4].includes(ctx2[6]) ? "internal" : "property")) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value);
      }
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_value_slot$1(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: {
      value: ctx[5](ctx[6])
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 64)
        jsonnode_changes.value = ctx2[5](ctx2[6]);
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_fragment$3(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: ctx[3],
      $$slots: {
        item_value: [
          create_item_value_slot$1,
          ({ key }) => ({ 6: key }),
          ({ key }) => key ? 64 : 0
        ],
        item_key: [
          create_item_key_slot$1,
          ({ key }) => ({ 6: key }),
          ({ key }) => key ? 64 : 0
        ],
        preview: [create_preview_slot$1],
        summary: [create_summary_slot$1]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      const jsonnested_changes = {};
      if (dirty & 8)
        jsonnested_changes.keys = ctx2[3];
      if (dirty & 327) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
const TO_STRING_TAG = "Symbol(Symbol.toStringTag)";
function instance$3($$self, $$props, $$invalidate) {
  let keys;
  let preview;
  let { value } = $$props;
  let { nodeType } = $$props;
  const internalKeys = ["buffer", "byteLength", "byteOffset", "length", TO_STRING_TAG];
  function getValue(key) {
    if (key === TO_STRING_TAG) {
      return value[Symbol.toStringTag];
    }
    return value[key];
  }
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(0, value = $$props2.value);
    if ("nodeType" in $$props2)
      $$invalidate(1, nodeType = $$props2.nodeType);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 1) {
      $$invalidate(3, keys = [...Object.getOwnPropertyNames(value), ...internalKeys]);
    }
    if ($$self.$$.dirty & 1) {
      $$invalidate(2, preview = value.slice(0, 5));
    }
  };
  return [value, nodeType, preview, keys, internalKeys, getValue];
}
class TypedArrayNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$3, create_fragment$3, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 0, nodeType: 1 });
  }
}
function add_css$1(target) {
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append_styles)(target, "svelte-17k1wqt", ".regex.svelte-17k1wqt{color:var(--regex-color)}");
}
function create_summary_slot(ctx) {
  let span;
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(ctx[1]);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "regex svelte-17k1wqt");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 2)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, ctx2[1]);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_preview_slot(ctx) {
  let span;
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(ctx[1]);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "regex svelte-17k1wqt");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 2)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, ctx2[1]);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_key_slot(ctx) {
  let span;
  let t_value = String(ctx[3]) + "";
  let t;
  return {
    c() {
      span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
      t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "internal");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);
    },
    p(ctx2, dirty) {
      if (dirty & 8 && t_value !== (t_value = String(ctx2[3]) + ""))
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
    }
  };
}
function create_item_value_slot(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({
    props: { value: ctx[0][ctx[3]] }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 9)
        jsonnode_changes.value = ctx2[0][ctx2[3]];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_fragment$2(ctx) {
  let jsonnested;
  let current;
  jsonnested = new JSONNested({
    props: {
      keys: ctx[2],
      $$slots: {
        item_value: [
          create_item_value_slot,
          ({ key }) => ({ 3: key }),
          ({ key }) => key ? 8 : 0
        ],
        item_key: [
          create_item_key_slot,
          ({ key }) => ({ 3: key }),
          ({ key }) => key ? 8 : 0
        ],
        preview: [create_preview_slot],
        summary: [create_summary_slot]
      },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnested.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnested, target, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      const jsonnested_changes = {};
      if (dirty & 27) {
        jsonnested_changes.$$scope = { dirty, ctx: ctx2 };
      }
      jsonnested.$set(jsonnested_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnested.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnested.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnested, detaching);
    }
  };
}
function instance$2($$self, $$props, $$invalidate) {
  let str;
  let { value } = $$props;
  const keys = [
    "lastIndex",
    "dotAll",
    "flags",
    "global",
    "hasIndices",
    "ignoreCase",
    "multiline",
    "source",
    "sticky",
    "unicode"
  ];
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(0, value = $$props2.value);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 1) {
      $$invalidate(1, str = value.toString());
    }
  };
  return [value, str, keys];
}
class RegExpNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$2, create_fragment$2, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 0 }, add_css$1);
  }
}
function create_fragment$1(ctx) {
  let switch_instance;
  let switch_instance_anchor;
  let current;
  const switch_instance_spread_levels = [{ value: ctx[0] }, ctx[1]];
  var switch_value = ctx[2];
  function switch_props(ctx2) {
    let switch_instance_props = {};
    for (let i = 0; i < switch_instance_spread_levels.length; i += 1) {
      switch_instance_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(switch_instance_props, switch_instance_spread_levels[i]);
    }
    return { props: switch_instance_props };
  }
  if (switch_value) {
    switch_instance = new switch_value(switch_props());
  }
  return {
    c() {
      if (switch_instance)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
      switch_instance_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
    },
    m(target, anchor) {
      if (switch_instance) {
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, target, anchor);
      }
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, switch_instance_anchor, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      const switch_instance_changes = dirty & 3 ? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(switch_instance_spread_levels, [
        dirty & 1 && { value: ctx2[0] },
        dirty & 2 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(ctx2[1])
      ]) : {};
      if (switch_value !== (switch_value = ctx2[2])) {
        if (switch_instance) {
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
          const old_component = switch_instance;
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
            (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
          });
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
        }
        if (switch_value) {
          switch_instance = new switch_value(switch_props());
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
          (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, switch_instance_anchor.parentNode, switch_instance_anchor);
        } else {
          switch_instance = null;
        }
      } else if (switch_value) {
        switch_instance.$set(switch_instance_changes);
      }
    },
    i(local) {
      if (current)
        return;
      if (switch_instance)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
      current = true;
    },
    o(local) {
      if (switch_instance)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(switch_instance_anchor);
      if (switch_instance)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance, detaching);
    }
  };
}
function instance$1($$self, $$props, $$invalidate) {
  let componentType;
  let props;
  let $nodeType;
  let { value } = $$props;
  const nodeType = (0,svelte_store__WEBPACK_IMPORTED_MODULE_2__.writable)();
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, nodeType, (value2) => $$invalidate(4, $nodeType = value2));
  function getComponentAndProps(nodeType2, value2) {
    switch (nodeType2) {
      case "Object":
        if (typeof value2.subscribe === "function")
          return [JSONSvelteStoreNode];
        return [JSONObjectNode];
      case "Error":
        return [ErrorNode];
      case "Array":
        return [JSONArrayNode];
      case "Map":
        return [JSONIterableMapNode];
      case "Iterable":
      case "Set":
        return [JSONIterableArrayNode, { nodeType: nodeType2 }];
      case "Number":
        return [JSONValueNode, { nodeType: nodeType2 }];
      case "String":
        return [JSONStringNode];
      case "Boolean":
        return [
          JSONValueNode,
          {
            nodeType: nodeType2,
            value: value2 ? "true" : "false"
          }
        ];
      case "Date":
        return [JSONValueNode, { nodeType: nodeType2, value: value2.toISOString() }];
      case "Null":
        return [JSONValueNode, { nodeType: nodeType2, value: "null" }];
      case "Undefined":
        return [JSONValueNode, { nodeType: nodeType2, value: "undefined" }];
      case "Function":
      case "AsyncFunction":
      case "AsyncGeneratorFunction":
      case "GeneratorFunction":
        return [JSONFunctionNode];
      case "Symbol":
        return [JSONValueNode, { nodeType: nodeType2, value: value2.toString() }];
      case "BigInt":
        return [JSONValueNode, { nodeType: nodeType2, value: String(value2) + "n" }];
      case "ArrayBuffer":
        return [
          JSONValueNode,
          {
            nodeType: nodeType2,
            value: `ArrayBuffer(${value2.byteLength})`
          }
        ];
      case "BigInt64Array":
      case "BigUint64Array":
      case "Float32Array":
      case "Float64Array":
      case "Int8Array":
      case "Int16Array":
      case "Int32Array":
      case "Uint8Array":
      case "Uint8ClampedArray":
      case "Uint16Array":
      case "Uint32Array":
        return [TypedArrayNode, { nodeType: nodeType2 }];
      case "RegExp":
        return [RegExpNode];
      default:
        return [JSONValueNode, { nodeType: nodeType2, value: `<${nodeType2}>` }];
    }
  }
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(0, value = $$props2.value);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 1) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(nodeType, $nodeType = objType(value), $nodeType);
    }
    if ($$self.$$.dirty & 17) {
      $$invalidate(2, [componentType, props] = getComponentAndProps($nodeType, value), componentType, ($$invalidate(1, props), $$invalidate(4, $nodeType), $$invalidate(0, value)));
    }
  };
  return [value, props, componentType, nodeType, $nodeType];
}
class JSONNode extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance$1, create_fragment$1, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { value: 0 });
  }
}
function getShouldExpandNode({ defaultExpandedPaths, defaultExpandedLevel }) {
  const defaultExpandedPathsParts = defaultExpandedPaths.map((path) => path.split("."));
  function matchPath(keyPath) {
    outer:
      for (const parts of defaultExpandedPathsParts) {
        if (keyPath.length > parts.length)
          continue;
        const length = Math.min(keyPath.length, parts.length);
        for (let i = 0; i < length; i++) {
          if (parts[i] !== "*" && parts[i] !== String(keyPath[i]))
            continue outer;
        }
        return true;
      }
    return false;
  }
  return function({ keyPath, level }) {
    return level <= defaultExpandedLevel || matchPath(keyPath);
  };
}
function add_css(target) {
  (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append_styles)(target, "svelte-16cw61f", "ul.svelte-16cw61f{--string-color:var(--json-tree-string-color, #cb3f41);--symbol-color:var(--json-tree-symbol-color, #cb3f41);--boolean-color:var(--json-tree-boolean-color, #112aa7);--function-color:var(--json-tree-function-color, #112aa7);--number-color:var(--json-tree-number-color, #3029cf);--label-color:var(--json-tree-label-color, #871d8f);--property-color:var(--json-tree-property-color, #000000);--arrow-color:var(--json-tree-arrow-color, #727272);--operator-color:var(--json-tree-operator-color, #727272);--null-color:var(--json-tree-null-color, #8d8d8d);--undefined-color:var(--json-tree-undefined-color, #8d8d8d);--date-color:var(--json-tree-date-color, #8d8d8d);--internal-color:var(--json-tree-internal-color, grey);--regex-color:var(--json-tree-regex-color, var(--string-color));--li-identation:var(--json-tree-li-indentation, 1em);--li-line-height:var(--json-tree-li-line-height, 1.3);font-size:var(--json-tree-font-size, 12px);font-family:var(--json-tree-font-family, 'Courier New', Courier, monospace)}ul.svelte-16cw61f li{line-height:var(--li-line-height);display:var(--li-display, list-item);list-style:none}ul.svelte-16cw61f,ul.svelte-16cw61f ul{padding:0;margin:0}ul.svelte-16cw61f{margin-left:var(--li-identation)}ul.svelte-16cw61f{cursor:default}ul.svelte-16cw61f .label{color:var(--label-color)}ul.svelte-16cw61f .property{color:var(--property-color)}ul.svelte-16cw61f .internal{color:var(--internal-color)}ul.svelte-16cw61f .operator{color:var(--operator-color)}");
}
function create_default_slot(ctx) {
  let jsonnode;
  let current;
  jsonnode = new JSONNode({ props: { value: ctx[0] } });
  return {
    c() {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(jsonnode.$$.fragment);
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(jsonnode, target, anchor);
      current = true;
    },
    p(ctx2, dirty) {
      const jsonnode_changes = {};
      if (dirty & 1)
        jsonnode_changes.value = ctx2[0];
      jsonnode.$set(jsonnode_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(jsonnode.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(jsonnode.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(jsonnode, detaching);
    }
  };
}
function create_fragment(ctx) {
  let ul;
  let expandable;
  let current;
  expandable = new Expandable({
    props: {
      key: "$",
      expanded: ctx[1],
      $$slots: { default: [create_default_slot] },
      $$scope: { ctx }
    }
  });
  return {
    c() {
      ul = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("ul");
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(expandable.$$.fragment);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(ul, "class", "svelte-16cw61f");
    },
    m(target, anchor) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, ul, anchor);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(expandable, ul, null);
      current = true;
    },
    p(ctx2, [dirty]) {
      const expandable_changes = {};
      if (dirty & 33) {
        expandable_changes.$$scope = { dirty, ctx: ctx2 };
      }
      expandable.$set(expandable_changes);
    },
    i(local) {
      if (current)
        return;
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(expandable.$$.fragment, local);
      current = true;
    },
    o(local) {
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(expandable.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(ul);
      (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(expandable);
    }
  };
}
function instance($$self, $$props, $$invalidate) {
  let shouldExpandNode;
  let { value } = $$props;
  let { defaultExpandedPaths = [] } = $$props;
  let { defaultExpandedLevel = 0 } = $$props;
  const expanded = (0,svelte_store__WEBPACK_IMPORTED_MODULE_2__.writable)(true);
  useState({
    expanded,
    isParentExpanded: (0,svelte_store__WEBPACK_IMPORTED_MODULE_2__.readable)(true),
    root: true,
    shouldExpandNode: (opts) => shouldExpandNode(opts),
    level: 0,
    keyPath: []
  });
  $$self.$$set = ($$props2) => {
    if ("value" in $$props2)
      $$invalidate(0, value = $$props2.value);
    if ("defaultExpandedPaths" in $$props2)
      $$invalidate(2, defaultExpandedPaths = $$props2.defaultExpandedPaths);
    if ("defaultExpandedLevel" in $$props2)
      $$invalidate(3, defaultExpandedLevel = $$props2.defaultExpandedLevel);
  };
  $$self.$$.update = () => {
    if ($$self.$$.dirty & 12) {
      shouldExpandNode = getShouldExpandNode({
        defaultExpandedPaths,
        defaultExpandedLevel
      });
    }
  };
  return [value, expanded, defaultExpandedPaths, defaultExpandedLevel];
}
class Root extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
  constructor(options) {
    super();
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
      value: 0,
      defaultExpandedPaths: 2,
      defaultExpandedLevel: 3
    }, add_css);
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

/***/ "./js/helpers/table.ts":
/*!*****************************!*\
  !*** ./js/helpers/table.ts ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getIdFromTableRow": () => (/* binding */ getIdFromTableRow),
/* harmony export */   "getIdFromString": () => (/* binding */ getIdFromString),
/* harmony export */   "getRowCellByName": () => (/* binding */ getRowCellByName),
/* harmony export */   "resolveTableBySelector": () => (/* binding */ resolveTableBySelector)
/* harmony export */ });
/* harmony import */ var _global__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./global */ "./js/helpers/global.ts");

const getIdFromTableRow = (row) => {
    var _a, _b;
    if (row.classList.contains('no-items')) {
        return 0;
    }
    let item_id = getIdFromString(row.id);
    if (!item_id) {
        let input = row.querySelector('.check-column input[type=checkbox]');
        if (input) {
            item_id = getIdFromString(input.id);
        }
    }
    // Try to get the ID from the edit URL (MS Sites)
    if (!item_id) {
        let link = (_a = row.parentElement) === null || _a === void 0 ? void 0 : _a.querySelector('.edit a');
        if (!!link) {
            let href = link.getAttribute('href');
            if (href) {
                item_id = parseInt((_b = (0,_global__WEBPACK_IMPORTED_MODULE_0__.getParamFromUrl)('id', href)) !== null && _b !== void 0 ? _b : '');
            }
        }
    }
    row.dataset.id = item_id.toString();
    return item_id;
};
const getIdFromString = (value) => {
    let id_parts = value.split(/[_,\-]+/);
    return parseInt(id_parts[id_parts.length - 1]);
};
const getRowCellByName = (row, column_name) => {
    return row.querySelector(`td.column-${column_name}`);
};
const resolveTableBySelector = (selector) => {
    let table = document.querySelector(selector);
    if (!table) {
        return null;
    }
    if (table.tagName === 'TABLE') {
        return table;
    }
    if (table.tagName === 'TBODY') {
        return table.closest('table');
    }
    if (table.querySelector('table.wp-list-table')) {
        return table.querySelector('table.wp-list-table');
    }
    return null;
};


/***/ }),

/***/ "./js/helpers/translations.ts":
/*!************************************!*\
  !*** ./js/helpers/translations.ts ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getTableTranslation": () => (/* binding */ getTableTranslation)
/* harmony export */ });
const getTableTranslation = () => {
    return AC_I18N;
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
        this.noClick = typeof element.dataset.noclick !== 'undefined';
        this.waitingForClose = false;
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
            console.log('leave', this.noClick);
            this.checkClose();
        });
        this.element.addEventListener('click', () => {
            if (this.noClick) {
                return;
            }
            this.waitingForClose = true;
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
            if (!this.waitingForClose) {
                this.closeHandler();
            }
        }, 50);
    }
    closeHandler() {
        if (this.onScreen) {
            document.body.removeChild(this.container);
            this.onScreen = false;
            this.waitingForClose = false;
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
                closeHandler: () => this.closeHandler(),
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
        this.events.emit(`Service.Registered.${name}`, service);
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

/***/ "./js/modules/json-viewer.ts":
/*!***********************************!*\
  !*** ./js/modules/json-viewer.ts ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ JsonViewer)
/* harmony export */ });
/* harmony import */ var _components_JsonValue_svelte__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/JsonValue.svelte */ "./js/components/JsonValue.svelte");
// @ts-ignore

class JsonViewer {
    constructor(element) {
        var _a;
        this.element = element;
        this.value = (_a = element.dataset.json) !== null && _a !== void 0 ? _a : '';
        this.level = element.dataset.level ? parseInt(element.dataset.level) : 0;
        this.initEventHandlers();
    }
    initEventHandlers() {
        let element = document.createElement('div');
        this.element.innerText = '';
        this.element.append(element);
        this.component = new _components_JsonValue_svelte__WEBPACK_IMPORTED_MODULE_0__["default"]({
            target: element,
            props: {
                value: JSON.parse(this.value),
                level: this.level
            }
        });
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

/***/ "./js/modules/service-container.ts":
/*!*****************************************!*\
  !*** ./js/modules/service-container.ts ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ ServiceContainer)
/* harmony export */ });
class ServiceContainer {
    constructor() {
        this.services = {};
    }
    setService(name, service) {
        this.services[name] = service;
    }
    getService(name) {
        return this.hasService(name) ? this.services[name] : null;
    }
    hasService(name) {
        return this.services.hasOwnProperty(name);
    }
}


/***/ }),

/***/ "./js/modules/toggle-box-link.ts":
/*!***************************************!*\
  !*** ./js/modules/toggle-box-link.ts ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ ToggleBoxLink)
/* harmony export */ });
/* harmony import */ var _helpers_elements__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/elements */ "./js/helpers/elements.ts");
/* harmony import */ var _plugin_tooltip__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../plugin/tooltip */ "./js/plugin/tooltip.ts");


const $ = __webpack_require__(/*! jquery */ "jquery");
class ToggleBoxLink {
    constructor(element) {
        var _a, _b;
        this.element = element;
        this.element = element;
        this.initEvents();
        this.contentBox = (_b = (_a = element === null || element === void 0 ? void 0 : element.parentElement) === null || _a === void 0 ? void 0 : _a.querySelector('.ac-toggle-box-contents')) !== null && _b !== void 0 ? _b : null;
        if (!this.contentBox) {
            this.createContentBox();
        }
    }
    isAjax() {
        var _a;
        return parseInt((_a = this.element.dataset.ajaxPopulate) !== null && _a !== void 0 ? _a : '') === 1;
    }
    isInited() {
        return this.element.dataset.toggleBoxInit;
    }
    createContentBox() {
        let contentBox = document.createElement('div');
        contentBox.classList.add('ac-toggle-box-contents');
        (0,_helpers_elements__WEBPACK_IMPORTED_MODULE_0__.insertAfter)(contentBox, this.element);
        this.contentBox = contentBox;
        return this.contentBox;
    }
    initEvents() {
        if (this.isInited()) {
            return;
        }
        this.element.addEventListener('click', (e) => {
            e.preventDefault();
            if (this.isAjax() && !this.hasContent()) {
                this.manageAjaxValue();
            }
            this.toggleContentBox();
        });
        this.element.dataset.toggleBoxInit = 'true';
    }
    hasContent() {
        return this.getContentBox().innerHTML.length > 0;
    }
    setContent(content) {
        this.getContentBox().innerHTML = content;
    }
    getContentBox() {
        if (!this.contentBox) {
            return this.createContentBox();
        }
        return this.contentBox;
    }
    setLabel(open) {
        let label = this.element.dataset.label;
        if (open && this.element.dataset.labelClose) {
            label = this.element.dataset.labelClose;
        }
        this.element.innerHTML = label + '<span class="spinner"></span>';
        (0,_plugin_tooltip__WEBPACK_IMPORTED_MODULE_1__.initAcTooltips)();
    }
    toggleContentBox() {
        if (this.getContentBox().classList.contains('-open')) {
            this.getContentBox().classList.remove('-open');
            this.setLabel(false);
        }
        else {
            this.getContentBox().classList.add('-open');
            this.setLabel(true);
        }
    }
    manageAjaxValue() {
        this.element.classList.add('loading');
        this.retrieveAjaxValue().done((response) => {
            var _a;
            this.setContent(response);
            $(this.element.parentElement).trigger('ajax_column_value_ready');
            (_a = AC_SERVICES.getService('Tooltips')) === null || _a === void 0 ? void 0 : _a.init();
        }).always(() => {
            this.element.classList.remove('loading');
        });
    }
    retrieveAjaxValue() {
        return $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'ac_get_column_value',
                list_screen: AC.list_screen,
                layout: AC.layout,
                column: this.element.dataset.column,
                pk: this.element.dataset.itemId,
                _ajax_nonce: AC.ajax_nonce
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

/***/ "./js/modules/value-modals.ts":
/*!************************************!*\
  !*** ./js/modules/value-modals.ts ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ ValueModals)
/* harmony export */ });
/* harmony import */ var _components_ValueModal_svelte__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/ValueModal.svelte */ "./js/components/ValueModal.svelte");
// @ts-ignore

class ValueModals {
    constructor(links) {
        this.links = links;
        this.component = null;
        this.initEventHandlers();
    }
    initEventHandlers() {
        this.links.forEach(item => {
            item.element.addEventListener('click', (e) => {
                e.preventDefault();
                this.displayModal(item.objectId);
            });
        });
    }
    destroyComponent() {
        if (this.component !== null) {
            this.component.$destroy();
        }
    }
    displayModal(id) {
        let element = document.createElement('div');
        document.body.appendChild(element);
        this.component = new _components_ValueModal_svelte__WEBPACK_IMPORTED_MODULE_0__["default"]({
            target: element,
            props: {
                items: this.links,
                objectId: id,
                destroyHandler: () => this.destroyComponent()
            }
        });
    }
}


/***/ }),

/***/ "./js/plugin/show-more.ts":
/*!********************************!*\
  !*** ./js/plugin/show-more.ts ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "auto_init_show_more": () => (/* binding */ auto_init_show_more),
/* harmony export */   "default": () => (/* binding */ ShowMore)
/* harmony export */ });
const auto_init_show_more = () => {
    document.querySelectorAll('.ac-show-more').forEach((el) => {
        new ShowMore(el);
    });
};
class ShowMore {
    constructor(element) {
        this.element = element;
        this.initEvents();
    }
    initEvents() {
        var _a;
        if (this.isInited()) {
            return;
        }
        if (this.getToggleElement()) {
            (_a = this.getToggleElement()) === null || _a === void 0 ? void 0 : _a.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();
                this.toggle();
            });
        }
        this.element.dataset.showMoreInit = 'true';
    }
    getToggleElement() {
        return this.element.querySelector('.ac-show-more__toggle');
    }
    isInited() {
        return this.element.dataset.showMoreInit === 'true';
    }
    toggle() {
        if (this.element.classList.contains('-on')) {
            this.hide();
        }
        else {
            this.show();
        }
    }
    show() {
        var _a, _b, _c;
        this.element.classList.add('-on');
        this.setToggleText((_c = (_b = (_a = this.getToggleElement()) === null || _a === void 0 ? void 0 : _a.dataset) === null || _b === void 0 ? void 0 : _b.less) !== null && _c !== void 0 ? _c : '');
    }
    hide() {
        var _a, _b, _c;
        this.element.classList.remove('-on');
        this.setToggleText((_c = (_b = (_a = this.getToggleElement()) === null || _a === void 0 ? void 0 : _a.dataset) === null || _b === void 0 ? void 0 : _b.more) !== null && _c !== void 0 ? _c : '');
    }
    setToggleText(text) {
        let toggle = this.getToggleElement();
        if (toggle) {
            toggle.innerHTML = text;
        }
    }
}


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

/***/ "./js/table/actions.ts":
/*!*****************************!*\
  !*** ./js/table/actions.ts ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Actions)
/* harmony export */ });
/* harmony import */ var _helpers_elements__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/elements */ "./js/helpers/elements.ts");

var nanobus = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
class Actions {
    constructor(element) {
        this.container = element;
        this.events = nanobus();
        this.init();
    }
    init() {
        this.container.addEventListener('update', () => {
            this.refresh();
        });
        let reference = document.querySelectorAll('.tablenav.top .actions');
        if (reference && reference.length) {
            (0,_helpers_elements__WEBPACK_IMPORTED_MODULE_0__.insertAfter)(this.container, reference[reference.length - 1]);
            this.container.classList.add('-init');
            this.container.dispatchEvent(new CustomEvent('update'));
        }
    }
    getElement() {
        return this.container;
    }
    refresh() {
        this.container.querySelectorAll('.ac-table-actions-buttons > a').forEach((element) => {
            element.classList.remove('last');
        });
        let buttons = [].slice.call(this.container.querySelectorAll('.ac-table-actions-buttons > a'), 0);
        buttons.reverse();
        for (var i = 0; i < buttons.length; i++) {
            if (buttons[i].offsetParent) {
                buttons[i].classList.add('last');
                break;
            }
        }
    }
}


/***/ }),

/***/ "./js/table/cell.ts":
/*!**************************!*\
  !*** ./js/table/cell.ts ***!
  \**************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Cell)
/* harmony export */ });
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nanobus */ "./node_modules/nanobus/index.js");
/* harmony import */ var nanobus__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(nanobus__WEBPACK_IMPORTED_MODULE_0__);

class Cell {
    constructor(id, name, el) {
        this.object_id = id;
        this.column_name = name;
        this.original_value = el.innerHTML;
        this.el = el;
        this.services = {};
        this.events = new (nanobus__WEBPACK_IMPORTED_MODULE_0___default())();
    }
    getOriginalValue() {
        return this.original_value;
    }
    getObjectID() {
        return this.object_id;
    }
    getName() {
        return this.column_name;
    }
    getElement() {
        return this.el;
    }
    setElement(element) {
        this.el = element;
    }
    getRow() {
        return this.el.parentElement;
    }
    getSettings() {
        var _a;
        return (_a = AC_SERVICES.getService('Table')) === null || _a === void 0 ? void 0 : _a.Columns.get(this.getName());
    }
    hasChanged(content) {
        return this.original_value !== content;
    }
    setValue(value) {
        let rowActions = this.el.querySelector('.row-actions');
        this.original_value = value;
        this.el.innerHTML = value;
        if (rowActions) {
            this.el.append(rowActions);
        }
        this.original_value = value;
        this.events.emit('setValue', this);
        return this;
    }
    setService(name, service) {
        this.services[name] = service;
    }
    getService(name) {
        return this.hasService(name) ? this.services[name] : null;
    }
    hasService(name) {
        return this.services.hasOwnProperty(name);
    }
}


/***/ }),

/***/ "./js/table/cells.ts":
/*!***************************!*\
  !*** ./js/table/cells.ts ***!
  \***************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Cells)
/* harmony export */ });
class Cells {
    constructor(cells = {}) {
        this.cells = cells;
    }
    add(id, cell) {
        if (!this.cells.hasOwnProperty(id)) {
            this.cells[id] = {};
        }
        this.cells[id][cell.getName()] = cell;
    }
    getByID(id) {
        let result = [];
        if (!this.cells.hasOwnProperty(id.toString())) {
            return result;
        }
        let cells = this.cells[id.toString()];
        Object.keys(cells).forEach(name => result.push(cells[name]));
        return result;
    }
    getAll() {
        let results = [];
        Object.keys(this.cells).forEach(id => {
            let cells = this.cells[id];
            Object.keys(cells).forEach(name => results.push(cells[name]));
        });
        return results;
    }
    getByName(name) {
        let results = [];
        Object.keys(this.cells).forEach(id => {
            let cells = this.cells[id];
            Object.keys(cells).forEach(column_name => {
                if (name === column_name) {
                    results.push(cells[column_name]);
                }
            });
        });
        return results;
    }
    get(id, name) {
        return this.cells.hasOwnProperty(id.toString()) ? this.cells[id][name] : null;
    }
}


/***/ }),

/***/ "./js/table/columns.ts":
/*!*****************************!*\
  !*** ./js/table/columns.ts ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Columns),
/* harmony export */   "ColumnTableSettings": () => (/* binding */ ColumnTableSettings)
/* harmony export */ });
class Columns {
    constructor(table, columns = {}) {
        this.table = table;
        this.columns = columns;
        this.init();
    }
    init() {
        var _a;
        (_a = this.table.querySelector('thead')) === null || _a === void 0 ? void 0 : _a.querySelectorAll('th').forEach(cell => {
            let headerName = cell === null || cell === void 0 ? void 0 : cell.id;
            this.columns[headerName] = new ColumnTableSettings(headerName, AC.column_types[headerName], this.sanitizeLabel(cell));
        });
    }
    getColumns() {
        return this.columns;
    }
    getColumnNames() {
        return Object.keys(this.columns);
    }
    get(column_name) {
        return this.columns.hasOwnProperty(column_name) ? this.columns[column_name] : null;
    }
    sanitizeLabel(header) {
        let link = header.querySelector('a');
        let label = header.innerHTML;
        // If it contains a link, we presume that it is because of sorting
        if (link) {
            let elements = link.getElementsByTagName('span');
            if (elements.length > 0) {
                label = elements[0].innerHTML;
            }
        }
        return label;
    }
}
class ColumnTableSettings {
    constructor(name, type, label) {
        this.name = name;
        this.type = type;
        this.label = label;
        this.services = {};
    }
    setService(name, service) {
        this.services[name] = service;
    }
    getService(name) {
        return this.hasService(name) ? this.services[name] : null;
    }
    hasService(name) {
        return this.services.hasOwnProperty(name);
    }
}


/***/ }),

/***/ "./js/table/functions.ts":
/*!*******************************!*\
  !*** ./js/table/functions.ts ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "init_actions_tooltips": () => (/* binding */ init_actions_tooltips)
/* harmony export */ });
/* harmony import */ var _plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../plugin/tooltip */ "./js/plugin/tooltip.ts");

const init_actions_tooltips = () => {
    document.querySelectorAll('.cpac_use_icons').forEach((el) => {
        var _a;
        (_a = el === null || el === void 0 ? void 0 : el.parentElement) === null || _a === void 0 ? void 0 : _a.querySelectorAll('.row-actions a').forEach((el) => {
            new _plugin_tooltip__WEBPACK_IMPORTED_MODULE_0__.Tooltip(el, el.innerText);
        });
    });
};


/***/ }),

/***/ "./js/table/row-selection.ts":
/*!***********************************!*\
  !*** ./js/table/row-selection.ts ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ RowSelection)
/* harmony export */ });
class RowSelection {
    constructor(table) {
        this.Table = table;
    }
    getIDs() {
        let ids = [];
        let checked = this.Table.getElement().querySelectorAll('tbody th.check-column input[type=checkbox]:checked');
        if (checked.length === 0) {
            return ids;
        }
        for (let i = 0; i < checked.length; i++) {
            ids.push(parseInt(checked[i].value));
        }
        return ids;
    }
    /**
     * Get selected cells for specific column
     */
    getSelectedCells(name) {
        let ids = this.getIDs();
        if (ids.length === 0) {
            return [];
        }
        let cells = [];
        ids.forEach((id) => {
            let cell = this.Table.Cells.get(id, name);
            if (cell) {
                cells.push(cell);
            }
        });
        return cells;
    }
    getCount() {
        return this.getIDs().length;
    }
    isAllSelected() {
        return !!this.Table.getElement().querySelector('thead #cb input:checked');
    }
}


/***/ }),

/***/ "./js/table/screen-options-columns.ts":
/*!********************************************!*\
  !*** ./js/table/screen-options-columns.ts ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ ScreenOptionsColumns)
/* harmony export */ });
/* harmony import */ var _helpers_html_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/html-element */ "./js/helpers/html-element.ts");

class ScreenOptionsColumns {
    constructor(columns) {
        this.columns = columns;
        this.columns.getColumnNames().forEach((column_name) => {
            var _a, _b;
            let column = columns.get(column_name);
            if (column) {
                let input = ScreenOptionsColumns.getInputByName(column.name);
                if (input && ((_b = (_a = input === null || input === void 0 ? void 0 : input.parentElement) === null || _a === void 0 ? void 0 : _a.textContent) === null || _b === void 0 ? void 0 : _b.length) === 0) {
                    input.parentElement.appendChild(_helpers_html_element__WEBPACK_IMPORTED_MODULE_0__["default"].create('span').addHtml(column.label).getElement());
                }
            }
        });
    }
    static getInputByName(name) {
        return document.querySelector(`input[name='${name}-hide']`);
    }
}


/***/ }),

/***/ "./js/table/table.ts":
/*!***************************!*\
  !*** ./js/table/table.ts ***!
  \***************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Table)
/* harmony export */ });
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./actions */ "./js/table/actions.ts");
/* harmony import */ var _cells__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cells */ "./js/table/cells.ts");
/* harmony import */ var _columns__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./columns */ "./js/table/columns.ts");
/* harmony import */ var _cell__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./cell */ "./js/table/cell.ts");
/* harmony import */ var _row_selection__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./row-selection */ "./js/table/row-selection.ts");
/* harmony import */ var _helpers_table__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../helpers/table */ "./js/helpers/table.ts");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../constants */ "./js/constants.ts");
/* harmony import */ var _modules_service_container__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../modules/service-container */ "./js/modules/service-container.ts");








class Table {
    constructor(el, services) {
        this.el = el;
        this.AcServices = services;
        this.Services = new _modules_service_container__WEBPACK_IMPORTED_MODULE_7__["default"]();
        this.Columns = new _columns__WEBPACK_IMPORTED_MODULE_2__["default"](el);
        this.Cells = new _cells__WEBPACK_IMPORTED_MODULE_1__["default"]();
        let actionsElement = document.getElementById('ac-table-actions');
        this.Actions = actionsElement ? new _actions__WEBPACK_IMPORTED_MODULE_0__["default"](actionsElement) : null;
        this.Selection = new _row_selection__WEBPACK_IMPORTED_MODULE_4__["default"](this);
    }
    getElement() {
        return this.el;
    }
    getIdsFromTable() {
        let result = [];
        this.el.getElementsByTagName('tbody')[0].querySelectorAll('tr').forEach(row => {
            result.push((0,_helpers_table__WEBPACK_IMPORTED_MODULE_5__.getIdFromTableRow)(row));
        });
        result = result.filter(id => id !== 0);
        return result;
    }
    init() {
        this.initTable();
        this.addCellClasses();
        document.dispatchEvent(new CustomEvent('AC_Table_Ready', { detail: { table: this } }));
        this.AcServices.emitEvent(_constants__WEBPACK_IMPORTED_MODULE_6__.EventConstants.TABLE.READY, { table: this });
        return this;
    }
    addCellClasses() {
        this.Columns.getColumnNames().forEach((name) => {
            var _a;
            let type = (_a = this.Columns.get(name)) === null || _a === void 0 ? void 0 : _a.type;
            let cells = this.Cells.getByName(name);
            if (type) {
                cells.forEach((cell) => {
                    cell.getElement().classList.add(type !== null && type !== void 0 ? type : '');
                });
            }
        });
    }
    initTable() {
        this.el.getElementsByTagName('tbody')[0].querySelectorAll('tr').forEach(row => {
            this.updateRow(row);
        });
    }
    updateRow(row) {
        let id = (0,_helpers_table__WEBPACK_IMPORTED_MODULE_5__.getIdFromTableRow)(row);
        row.dataset.id = id.toString();
        this.setCellsForRow(row);
    }
    setCellsForRow(row) {
        let id = (0,_helpers_table__WEBPACK_IMPORTED_MODULE_5__.getIdFromTableRow)(row);
        this.Columns.getColumnNames().forEach((name) => {
            let selector = name.replace(/\./g, '\\.');
            let td = row.querySelector("td.column-" + selector);
            if (td) {
                let cell = new _cell__WEBPACK_IMPORTED_MODULE_3__["default"](id, name, td);
                this.Cells.add(id, cell);
            }
        });
    }
}


/***/ }),

/***/ "./js/types/admin-columns.ts":
/*!***********************************!*\
  !*** ./js/types/admin-columns.ts ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);



/***/ }),

/***/ "./js/types/table.ts":
/*!***************************!*\
  !*** ./js/types/table.ts ***!
  \***************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);



/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ ((module) => {

"use strict";
module.exports = jQuery;

/***/ }),

/***/ "./node_modules/svelte/index.mjs":
/*!***************************************!*\
  !*** ./node_modules/svelte/index.mjs ***!
  \***************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "SvelteComponent": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.SvelteComponentDev),
/* harmony export */   "SvelteComponentTyped": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.SvelteComponentTyped),
/* harmony export */   "afterUpdate": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.afterUpdate),
/* harmony export */   "beforeUpdate": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.beforeUpdate),
/* harmony export */   "createEventDispatcher": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.createEventDispatcher),
/* harmony export */   "getAllContexts": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.getAllContexts),
/* harmony export */   "getContext": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.getContext),
/* harmony export */   "hasContext": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.hasContext),
/* harmony export */   "onDestroy": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.onDestroy),
/* harmony export */   "onMount": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.onMount),
/* harmony export */   "setContext": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.setContext),
/* harmony export */   "tick": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.tick)
/* harmony export */ });
/* harmony import */ var _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./internal/index.mjs */ "./node_modules/svelte/internal/index.mjs");



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




/***/ }),

/***/ "./node_modules/svelte/store/index.mjs":
/*!*********************************************!*\
  !*** ./node_modules/svelte/store/index.mjs ***!
  \*********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "get": () => (/* reexport safe */ _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.get_store_value),
/* harmony export */   "derived": () => (/* binding */ derived),
/* harmony export */   "readable": () => (/* binding */ readable),
/* harmony export */   "writable": () => (/* binding */ writable)
/* harmony export */ });
/* harmony import */ var _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../internal/index.mjs */ "./node_modules/svelte/internal/index.mjs");



const subscriber_queue = [];
/**
 * Creates a `Readable` store that allows reading by subscription.
 * @param value initial value
 * @param {StartStopNotifier}start start and stop notifications for subscriptions
 */
function readable(value, start) {
    return {
        subscribe: writable(value, start).subscribe
    };
}
/**
 * Create a `Writable` store that allows both updating and reading by subscription.
 * @param {*=}value initial value
 * @param {StartStopNotifier=}start start and stop notifications for subscriptions
 */
function writable(value, start = _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.noop) {
    let stop;
    const subscribers = new Set();
    function set(new_value) {
        if ((0,_internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal)(value, new_value)) {
            value = new_value;
            if (stop) { // store is ready
                const run_queue = !subscriber_queue.length;
                for (const subscriber of subscribers) {
                    subscriber[1]();
                    subscriber_queue.push(subscriber, value);
                }
                if (run_queue) {
                    for (let i = 0; i < subscriber_queue.length; i += 2) {
                        subscriber_queue[i][0](subscriber_queue[i + 1]);
                    }
                    subscriber_queue.length = 0;
                }
            }
        }
    }
    function update(fn) {
        set(fn(value));
    }
    function subscribe(run, invalidate = _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.noop) {
        const subscriber = [run, invalidate];
        subscribers.add(subscriber);
        if (subscribers.size === 1) {
            stop = start(set) || _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.noop;
        }
        run(value);
        return () => {
            subscribers.delete(subscriber);
            if (subscribers.size === 0) {
                stop();
                stop = null;
            }
        };
    }
    return { set, update, subscribe };
}
function derived(stores, fn, initial_value) {
    const single = !Array.isArray(stores);
    const stores_array = single
        ? [stores]
        : stores;
    const auto = fn.length < 2;
    return readable(initial_value, (set) => {
        let inited = false;
        const values = [];
        let pending = 0;
        let cleanup = _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.noop;
        const sync = () => {
            if (pending) {
                return;
            }
            cleanup();
            const result = fn(single ? values[0] : values, set);
            if (auto) {
                set(result);
            }
            else {
                cleanup = (0,_internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.is_function)(result) ? result : _internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.noop;
            }
        };
        const unsubscribers = stores_array.map((store, i) => (0,_internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.subscribe)(store, (value) => {
            values[i] = value;
            pending &= ~(1 << i);
            if (inited) {
                sync();
            }
        }, () => {
            pending |= (1 << i);
        }));
        inited = true;
        sync();
        return function stop() {
            (0,_internal_index_mjs__WEBPACK_IMPORTED_MODULE_0__.run_all)(unsubscribers);
            cleanup();
        };
    });
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
/*!*********************!*\
  !*** ./js/table.ts ***!
  \*********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _table_table__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./table/table */ "./js/table/table.ts");
/* harmony import */ var _modules_tooltips__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/tooltips */ "./js/modules/tooltips.ts");
/* harmony import */ var _table_screen_options_columns__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./table/screen-options-columns */ "./js/table/screen-options-columns.ts");
/* harmony import */ var _modules_toggle_box_link__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modules/toggle-box-link */ "./js/modules/toggle-box-link.ts");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _plugin_show_more__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./plugin/show-more */ "./js/plugin/show-more.ts");
/* harmony import */ var _table_functions__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./table/functions */ "./js/table/functions.ts");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./constants */ "./js/constants.ts");
/* harmony import */ var _helpers_table__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./helpers/table */ "./js/helpers/table.ts");
/* harmony import */ var _helpers_admin_columns__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./helpers/admin-columns */ "./js/helpers/admin-columns.ts");
/* harmony import */ var _modules_modals__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./modules/modals */ "./js/modules/modals.ts");
/* harmony import */ var _modules_ac_pointer__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./modules/ac-pointer */ "./js/modules/ac-pointer.ts");
/* harmony import */ var _modules_value_modals__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./modules/value-modals */ "./js/modules/value-modals.ts");
/* harmony import */ var _plugin_tooltip__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./plugin/tooltip */ "./js/plugin/tooltip.ts");
/* harmony import */ var _modules_json_viewer__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./modules/json-viewer */ "./js/modules/json-viewer.ts");




// @ts-ignore











let AC_SERVICES = (0,_helpers_admin_columns__WEBPACK_IMPORTED_MODULE_9__.initAcServices)();
AC_SERVICES.registerService('Modals', new _modules_modals__WEBPACK_IMPORTED_MODULE_10__["default"]());
AC_SERVICES.registerService('tooltips', _plugin_tooltip__WEBPACK_IMPORTED_MODULE_13__.initAcTooltips);
document.addEventListener('DOMContentLoaded', () => {
    let table = (0,_helpers_table__WEBPACK_IMPORTED_MODULE_8__.resolveTableBySelector)(AC.table_id);
    (0,_modules_ac_pointer__WEBPACK_IMPORTED_MODULE_11__.initPointers)();
    if (table) {
        const TableModule = new _table_table__WEBPACK_IMPORTED_MODULE_0__["default"](table, AC_SERVICES).init();
        AC_SERVICES.registerService('Table', TableModule);
        AC_SERVICES.registerService('ScreenOptionsColumns', new _table_screen_options_columns__WEBPACK_IMPORTED_MODULE_2__["default"](TableModule.Columns));
    }
    AC_SERVICES.registerService('Tooltips', new _modules_tooltips__WEBPACK_IMPORTED_MODULE_1__["default"]());
    document.querySelectorAll('.ac-toggle-box-link').forEach(el => {
        new _modules_toggle_box_link__WEBPACK_IMPORTED_MODULE_3__["default"](el);
    });
    jquery__WEBPACK_IMPORTED_MODULE_4___default()('.wp-list-table').on('updated', 'tr', function () {
        AC_SERVICES.getService('Table').addCellClasses();
        (0,_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__.auto_init_show_more)();
    });
});
AC_SERVICES.addListener(_constants__WEBPACK_IMPORTED_MODULE_7__.EventConstants.TABLE.READY, (event) => {
    (0,_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__.auto_init_show_more)();
    (0,_table_functions__WEBPACK_IMPORTED_MODULE_6__.init_actions_tooltips)();
    let observer = new MutationObserver(mutations => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.tagName === 'TR' && node.classList.contains('iedit')) {
                    jquery__WEBPACK_IMPORTED_MODULE_4___default()(node).trigger('updated', { id: (0,_helpers_table__WEBPACK_IMPORTED_MODULE_8__.getIdFromTableRow)(node), row: node });
                }
            });
        });
    });
    observer.observe(event.table.getElement(), { childList: true, subtree: true });
    event.table.Cells.getAll().forEach(cell => {
        cell.events.addListener('setValue', () => {
            (0,_plugin_show_more__WEBPACK_IMPORTED_MODULE_5__.auto_init_show_more)();
        });
    });
    let items = {};
    event.table.Cells.getAll().forEach(cell => {
        var _a, _b, _c;
        let link = cell.getElement().querySelector('[data-modal-value]');
        if (link) {
            if (!items.hasOwnProperty(cell.getName())) {
                items[cell.getName()] = [];
            }
            items[cell.getName()].push({
                element: link,
                editLink: (_a = link.dataset.modalEditLink) !== null && _a !== void 0 ? _a : '',
                downloadLink: (_b = link.dataset.modalDownloadLink) !== null && _b !== void 0 ? _b : '',
                title: (_c = link.dataset.modalTitle) !== null && _c !== void 0 ? _c : null,
                columnName: cell.getName(),
                objectId: cell.getObjectID()
            });
        }
    });
    Object.keys(items).forEach(i => new _modules_value_modals__WEBPACK_IMPORTED_MODULE_12__["default"](items[i]));
    document.querySelectorAll('[data-component="ac-json"]').forEach(el => {
        new _modules_json_viewer__WEBPACK_IMPORTED_MODULE_14__["default"](el);
    });
});

})();

/******/ })()
;
//# sourceMappingURL=table.js.map