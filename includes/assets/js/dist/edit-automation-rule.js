(()=>{"use strict";var e={};function t(e){return t="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},t(e)}function n(e,n,r){return(n=function(e){var n=function(e,n){if("object"!==t(e)||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var o=r.call(e,"string");if("object"!==t(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return String(e)}(e);return"symbol"===t(n)?n:String(n)}(n))in e?Object.defineProperty(e,n,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[n]=r,e}e.n=t=>{var n=t&&t.__esModule?()=>t.default:()=>t;return e.d(n,{a:n}),n},e.d=(t,n)=>{for(var r in n)e.o(n,r)&&!e.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:n[r]})},e.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t);const r=window.wp.element,o=window.wp.domReady;var a=e.n(o);function l(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function i(e,t){if(e){if("string"==typeof e)return l(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?l(e,t):void 0}}function c(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=n){var r,o,a,l,i=[],c=!0,s=!1;try{if(a=(n=n.call(e)).next,0===t){if(Object(n)!==n)return;c=!1}else for(;!(c=(r=a.call(n)).done)&&(i.push(r.value),i.length!==t);c=!0);}catch(e){s=!0,o=e}finally{try{if(!c&&null!=n.return&&(l=n.return(),Object(l)!==l))return}finally{if(s)throw o}}return i}}(e,t)||i(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}const s=window.wp.components,u=window.wp.apiFetch;var p=e.n(u);const m=window.wp.i18n;function f(e){var t=e.title,n=e.isSecodary,o=e.className,a=e.children,l=c((0,r.useState)(!0),2),i=l[0],u=l[1];return o=o||"",(0,r.createElement)(s.Card,{variant:n?"secondary":"primary",className:"noptin-component__section ".concat(o)},(0,r.createElement)(s.CardHeader,null,(0,r.createElement)(s.Flex,null,(0,r.createElement)(s.FlexBlock,null,(0,r.createElement)("h3",null,t)),(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.Button,{isTertiary:!0,onClick:function(){return u(!i)}},(0,r.createElement)(s.Icon,{icon:i?"arrow-up-alt2":"arrow-down-alt2"}))))),i&&a)}function d(e){var t=e.label,n=e.value,o=t.toLowerCase().replace(/[^a-z0-9]/g,"-");return(0,r.createElement)("li",{className:"noptin-list-item noptin-list-item__".concat(o)},(0,r.createElement)("div",{className:"noptin-list-item__key"},t),(0,r.createElement)("div",{className:"noptin-list-item__value"},n))}function b(e){var t=e.items;return(0,r.createElement)("ul",{className:"noptin-component__list"},t.map((function(e){return(0,r.createElement)(d,{key:e.label,label:e.label,value:e.value})})))}function g(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function y(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?g(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):g(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function v(e){if(!e)return"-";var t=new Date;return"string"==typeof e?t.setTime(Date.parse(e)):t.setTime(Date.parse(e.date)),t.toLocaleString()}function _(e){var t=e.automationRule,n=e.setError,o=e.setSuccess,a=e.setAutomationRule,l=e.isSaving,i=e.setIsSaving,c=t.id>0?"/noptin/v1/automation_rules/".concat(t.id):"/noptin/v1/automation_rules/",u=[{label:(0,m.__)("ID","newsletter-optin-box"),value:t.id?t.id:(0,m.__)("New","newsletter-optin-box")},{label:(0,m.__)("Status","newsletter-optin-box"),value:(0,r.createElement)(s.ToggleControl,{label:t.status?(0,m.__)("Active","newsletter-optin-box"):(0,m.__)("Inactive","newsletter-optin-box"),checked:!!t.status,className:"noptin-mb0",onChange:function(e){a(y(y({},t),{},{status:e}))},__nextHasNoMarginBottom:!0})}];return t.id>0&&(u.push({label:(0,m.__)("Times Run","newsletter-optin-box"),value:t.times_run},{label:(0,m.__)("Created at","newsletter-optin-box"),value:v(t.created_at)},{label:(0,m.__)("Updated at","newsletter-optin-box"),value:v(t.updated_at)}),Array.isArray(t.metadata)||Object.keys(t.metadata).forEach((function(e){"string"!=typeof t.metadata[e]&&"number"!=typeof t.metadata[e]||u.push({label:e,value:t.metadata[e]})}))),(0,r.createElement)(f,{title:(0,m.__)("Automation Rule","newsletter-optin-box")},(0,r.createElement)(s.CardBody,null,(0,r.createElement)(b,{items:u}),(0,r.createElement)(s.Button,{variant:"primary",onClick:function(){i(!0),n(null),o(null),p()({path:c,method:"POST",data:t}).then((function(e){return o((0,m.__)("Automation rule saved.","newsletter-optin-box")),a(y(y({},t),e)),e})).then((function(e){e.id>0&&window.history.replaceState({},"","?page=noptin-automation-rules&noptin_edit_automation_rule=".concat(e.id))})).catch((function(e){e.message?n(e.message):n((0,m.__)("An error occurred while saving.","newsletter-optin-box"))})).finally((function(){i(!1)}))},disabled:l,isPressed:l},!l&&(0,m.__)("Save Automation","newsletter-optin-box"),l&&(0,m.__)("Saving...","newsletter-optin-box"),l&&(0,r.createElement)(s.Spinner,null))))}function h(e){return function(e){if(Array.isArray(e))return l(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||i(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function E(e,t){if(null==e)return{};var n,r,o=function(e,t){if(null==e)return{};var n,r,o={},a=Object.keys(e);for(r=0;r<a.length;r++)n=a[r],t.indexOf(n)>=0||(o[n]=e[n]);return o}(e,t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);for(r=0;r<a.length;r++)n=a[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(o[n]=e[n])}return o}const w=window.wp.primitives,O=(0,r.createElement)(w.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,r.createElement)(w.Path,{d:"M6.6 6L5.4 7l4.5 5-4.5 5 1.1 1 5.5-6-5.4-6zm6 0l-1.1 1 4.5 5-4.5 5 1.1 1 5.5-6-5.5-6z"}));function x(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function j(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?x(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):x(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var S=[{label:(0,m.__)("Only run if","newsletter-optin-box"),value:"allow"},{label:(0,m.__)("Do not run if","newsletter-optin-box"),value:"prevent"}],C=[{label:(0,m.__)("all","newsletter-optin-box"),value:"all"},{label:(0,m.__)("any","newsletter-optin-box"),value:"any"}];function k(e,t){return[{label:t,value:"",disabled:!0}].concat(h(e))}function P(e){var t=e.type,n=e.action,o=e.ruleCount,a=e.setConditionalLogicAttribute,l=o>1;return(0,r.createElement)(s.Flex,{className:"noptin-component__field-lg",wrap:!0},(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.SelectControl,{label:(0,m.__)("If","newsletter-optin-box"),hideLabelFromVision:!0,value:n||"allow",options:S,onChange:function(e){return a("action",e)},size:"default",__nextHasNoMarginBottom:!0})),l&&(0,r.createElement)(r.Fragment,null,(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.SelectControl,{label:(0,m.__)("all","newsletter-optin-box"),hideLabelFromVision:!0,value:t||"all",options:C,onChange:function(e){return a("type",e)},size:"default",__nextHasNoMarginBottom:!0})),(0,r.createElement)(s.FlexBlock,null,(0,m.__)("of the following rules are true:","newsletter-optin-box"))))}function T(e){var t=e.rule,n=e.comparisons,o=e.availableConditionTypes,a=e.updateRule,l=e.removeRule,i=e.conditionType,c=e.isLastRule,u=(e.isFirstRule,(0,r.useMemo)((function(){return e=t.type,o[e]||{};var e}),[o,t.type])),p=(0,r.useMemo)((function(){return e=u.options,t=[],e?Array.isArray(e)?(e.forEach((function(e,n){t.push({label:e,value:n})})),t):(Object.keys(e).forEach((function(n){t.push({label:e[n],value:n})})),t):t;var e,t}),[u]),f=p.length>0,d=(0,r.useMemo)((function(){return u.type?u.type:"string"}),[u]),b=(0,r.useMemo)((function(){var e=[];return Object.keys(n).forEach((function(t){var r=n[t].type;if(f){if("string"===d&&"is"!=t&&"is_not"!=t)return;if("is_empty"===t||"is_not_empty"===t||"is_between"===t)return}"any"!==r&&r!=d||e.push({label:n[t].name,value:t})})),e}),[d]),g="",y=[];Object.keys(o).forEach((function(e){var t=o[e];""===g&&(g=t.type),y.push({label:t.label,value:e})}));var v=function(e,n){a(e,n),"type"!==e&&""===t.type&&a("type",g),"condition"!==e&&""===t.condition&&a("condition","is"),"type"===e&&(a("condition","is"),a("value",""))},_="is_empty"===t.condition||"is_not_empty"===t.condition,h=f&&!_,E=!f&&!_;return(0,r.createElement)(s.Flex,{className:"noptin-component__field-lg",wrap:!0},(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.SelectControl,{label:(0,m.__)("Condition Type","newsletter-optin-box"),hideLabelFromVision:!0,value:t.type?t.type:g,options:k(y,(0,m.__)("Select a condition","newsletter-optin-box")),onChange:function(e){return v("type",e)},size:"default",__nextHasNoMarginBottom:!0})),(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.SelectControl,{label:(0,m.__)("Comparison","newsletter-optin-box"),hideLabelFromVision:!0,value:t.condition?t.condition:"is",options:k(b,(0,m.__)("Select a comparison","newsletter-optin-box")),onChange:function(e){return v("condition",e)},size:"default",__nextHasNoMarginBottom:!0})),(0,r.createElement)(s.FlexItem,null,h&&(0,r.createElement)(s.SelectControl,{label:(0,m.__)("Value","newsletter-optin-box"),hideLabelFromVision:!0,value:t.value?t.value:"",options:k(p,(0,m.__)("Select a value","newsletter-optin-box")),onChange:function(e){return a("value",e)},size:"default",__nextHasNoMarginBottom:!0}),E&&(0,r.createElement)(s.TextControl,{type:"number"===d?"number":"text",label:(0,m.__)("Value","newsletter-optin-box"),hideLabelFromVision:!0,value:t.value?t.value:"",onChange:function(e){return a("value",e)},__nextHasNoMarginBottom:!0})),(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.Button,{onClick:l,icon:"trash"})),(0,r.createElement)(s.FlexBlock,null,!c&&(0,r.createElement)(r.Fragment,null,"any"===i&&(0,m.__)("or","newsletter-optin-box"),"all"===i&&(0,m.__)("and","newsletter-optin-box"))))}function A(e){var t=e.rules,n=e.conditionType,o=e.comparisons,a=e.availableSmartTags,l=e.setConditionalLogicAttribute,i=Array.isArray(t)?t:[],c=(0,r.useMemo)((function(){var e={};return a.forEach((function(t){t.conditional_logic&&(e[t.smart_tag]={key:t.smart_tag,label:t.label,options:t.options,type:t.conditional_logic,placeholder:t.placeholder?t.placeholder:""})})),e}),[a]),u=i.length;return(0,r.createElement)("div",{className:"noptin-conditional-logic-rules"},i.map((function(e,t){return(0,r.createElement)(T,{key:t,rule:e,updateRule:function(e,n){return function(e,t,n){var r=h(i);r[e][t]=n,l("rules",r)}(t,e,n)},removeRule:function(){return function(e){var t=h(i);t.splice(e,1),l("rules",t)}(t)},availableConditionTypes:c,isLastRule:t===u-1,isFirstRule:0===t,conditionType:n,comparisons:o})})),(0,r.createElement)(s.Button,{className:"noptin-add-conditional-rule",onClick:function(){var e=Object.keys(c)[0],t=c[e].options,n=c[e].placeholder?c[e].placeholder:"",r=Array.isArray(t)&&t.length?Object.keys(t)[0]:n,o=h(i);o.push({type:e,condition:"is",value:r}),l("rules",o)},variant:"secondary"},0===u?(0,m.__)("Add a rule","newsletter-optin-box"):(0,m.__)("Add another rule","newsletter-optin-box")))}function N(e){var o=e.onChange,a=e.value,l=e.comparisons,i=e.toggleText,c=e.availableSmartTags,u=e.className;"object"!==t(a)&&(a={enabled:!1,action:"allow",rules:[{condition:"is",type:"date",value:""}],type:"all"});var p=function(e,t){o(j(j({},a),{},n({},e,t)))};return(0,r.createElement)("div",{className:u},(0,r.createElement)(s.ToggleControl,{checked:!!a.enabled,onChange:function(e){return p("enabled",e)},className:"noptin-component__field",label:i||(0,m.__)("Optionally enable/disable this trigger depending on specific conditions.","newsletter-optin-box"),__nextHasNoMarginBottom:!0}),a.enabled&&(0,r.createElement)(r.Fragment,null,(0,r.createElement)(P,{ruleCount:a.rules?a.rules.length:0,type:a.type,action:a.action,setConditionalLogicAttribute:p}),(0,r.createElement)(A,{rules:a.rules,conditionType:a.type,comparisons:l,availableSmartTags:c,setConditionalLogicAttribute:p})))}var D={"==":function(e,t){return e==t},"===":function(e,t){return e===t},"!=":function(e,t){return e!=t},"!==":function(e,t){return e!==t},">":function(e,t){return e>t},">=":function(e,t){return e>=t},"<":function(e,t){return e<t},"<=":function(e,t){return e<=t},includes:function(e,t){return e.includes(t)},"!includes":function(e,t){return!e.includes(t)},empty:function(e){return!e},"!empty":function(e){return e}},F=function(e,t,n){return!!D[t]&&D[t](e,n)},B=["setting","availableSmartTags","isPressEnterToChange"],R=["setting","availableSmartTags","value","onChange"],I=["setting","value","options","onChange"];function M(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function L(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?M(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):M(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var H=["number","search","email","password","tel","url","date"];function z(e){var t=e.setting,n=e.availableSmartTags,o=e.isPressEnterToChange,a=E(e,B);void 0===o&&(o=!0);var l=K(n,(0,r.useCallback)((function(e){a.onChange&&a.onChange(a.value?"".concat(a.value," ").concat(e).trim():e)}),[a.value,a.onChange]));return t.disabled&&(a.readOnly=!0,a.onFocus=function(e){return e.target.select()}),(0,r.createElement)(r.Fragment,null,(0,r.createElement)(s.__experimentalInputControl,L(L({},a),{},{type:H.includes(t.type)?t.type:"text",placeholder:t.placeholder?t.placeholder:"",suffix:l,isPressEnterToChange:o,__nextHasNoMarginBottom:!0,__next36pxDefaultSize:!0})))}var V=[{id:"key",label:(0,m.__)("Key","noptin-addons-pack"),type:"text"},{id:"value",label:(0,m.__)("Value","noptin-addons-pack"),type:"text"}];function K(e,t){var n=(0,r.useMemo)((function(){if(!Array.isArray(e))return{};var t={};return e.forEach((function(e){var n=e.group?e.group:(0,m.__)("General","newsletter-optin-box");t[n]=t[n]?t[n]:[],t[n].push(e)})),t}),[e]),o=Object.keys(n).length,a=null;return o>0&&(a=(0,r.createElement)(s.DropdownMenu,{icon:"shortcode",label:(0,m.__)("Insert merge tag","newsletter-optin-box"),showTooltip:!0},(function(e){var a=e.onClose;return(0,r.createElement)(r.Fragment,null,Object.keys(n).map((function(e,l){return(0,r.createElement)(s.MenuGroup,{label:o>1?e:void 0,key:l},n[e].map((function(e){return(0,r.createElement)(s.MenuItem,{icon:e.icon||O,iconPosition:"left",onClick:function(){var n;t&&t("[[".concat((n=e).example?n.example:n.default?"".concat(n.smart_tag,' default="').concat(n.default,'"'):"".concat(n.smart_tag),"]]")),a()},key:e.smart_tag},e.label)})))})))}))),a}function U(e){var t=e.setting,n=e.availableSmartTags,o=e.value,a=e.onChange,l=E(e,R),i=(0,s.useBaseControlProps)(l),c=i.baseControlProps,u=i.controlProps;Array.isArray(o)||(o=[]);var p=(0,r.useCallback)((function(e){var t=e.item,l=e.index;return(0,r.createElement)(s.Flex,{className:"noptin-repeater-item",wrap:!0},V.map((function(e,i){return(0,r.createElement)(G,{key:i,availableSmartTags:n,field:e,value:void 0===t[e.id]?"":t[e.id],onChange:function(t){var n=h(o);n[l][e.id]=t,a(n)}})})),(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.Button,{icon:"trash",variant:"tertiary",className:"noptin-component__field",label:(0,m.__)("Delete","noptin-addons-pack"),showTooltip:!0,onClick:function(){var e=h(o);e.splice(l,1),a(e)},isDestructive:!0})))}),[o,a]);return(0,r.createElement)(s.BaseControl,L({},c),(0,r.createElement)("div",L({},u),o.map((function(e,t){return(0,r.createElement)(p,{key:t,item:e,index:t})})),(0,r.createElement)(s.Button,{onClick:function(){var e=h(o);e.push({}),a(e)},variant:"secondary"},t.add_field?t.add_field:(0,m.__)("Add","newsletter-optin-box"))))}function G(e){var t=e.field,n=e.availableSmartTags,o=e.value,a=e.onChange,l=K(n,(0,r.useCallback)((function(e){a&&a(o?"".concat(o," ").concat(e).trim():e)}),[o,a]));return(0,r.createElement)(s.FlexBlock,null,(0,r.createElement)(s.__experimentalInputControl,{label:t.label,type:t.type,value:o,placeholder:(0,m.sprintf)((0,m.__)("Enter %s","noptin-addons-pack"),t.label),className:"noptin-component__field noptin-condition-field",suffix:l,onChange:a,isPressEnterToChange:!0,__nextHasNoMarginBottom:!0,__next36pxDefaultSize:!0}))}function J(e){e.setting;var t=e.value,n=e.options,o=e.onChange,a=E(e,I),l=(0,s.useBaseControlProps)(a),i=l.baseControlProps,c=l.controlProps;return Array.isArray(t)||(t=[]),(0,r.createElement)(s.BaseControl,L({},i),(0,r.createElement)("div",L({},c),n.map((function(e,n){return(0,r.createElement)(s.CheckboxControl,{key:n,label:e.label,checked:t.includes(e.value),onChange:function(n){o(n?[].concat(h(t),[e.value]):t.filter((function(t){return t!==e.value})))}})}))))}function q(e){var t=e.settingKey,o=e.setting,a=e.availableSmartTags,l=e.prop,i=e.saved,c=e.setAttributes,u=(0,r.useCallback)((function(e){if(!l)return c(n({},t,e));var r=i[l]?i[l]:{},o=n({},l,L(L({},r),{},n({},t,e)));c(o)}),[t,l,i,c]);if(o.if||o.restrict){var p=o.restrict?o.restrict.split("."):o.if.split(".");if(console.log([p,i,i[p[0]]&&i[p[0]][p[1]]]),!(2!==p.length||i[p[0]]&&i[p[0]][p[1]]))return null;if(1===p.length&&!i[p[0]])return null}if(Array.isArray(o.conditions)){var f=o.conditions.every((function(e){var t=e.key.split("."),n=e.operator?e.operator:"==",r=i[t[0]];return 2===t.length?r&&r[t[1]]&&F(e.value,n,r[t[1]]):F(e.value,n,r)}));if(!f)return null}if(o.condition&&!o.condition(i))return null;var d=i[t];l&&(d=i[l]?i[l][t]:void 0),(void 0===d||o.disabled)&&(d=o.default);var b=void 0!==d&&""!==d&&null!==d,g=[];o.options&&(g=Object.keys(o.options).map((function(e){return{label:o.options[e],value:e}})));var y=o.fullWidth?"noptin-component__field noptin-component__field-".concat(t):"noptin-component__field-lg noptin-component__field-".concat(t),v=o.description?(0,r.createElement)("span",{dangerouslySetInnerHTML:{__html:o.description}}):"",_={label:o.label,value:b?d:"",onChange:u,className:"".concat(y),help:v};return"select"===o.el?(g.unshift({label:o.placeholder?o.placeholder:(0,m.__)("Select an option","newsletter-optin-box"),value:"",disabled:!o.canSelectPlaceholder}),(0,r.createElement)(s.SelectControl,L(L({},_),{},{options:g,__nextHasNoMarginBottom:!0,__next36pxDefaultSize:!0}))):"form_token"===o.el?(0,r.createElement)(s.FormTokenField,L(L({},_),{},{value:Array.isArray(_.value)?_.value:[],suggestions:Array.isArray(o.suggestions)?o.suggestions:[],__nextHasNoMarginBottom:!0,__next40pxDefaultSize:!0})):"multi_checkbox"===o.el||"multi_checkbox_alt"===o.el?(0,r.createElement)(J,L(L({},_),{},{options:g})):"conditional_logic"===o.el?(0,r.createElement)(N,L(L({},_),{},{availableSmartTags:a,comparisons:o.comparisons,toggleText:o.toggle_text})):"input"===o.el?o.type&&["toggle","switch","checkbox","checkbox_alt"].includes(o.type)?(0,r.createElement)(s.ToggleControl,L(L({},_),{},{checked:!!b&&d,onChange:function(e){u(e)}})):(0,r.createElement)(z,L(L({},_),{},{setting:o,availableSmartTags:"trigger_settings"===l?[]:a,isPressEnterToChange:!o.isInputToChange})):"textarea"===o.el?(0,r.createElement)(s.TextareaControl,L(L({},_),{},{setting:o,placeholder:o.placeholder?o.placeholder:"",__nextHasNoMarginBottom:!0})):"paragraph"===o.el?(0,r.createElement)("div",{className:y},(0,r.createElement)(s.Tip,null,o.content)):"hero"===o.el?(0,r.createElement)("div",{className:y},(0,r.createElement)("h3",null,o.content)):"key_value_repeater"===o.el||"webhook_key_value_repeater"===o.el?(0,r.createElement)(U,L(L({},_),{},{setting:o,availableSmartTags:"trigger_settings"===l?[]:a})):t}const W=window.React,Y=(0,W.createContext)(null),$={didCatch:!1,error:null};class Q extends W.Component{constructor(e){super(e),this.resetErrorBoundary=this.resetErrorBoundary.bind(this),this.state=$}static getDerivedStateFromError(e){return{didCatch:!0,error:e}}resetErrorBoundary(){const{error:e}=this.state;if(null!==e){for(var t,n,r=arguments.length,o=new Array(r),a=0;a<r;a++)o[a]=arguments[a];null===(t=(n=this.props).onReset)||void 0===t||t.call(n,{args:o,reason:"imperative-api"}),this.setState($)}}componentDidCatch(e,t){var n,r;null===(n=(r=this.props).onError)||void 0===n||n.call(r,e,t)}componentDidUpdate(e,t){const{didCatch:n}=this.state,{resetKeys:r}=this.props;var o,a;n&&null!==t.error&&function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:[];return e.length!==t.length||e.some(((e,n)=>!Object.is(e,t[n])))}(e.resetKeys,r)&&(null===(o=(a=this.props).onReset)||void 0===o||o.call(a,{next:r,prev:e.resetKeys,reason:"keys"}),this.setState($))}render(){const{children:e,fallbackRender:t,FallbackComponent:n,fallback:r}=this.props,{didCatch:o,error:a}=this.state;let l=e;if(o){const e={error:a,resetErrorBoundary:this.resetErrorBoundary};if((0,W.isValidElement)(r))l=r;else if("function"==typeof t)l=t(e);else{if(!n)throw new Error("react-error-boundary requires either a fallback, fallbackRender, or FallbackComponent prop");l=(0,W.createElement)(n,e)}}return(0,W.createElement)(Y.Provider,{value:{didCatch:o,error:a,resetErrorBoundary:this.resetErrorBoundary}},l)}}function X(e){var t=e.error;return(0,r.createElement)(s.Notice,{status:"error",isDismissible:!1},(0,r.createElement)("strong",null,(0,m.__)("Error:","newsletter-optin-box"))," ",t.message)}function Z(e){var t=e.children;return(0,r.createElement)(Q,{FallbackComponent:X},t)}function ee(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function te(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?ee(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):ee(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function ne(e){var t=e.sectionKey,n=e.label,o=e.prop,a=e.availableSmartTags,l=e.settings,i=e.automationRule,c=e.setAutomationRule,u=Object.keys(l);if(!u.length)return null;var p=(0,r.useCallback)((function(e){c(te(te({},i),e))}),[i,c]);return(0,r.createElement)(Z,null,(0,r.createElement)(f,{title:n,className:"noptin-automation-rule-editor__section noptin-automation-rule-editor__section-".concat(t)},(0,r.createElement)(s.CardBody,null,u.map((function(e){return(0,r.createElement)(q,{key:e,settingKey:e,prop:o,availableSmartTags:a,saved:i,setAttributes:p,setting:l[e]})})))))}function re(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function oe(e){var t=e.settings,o=e.availableSmartTags,a=e.automationRule,l=e.setAutomationRule;if(!t||!a)return null;var i=Object.keys(t);return i.length?(0,r.createElement)("div",{className:"noptin-automation-rule-editor__sections"},(0,r.createElement)(Z,null,i.map((function(e){return(0,r.createElement)(ne,function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?re(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):re(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({key:e,sectionKey:e,availableSmartTags:o,automationRule:a,setAutomationRule:l},t[e]))})))):null}var ae=function(e){var t=e.mergeTag,n=e.onMergeTagClick,o=function(){if(t.example)return t.example;var e="default value";return t.replacement&&(e=t.replacement),t.default&&(e=t.default),e?"".concat(t.smart_tag,' default="').concat(e,'"'):"".concat(t.smart_tag)},a=t.description&&t.description!==t.label;return(0,r.createElement)("tr",{className:"noptin-merge-tag"},(0,r.createElement)("td",null,(0,r.createElement)("label",null,(0,r.createElement)("span",{className:"noptin-merge-tag-label"},t.label),(0,r.createElement)("input",{type:"text",className:"widefat",value:"[[".concat(o(),"]]"),onFocus:function(e){e.target.select(),n&&n("[[".concat(o(),"]]"))},readOnly:!0})),a&&(0,r.createElement)("p",{className:"description noptin-mb0"},t.description)))},le=function(e){var t=e.availableSmartTags,n=e.onMergeTagClick;return(0,r.createElement)("div",{className:"noptin-merge-tags-wrapper"},(0,r.createElement)("table",{className:"widefat striped"},(0,r.createElement)("tbody",null,t.map((function(e){return(0,r.createElement)(ae,{key:e.smart_tag,mergeTag:e,onMergeTagClick:n})})))))},ie=function(e){var t=e.availableSmartTags;return(0,r.createElement)("div",{id:"noptin-automation-rule-smart-tags",style:{display:"none"}},(0,r.createElement)("h2",null,(0,m.__)("Smart Tags","newsletter-optin-box")),(0,r.createElement)("p",null,(0,m.__)("You can use the following smart tags to generate dynamic values.","newsletter-optin-box")),(0,r.createElement)(le,{availableSmartTags:t}))};function ce(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function se(e){var t=e.id,n=e.createNewUrl,o=!!(t&&parseInt(t)>0);return(0,r.createElement)("h1",{className:"wp-heading-inline"},o&&(0,r.createElement)(r.Fragment,null,(0,r.createElement)("span",null,(0,m.__)("Edit Automation Rule","newsletter-optin-box")),(0,r.createElement)("a",{href:n,className:"page-title-action"},(0,m.__)("Add New","newsletter-optin-box"))),!o&&(0,r.createElement)("span",null,(0,m.__)("Add Automation Rule","newsletter-optin-box")))}function ue(e){var t=e.id,o=e.action,a=e.trigger,l=e.settings,i=e.smartTags,u=e.createNewUrl,m={action_id:o,trigger_id:a,action_settings:{},status:!0,trigger_settings:{}};(0,r.useEffect)((function(){l&&Object.values(l).forEach((function(e){["trigger_settings","action_settings"].includes(e.prop)&&e.settings&&Object.keys(e.settings).forEach((function(t){var n=e.settings[t];void 0!==n.default&&(m[e.prop][t]=n.default)}))}))}),[l]);var f=(0,r.useState)(function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?ce(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):ce(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},m)),d=c(f,2),b=d[0],g=d[1],y=c((0,r.useState)(!1),2),v=y[0],h=y[1],E=c((0,r.useState)(1),2),w=E[0],O=E[1],x=c((0,r.useState)(null),2),j=x[0],S=x[1],C=c((0,r.useState)(null),2),k=C[0],P=C[1],T=function(e,t){var n=[];return e?(Object.keys(e).forEach((function(r){var o=e[r];if(!o.conditions||o.conditions.every((function(e){if(Array.isArray(e.value))var n=e.value.some((function(n){return n==t[e.key]}));else n=e.value==t[e.key];return n===("is"===e.operator)}))){var a=r;o.label?a=o.label:o.description&&(a=o.description),n.push({smart_tag:r,label:a,example:o.example?o.example:"",description:o.description?o.description:"",placeholder:o.placeholder?o.placeholder:"",conditional_logic:!!o.conditional_logic&&o.conditional_logic,options:o.options?o.options:[],icon:o.icon,group:o.group})}})),n):n}(i,b.trigger_settings||{}),A=w>0;(0,r.useEffect)((function(){t>0?(O(w+1),S(null),P(null),p()({path:"/noptin/v1/automation_rules/".concat(t)}).then((function(e){e&&g(e)})).catch((function(e){g(null),S(e.message)})).finally((function(){O(w-1)}))):O(w-1)}),[t]);var N={opacity:A||v?.5:1,pointerEvents:A||v?"none":"auto"};return(0,r.createElement)("div",{className:"noptin-automation-rule__editor",style:N},(0,r.createElement)(Z,null,(0,r.createElement)(s.SlotFillProvider,null,(0,r.createElement)(se,{id:b.id,createNewUrl:u}),(0,r.createElement)(s.Flex,{wrap:!0,align:"top"},(0,r.createElement)(s.FlexBlock,{className:"noptin-es6-editor__main"},j&&(0,r.createElement)(s.Notice,{status:"error",onDismiss:function(){S(null)}},j),k&&(0,r.createElement)(s.Notice,{status:"success",onDismiss:function(){P(null)}},k),A&&(0,r.createElement)(s.Spinner,null),!A&&(0,r.createElement)(oe,{settings:l,automationRule:b,setAutomationRule:g,availableSmartTags:T})),(0,r.createElement)(s.FlexItem,{className:"noptin-component-editor__sidebar"},(0,r.createElement)(_,{automationRule:b,setAutomationRule:g,setError:S,setSuccess:P,isSaving:v,setIsSaving:h}))),!A&&(0,r.createElement)(ie,{availableSmartTags:T}))))}function pe(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function me(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?pe(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):pe(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}a()((function(){var e=document.getElementById("noptin-automation-rule__editor-app");if(e){var t=me({},e.dataset);t.id=parseInt(t.id),t.settings=JSON.parse(t.settings),t.smartTags=JSON.parse(t.smartTags);var n=(0,r.createElement)(r.StrictMode,null,(0,r.createElement)(Z,null,(0,r.createElement)(ue,me({},t))));r.createRoot?(0,r.createRoot)(e).render(n):(0,r.render)(n,e)}}))})();