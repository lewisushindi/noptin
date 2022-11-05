(()=>{"use strict";var t,e={465:(t,e,n)=>{n.d(e,{Z:()=>o});var r=n(84);function o(t){(0,r.Z)(t).on("submit",(function(e){e.preventDefault();try{!function(t){var e=(0,r.Z)(t);e.addClass("noptin-submitting").removeClass("noptin-form-submitted noptin-has-error noptin-has-success");var n=e.find(".noptin-response").html("");window.fetch(noptinParams.resturl,{method:"POST",body:new FormData(t),credentials:"same-origin",headers:{Accept:"application/json"}}).then((function(t){if(t.status>=200&&t.status<300)return t;throw t})).then((function(t){return t.json()})).then((function(r){if(r){if(!1===r.success)e.addClass("noptin-has-error"),n.html(r.data);else{if(!0!==r.success)return void t.submit();"redirect"===r.data.action&&(window.location.href=r.data.redirect_url),r.data.msg&&(e.addClass("noptin-has-success"),n.html(r.data.msg))}e.removeClass("noptin-submitting").addClass("noptin-form-submitted")}else t.submit()})).catch((function(e){return t.submit()}))}(t)}catch(e){console.log(e),t.submit()}}))}},84:(t,e,n)=>{function r(t,e){return r=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(t,e){return t.__proto__=e,t},r(t,e)}function o(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(t){return!1}}function i(t,e,n){return i=o()?Reflect.construct.bind():function(t,e,n){var o=[null];o.push.apply(o,e);var i=new(Function.bind.apply(t,o));return n&&r(i,n.prototype),i},i.apply(null,arguments)}function a(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function c(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}function u(t){if(Array.isArray(t))return t}function s(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}function l(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}function f(t,e){if(t){if("string"==typeof t)return l(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Object"===n&&t.constructor&&(n=t.constructor.name),"Map"===n||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?l(t,e):void 0}}function p(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function d(t,e){return u(t)||function(t,e){var n=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=n){var r,o,i=[],a=!0,c=!1;try{for(n=n.call(t);!(a=(r=n.next()).done)&&(i.push(r.value),!e||i.length!==e);a=!0);}catch(t){c=!0,o=t}finally{try{a||null==n.return||n.return()}finally{if(c)throw o}}return i}}(t,e)||f(t,e)||p()}function y(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function h(t){return function(t){if(Array.isArray(t))return l(t)}(t)||s(t)||f(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function v(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,r)}return n}function b(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?v(Object(n),!0).forEach((function(e){y(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):v(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}n.d(e,{Z:()=>k});var m=[],g=function(t){return t?"string"==typeof t?h(document.querySelectorAll(t)):t instanceof NodeList?h(t):"function"==typeof t.addEventListener?[t]:[]:[]},w=function(t){return"string"!=typeof t?[]:t.split(" ").reduce((function(t,e){var n=t.keys,r=t.existing;return r[e]?{keys:n,existing:r}:{keys:[].concat(h(n),[e]),existing:b(b({},r),{},y({},e,!0))}}),{keys:[],existing:{}}).keys},O=function(t,e,n,r){var o="function"==typeof t.dispatchEvent;w(e).forEach((function(e){var i,a=function(t){return void 0!==document["on".concat(t)]}(e);i=n||!a?new CustomEvent(e,Object.assign({detail:n,bubbles:!0},r)):new Event(e,Object.assign({bubbles:!0},r)),a&&"function"==typeof t[e]&&t[e](),o&&t.dispatchEvent(i)}))},j=function(t,e,n){if(t&&e&&n){var r=m.findIndex((function(e){return d(e,1)[0]===t})),o=d(m[r]||[t,{}],2),i=(o[0],o[1]);i[e]=i[e]||[],i[e].push(n),-1===r?m.push([t,i]):m[r]=[t,i]}},A=function(t,e,n){var r,o=u(r=n)||s(r)||f(r)||p(),i=o[0],a=o.slice(1);if(a.length){var c=a.length,l=a[c-1],d=a[c-2],y=w(i);return t.each((function(t){return y.forEach((function(n){return function(t,e,n,r){var o=r.delegate,i=r.once,a=function r(a){if(u=a.target,l=t,!(s=o)||"string"!=typeof s||function(t,e){return!!t&&("function"==typeof t.matches?t.matches(e):"function"==typeof t.msMatchesSelector&&t.msMatchesSelector(e))}(u,s)||l.contains(u.closest(s))){var c=a&&a.detail;n.call(o?a.target:t,a,c),i&&t.removeEventListener(e,r)}var u,s,l};a.originalHandler=n,t.addEventListener(e,a),i||j(t,e,a)}(t,n,l,b(b({},e),{},{delegate:d}))}))}))}},P=function(){function t(){a(this,t),this.length=0,this.add.apply(this,arguments)}var e,n;return e=t,n=[{key:"splice",value:function(){for(var t=arguments.length,e=new Array(t),n=0;n<t;n++)e[n]=arguments[n];return Array.prototype.splice.apply(this,e)}},{key:"each",value:function(){for(var t,e=arguments.length,n=new Array(e),r=0;r<e;r++)n[r]=arguments[r];return(t=Array.prototype.forEach).call.apply(t,[this].concat(n)),this}},{key:"add",value:function(){for(var t=this,e=arguments.length,n=new Array(e),r=0;r<e;r++)n[r]=arguments[r];return n.forEach((function(e){g(e).forEach((function(e){for(var n=0;n<t.length;n++)if(t[n]===e)return;t[t.length]=e,t.length++}))})),this}},{key:"on",value:function(){for(var t=arguments.length,e=new Array(t),n=0;n<t;n++)e[n]=arguments[n];return A(this,{},e)}},{key:"once",value:function(){for(var t=arguments.length,e=new Array(t),n=0;n<t;n++)e[n]=arguments[n];return A(this,{once:!0},e)}},{key:"off",value:function(t,e){return this.each((function(n){return function(t,e,n){var r=m.findIndex((function(e){return d(e,1)[0]===t}));if(-1!==r){var o=d(m[r],2)[1],i=w(e),a=function(r){-1===i.indexOf(r)&&e||!Object.prototype.hasOwnProperty.call(o,r)||!Array.isArray(o[r])||(o[r]=o[r].filter((function(e){return n&&e.originalHandler!==n||(t.removeEventListener(r,e),!1)})),e||delete o[r])};for(var c in o)a(c);e||m.splice(r,1)}}(n,t,e)}))}},{key:"trigger",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},n=e.data,r=e.options;return this.each((function(e){return O(e,t,n,r)}))}},{key:"hasClass",value:function(t){return!!this[0]&&this[0].classList.contains(t)}},{key:"addClass",value:function(t){return this.each((function(e){var n=e.classList;n.add.apply(n,t.split(/\s/))})),this}},{key:"removeClass",value:function(t){return this.each((function(e){var n=e.classList;n.remove.apply(n,t.split(/\s/))})),this}},{key:"toggleClass",value:function(t,e){return this.each((function(n){var r=n.classList;"boolean"!=typeof e&&(e=!r.contains(t)),r[e?"add":"remove"].apply(r,t.split(/\s/))})),this}},{key:"html",value:function(t){return void 0===t?this[0]?this[0].innerHTML:"":(this.each((function(e){e.innerHTML=t})),this)}},{key:"text",value:function(t){return void 0===t?this[0]?this[0].textContent:"":(this.each((function(e){e.textContent=t})),this)}},{key:"find",value:function(e){var n=new t;return this[0]&&n.add(this[0].querySelectorAll(e)),n}},{key:"append",value:function(t){return this.each((function(e){e.insertAdjacentHTML("beforeend",t)})),this}}],n&&c(e.prototype,n),Object.defineProperty(e,"prototype",{writable:!1}),t}();const k=function(){for(var t=arguments.length,e=new Array(t),n=0;n<t;n++)e[n]=arguments[n];return i(P,e)}}},n={};function r(t){var o=n[t];if(void 0!==o)return o.exports;var i=n[t]={exports:{}};return e[t](i,i.exports,r),i.exports}r.d=(t,e)=>{for(var n in e)r.o(e,n)&&!r.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:e[n]})},r.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e),t=function(){if(window.FormData)if("undefined"!=typeof noptinParams)if(void 0!==noptinParams.resturl){var t=r(465).Z;(0,r(84).Z)(".noptin-newsletter-form").each(t)}else console.error("noptinParams.resturl is not defined.");else console.error("noptinParams is not defined.");else console.error("FormData is not supported.")},"loading"===document.readyState?document.addEventListener("DOMContentLoaded",t):t()})();