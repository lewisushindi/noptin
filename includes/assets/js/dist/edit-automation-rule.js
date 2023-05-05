(()=>{"use strict";var e={};function t(e){return t="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},t(e)}function n(e,n,r){return(n=function(e){var n=function(e,n){if("object"!==t(e)||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var a=r.call(e,"string");if("object"!==t(a))return a;throw new TypeError("@@toPrimitive must return a primitive value.")}return String(e)}(e);return"symbol"===t(n)?n:String(n)}(n))in e?Object.defineProperty(e,n,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[n]=r,e}e.n=t=>{var n=t&&t.__esModule?()=>t.default:()=>t;return e.d(n,{a:n}),n},e.d=(t,n)=>{for(var r in n)e.o(n,r)&&!e.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:n[r]})},e.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t);const r=window.wp.element,a=window.wp.domReady;var o=e.n(a);function l(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function i(e,t){if(e){if("string"==typeof e)return l(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?l(e,t):void 0}}function c(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=n){var r,a,o,l,i=[],c=!0,s=!1;try{if(o=(n=n.call(e)).next,0===t){if(Object(n)!==n)return;c=!1}else for(;!(c=(r=o.call(n)).done)&&(i.push(r.value),i.length!==t);c=!0);}catch(e){s=!0,a=e}finally{try{if(!c&&null!=n.return&&(l=n.return(),Object(l)!==l))return}finally{if(s)throw a}}return i}}(e,t)||i(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}const s=window.wp.components,u=window.wp.apiFetch;var p=e.n(u);const m=window.wp.i18n;function d(e){var t=e.title,n=e.isSecodary,a=e.className,o=e.children,l=c((0,r.useState)(!0),2),i=l[0],u=l[1];return a=a||"",(0,r.createElement)(s.Card,{variant:n?"secondary":"primary",className:"noptin-component__section ".concat(a)},(0,r.createElement)(s.CardHeader,null,(0,r.createElement)(s.Flex,null,(0,r.createElement)(s.FlexBlock,null,(0,r.createElement)("h3",null,t)),(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.Button,{isTertiary:!0,onClick:function(){return u(!i)}},(0,r.createElement)(s.Icon,{icon:i?"arrow-up-alt2":"arrow-down-alt2"}))))),i&&o)}function f(e){var t=e.label,n=e.value,a=t.toLowerCase().replace(/[^a-z0-9]/g,"-");return(0,r.createElement)("li",{className:"noptin-list-item noptin-list-item__".concat(a)},(0,r.createElement)("div",{className:"noptin-list-item__key"},t),(0,r.createElement)("div",{className:"noptin-list-item__value"},n))}function b(e){var t=e.items;return(0,r.createElement)("ul",{className:"noptin-component__list"},t.map((function(e){return(0,r.createElement)(f,{key:e.label,label:e.label,value:e.value})})))}function g(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function y(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?g(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):g(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function v(e){if(!e)return"-";var t=new Date;return"string"==typeof e?t.setTime(Date.parse(e)):t.setTime(Date.parse(e.date)),t.toLocaleString()}function _(e){var t=e.automationRule,n=e.setError,a=e.setSuccess,o=e.setAutomationRule,l=e.isSaving,i=e.setIsSaving,c=t.id>0?"/noptin/v1/automation_rules/".concat(t.id):"/noptin/v1/automation_rules/",u=[{label:(0,m.__)("ID","newsletter-optin-box"),value:t.id?t.id:(0,m.__)("New","newsletter-optin-box")},{label:(0,m.__)("Status","newsletter-optin-box"),value:(0,r.createElement)(s.ToggleControl,{label:t.status?(0,m.__)("Active","newsletter-optin-box"):(0,m.__)("Inactive","newsletter-optin-box"),checked:!!t.status,onChange:function(e){o(y(y({},t),{},{status:e}))},__nextHasNoMarginBottom:!0})}];return t.id>0&&u.push({label:(0,m.__)("Times Run","newsletter-optin-box"),value:t.times_run},{label:(0,m.__)("Created at","newsletter-optin-box"),value:v(t.created_at)},{label:(0,m.__)("Updated at","newsletter-optin-box"),value:v(t.updated_at)}),Array.isArray(t.metadata)&&t.metadata.forEach((function(e){"string"!=typeof e.value&&"number"!=typeof e.value||u.push({label:e.key,value:e.value})})),(0,r.createElement)(d,{title:(0,m.__)("Automation Rule","newsletter-optin-box")},(0,r.createElement)(s.CardBody,null,(0,r.createElement)(b,{items:u}),(0,r.createElement)(s.Button,{variant:"primary",onClick:function(){i(!0),n(null),a(null),p()({path:c,method:"POST",data:t}).then((function(e){return a((0,m.__)("Automation rule saved.","newsletter-optin-box")),o(y(y({},t),e)),e})).then((function(e){e.id>0&&window.history.replaceState({},"","?page=noptin-automation-rules&noptin_edit_automation_rule=".concat(e.id))})).catch((function(e){e.message?n(e.message):n((0,m.__)("An error occurred while saving.","newsletter-optin-box"))})).finally((function(){i(!1)}))},disabled:l,isPressed:l},!l&&(0,m.__)("Save Automation","newsletter-optin-box"),l&&(0,m.__)("Saving...","newsletter-optin-box"),l&&(0,r.createElement)(s.Spinner,null))))}function h(){return h=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},h.apply(this,arguments)}function E(e){return function(e){if(Array.isArray(e))return l(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||i(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function w(e,t){if(null==e)return{};var n,r,a=function(e,t){if(null==e)return{};var n,r,a={},o=Object.keys(e);for(r=0;r<o.length;r++)n=o[r],t.indexOf(n)>=0||(a[n]=e[n]);return a}(e,t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);for(r=0;r<o.length;r++)n=o[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(a[n]=e[n])}return a}function O(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function x(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?O(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):O(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var S=[{label:(0,m.__)("Only run if","newsletter-optin-box"),value:"allow"},{label:(0,m.__)("Do not run if","newsletter-optin-box"),value:"prevent"}],j=[{label:(0,m.__)("all","newsletter-optin-box"),value:"all"},{label:(0,m.__)("any","newsletter-optin-box"),value:"any"}];function C(e,t){return[{label:t,value:"",disabled:!0}].concat(E(e))}function k(e){var t=e.type,n=e.action,a=e.ruleCount,o=e.setConditionalLogicAttribute,l=a>1;return(0,r.createElement)(s.Flex,{className:"noptin-component__field-lg",wrap:!0},(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.SelectControl,{label:(0,m.__)("If","newsletter-optin-box"),hideLabelFromVision:!0,value:n||"allow",options:S,onChange:function(e){return o("action",e)},size:"default",__nextHasNoMarginBottom:!0})),l&&(0,r.createElement)(r.Fragment,null,(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.SelectControl,{label:(0,m.__)("all","newsletter-optin-box"),hideLabelFromVision:!0,value:t||"all",options:j,onChange:function(e){return o("type",e)},size:"default",__nextHasNoMarginBottom:!0})),(0,r.createElement)(s.FlexBlock,null,(0,m.__)("of the following rules are true:","newsletter-optin-box"))))}function T(e){var t=e.rule,n=e.comparisons,a=e.availableConditionTypes,o=e.updateRule,l=e.removeRule,i=e.conditionType,c=e.isLastRule,u=(e.isFirstRule,(0,r.useMemo)((function(){return e=t.type,a[e]||{};var e}),[a,t.type])),p=(0,r.useMemo)((function(){return e=u.options,t=[],e?Array.isArray(e)?(e.forEach((function(e,n){t.push({label:e,value:n})})),t):(Object.keys(e).forEach((function(n){t.push({label:e[n],value:n})})),t):t;var e,t}),[u]),d=p.length>0,f=(0,r.useMemo)((function(){return u.type?u.type:"string"}),[u]),b=(0,r.useMemo)((function(){var e=[];return Object.keys(n).forEach((function(t){var r=n[t].type;if(d){if("string"===f&&"is"!=t&&"is_not"!=t)return;if("is_empty"===t||"is_not_empty"===t||"is_between"===t)return}"any"!==r&&r!=f||e.push({label:n[t].name,value:t})})),e}),[f]),g="",y=[];Object.keys(a).forEach((function(e){var t=a[e];""===g&&(g=t.type),y.push({label:t.label,value:e})}));var v=function(e,n){o(e,n),"type"!==e&&""===t.type&&o("type",g),"condition"!==e&&""===t.condition&&o("condition","is"),"type"===e&&(o("condition","is"),o("value",""))},_="is_empty"===t.condition||"is_not_empty"===t.condition,h=d&&!_,E=!d&&!_;return(0,r.createElement)(s.Flex,{className:"noptin-component__field-lg",wrap:!0},(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.SelectControl,{label:(0,m.__)("Condition Type","newsletter-optin-box"),hideLabelFromVision:!0,value:t.type?t.type:g,options:C(y,(0,m.__)("Select a condition","newsletter-optin-box")),onChange:function(e){return v("type",e)},size:"default",__nextHasNoMarginBottom:!0})),(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.SelectControl,{label:(0,m.__)("Comparison","newsletter-optin-box"),hideLabelFromVision:!0,value:t.condition?t.condition:"is",options:C(b,(0,m.__)("Select a comparison","newsletter-optin-box")),onChange:function(e){return v("condition",e)},size:"default",__nextHasNoMarginBottom:!0})),(0,r.createElement)(s.FlexItem,null,h&&(0,r.createElement)(s.SelectControl,{label:(0,m.__)("Value","newsletter-optin-box"),hideLabelFromVision:!0,value:t.value?t.value:"",options:C(p,(0,m.__)("Select a value","newsletter-optin-box")),onChange:function(e){return o("value",e)},size:"default",__nextHasNoMarginBottom:!0}),E&&(0,r.createElement)(s.TextControl,{type:"number"===f?"number":"text",label:(0,m.__)("Value","newsletter-optin-box"),hideLabelFromVision:!0,value:t.value?t.value:"",onChange:function(e){return o("value",e)},__nextHasNoMarginBottom:!0})),(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.Button,{onClick:l,icon:"trash"})),(0,r.createElement)(s.FlexBlock,null,!c&&(0,r.createElement)(r.Fragment,null,"any"===i&&(0,m.__)("or","newsletter-optin-box"),"all"===i&&(0,m.__)("and","newsletter-optin-box"))))}function P(e){var t=e.rules,n=e.conditionType,a=e.comparisons,o=e.availableSmartTags,l=e.setConditionalLogicAttribute,i=Array.isArray(t)?t:[],c=(0,r.useMemo)((function(){var e={};return o.forEach((function(t){t.conditional_logic&&(e[t.smart_tag]={key:t.smart_tag,label:t.label,options:t.options,type:t.conditional_logic,placeholder:t.placeholder?t.placeholder:""})})),e}),[o]),u=i.length;return(0,r.createElement)("div",{className:"noptin-conditional-logic-rules"},i.map((function(e,t){return(0,r.createElement)(T,{key:t,rule:e,updateRule:function(e,n){return function(e,t,n){var r=E(i);r[e][t]=n,l("rules",r)}(t,e,n)},removeRule:function(){return function(e){var t=E(i);t.splice(e,1),l("rules",t)}(t)},availableConditionTypes:c,isLastRule:t===u-1,isFirstRule:0===t,conditionType:n,comparisons:a})})),(0,r.createElement)(s.Button,{className:"noptin-add-conditional-rule",onClick:function(){var e=Object.keys(c)[0],t=c[e].options,n=c[e].placeholder?c[e].placeholder:"",r=Array.isArray(t)&&t.length?Object.keys(t)[0]:n,a=E(i);a.push({type:e,condition:"is",value:r}),l("rules",a)},variant:"secondary"},0===u?(0,m.__)("Add a rule","newsletter-optin-box"):(0,m.__)("Add another rule","newsletter-optin-box")))}function N(e){var a=e.onChange,o=e.value,l=e.comparisons,i=e.toggleText,c=e.availableSmartTags,u=e.className;"object"!==t(o)&&(o={enabled:!1,action:"allow",rules:[{condition:"is",type:"date",value:""}],type:"all"});var p=function(e,t){a(x(x({},o),{},n({},e,t)))};return(0,r.createElement)("div",{className:u},(0,r.createElement)(s.ToggleControl,{checked:!!o.enabled,onChange:function(e){return p("enabled",e)},className:"noptin-component__field",label:i||(0,m.__)("Optionally enable/disable this trigger depending on specific conditions.","newsletter-optin-box"),__nextHasNoMarginBottom:!0}),o.enabled&&(0,r.createElement)(r.Fragment,null,(0,r.createElement)(k,{ruleCount:o.rules?o.rules.length:0,type:o.type,action:o.action,setConditionalLogicAttribute:p}),(0,r.createElement)(P,{rules:o.rules,conditionType:o.type,comparisons:l,availableSmartTags:c,setConditionalLogicAttribute:p})))}var A=function(e){var t=e.mergeTag,n=e.onMergeTagClick,a=function(){if(t.example)return t.example;var e="default value";return t.replacement&&(e=t.replacement),t.default&&(e=t.default),e?"".concat(t.smart_tag,' default="').concat(e,'"'):"".concat(t.smart_tag)},o=t.description&&t.description!==t.label;return(0,r.createElement)("tr",{className:"noptin-merge-tag"},(0,r.createElement)("td",null,(0,r.createElement)("label",null,(0,r.createElement)("span",{className:"noptin-merge-tag-label"},t.label),(0,r.createElement)("input",{type:"text",className:"widefat",value:"[[".concat(a(),"]]"),onFocus:function(e){e.target.select(),n&&n("[[".concat(a(),"]]"))},readOnly:!0})),o&&(0,r.createElement)("p",{className:"description noptin-mb0"},t.description)))},F=function(e){var t=e.availableSmartTags,n=e.onMergeTagClick;return(0,r.createElement)("div",{className:"noptin-merge-tags-wrapper"},(0,r.createElement)("table",{className:"widefat striped"},(0,r.createElement)("tbody",null,t.map((function(e){return(0,r.createElement)(A,{key:e.smart_tag,mergeTag:e,onMergeTagClick:n})})))))},D=function(e){var t=e.isOpen,n=e.closeModal,a=e.availableSmartTags,o=e.onMergeTagClick;return(0,r.createElement)(r.Fragment,null,t&&(0,r.createElement)(s.Modal,{title:(0,m.__)("Smart Tags","newsletter-optin-box"),onRequestClose:n},(0,r.createElement)("div",{className:"noptin-component__field-lg noptin-component__field-noptin_description"},(0,r.createElement)(s.Tip,null,(0,m.__)("You can use the following smart tags to generate dynamic values.","newsletter-optin-box"))),(0,r.createElement)(F,{availableSmartTags:a,onMergeTagClick:o})))},M=function(e){var t=e.availableSmartTags;return(0,r.createElement)("div",{id:"noptin-automation-rule-smart-tags",style:{display:"none"}},(0,r.createElement)("h2",null,(0,m.__)("Smart Tags","newsletter-optin-box")),(0,r.createElement)("p",null,(0,m.__)("You can use the following smart tags to generate dynamic values.","newsletter-optin-box")),(0,r.createElement)(F,{availableSmartTags:t}))},B=["setting","availableSmartTags"],I=["setting","availableSmartTags","value","onChange"];function R(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function L(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?R(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):R(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var H=["number","search","email","password","tel","url"],z=[{id:"key",label:(0,m.__)("Key","noptin-addons-pack"),type:"text"},{id:"value",label:(0,m.__)("Value","noptin-addons-pack"),type:"text"}];function V(e,t){var n=c((0,r.useState)(!1),2),a=n[0],o=n[1],l=(0,r.useCallback)((function(){o(!1)}),[o]),i=(0,r.useCallback)((function(e){t&&(t(e),l())})),u=null,p=null;return Array.isArray(e)&&e.length>0&&(p=(0,r.createElement)(D,{isOpen:a,closeModal:l,availableSmartTags:e,onMergeTagClick:i}),u=(0,r.createElement)(s.Button,{icon:"shortcode",variant:"tertiary",isPressed:a,label:(0,m.__)("Insert merge tag","newsletter-optin-box"),onClick:function(){o(!0)},showTooltip:!0})),[u,p]}function K(e){var t=e.setting,n=e.availableSmartTags,a=w(e,B),o=c(V(n,(0,r.useCallback)((function(e){a.onChange&&a.onChange(a.value?a.value+e:e)}),[a.value,a.onChange])),2),l=o[0],i=o[1];return t.disabled&&(a.readOnly=!0,a.onFocus=function(e){return e.target.select()}),(0,r.createElement)(r.Fragment,null,i,(0,r.createElement)(s.__experimentalInputControl,h({},a,{type:H.includes(t.type)?t.type:"text",placeholder:t.placeholder?t.placeholder:"",suffix:l,isPressEnterToChange:!0,__nextHasNoMarginBottom:!0,__next36pxDefaultSize:!0})))}function U(e){var t=e.setting,n=e.availableSmartTags,a=e.value,o=e.onChange,l=w(e,I),i=c((0,r.useState)(!1),2),u=i[0],p=i[1],d=c(V(n,(0,r.useCallback)((function(e){Array.isArray(u)&&((a=E(a))[u[0]][u[1]]+=e,o(a))}),[u,a,o])),2),f=d[0],b=d[1],g=(0,s.useBaseControlProps)(l),y=g.baseControlProps,v=g.controlProps;Array.isArray(a)||(a=[]);var _=(0,r.useCallback)((function(e){var t=e.item,n=e.field,a=e.index,o=e.onChange;return(0,r.createElement)(s.FlexBlock,null,(0,r.createElement)(s.__experimentalInputControl,{label:n.label,type:n.type,value:void 0===t[n.id]?"":t[n.id],placeholder:(0,m.sprintf)((0,m.__)("Enter %s","noptin-addons-pack"),n.label),className:"noptin-component__field noptin-condition-field",suffix:f,onChange:o,onFocus:function(){p([a,n.id])},isPressEnterToChange:!0,__nextHasNoMarginBottom:!0,__next36pxDefaultSize:!0}))}),[f]),h=(0,r.useCallback)((function(e){var t=e.item,n=e.index;return(0,r.createElement)(s.Flex,{className:"noptin-repeater-item",wrap:!0},z.map((function(e,l){return(0,r.createElement)(_,{key:l,index:n,item:t,field:e,onChange:function(t){var r=E(a);r[n][e.id]=t,o(r)}})})),(0,r.createElement)(s.FlexItem,null,(0,r.createElement)(s.Button,{icon:"trash",variant:"tertiary",className:"noptin-component__field",label:(0,m.__)("Delete","noptin-addons-pack"),showTooltip:!0,onClick:function(){var e=E(a);e.splice(n,1),o(e)}})))}),[a,o]);return(0,r.createElement)(s.BaseControl,y,b,(0,r.createElement)("div",v,a.map((function(e,t){return(0,r.createElement)(h,{key:t,item:e,index:t})})),(0,r.createElement)(s.Button,{onClick:function(){var e=E(a);e.push({}),o(e)},variant:"secondary"},t.add_field?t.add_field:(0,m.__)("Add","newsletter-optin-box"))))}function J(e){var t=e.settingKey,a=e.setting,o=e.availableSmartTags,l=e.prop,i=e.saved,c=e.setAttributes,u=(0,r.useCallback)((function(e){if(!l)return c(n({},t,e));var r=i[l]?i[l]:{},a=n({},l,L(L({},r),{},n({},t,e)));c(a)}),[t,l,i,c]),p=i[t];l&&(p=i[l]?i[l][t]:void 0),(void 0===p||a.disabled)&&(p=a.default);var m=void 0!==p&&""!==p&&null!==p,d=[];a.options&&(d=Object.keys(a.options).map((function(e){return{label:a.options[e],value:e}})));var f=a.fullWidth?"noptin-component__field noptin-component__field-".concat(t):"noptin-component__field-lg noptin-component__field-".concat(t),b=a.description?(0,r.createElement)("span",{dangerouslySetInnerHTML:{__html:a.description}}):"",g={label:a.label,value:m?p:"",onChange:u,className:"".concat(f),help:b};return"select"===a.el?(a.placeholder&&d.unshift({label:a.placeholder,value:"",disabled:!0}),(0,r.createElement)(s.SelectControl,h({},g,{options:d,__nextHasNoMarginBottom:!0,__next36pxDefaultSize:!0}))):"conditional_logic"===a.el?(0,r.createElement)(N,h({},g,{availableSmartTags:o,comparisons:a.comparisons,toggleText:a.toggle_text})):"input"===a.el?a.type&&["toggle","switch","checkbox","checkbox_alt"].includes(a.type)?(0,r.createElement)(s.ToggleControl,h({},g,{checked:!!m&&p,onChange:function(e){u(e)}})):(0,r.createElement)(K,h({},g,{setting:a,availableSmartTags:"trigger_settings"===l?[]:o})):"textarea"===a.el?(0,r.createElement)(s.TextareaControl,h({},g,{setting:a,placeholder:a.placeholder?a.placeholder:"",__nextHasNoMarginBottom:!0})):"paragraph"===a.el?(0,r.createElement)("div",{className:f},(0,r.createElement)(s.Tip,null,a.content)):"hero"===a.el?(0,r.createElement)("div",{className:f},(0,r.createElement)("h3",null,a.content)):"key_value_repeater"===a.el||"webhook_key_value_repeater"===a.el?(0,r.createElement)(U,h({},g,{setting:a,availableSmartTags:"trigger_settings"===l?[]:o})):t}function Y(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function q(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Y(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Y(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function W(e){var t=e.sectionKey,n=e.label,a=e.prop,o=e.availableSmartTags,l=e.settings,i=e.automationRule,c=e.setAutomationRule,u=Object.keys(l);if(!u.length)return null;var p=(0,r.useCallback)((function(e){c(q(q({},i),e))}),[i,c]);return(0,r.createElement)(d,{title:n,className:"noptin-automation-rule-editor__section noptin-automation-rule-editor__section-".concat(t)},(0,r.createElement)(s.CardBody,null,u.map((function(e){return(0,r.createElement)(J,{key:e,settingKey:e,prop:a,availableSmartTags:o,saved:i,setAttributes:p,setting:l[e]})}))))}function $(e){var t=e.settings,n=e.availableSmartTags,a=e.automationRule,o=e.setAutomationRule;if(!t||!a)return null;var l=Object.keys(t);return l.length?(0,r.createElement)("div",{className:"noptin-automation-rule-editor__sections"},l.map((function(e){return(0,r.createElement)(W,h({key:e,sectionKey:e,availableSmartTags:n,automationRule:a,setAutomationRule:o},t[e]))}))):null}function G(e){var t=e.id,n=e.createNewUrl,a=!!(t&&parseInt(t)>0);return(0,r.createElement)("h1",{className:"wp-heading-inline"},a&&(0,r.createElement)(r.Fragment,null,(0,r.createElement)("span",null,(0,m.__)("Edit Automation Rule","newsletter-optin-box")),(0,r.createElement)("a",{href:n,className:"page-title-action"},(0,m.__)("Add New","newsletter-optin-box"))),!a&&(0,r.createElement)("span",null,(0,m.__)("Add Automation Rule","newsletter-optin-box")))}function Q(e){var t=e.id,n=e.action,a=e.trigger,o=e.settings,l=e.smartTags,i=e.createNewUrl,u=c((0,r.useState)({}),2),m=u[0],d=u[1],f=c((0,r.useState)(!1),2),b=f[0],g=f[1],y=c((0,r.useState)(0),2),v=y[0],h=y[1],E=c((0,r.useState)(null),2),w=E[0],O=E[1],x=c((0,r.useState)(null),2),S=x[0],j=x[1],C=(0,r.useMemo)((function(){return function(e,t){var n=[];return e?(Object.keys(e).forEach((function(r){var a=e[r];if(!a.conditions||a.conditions.every((function(e){if(Array.isArray(e.value))var n=e.value.some((function(n){return n==t[e.key]}));else n=e.value==t[e.key];return n===("is"===e.operator)}))){var o=r;a.label?o=a.label:a.description&&(o=a.description),n.push({smart_tag:r,label:o,example:a.example?a.example:"",description:a.description?a.description:"",placeholder:a.placeholder?a.placeholder:"",conditional_logic:!!a.conditional_logic&&a.conditional_logic,options:a.options?a.options:[]})}})),n):n}(l,m.trigger_settings)}),[l,m.trigger_settings]),k=v>0,T={action_id:n,trigger_id:a,action_settings:{},status:!0,trigger_settings:{}};(0,r.useEffect)((function(){t>0?(h(v+1),O(null),j(null),p()({path:"/noptin/v1/automation_rules/".concat(t)}).then((function(e){d(e||T)})).catch((function(e){d(null),O(e.message)})).finally((function(){h(v-1)}))):d(T)}),[t]);var P={opacity:k||b?.5:1,pointerEvents:k||b?"none":"auto"};return(0,r.createElement)("div",{className:"noptin-automation-rule__editor",style:P},(0,r.createElement)(s.SlotFillProvider,null,(0,r.createElement)(G,{id:m.id,createNewUrl:i}),(0,r.createElement)(s.Flex,{wrap:!0,align:"top"},(0,r.createElement)(s.FlexBlock,{className:"noptin-es6-editor__main"},w&&(0,r.createElement)(s.Notice,{status:"error",onDismiss:function(){O(null)}},w),S&&(0,r.createElement)(s.Notice,{status:"success",onDismiss:function(){j(null)}},S),k&&(0,r.createElement)(s.Spinner,null),!k&&(0,r.createElement)($,{settings:o,automationRule:m,setAutomationRule:d,availableSmartTags:C})),(0,r.createElement)(s.FlexItem,{className:"noptin-component-editor__sidebar"},(0,r.createElement)(_,{automationRule:m,setAutomationRule:d,setError:O,setSuccess:j,isSaving:b,setIsSaving:g}))),!k&&(0,r.createElement)(M,{availableSmartTags:C})))}function X(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}o()((function(){var e=document.getElementById("noptin-automation-rule__editor-app");if(e){var t=function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?X(Object(r),!0).forEach((function(t){n(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):X(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},e.dataset);t.id=parseInt(t.id),t.settings=JSON.parse(t.settings),t.smartTags=JSON.parse(t.smartTags);var a=(0,r.createElement)(r.StrictMode,null,(0,r.createElement)(Q,t));r.createRoot?(0,r.createRoot)(e).render(a):(0,r.render)(a,e)}}))})();