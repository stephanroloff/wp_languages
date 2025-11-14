/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/edit-post":
/*!**********************************!*\
  !*** external ["wp","editPost"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["editPost"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["plugins"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

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
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!***********************!*\
  !*** ./src/editor.js ***!
  \***********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/plugins */ "@wordpress/plugins");
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/edit-post */ "@wordpress/edit-post");
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__);







const editorData = window.wpLanguagesData || {};
const languages = editorData.languages || {};
const allCpt = editorData.allCpt || [];
console.log('WP Languages Editor: Data loaded', {
  languages,
  allCpt,
  editorData
});
if (editorData.nonce && !window.wpLanguagesNonceApplied) {
  _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default().use(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default().createNonceMiddleware(editorData.nonce));
  window.wpLanguagesNonceApplied = true;
}
const getContextForPostType = postType => {
  if (!postType || !Array.isArray(allCpt)) {
    return null;
  }
  let foundGroup = null;
  let sourceLang = null;
  allCpt.some(group => {
    if (!group) {
      return false;
    }
    return Object.keys(group).some(langKey => {
      if (group[langKey] === postType) {
        foundGroup = group;
        sourceLang = langKey;
        return true;
      }
      return false;
    });
  });
  if (!foundGroup || !sourceLang) {
    return null;
  }
  const otherLangs = Object.keys(foundGroup).filter(langKey => langKey !== sourceLang);
  return {
    group: foundGroup,
    sourceLang,
    otherLangs
  };
};
const ConnectionsPanel = () => {
  const postId = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => select('core/editor').getCurrentPostId(), []);
  const postType = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => select('core/editor').getCurrentPostType(), []);
  const context = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useMemo)(() => getContextForPostType(postType), [postType]);
  const [selectedLang, setSelectedLang] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)('');
  const [connections, setConnections] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)({});
  const [connectionsLoading, setConnectionsLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false);
  const [availablePosts, setAvailablePosts] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)([]);
  const [availableLoading, setAvailableLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false);
  const [actionLoading, setActionLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false);
  const [error, setError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(null);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(() => {
    if (!context) {
      setSelectedLang('');
      setConnections({});
      setAvailablePosts([]);
      return;
    }
    if (context.otherLangs.includes(selectedLang)) {
      return;
    }
    setSelectedLang(context.otherLangs.length ? context.otherLangs[0] : '');
  }, [context]);
  const loadConnections = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useCallback)(() => {
    if (!postId || !context) {
      setConnections({});
      return;
    }
    setConnectionsLoading(true);
    setError(null);
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path: `/wp-languages/v1/connections?post_id=${postId}`
    }).then(response => {
      setConnections(response?.connections || {});
    }).catch(err => {
      const message = err?.message || err?.data?.message || 'Error al cargar las conexiones.';
      setError(message);
    }).finally(() => {
      setConnectionsLoading(false);
    });
  }, [postId, context]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(() => {
    if (!postId || !context) {
      return;
    }
    loadConnections();
  }, [postId, context, loadConnections]);
  const loadAvailable = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useCallback)(langKey => {
    if (!postId || !context || !langKey) {
      setAvailablePosts([]);
      return;
    }
    setAvailableLoading(true);
    setError(null);
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path: `/wp-languages/v1/available?post_id=${postId}&target_lang=${langKey}`
    }).then(response => {
      setAvailablePosts(response?.posts || []);
    }).catch(err => {
      const message = err?.message || err?.data?.message || 'Error al cargar los posts disponibles.';
      setError(message);
    }).finally(() => {
      setAvailableLoading(false);
    });
  }, [postId, context]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(() => {
    if (!selectedLang) {
      setAvailablePosts([]);
      return;
    }
    loadAvailable(selectedLang);
  }, [selectedLang, loadAvailable]);
  const handleLanguageChange = value => {
    setSelectedLang(value);
    setAvailablePosts([]);
  };
  const handlePostChange = value => {
    if (!selectedLang || !postId) {
      return;
    }
    const targetId = value ? parseInt(value, 10) : 0;
    setActionLoading(true);
    setError(null);
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_5___default()({
      path: '/wp-languages/v1/connect',
      method: 'POST',
      data: {
        post_id: postId,
        target_lang: selectedLang,
        target_post_id: targetId
      }
    }).then(response => {
      setConnections(response?.connections || {});
      loadAvailable(selectedLang);
    }).catch(err => {
      const message = err?.message || err?.data?.message || 'Error al guardar la conexión.';
      setError(message);
    }).finally(() => {
      setActionLoading(false);
    });
  };
  if (!context) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__.PluginDocumentSettingPanel, {
      name: "wp-languages-connections",
      title: "Conexiones de idiomas",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("p", {
        children: "Este tipo de contenido no admite conexiones entre idiomas."
      })
    });
  }
  if (!context.otherLangs.length) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__.PluginDocumentSettingPanel, {
      name: "wp-languages-connections",
      title: "Conexiones de idiomas",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("p", {
        children: "No hay otros idiomas configurados para este tipo de contenido."
      })
    });
  }
  const languageOptions = context.otherLangs.map(langKey => ({
    value: langKey,
    label: languages[langKey] || langKey
  }));
  const postsOptions = [{
    value: '',
    label: availableLoading ? 'Cargando opciones…' : '— Sin conexión —'
  }, ...availablePosts.map(item => ({
    value: String(item.id),
    label: item.title ? `${item.title}${item.status && item.status !== 'publish' ? ` (${item.status})` : ''}` : `(ID ${item.id})`
  }))];
  const selectedPostId = selectedLang && connections[selectedLang]?.id ? String(connections[selectedLang].id) : '';
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__.PluginDocumentSettingPanel, {
    name: "wp-languages-connections",
    title: "Conexiones de idiomas",
    children: [error && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Notice, {
      status: "error",
      onRemove: () => setError(null),
      isDismissible: true,
      children: error
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
      label: "Idioma destino",
      value: selectedLang,
      onChange: handleLanguageChange,
      options: [{
        value: '',
        label: 'Selecciona un idioma'
      }, ...languageOptions]
    }), connectionsLoading && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Spinner, {}), selectedLang ? availableLoading ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Spinner, {}) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
        label: "Post conectado",
        value: selectedPostId,
        onChange: handlePostChange,
        options: postsOptions,
        help: "Solo se muestran los posts que a\xFAn no est\xE1n emparejados.",
        disabled: actionLoading
      }), !availablePosts.length && !selectedPostId && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("p", {
        children: "No hay posts disponibles para este idioma."
      })]
    }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("p", {
      children: "Elige primero un idioma para ver los posts disponibles."
    })]
  });
};
(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_0__.registerPlugin)('wp-languages-connections', {
  render: ConnectionsPanel,
  icon: 'translation'
});
console.log('WP Languages Editor: Plugin registered');
})();

/******/ })()
;
//# sourceMappingURL=editor.js.map