/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./includes/assets/js/src/blocks.js":
/*!******************************************!*\
  !*** ./includes/assets/js/src/blocks.js ***!
  \******************************************/
/***/ (() => {

eval("(function (blocks, editor, i18n, element, components, _) {\n  var el = element.createElement;\n  var RichText = editor.RichText;\n  var InspectorControls = editor.InspectorControls;\n  var ColorPalette = editor.ColorPalette;\n  var TextControl = components.TextControl;\n  blocks.registerBlockType('noptin/email-optin', {\n    title: i18n.__('Newsletter Optin', 'newsletter-optin-box'),\n    icon: 'forms',\n    category: 'layout',\n    attributes: {\n      title: {\n        type: 'string',\n        source: 'children',\n        selector: 'h2',\n        \"default\": i18n.__('JOIN OUR NEWSLETTER', 'newsletter-optin-box')\n      },\n      description: {\n        type: 'string',\n        source: 'children',\n        \"default\": i18n.__('Click the above title to edit it. You can also edit this section by clicking on it.', 'newsletter-optin-box'),\n        selector: '.noptin_form_description'\n      },\n      button: {\n        type: 'string',\n        \"default\": 'SUBSCRIBE'\n      },\n      bg_color: {\n        type: 'string',\n        \"default\": '#eeeeee'\n      },\n      title_color: {\n        type: 'string',\n        \"default\": '#313131'\n      },\n      text_color: {\n        type: 'string',\n        \"default\": '#32373c'\n      },\n      button_color: {\n        type: 'string',\n        \"default\": '#313131'\n      },\n      button_text_color: {\n        type: 'string',\n        \"default\": '#fafafa'\n      }\n    },\n    edit: function edit(props) {\n      var attributes = props.attributes;\n      return [el(InspectorControls, {\n        key: 'controls'\n      }, el(components.PanelBody, {\n        'title': i18n.__('Button Text', 'newsletter-optin-box')\n      }, el(TextControl, {\n        value: attributes.button,\n        type: 'text',\n        onChange: function onChange(value) {\n          props.setAttributes({\n            button: value\n          });\n        }\n      })), //Redirect url\n      el(components.PanelBody, {\n        'title': i18n.__('Redirect Url', 'newsletter-optin-box'),\n        initialOpen: false\n      }, el('h2', null, i18n.__('Redirect Url', 'newsletter-optin-box')), el('p', null, i18n.__('Optional. Where should we redirect users after they have successfully signed up?', 'newsletter-optin-box')), el(TextControl, {\n        value: attributes.redirect,\n        placeholder: 'http://example.com/download/gift.pdf',\n        type: 'url',\n        onChange: function onChange(value) {\n          props.setAttributes({\n            redirect: value\n          });\n        }\n      })), //Background color\n      el(components.PanelBody, {\n        'title': i18n.__('Background Color', 'newsletter-optin-box'),\n        initialOpen: false\n      }, el(components.PanelRow, null, el(ColorPalette, {\n        onChange: function onChange(value) {\n          props.setAttributes({\n            bg_color: value\n          });\n        }\n      }))), //Title color\n      el(components.PanelBody, {\n        'title': i18n.__('Title Color', 'newsletter-optin-box'),\n        initialOpen: false\n      }, el(components.PanelRow, null, el(ColorPalette, {\n        onChange: function onChange(value) {\n          props.setAttributes({\n            title_color: value\n          });\n        }\n      }))), //Text color\n      el(components.PanelBody, {\n        'title': i18n.__('Description Color', 'newsletter-optin-box'),\n        initialOpen: false\n      }, el(components.PanelRow, null, el(ColorPalette, {\n        onChange: function onChange(value) {\n          props.setAttributes({\n            text_color: value\n          });\n        }\n      }))), //Button\n      el(components.PanelBody, {\n        'title': i18n.__('Button Color', 'nnewsletter-optin-boxoptin'),\n        initialOpen: false\n      }, //Color\n      el('p', null, i18n.__('Text Color', 'newsletter-optin-box')), el(ColorPalette, {\n        onChange: function onChange(value) {\n          props.setAttributes({\n            button_text_color: value\n          });\n        }\n      }), //Background color\n      el('p', null, i18n.__('Background Color', 'newsletter-optin-box')), el(ColorPalette, {\n        onChange: function onChange(value) {\n          props.setAttributes({\n            button_color: value\n          });\n        }\n      }))), el('div', {\n        className: props.className,\n        style: {\n          backgroundColor: attributes.bg_color,\n          padding: '20px',\n          color: attributes.text_color\n        }\n      }, el('form', {}, el(RichText, {\n        tagName: 'h2',\n        inline: true,\n        style: {\n          color: attributes.title_color,\n          textAlign: 'center'\n        },\n        placeholder: i18n.__('Write Form title…', 'newsletter-optin-box'),\n        value: attributes.title,\n        className: 'noptin_form_title',\n        onChange: function onChange(value) {\n          props.setAttributes({\n            title: value\n          });\n        }\n      }), el(RichText, {\n        tagName: 'p',\n        inline: true,\n        style: {\n          textAlign: 'center'\n        },\n        placeholder: i18n.__('Write Form Description', 'newsletter-optin-box'),\n        value: attributes.description,\n        className: 'noptin_form_description',\n        onChange: function onChange(value) {\n          props.setAttributes({\n            description: value\n          });\n        }\n      }), el('input', {\n        type: 'email',\n        className: 'noptin_form_input_email',\n        placeholder: 'Email Address',\n        required: true\n      }), el('input', {\n        value: attributes.button,\n        type: 'submit',\n        style: {\n          backgroundColor: attributes.button_color,\n          color: attributes.button_text_color\n        },\n        className: 'noptin_form_submit'\n      }), el('div', {\n        style: {\n          border: '1px solid rgba(6, 147, 227, 0.8)',\n          display: 'none',\n          padding: '10px',\n          marginTop: '10px'\n        },\n        className: 'noptin_feedback_success'\n      }), el('div', {\n        style: {\n          border: '1px solid rgba(227, 6, 37, 0.8)',\n          display: 'none',\n          padding: '10px',\n          marginTop: '10px'\n        },\n        className: 'noptin_feedback_error'\n      })))];\n    },\n    save: function save(props) {\n      var attributes = props.attributes;\n      return el('div', {\n        className: props.className,\n        style: {\n          backgroundColor: attributes.bg_color,\n          padding: '20px',\n          color: attributes.text_color\n        }\n      }, el('form', {}, el(RichText.Content, {\n        tagName: 'h2',\n        inline: true,\n        style: {\n          color: attributes.title_color,\n          textAlign: 'center'\n        },\n        value: attributes.title,\n        className: 'noptin_form_title'\n      }), el(RichText.Content, {\n        tagName: 'p',\n        inline: true,\n        style: {\n          textAlign: 'center'\n        },\n        value: attributes.description,\n        className: 'noptin_form_description'\n      }), el('input', {\n        type: 'email',\n        className: 'noptin_form_input_email',\n        placeholder: 'Email Address',\n        required: true\n      }), el('input', {\n        value: attributes.button,\n        type: 'submit',\n        style: {\n          backgroundColor: attributes.button_color,\n          color: attributes.button_text_color\n        },\n        className: 'noptin_form_submit'\n      }), el('input', {\n        value: attributes.redirect,\n        type: 'hidden',\n        className: 'noptin_form_redirect'\n      }), el('div', {\n        style: {\n          border: '1px solid rgba(6, 147, 227, 0.8)',\n          display: 'none',\n          padding: '10px',\n          marginTop: '10px'\n        },\n        className: 'noptin_feedback_success'\n      }), el('div', {\n        style: {\n          border: '1px solid rgba(227, 6, 37, 0.8)',\n          display: 'none',\n          padding: '10px',\n          marginTop: '10px'\n        },\n        className: 'noptin_feedback_error'\n      })));\n    }\n  });\n})(window.wp.blocks, window.wp.editor, window.wp.i18n, window.wp.element, window.wp.components, window._);\n\n//# sourceURL=webpack://noptin/./includes/assets/js/src/blocks.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./includes/assets/js/src/blocks.js"]();
/******/ 	
/******/ })()
;