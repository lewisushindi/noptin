(()=>{var e,t={357:(e,t,n)=>{"use strict";const r=window.wp.domReady;var a=n.n(r);const o=window.React,l=window.wp.components,i=window.wp.i18n,c=window.wp.element,s=window.wp.url,u=window.noptinEmailSettingsMisc||{},m=(u.license,window.noptinEmailEditorSettings||{}),p=window.noptinEmailSettingsMisc||{},d=(p.license,m.types||{}),_=(Object.keys(d).map((e=>({value:e,label:d[e].label}))),Object.keys(m.templates||{}).map((e=>({value:e,label:m.templates[e]}))),p.license||{}),g=(_.is_usable&&_.key,/_published|_unpublished|_deleted|_user_role$/),y=["noptin_subscriber_","delete_user","new_user","update_user","wp_login","after_password_reset","create_or_update_"],f=[["woocommerce","subscription"],["latest_","_digest"]],E=["WordPress Users"],b=["latest_posts_digest"],w=["periodic"],x=(e,t=void 0)=>{if(!e||b.includes(e))return!1;if(w.includes(e))return!0;if(t&&E.includes(t))return!0;if(f.some((t=>t.every((t=>e.includes(t))))))return!0;const n=e.replace(/^automation_rule_/,"");return g.test(n)?"post_published"!==n:y.some((e=>n.startsWith(e)))};var h=n(942),v=n.n(h);const k=function({categories:e,selectedCategory:t,onClickCategory:n}){const r="noptin-campaign-explorer__sidebar";return(0,o.createElement)("div",{className:r},(0,o.createElement)("div",{className:`${r}__categories-list`},e.map((e=>(0,o.createElement)(l.Button,{key:e,label:e,className:`${r}__categories-list__item`,isPressed:t===e,onClick:()=>{n(e)}},e)))))},S=window.noptinEmailSettingsMisc||{},C=S.license?.key||"",O=S.license||{},T=({image:e,title:t})=>{if("string"==typeof e&&e.startsWith("http"))return(0,o.createElement)("img",{src:e,width:24,alt:t,style:{maxWidth:24}});if("string"==typeof e)return(0,o.createElement)(l.Icon,{size:24,icon:e,style:{color:"#424242"}});if(e&&"object"==typeof e){const t=e.fill||"#008000",n=e.path||"",r=e.viewBox||"0 0 24 24";return e.path?(0,o.createElement)(l.SVG,{viewBox:r,xmlns:"http://www.w3.org/2000/svg",style:{maxWidth:24}},(0,o.createElement)(l.Path,{fill:t,d:n})):(0,o.createElement)(l.Icon,{size:24,style:{color:t},icon:e.icon})}return(0,o.createElement)(l.Icon,{size:24,icon:"email",style:{color:"#424242"}})},M=({name:e,label:t,description:n,image:r,selectText:a,is_installed:c,forcePremium:u,onSelect:m})=>{const p=(0,o.useCallback)((()=>m(e)),[e,m]),[d,_]=((e,t,n,r,a)=>{if(t?e&&O.key:e)return[null,(0,o.createElement)(l.Button,{variant:"primary",onClick:r},(0,o.createElement)("span",{className:"noptin-selectable-card__label"},a||(0,i.__)("Select"))," ",(0,o.createElement)(l.Icon,{size:16,icon:"arrow-right-alt"}))];const c=!e&&O.install_desc||(0,i.__)("Activate your license key to unlock","newsletter-optin-box"),u=!e&&O.install_text||(0,i.__)("View Pricing","newsletter-optin-box");let m=!e&&O.install_url||O.upgrade_url||"https://noptin.com/pricing/";O.key||(m=(0,s.addQueryArgs)(m,{utm_source:n,utm_campaign:(S.data?.type||"noptin")+"-emails"}));const p=O.key?"primary":"secondary";return[c,(0,o.createElement)(l.Button,{variant:p,href:m},(0,o.createElement)("span",{className:"noptin-selectable-card-action__label"},u)," ",(0,o.createElement)(l.Icon,{size:16,icon:"lock"}))]})(!1!==c,u||!1,e,p,a);return(0,o.createElement)(l.Card,{className:`noptin-selectable-card noptin-selectable-card__${e}`,onClick:d?void 0:p,size:"small"},(0,o.createElement)(l.CardHeader,null,(0,o.createElement)(l.__experimentalHeading,{level:4,numberOfLines:1},t),(0,o.createElement)(T,{image:r,title:t})),(0,o.createElement)(l.CardBody,null,(0,o.createElement)(l.__experimentalVStack,{spacing:4},n&&(0,o.createElement)(l.__experimentalText,{as:"p",variant:"muted"},n),d&&(0,o.createElement)(l.__experimentalText,{as:"em",isDestructive:!0},d))),(0,o.createElement)(l.CardFooter,{isBorderless:!0,justify:"flex-end"},_),(0,o.createElement)(l.__experimentalElevation,{value:1,hover:3,isInteractive:!0}))},A=function({types:e,selectedCategory:t,showTitle:n,onSelect:r,selectText:a}){const i=[],c=[];return Object.entries(e).forEach((([e,t])=>{t.forcePremium&&!C?c.push(e):i.push(e)})),(0,o.createElement)("div",{className:"noptin-campaign-explorer__list"},n&&(0,o.createElement)(l.__experimentalHeading,{level:2,lineHeight:"48px",className:"noptin-campaign-explorer__category-name"},t),(0,o.createElement)("div",{role:"listbox",className:"noptin-campaign-explorer-list"},i.map((t=>(0,o.createElement)(M,{key:t,name:t,onSelect:r,selectText:a,...e[t]}))),c.map((t=>(0,o.createElement)(M,{key:t,name:t,onSelect:r,selectText:a,...e[t]})))))};function j({cardGroups:e,selectText:t,onSelect:n}){const r=Object.keys(e).length,[a,l]=(0,c.useState)(Object.keys(e)[0]);(0,c.useEffect)((()=>{r>0&&!e[a]&&l(Object.keys(e)[0])}),[e,a]);const i=r>1,s=(0,c.useMemo)((()=>Object.keys(e)),[e]),u=(0,c.useMemo)((()=>e[a]||{}),[e,a]),m=v()("noptin-campaign-explorer",{"noptin-campaign-explorer--show-sidebar":i});return(0,o.createElement)("div",{className:m},i&&(0,o.createElement)(k,{selectedCategory:a,categories:s,onClickCategory:l}),(0,o.createElement)(A,{showTitle:i,selectedCategory:a,types:u,selectText:t,onSelect:n}))}const B=function({title:e,isOpen:t,closeModal:n,back:r,...a}){const c=(0,o.createElement)(o.Fragment,null,r&&(0,o.createElement)(l.Button,{icon:"arrow-left-alt",onClick:r,label:(0,i.__)("Back","newsletter-optin-box"),showTooltip:!0}));return(0,o.createElement)(o.Fragment,null,t&&(0,o.createElement)(l.Modal,{title:e,onRequestClose:n,headerActions:c,isFullScreen:!0},(0,o.createElement)(j,{...a})))},I=u.data?.add_new||(0,s.addQueryArgs)(window.location.href,{noptin_edit_automation_rule:"0"});function N(e,t){const n={};return Array.isArray(e)||Object.entries(e).forEach((([e,t])=>{if("email"!==e){if(!t.category){if(!u.isTest)return;t.category="Deprecated"}n[t.category]||(n[t.category]={}),n[t.category][e]={...t,forcePremium:x(e,t.category)}}})),Array.isArray(u.integrations)&&u.integrations.forEach((r=>{r.plan&&"free"!==r.plan&&r[t]&&!Array.isArray(r[t])&&Object.entries(r[t]).forEach((([t,a])=>{a.forEach((({id:a,label:o,description:l,premium:i=!1})=>{e[a]?e[a].category&&n[e[a].category][a]&&("premium"===r.plan||i)&&(n[e[a].category][a].forcePremium=!0):"email"!==a&&(n[t]||(n[t]={}),n[t][a]={name:a,label:o,description:l,category:t,image:r.icon_url,forcePremium:"premium"===r.plan||i})}))}))})),n}const P=N(u.data?.triggers||{},"triggers"),z=N(u.data?.actions||{},"actions"),F=({text:e})=>{const t=(()=>{const[e,t]=(0,o.useState)(!1),[n,r]=(0,o.useState)(""),[a,u]=(0,o.useState)(""),[m,p]=(0,o.useState)(!1),d=(0,o.useCallback)((()=>{t(!0)}),[t]),_=(0,o.useCallback)((()=>{t(!1),u(""),r("")}),[t]);(0,c.useEffect)((()=>{if(!n||!a)return;const e={noptin_edit_automation_rule:"0","noptin-trigger":n,"noptin-action":a};window.location.href=(0,s.addQueryArgs)(I,e),p(!0)}),[n,a]);const g=(0,o.useCallback)((()=>{p(!1),r(""),u("")}),[p,u]),y=(0,o.useCallback)((()=>{p(!1),_()}),[p,_,u,r]);return m?{hasModal:!0,openModal:d,url:I,modal:(0,o.createElement)(o.Fragment,null,e&&(0,o.createElement)(l.Modal,{onRequestClose:y,__experimentalHideHeader:!0},(0,o.createElement)(l.Flex,null,(0,o.createElement)(l.FlexItem,null,(0,o.createElement)(l.Spinner,null)),(0,o.createElement)(l.FlexItem,null,(0,i.__)("Redirecting...","newsletter-optin-box")))))}:n?a?{hasModal:!1,url:I,openModal:d,modal:null}:{hasModal:!0,url:I,openModal:d,modal:(0,o.createElement)(B,{isOpen:e,title:(0,i.__)("Select an action for your automation rule","newsletter-optin-box"),cardGroups:z,onSelect:u,selectText:(0,i.__)("Set-up","newsletter-optin-box"),closeModal:_,back:g})}:{hasModal:!0,url:I,openModal:d,modal:(0,o.createElement)(B,{isOpen:e,title:(0,i.__)("Select a trigger for your automation rule","newsletter-optin-box"),cardGroups:P,selectText:(0,i.__)("Use trigger","newsletter-optin-box"),onSelect:r,closeModal:_})}})(),n=e||(0,i.__)("Add New Automation","newsletter-optin-box");return(0,o.createElement)(o.Fragment,null,(0,o.createElement)(l.Button,{variant:"primary",text:n,type:"button",onClick:t.hasModal?t.openModal:void 0}),t.hasModal&&t.modal)},H=()=>(0,o.createElement)(l.__experimentalVStack,{alignment:"center",justify:"center",spacing:6,style:{minHeight:320}},(0,o.createElement)(l.Icon,{icon:"admin-generic",size:100,style:{color:"#646970"}}),(0,o.createElement)(l.__experimentalText,{align:"center",color:"#646970",size:16,isBlock:!0},(0,i.__)('Automation rules are simple "if this, then that" commands. Trigger an action when a product is purchased, a user creates an account, someone is tagged, etc.',"newsletter-optin-box")),(0,o.createElement)(F,{text:(0,i.__)("Create your first automation rule","newsletter-optin-box")}),(0,o.createElement)(l.__experimentalText,{align:"center",size:14,isBlock:!0},(0,o.createElement)("a",{href:"https://noptin.com/guide/automation-rules/",style:{color:"#646970"},target:"_blank"},(0,i.__)("Or Learn more","newsletter-optin-box")))),R=window.wp.apiFetch;var D=n.n(R);const $=({ruleId:e})=>{const[t,n]=(0,o.useState)(!1);return(0,o.createElement)(o.Fragment,null,(0,o.createElement)(l.Button,{icon:"trash",size:"compact",showTooltip:!0,label:(0,i.__)("Delete","newsletter-optin-box"),type:"button",onClick:()=>n(!0),isDestructive:!0}),t&&(0,o.createElement)(l.Modal,{onRequestClose:()=>n(!1),title:(0,i.__)("Delete Rule","newsletter-optin-box"),size:"small"},(0,o.createElement)(l.__experimentalVStack,{spacing:4},(0,o.createElement)(l.__experimentalText,null,(0,i.__)("Are you sure you want to delete this automation rule?","newsletter-optin-box")),(0,o.createElement)(l.__experimentalHStack,{spacing:4,justify:"flex-start",alignment:"flex-start"},(0,o.createElement)(l.Button,{variant:"primary",text:(0,i.__)("Delete","newsletter-optin-box"),type:"button",onClick:()=>{n(!1);const t=document.querySelector(`.noptin_automation_rule_${e}`);t&&t.classList.add("noptin-fade-out"),D()({path:`/noptin/v1/automation_rules/${e}`,method:"DELETE"}).then((e=>(t&&t.remove(),e))).catch((e=>{alert(e.message),t&&t.classList.remove("noptin-fade-out")}))},isDestructive:!0}),(0,o.createElement)(l.Button,{variant:"secondary",text:(0,i.__)("Cancel","newsletter-optin-box"),type:"button",onClick:()=>n(!1)})))))},q=({ruleId:e,status:t})=>{const[n,r]=(0,o.useState)(t);return(0,o.createElement)(l.ToggleControl,{checked:n,label:n?(0,i.__)("Active","newsletter-optin-box"):(0,i.__)("Inactive","newsletter-optin-box"),className:"noptin-toggle-button",onChange:()=>{r(!n),D()({path:`/noptin/v1/automation_rules/${e}`,method:"PATCH",data:{status:!n}}).catch((e=>{alert(e.message),r(n)}))},__nextHasNoMarginBottom:!0})},L=e=>(0,o.createElement)(l.__experimentalHStack,{alignment:"center",justify:"flex-end",spacing:1},(0,o.createElement)(l.Button,{href:e.editUrl,label:(0,i.__)("Edit","newsletter-optin-box"),size:"compact",icon:"edit",showTooltip:!0}),(0,o.createElement)($,{ruleId:e.ruleId}),(0,o.createElement)(q,{ruleId:e.ruleId,status:e.status})),V=(e,t)=>{if(t){const n=t.getAttribute("data-app"),r=n?JSON.parse(n):{};c.createRoot?(0,c.createRoot)(t).render((0,o.createElement)(e,{...r})):(0,c.render)((0,o.createElement)(e,{...r}),t)}};a()((()=>{V(H,document.getElementById("noptin-automation-rules__editor--add-new__in-table")),document.querySelectorAll(".noptin-automation-rules__editor--add-new__button").forEach((e=>{V(F,e)})),document.querySelectorAll(".noptin-automation-rule-actions__app").forEach((e=>{V(L,e)}))}))},942:(e,t)=>{var n;!function(){"use strict";var r={}.hasOwnProperty;function a(){for(var e="",t=0;t<arguments.length;t++){var n=arguments[t];n&&(e=l(e,o(n)))}return e}function o(e){if("string"==typeof e||"number"==typeof e)return e;if("object"!=typeof e)return"";if(Array.isArray(e))return a.apply(null,e);if(e.toString!==Object.prototype.toString&&!e.toString.toString().includes("[native code]"))return e.toString();var t="";for(var n in e)r.call(e,n)&&e[n]&&(t=l(t,n));return t}function l(e,t){return t?e?e+" "+t:e+t:e}e.exports?(a.default=a,e.exports=a):void 0===(n=function(){return a}.apply(t,[]))||(e.exports=n)}()}},n={};function r(e){var a=n[e];if(void 0!==a)return a.exports;var o=n[e]={exports:{}};return t[e](o,o.exports,r),o.exports}r.m=t,e=[],r.O=(t,n,a,o)=>{if(!n){var l=1/0;for(u=0;u<e.length;u++){for(var[n,a,o]=e[u],i=!0,c=0;c<n.length;c++)(!1&o||l>=o)&&Object.keys(r.O).every((e=>r.O[e](n[c])))?n.splice(c--,1):(i=!1,o<l&&(l=o));if(i){e.splice(u--,1);var s=a();void 0!==s&&(t=s)}}return t}o=o||0;for(var u=e.length;u>0&&e[u-1][2]>o;u--)e[u]=e[u-1];e[u]=[n,a,o]},r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={229:0,833:0};r.O.j=t=>0===e[t];var t=(t,n)=>{var a,o,[l,i,c]=n,s=0;if(l.some((t=>0!==e[t]))){for(a in i)r.o(i,a)&&(r.m[a]=i[a]);if(c)var u=c(r)}for(t&&t(n);s<l.length;s++)o=l[s],r.o(e,o)&&e[o]&&e[o][0](),e[o]=0;return r.O(u)},n=globalThis.webpackChunknoptin_premium=globalThis.webpackChunknoptin_premium||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var a=r.O(void 0,[833],(()=>r(357)));a=r.O(a)})();