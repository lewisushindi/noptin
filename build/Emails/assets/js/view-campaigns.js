(()=>{var e,t={453:(e,t,n)=>{"use strict";var r={};n.r(r),n.d(r,{AddNewButton:()=>W,AddNewTable:()=>V,EditParent:()=>qt,EmailStatus:()=>Ht,Image:()=>T,Section:()=>U,SelectableCard:()=>P,SelectableCards:()=>Y,useAddNewModal:()=>G});const a=window.wp.domReady;var o=n.n(a);const i=window.React,l=window.wp.components,s=window.noptinEmailSettingsMisc||{},c=s.license?.key||"",u=window.wp.i18n,d=window.wp.url,p=window.wp.apiFetch;var m=n.n(p);const f=window.noptinEmailEditorSettings||{},h=window.noptinEmailSettingsMisc||{},g=(h.license,f.types||{}),y=(Object.keys(g).map((e=>({value:e,label:g[e].label}))),Object.keys(f.templates||{}).map((e=>({value:e,label:f.templates[e]}))),h.license||{}),b=(y.is_usable&&y.key,/_published|_unpublished|_deleted|_user_role$/),v=["noptin_subscriber_","delete_user","new_user","update_user","wp_login","after_password_reset"],_=[["woocommerce","subscription"],["latest_","_digest"]],w=["WordPress Users"],x=["latest_posts_digest"],k=["periodic"],E=(e,t=void 0)=>{if(!e||x.includes(e))return!1;if(k.includes(e))return!0;if(t&&w.includes(t))return!0;if(_.some((t=>t.every((t=>e.includes(t))))))return!0;const n=e.replace(/^automation_rule_/,"");return b.test(n)?"post_published"!==n:v.some((e=>n.startsWith(e)))};var S=n(942),C=n.n(S);const O=window.wp.element,M=function({categories:e,selectedCategory:t,onClickCategory:n}){const r="noptin-campaign-explorer__sidebar";return(0,i.createElement)("div",{className:r},(0,i.createElement)("div",{className:`${r}__categories-list`},e.map((e=>(0,i.createElement)(l.Button,{key:e,label:e,className:`${r}__categories-list__item`,isPressed:t===e,onClick:()=>{n(e)}},e)))))},A=s.license||{},T=({image:e,title:t})=>{if("string"==typeof e&&e.startsWith("http"))return(0,i.createElement)("img",{src:e,width:24,alt:t,style:{maxWidth:24}});if("string"==typeof e)return(0,i.createElement)(l.Icon,{size:24,icon:e,style:{color:"#424242"}});if(e&&"object"==typeof e){const t=e.fill||"#008000",n=e.path||"",r=e.viewBox||"0 0 24 24";return e.path?(0,i.createElement)(l.SVG,{viewBox:r,xmlns:"http://www.w3.org/2000/svg",style:{maxWidth:24}},(0,i.createElement)(l.Path,{fill:t,d:n})):(0,i.createElement)(l.Icon,{size:24,style:{color:t},icon:e.icon})}return(0,i.createElement)(l.Icon,{size:24,icon:"email",style:{color:"#424242"}})},P=({name:e,label:t,description:n,image:r,selectText:a,is_installed:o,forcePremium:c,onSelect:p})=>{const m=(0,i.useCallback)((()=>p(e)),[e,p]),[f,h]=((e,t,n,r,a)=>{if(t?e&&A.key:e)return[null,(0,i.createElement)(l.Button,{variant:"primary",onClick:r},(0,i.createElement)("span",{className:"noptin-selectable-card__label"},a||(0,u.__)("Select"))," ",(0,i.createElement)(l.Icon,{size:16,icon:"arrow-right-alt"}))];const o=!e&&A.install_desc||(0,u.__)("Activate your license key to unlock","newsletter-optin-box"),c=!e&&A.install_text||(0,u.__)("View Pricing","newsletter-optin-box");let p=!e&&A.install_url||A.upgrade_url||"https://noptin.com/pricing/";A.key||(p=(0,d.addQueryArgs)(p,{utm_source:n,utm_campaign:(s.data?.type||"noptin")+"-emails"}));const m=A.key?"primary":"secondary";return[o,(0,i.createElement)(l.Button,{variant:m,href:p},(0,i.createElement)("span",{className:"noptin-selectable-card-action__label"},c)," ",(0,i.createElement)(l.Icon,{size:16,icon:"lock"}))]})(!1!==o,c||!1,e,m,a);return(0,i.createElement)(l.Card,{className:`noptin-selectable-card noptin-selectable-card__${e}`,onClick:f?void 0:m,size:"small"},(0,i.createElement)(l.CardHeader,null,(0,i.createElement)(l.__experimentalHeading,{level:4,numberOfLines:1},t),(0,i.createElement)(T,{image:r,title:t})),(0,i.createElement)(l.CardBody,null,(0,i.createElement)(l.__experimentalVStack,{spacing:4},n&&(0,i.createElement)(l.__experimentalText,{as:"p",variant:"muted"},n),f&&(0,i.createElement)(l.__experimentalText,{as:"em",isDestructive:!0},f))),(0,i.createElement)(l.CardFooter,{isBorderless:!0,justify:"flex-end"},h),(0,i.createElement)(l.__experimentalElevation,{value:1,hover:3,isInteractive:!0}))},$=function({types:e,selectedCategory:t,showTitle:n,onSelect:r,selectText:a}){const o=[],s=[];return Object.entries(e).forEach((([e,t])=>{t.forcePremium&&!c?s.push(e):o.push(e)})),(0,i.createElement)("div",{className:"noptin-campaign-explorer__list"},n&&(0,i.createElement)(l.__experimentalHeading,{level:2,lineHeight:"48px",className:"noptin-campaign-explorer__category-name"},t),(0,i.createElement)("div",{role:"listbox",className:"noptin-campaign-explorer-list"},o.map((t=>(0,i.createElement)(P,{key:t,name:t,onSelect:r,selectText:a,...e[t]}))),s.map((t=>(0,i.createElement)(P,{key:t,name:t,onSelect:r,selectText:a,...e[t]})))))};function j({cardGroups:e,selectText:t,onSelect:n}){const r=Object.keys(e).length,[a,o]=(0,O.useState)(Object.keys(e)[0]);(0,O.useEffect)((()=>{r>0&&!e[a]&&o(Object.keys(e)[0])}),[e,a]);const l=r>1,s=(0,O.useMemo)((()=>Object.keys(e)),[e]),c=(0,O.useMemo)((()=>e[a]||{}),[e,a]),u=C()("noptin-campaign-explorer",{"noptin-campaign-explorer--show-sidebar":l});return(0,i.createElement)("div",{className:u},l&&(0,i.createElement)(M,{selectedCategory:a,categories:s,onClickCategory:o}),(0,i.createElement)($,{showTitle:l,selectedCategory:a,types:c,selectText:t,onSelect:n}))}const R=function({title:e,isOpen:t,closeModal:n,back:r,...a}){const o=(0,i.createElement)(i.Fragment,null,r&&(0,i.createElement)(l.Button,{icon:"arrow-left-alt",onClick:r,label:(0,u.__)("Back","newsletter-optin-box"),showTooltip:!0}));return(0,i.createElement)(i.Fragment,null,t&&(0,i.createElement)(l.Modal,{title:e,onRequestClose:n,headerActions:o,isFullScreen:!0},(0,i.createElement)(j,{...a})))},N=s.data?.add_new||(0,d.addQueryArgs)(window.location.href,{noptin_campaign:0}),z=s.senders||{},B=function(e){const t={};return Array.isArray(e)?{}:(Object.entries(e).forEach((([e,n])=>{if(!n.category){if(!s.isTest)return;n.category="Deprecated"}t[n.category]||(t[n.category]={}),n.alt_category&&!t[n.alt_category]&&(t[n.alt_category]={}),t[n.category][e]={...n,forcePremium:E(e,n.category)},n.alt_category&&(t[n.alt_category][e]=t[n.category][e])})),t)}(s.data?.sub_types||{}),I=Object.keys(B).length>0,F=(0,i.memo)((({isOpen:e,stopRedirecting:t})=>(0,i.createElement)(i.Fragment,null,e&&(0,i.createElement)(l.Modal,{onRequestClose:t,__experimentalHideHeader:!0},(0,i.createElement)(l.__experimentalHStack,null,(0,i.createElement)(l.Spinner,null),(0,i.createElement)(l.__experimentalText,null,(0,u.__)("Redirecting...","newsletter-optin-box"))))))),H=(0,i.memo)((({isOpen:e,onSelect:t,closeModal:n})=>(0,i.createElement)(R,{isOpen:e,title:(0,u.__)("Select Campaign Type","newsletter-optin-box"),cardGroups:B,onSelect:t,closeModal:n}))),L=(0,i.memo)((({isOpen:e,onSelect:t,closeModal:n,back:r})=>(0,i.createElement)(R,{isOpen:e,title:(0,u.__)("Send to","newsletter-optin-box"),cardGroups:{[(0,u.__)("Send to","newsletter-optin-box")]:z},onSelect:t,closeModal:n,back:r}))),q=(0,i.createElement)((()=>{const[e,t]=(0,i.useState)(""),[n,r]=(0,i.useState)(!1),[a,o]=(0,i.useState)(""),c=(0,i.useCallback)((t=>{t.preventDefault(),e&&(o(""),r(!0),m()({path:"/wp/v2/noptin-campaign",method:"POST",data:{title:e,status:"publish",meta:{campaign_type:s.data?.type}}}).then((e=>{e.noptin_campaign_type?.edit_url?window.location.href=e.noptin_campaign_type.edit_url:window.location.href=window.location.href})).catch((e=>{o(e.message),r(!1)})))}),[e]);return(0,i.createElement)(l.__experimentalVStack,{as:"form",onSubmit:c,style:{opacity:n?.5:1}},(0,i.createElement)(l.__experimentalInputControl,{label:(0,u.sprintf)(/* translators: %s: campaign type */ /* translators: %s: campaign type */
(0,u.__)("%s name","newsletter-optin-box"),s.data?.label),help:(0,u.__)("Enter a name for your campaign","newsletter-optin-box"),value:e,onChange:t,style:{width:"100%"},required:!0,disabled:n,suffix:(0,i.createElement)(l.Button,{variant:"primary",type:"submit",disabled:!e,isBusy:n,isPressed:n},(0,u.__)("Create","newsletter-optin-box"),n&&(0,i.createElement)(l.Spinner,null))}),a&&(0,i.createElement)(l.Notice,{status:"error",onDismiss:()=>o("")},a))}),null),D=(0,i.memo)((({isOpen:e,closeModal:t})=>(0,i.createElement)(i.Fragment,null,e&&(0,i.createElement)(l.Modal,{onRequestClose:t,title:s.data?.new_campaign_label,size:"medium"},q)))),G=()=>{const[e,t]=(0,i.useState)(!1),[n,r]=(0,i.useState)(""),[a,o]=(0,i.useState)(""),[l,c]=(0,i.useState)(!1),u=(0,i.useCallback)((()=>{t(!0)}),[t]),p=(0,i.useCallback)((()=>{t(!1)}),[t]);(0,i.useEffect)((()=>{if(!n&&!a)return;if(I&&!n)return;if(s.data?.supports_recipients&&!a)return;const e={};n&&(e.noptin_email_sub_type=n),a&&(e.noptin_email_sender=a),window.location.href=(0,d.addQueryArgs)(N,e),c(!0)}),[n,a]);const m=(0,i.useCallback)((()=>{c(!1),r("")}),[c,r]),f=(0,i.useCallback)((()=>{c(!1),p(),o(""),r("")}),[c,p,o,r]);return l?{hasModal:!0,openModal:u,url:N,modal:(0,i.createElement)(F,{isOpen:l,stopRedirecting:f})}:I&&!n?{hasModal:!0,url:N,openModal:u,modal:(0,i.createElement)(H,{isOpen:e,onSelect:r,closeModal:p})}:s.data?.supports_recipients?{hasModal:!0,url:N,openModal:u,modal:(0,i.createElement)(L,{isOpen:e,onSelect:o,closeModal:p,back:I?m:void 0})}:s.data?.child_type?{hasModal:!0,url:N,openModal:u,modal:(0,i.createElement)(D,{isOpen:e,closeModal:p})}:{hasModal:!1,url:N,openModal:u,modal:null}},W=()=>{const e=G();return(0,i.createElement)(i.Fragment,null,(0,i.createElement)(l.Button,{variant:"primary",href:e.hasModal?void 0:e.url,text:s.data?.new_campaign_label,type:"button",onClick:e.hasModal?e.openModal:void 0}),e.hasModal&&e.modal)},V=()=>(0,i.createElement)(l.__experimentalVStack,{alignment:"center",justify:"center",spacing:6,style:{minHeight:320}},(0,i.createElement)(l.Icon,{icon:s.data?.icon,size:100,style:{color:"#646970"}}),(0,i.createElement)(l.__experimentalText,{align:"center",color:"#646970",size:16,isBlock:!0},s.data?.click_to_add_first),(0,i.createElement)(W,null));function U({title:e,isSecodary:t,className:n,children:r}){const[a,o]=(0,i.useState)(!0),s=C()(n,"noptin-component__section");return(0,i.createElement)(l.Card,{variant:t?"secondary":"primary",className:s},(0,i.createElement)(l.CardHeader,null,(0,i.createElement)(l.Flex,null,(0,i.createElement)(l.FlexBlock,null,(0,i.createElement)("h3",null,e)),(0,i.createElement)(l.FlexItem,null,(0,i.createElement)(l.Button,{variant:"tertiary",onClick:()=>o(!a),icon:a?"arrow-up-alt2":"arrow-down-alt2"})))),a&&r)}const X=({showingAll:e=!0,cards:t,onSelect:n})=>{const r=Object.entries(t),a=e?r:r.slice(0,3);return(0,i.createElement)(l.Flex,{className:"noptin-selectable-card",justify:"left",align:"stretch",wrap:!0},a.map((([e,t],r)=>(0,i.createElement)(P,{key:`${e}__${r}`,onSelect:n,name:e,...t}))))},Y=({cards:e,title:t,onSelect:n,onGroupSelect:r,unwrap:a=!1})=>{const o=Object.keys(e).length,s=r&&o>3;if(console.log(o),0===o)return null;const c=(0,i.createElement)(X,{cards:e,onSelect:n,showingAll:a||!s});if(a)return c;const d={};return s&&(d["aria-expanded"]="false",d.onClick=()=>r&&r(t),d.label=(0,u.__)("Show all","newsletter-optin-box"),d.showTooltip=!0),(0,i.createElement)(l.__experimentalVStack,{spacing:2},(0,i.createElement)(l.__experimentalHStack,{as:s?l.Button:"h2",...d},t),c)};function Q(){return Q=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},Q.apply(this,arguments)}function K(e){var t=Object.create(null);return function(n){return void 0===t[n]&&(t[n]=e(n)),t[n]}}var Z=/^((children|dangerouslySetInnerHTML|key|ref|autoFocus|defaultValue|defaultChecked|innerHTML|suppressContentEditableWarning|suppressHydrationWarning|valueLink|abbr|accept|acceptCharset|accessKey|action|allow|allowUserMedia|allowPaymentRequest|allowFullScreen|allowTransparency|alt|async|autoComplete|autoPlay|capture|cellPadding|cellSpacing|challenge|charSet|checked|cite|classID|className|cols|colSpan|content|contentEditable|contextMenu|controls|controlsList|coords|crossOrigin|data|dateTime|decoding|default|defer|dir|disabled|disablePictureInPicture|download|draggable|encType|enterKeyHint|form|formAction|formEncType|formMethod|formNoValidate|formTarget|frameBorder|headers|height|hidden|high|href|hrefLang|htmlFor|httpEquiv|id|inputMode|integrity|is|keyParams|keyType|kind|label|lang|list|loading|loop|low|marginHeight|marginWidth|max|maxLength|media|mediaGroup|method|min|minLength|multiple|muted|name|nonce|noValidate|open|optimum|pattern|placeholder|playsInline|poster|preload|profile|radioGroup|readOnly|referrerPolicy|rel|required|reversed|role|rows|rowSpan|sandbox|scope|scoped|scrolling|seamless|selected|shape|size|sizes|slot|span|spellCheck|src|srcDoc|srcLang|srcSet|start|step|style|summary|tabIndex|target|title|translate|type|useMap|value|width|wmode|wrap|about|datatype|inlist|prefix|property|resource|typeof|vocab|autoCapitalize|autoCorrect|autoSave|color|incremental|fallback|inert|itemProp|itemScope|itemType|itemID|itemRef|on|option|results|security|unselectable|accentHeight|accumulate|additive|alignmentBaseline|allowReorder|alphabetic|amplitude|arabicForm|ascent|attributeName|attributeType|autoReverse|azimuth|baseFrequency|baselineShift|baseProfile|bbox|begin|bias|by|calcMode|capHeight|clip|clipPathUnits|clipPath|clipRule|colorInterpolation|colorInterpolationFilters|colorProfile|colorRendering|contentScriptType|contentStyleType|cursor|cx|cy|d|decelerate|descent|diffuseConstant|direction|display|divisor|dominantBaseline|dur|dx|dy|edgeMode|elevation|enableBackground|end|exponent|externalResourcesRequired|fill|fillOpacity|fillRule|filter|filterRes|filterUnits|floodColor|floodOpacity|focusable|fontFamily|fontSize|fontSizeAdjust|fontStretch|fontStyle|fontVariant|fontWeight|format|from|fr|fx|fy|g1|g2|glyphName|glyphOrientationHorizontal|glyphOrientationVertical|glyphRef|gradientTransform|gradientUnits|hanging|horizAdvX|horizOriginX|ideographic|imageRendering|in|in2|intercept|k|k1|k2|k3|k4|kernelMatrix|kernelUnitLength|kerning|keyPoints|keySplines|keyTimes|lengthAdjust|letterSpacing|lightingColor|limitingConeAngle|local|markerEnd|markerMid|markerStart|markerHeight|markerUnits|markerWidth|mask|maskContentUnits|maskUnits|mathematical|mode|numOctaves|offset|opacity|operator|order|orient|orientation|origin|overflow|overlinePosition|overlineThickness|panose1|paintOrder|pathLength|patternContentUnits|patternTransform|patternUnits|pointerEvents|points|pointsAtX|pointsAtY|pointsAtZ|preserveAlpha|preserveAspectRatio|primitiveUnits|r|radius|refX|refY|renderingIntent|repeatCount|repeatDur|requiredExtensions|requiredFeatures|restart|result|rotate|rx|ry|scale|seed|shapeRendering|slope|spacing|specularConstant|specularExponent|speed|spreadMethod|startOffset|stdDeviation|stemh|stemv|stitchTiles|stopColor|stopOpacity|strikethroughPosition|strikethroughThickness|string|stroke|strokeDasharray|strokeDashoffset|strokeLinecap|strokeLinejoin|strokeMiterlimit|strokeOpacity|strokeWidth|surfaceScale|systemLanguage|tableValues|targetX|targetY|textAnchor|textDecoration|textRendering|textLength|to|transform|u1|u2|underlinePosition|underlineThickness|unicode|unicodeBidi|unicodeRange|unitsPerEm|vAlphabetic|vHanging|vIdeographic|vMathematical|values|vectorEffect|version|vertAdvY|vertOriginX|vertOriginY|viewBox|viewTarget|visibility|widths|wordSpacing|writingMode|x|xHeight|x1|x2|xChannelSelector|xlinkActuate|xlinkArcrole|xlinkHref|xlinkRole|xlinkShow|xlinkTitle|xlinkType|xmlBase|xmlns|xmlnsXlink|xmlLang|xmlSpace|y|y1|y2|yChannelSelector|z|zoomAndPan|for|class|autofocus)|(([Dd][Aa][Tt][Aa]|[Aa][Rr][Ii][Aa]|x)-.*))$/,J=K((function(e){return Z.test(e)||111===e.charCodeAt(0)&&110===e.charCodeAt(1)&&e.charCodeAt(2)<91})),ee=function(){function e(e){var t=this;this._insertTag=function(e){var n;n=0===t.tags.length?t.insertionPoint?t.insertionPoint.nextSibling:t.prepend?t.container.firstChild:t.before:t.tags[t.tags.length-1].nextSibling,t.container.insertBefore(e,n),t.tags.push(e)},this.isSpeedy=void 0===e.speedy||e.speedy,this.tags=[],this.ctr=0,this.nonce=e.nonce,this.key=e.key,this.container=e.container,this.prepend=e.prepend,this.insertionPoint=e.insertionPoint,this.before=null}var t=e.prototype;return t.hydrate=function(e){e.forEach(this._insertTag)},t.insert=function(e){this.ctr%(this.isSpeedy?65e3:1)==0&&this._insertTag(function(e){var t=document.createElement("style");return t.setAttribute("data-emotion",e.key),void 0!==e.nonce&&t.setAttribute("nonce",e.nonce),t.appendChild(document.createTextNode("")),t.setAttribute("data-s",""),t}(this));var t=this.tags[this.tags.length-1];if(this.isSpeedy){var n=function(e){if(e.sheet)return e.sheet;for(var t=0;t<document.styleSheets.length;t++)if(document.styleSheets[t].ownerNode===e)return document.styleSheets[t]}(t);try{n.insertRule(e,n.cssRules.length)}catch(e){}}else t.appendChild(document.createTextNode(e));this.ctr++},t.flush=function(){this.tags.forEach((function(e){return e.parentNode&&e.parentNode.removeChild(e)})),this.tags=[],this.ctr=0},e}(),te=Math.abs,ne=String.fromCharCode,re=Object.assign;function ae(e){return e.trim()}function oe(e,t,n){return e.replace(t,n)}function ie(e,t){return e.indexOf(t)}function le(e,t){return 0|e.charCodeAt(t)}function se(e,t,n){return e.slice(t,n)}function ce(e){return e.length}function ue(e){return e.length}function de(e,t){return t.push(e),e}var pe=1,me=1,fe=0,he=0,ge=0,ye="";function be(e,t,n,r,a,o,i){return{value:e,root:t,parent:n,type:r,props:a,children:o,line:pe,column:me,length:i,return:""}}function ve(e,t){return re(be("",null,null,"",null,null,0),e,{length:-e.length},t)}function _e(){return ge=he>0?le(ye,--he):0,me--,10===ge&&(me=1,pe--),ge}function we(){return ge=he<fe?le(ye,he++):0,me++,10===ge&&(me=1,pe++),ge}function xe(){return le(ye,he)}function ke(){return he}function Ee(e,t){return se(ye,e,t)}function Se(e){switch(e){case 0:case 9:case 10:case 13:case 32:return 5;case 33:case 43:case 44:case 47:case 62:case 64:case 126:case 59:case 123:case 125:return 4;case 58:return 3;case 34:case 39:case 40:case 91:return 2;case 41:case 93:return 1}return 0}function Ce(e){return pe=me=1,fe=ce(ye=e),he=0,[]}function Oe(e){return ye="",e}function Me(e){return ae(Ee(he-1,Pe(91===e?e+2:40===e?e+1:e)))}function Ae(e){for(;(ge=xe())&&ge<33;)we();return Se(e)>2||Se(ge)>3?"":" "}function Te(e,t){for(;--t&&we()&&!(ge<48||ge>102||ge>57&&ge<65||ge>70&&ge<97););return Ee(e,ke()+(t<6&&32==xe()&&32==we()))}function Pe(e){for(;we();)switch(ge){case e:return he;case 34:case 39:34!==e&&39!==e&&Pe(ge);break;case 40:41===e&&Pe(e);break;case 92:we()}return he}function $e(e,t){for(;we()&&e+ge!==57&&(e+ge!==84||47!==xe()););return"/*"+Ee(t,he-1)+"*"+ne(47===e?e:we())}function je(e){for(;!Se(xe());)we();return Ee(e,he)}var Re="-ms-",Ne="-moz-",ze="-webkit-",Be="comm",Ie="rule",Fe="decl",He="@keyframes";function Le(e,t){for(var n="",r=ue(e),a=0;a<r;a++)n+=t(e[a],a,e,t)||"";return n}function qe(e,t,n,r){switch(e.type){case"@layer":if(e.children.length)break;case"@import":case Fe:return e.return=e.return||e.value;case Be:return"";case He:return e.return=e.value+"{"+Le(e.children,r)+"}";case Ie:e.value=e.props.join(",")}return ce(n=Le(e.children,r))?e.return=e.value+"{"+n+"}":""}function De(e){return Oe(Ge("",null,null,null,[""],e=Ce(e),0,[0],e))}function Ge(e,t,n,r,a,o,i,l,s){for(var c=0,u=0,d=i,p=0,m=0,f=0,h=1,g=1,y=1,b=0,v="",_=a,w=o,x=r,k=v;g;)switch(f=b,b=we()){case 40:if(108!=f&&58==le(k,d-1)){-1!=ie(k+=oe(Me(b),"&","&\f"),"&\f")&&(y=-1);break}case 34:case 39:case 91:k+=Me(b);break;case 9:case 10:case 13:case 32:k+=Ae(f);break;case 92:k+=Te(ke()-1,7);continue;case 47:switch(xe()){case 42:case 47:de(Ve($e(we(),ke()),t,n),s);break;default:k+="/"}break;case 123*h:l[c++]=ce(k)*y;case 125*h:case 59:case 0:switch(b){case 0:case 125:g=0;case 59+u:-1==y&&(k=oe(k,/\f/g,"")),m>0&&ce(k)-d&&de(m>32?Ue(k+";",r,n,d-1):Ue(oe(k," ","")+";",r,n,d-2),s);break;case 59:k+=";";default:if(de(x=We(k,t,n,c,u,a,l,v,_=[],w=[],d),o),123===b)if(0===u)Ge(k,t,x,x,_,o,d,l,w);else switch(99===p&&110===le(k,3)?100:p){case 100:case 108:case 109:case 115:Ge(e,x,x,r&&de(We(e,x,x,0,0,a,l,v,a,_=[],d),w),a,w,d,l,r?_:w);break;default:Ge(k,x,x,x,[""],w,0,l,w)}}c=u=m=0,h=y=1,v=k="",d=i;break;case 58:d=1+ce(k),m=f;default:if(h<1)if(123==b)--h;else if(125==b&&0==h++&&125==_e())continue;switch(k+=ne(b),b*h){case 38:y=u>0?1:(k+="\f",-1);break;case 44:l[c++]=(ce(k)-1)*y,y=1;break;case 64:45===xe()&&(k+=Me(we())),p=xe(),u=d=ce(v=k+=je(ke())),b++;break;case 45:45===f&&2==ce(k)&&(h=0)}}return o}function We(e,t,n,r,a,o,i,l,s,c,u){for(var d=a-1,p=0===a?o:[""],m=ue(p),f=0,h=0,g=0;f<r;++f)for(var y=0,b=se(e,d+1,d=te(h=i[f])),v=e;y<m;++y)(v=ae(h>0?p[y]+" "+b:oe(b,/&\f/g,p[y])))&&(s[g++]=v);return be(e,t,n,0===a?Ie:l,s,c,u)}function Ve(e,t,n){return be(e,t,n,Be,ne(ge),se(e,2,-2),0)}function Ue(e,t,n,r){return be(e,t,n,Fe,se(e,0,r),se(e,r+1,-1),r)}var Xe=function(e,t,n){for(var r=0,a=0;r=a,a=xe(),38===r&&12===a&&(t[n]=1),!Se(a);)we();return Ee(e,he)},Ye=new WeakMap,Qe=function(e){if("rule"===e.type&&e.parent&&!(e.length<1)){for(var t=e.value,n=e.parent,r=e.column===n.column&&e.line===n.line;"rule"!==n.type;)if(!(n=n.parent))return;if((1!==e.props.length||58===t.charCodeAt(0)||Ye.get(n))&&!r){Ye.set(e,!0);for(var a=[],o=function(e,t){return Oe(function(e,t){var n=-1,r=44;do{switch(Se(r)){case 0:38===r&&12===xe()&&(t[n]=1),e[n]+=Xe(he-1,t,n);break;case 2:e[n]+=Me(r);break;case 4:if(44===r){e[++n]=58===xe()?"&\f":"",t[n]=e[n].length;break}default:e[n]+=ne(r)}}while(r=we());return e}(Ce(e),t))}(t,a),i=n.props,l=0,s=0;l<o.length;l++)for(var c=0;c<i.length;c++,s++)e.props[s]=a[l]?o[l].replace(/&\f/g,i[c]):i[c]+" "+o[l]}}},Ke=function(e){if("decl"===e.type){var t=e.value;108===t.charCodeAt(0)&&98===t.charCodeAt(2)&&(e.return="",e.value="")}};function Ze(e,t){switch(function(e,t){return 45^le(e,0)?(((t<<2^le(e,0))<<2^le(e,1))<<2^le(e,2))<<2^le(e,3):0}(e,t)){case 5103:return ze+"print-"+e+e;case 5737:case 4201:case 3177:case 3433:case 1641:case 4457:case 2921:case 5572:case 6356:case 5844:case 3191:case 6645:case 3005:case 6391:case 5879:case 5623:case 6135:case 4599:case 4855:case 4215:case 6389:case 5109:case 5365:case 5621:case 3829:return ze+e+e;case 5349:case 4246:case 4810:case 6968:case 2756:return ze+e+Ne+e+Re+e+e;case 6828:case 4268:return ze+e+Re+e+e;case 6165:return ze+e+Re+"flex-"+e+e;case 5187:return ze+e+oe(e,/(\w+).+(:[^]+)/,ze+"box-$1$2"+Re+"flex-$1$2")+e;case 5443:return ze+e+Re+"flex-item-"+oe(e,/flex-|-self/,"")+e;case 4675:return ze+e+Re+"flex-line-pack"+oe(e,/align-content|flex-|-self/,"")+e;case 5548:return ze+e+Re+oe(e,"shrink","negative")+e;case 5292:return ze+e+Re+oe(e,"basis","preferred-size")+e;case 6060:return ze+"box-"+oe(e,"-grow","")+ze+e+Re+oe(e,"grow","positive")+e;case 4554:return ze+oe(e,/([^-])(transform)/g,"$1"+ze+"$2")+e;case 6187:return oe(oe(oe(e,/(zoom-|grab)/,ze+"$1"),/(image-set)/,ze+"$1"),e,"")+e;case 5495:case 3959:return oe(e,/(image-set\([^]*)/,ze+"$1$`$1");case 4968:return oe(oe(e,/(.+:)(flex-)?(.*)/,ze+"box-pack:$3"+Re+"flex-pack:$3"),/s.+-b[^;]+/,"justify")+ze+e+e;case 4095:case 3583:case 4068:case 2532:return oe(e,/(.+)-inline(.+)/,ze+"$1$2")+e;case 8116:case 7059:case 5753:case 5535:case 5445:case 5701:case 4933:case 4677:case 5533:case 5789:case 5021:case 4765:if(ce(e)-1-t>6)switch(le(e,t+1)){case 109:if(45!==le(e,t+4))break;case 102:return oe(e,/(.+:)(.+)-([^]+)/,"$1"+ze+"$2-$3$1"+Ne+(108==le(e,t+3)?"$3":"$2-$3"))+e;case 115:return~ie(e,"stretch")?Ze(oe(e,"stretch","fill-available"),t)+e:e}break;case 4949:if(115!==le(e,t+1))break;case 6444:switch(le(e,ce(e)-3-(~ie(e,"!important")&&10))){case 107:return oe(e,":",":"+ze)+e;case 101:return oe(e,/(.+:)([^;!]+)(;|!.+)?/,"$1"+ze+(45===le(e,14)?"inline-":"")+"box$3$1"+ze+"$2$3$1"+Re+"$2box$3")+e}break;case 5936:switch(le(e,t+11)){case 114:return ze+e+Re+oe(e,/[svh]\w+-[tblr]{2}/,"tb")+e;case 108:return ze+e+Re+oe(e,/[svh]\w+-[tblr]{2}/,"tb-rl")+e;case 45:return ze+e+Re+oe(e,/[svh]\w+-[tblr]{2}/,"lr")+e}return ze+e+Re+e+e}return e}var Je=[function(e,t,n,r){if(e.length>-1&&!e.return)switch(e.type){case Fe:e.return=Ze(e.value,e.length);break;case He:return Le([ve(e,{value:oe(e.value,"@","@"+ze)})],r);case Ie:if(e.length)return function(e,t){return e.map(t).join("")}(e.props,(function(t){switch(function(e,t){return(e=/(::plac\w+|:read-\w+)/.exec(e))?e[0]:e}(t)){case":read-only":case":read-write":return Le([ve(e,{props:[oe(t,/:(read-\w+)/,":-moz-$1")]})],r);case"::placeholder":return Le([ve(e,{props:[oe(t,/:(plac\w+)/,":"+ze+"input-$1")]}),ve(e,{props:[oe(t,/:(plac\w+)/,":-moz-$1")]}),ve(e,{props:[oe(t,/:(plac\w+)/,Re+"input-$1")]})],r)}return""}))}}],et=function(e){var t=e.key;if("css"===t){var n=document.querySelectorAll("style[data-emotion]:not([data-s])");Array.prototype.forEach.call(n,(function(e){-1!==e.getAttribute("data-emotion").indexOf(" ")&&(document.head.appendChild(e),e.setAttribute("data-s",""))}))}var r,a,o=e.stylisPlugins||Je,i={},l=[];r=e.container||document.head,Array.prototype.forEach.call(document.querySelectorAll('style[data-emotion^="'+t+' "]'),(function(e){for(var t=e.getAttribute("data-emotion").split(" "),n=1;n<t.length;n++)i[t[n]]=!0;l.push(e)}));var s,c,u,d,p=[qe,(d=function(e){s.insert(e)},function(e){e.root||(e=e.return)&&d(e)})],m=(c=[Qe,Ke].concat(o,p),u=ue(c),function(e,t,n,r){for(var a="",o=0;o<u;o++)a+=c[o](e,t,n,r)||"";return a});a=function(e,t,n,r){s=n,Le(De(e?e+"{"+t.styles+"}":t.styles),m),r&&(f.inserted[t.name]=!0)};var f={key:t,sheet:new ee({key:t,container:r,nonce:e.nonce,speedy:e.speedy,prepend:e.prepend,insertionPoint:e.insertionPoint}),nonce:e.nonce,inserted:i,registered:{},insert:a};return f.sheet.hydrate(l),f},tt={animationIterationCount:1,aspectRatio:1,borderImageOutset:1,borderImageSlice:1,borderImageWidth:1,boxFlex:1,boxFlexGroup:1,boxOrdinalGroup:1,columnCount:1,columns:1,flex:1,flexGrow:1,flexPositive:1,flexShrink:1,flexNegative:1,flexOrder:1,gridRow:1,gridRowEnd:1,gridRowSpan:1,gridRowStart:1,gridColumn:1,gridColumnEnd:1,gridColumnSpan:1,gridColumnStart:1,msGridRow:1,msGridRowSpan:1,msGridColumn:1,msGridColumnSpan:1,fontWeight:1,lineHeight:1,opacity:1,order:1,orphans:1,tabSize:1,widows:1,zIndex:1,zoom:1,WebkitLineClamp:1,fillOpacity:1,floodOpacity:1,stopOpacity:1,strokeDasharray:1,strokeDashoffset:1,strokeMiterlimit:1,strokeOpacity:1,strokeWidth:1},nt=/[A-Z]|^ms/g,rt=/_EMO_([^_]+?)_([^]*?)_EMO_/g,at=function(e){return 45===e.charCodeAt(1)},ot=function(e){return null!=e&&"boolean"!=typeof e},it=K((function(e){return at(e)?e:e.replace(nt,"-$&").toLowerCase()})),lt=function(e,t){switch(e){case"animation":case"animationName":if("string"==typeof t)return t.replace(rt,(function(e,t,n){return ct={name:t,styles:n,next:ct},t}))}return 1===tt[e]||at(e)||"number"!=typeof t||0===t?t:t+"px"};function st(e,t,n){if(null==n)return"";if(void 0!==n.__emotion_styles)return n;switch(typeof n){case"boolean":return"";case"object":if(1===n.anim)return ct={name:n.name,styles:n.styles,next:ct},n.name;if(void 0!==n.styles){var r=n.next;if(void 0!==r)for(;void 0!==r;)ct={name:r.name,styles:r.styles,next:ct},r=r.next;return n.styles+";"}return function(e,t,n){var r="";if(Array.isArray(n))for(var a=0;a<n.length;a++)r+=st(e,t,n[a])+";";else for(var o in n){var i=n[o];if("object"!=typeof i)null!=t&&void 0!==t[i]?r+=o+"{"+t[i]+"}":ot(i)&&(r+=it(o)+":"+lt(o,i)+";");else if(!Array.isArray(i)||"string"!=typeof i[0]||null!=t&&void 0!==t[i[0]]){var l=st(e,t,i);switch(o){case"animation":case"animationName":r+=it(o)+":"+l+";";break;default:r+=o+"{"+l+"}"}}else for(var s=0;s<i.length;s++)ot(i[s])&&(r+=it(o)+":"+lt(o,i[s])+";")}return r}(e,t,n);case"function":if(void 0!==e){var a=ct,o=n(e);return ct=a,st(e,t,o)}}if(null==t)return n;var i=t[n];return void 0!==i?i:n}var ct,ut=/label:\s*([^\s;\n{]+)\s*(;|$)/g,dt=!!i.useInsertionEffect&&i.useInsertionEffect,pt=dt||function(e){return e()},mt=(dt||i.useLayoutEffect,i.createContext("undefined"!=typeof HTMLElement?et({key:"css"}):null));mt.Provider;var ft=i.createContext({}),ht=function(e,t,n){var r=e.key+"-"+t.name;!1===n&&void 0===e.registered[r]&&(e.registered[r]=t.styles)},gt=J,yt=function(e){return"theme"!==e},bt=function(e){return"string"==typeof e&&e.charCodeAt(0)>96?gt:yt},vt=function(e,t,n){var r;if(t){var a=t.shouldForwardProp;r=e.__emotion_forwardProp&&a?function(t){return e.__emotion_forwardProp(t)&&a(t)}:a}return"function"!=typeof r&&n&&(r=e.__emotion_forwardProp),r},_t=function(e){var t=e.cache,n=e.serialized,r=e.isStringTag;return ht(t,n,r),pt((function(){return function(e,t,n){ht(e,t,n);var r=e.key+"-"+t.name;if(void 0===e.inserted[t.name]){var a=t;do{e.insert(t===a?"."+r:"",a,e.sheet,!0),a=a.next}while(void 0!==a)}}(t,n,r)})),null},wt=function e(t,n){var r,a,o=t.__emotion_real===t,l=o&&t.__emotion_base||t;void 0!==n&&(r=n.label,a=n.target);var s=vt(t,n,o),c=s||bt(l),u=!c("as");return function(){var d=arguments,p=o&&void 0!==t.__emotion_styles?t.__emotion_styles.slice(0):[];if(void 0!==r&&p.push("label:"+r+";"),null==d[0]||void 0===d[0].raw)p.push.apply(p,d);else{p.push(d[0][0]);for(var m=d.length,f=1;f<m;f++)p.push(d[f],d[0][f])}var h,g=(h=function(e,t,n){var r,o,d,m,f=u&&e.as||l,h="",g=[],y=e;if(null==e.theme){for(var b in y={},e)y[b]=e[b];y.theme=i.useContext(ft)}"string"==typeof e.className?(r=t.registered,o=g,d=e.className,m="",d.split(" ").forEach((function(e){void 0!==r[e]?o.push(r[e]+";"):m+=e+" "})),h=m):null!=e.className&&(h=e.className+" ");var v=function(e,t,n){if(1===e.length&&"object"==typeof e[0]&&null!==e[0]&&void 0!==e[0].styles)return e[0];var r=!0,a="";ct=void 0;var o=e[0];null==o||void 0===o.raw?(r=!1,a+=st(n,t,o)):a+=o[0];for(var i=1;i<e.length;i++)a+=st(n,t,e[i]),r&&(a+=o[i]);ut.lastIndex=0;for(var l,s="";null!==(l=ut.exec(a));)s+="-"+l[1];var c=function(e){for(var t,n=0,r=0,a=e.length;a>=4;++r,a-=4)t=1540483477*(65535&(t=255&e.charCodeAt(r)|(255&e.charCodeAt(++r))<<8|(255&e.charCodeAt(++r))<<16|(255&e.charCodeAt(++r))<<24))+(59797*(t>>>16)<<16),n=1540483477*(65535&(t^=t>>>24))+(59797*(t>>>16)<<16)^1540483477*(65535&n)+(59797*(n>>>16)<<16);switch(a){case 3:n^=(255&e.charCodeAt(r+2))<<16;case 2:n^=(255&e.charCodeAt(r+1))<<8;case 1:n=1540483477*(65535&(n^=255&e.charCodeAt(r)))+(59797*(n>>>16)<<16)}return(((n=1540483477*(65535&(n^=n>>>13))+(59797*(n>>>16)<<16))^n>>>15)>>>0).toString(36)}(a)+s;return{name:c,styles:a,next:ct}}(p.concat(g),t.registered,y);h+=t.key+"-"+v.name,void 0!==a&&(h+=" "+a);var _=u&&void 0===s?bt(f):c,w={};for(var x in e)u&&"as"===x||_(x)&&(w[x]=e[x]);return w.className=h,w.ref=n,i.createElement(i.Fragment,null,i.createElement(_t,{cache:t,serialized:v,isStringTag:"string"==typeof f}),i.createElement(f,w))},(0,i.forwardRef)((function(e,t){var n=(0,i.useContext)(mt);return h(e,n,t)})));return g.displayName=void 0!==r?r:"Styled("+("string"==typeof l?l:l.displayName||l.name||"Component")+")",g.defaultProps=t.defaultProps,g.__emotion_real=g,g.__emotion_base=l,g.__emotion_styles=p,g.__emotion_forwardProp=s,Object.defineProperty(g,"toString",{value:function(){return"."+a}}),g.withComponent=function(t,r){return e(t,Q({},n,r,{shouldForwardProp:vt(g,r,!0)})).apply(void 0,p)},g}}.bind();["a","abbr","address","area","article","aside","audio","b","base","bdi","bdo","big","blockquote","body","br","button","canvas","caption","cite","code","col","colgroup","data","datalist","dd","del","details","dfn","dialog","div","dl","dt","em","embed","fieldset","figcaption","figure","footer","form","h1","h2","h3","h4","h5","h6","head","header","hgroup","hr","html","i","iframe","img","input","ins","kbd","keygen","label","legend","li","link","main","map","mark","marquee","menu","menuitem","meta","meter","nav","noscript","object","ol","optgroup","option","output","p","param","picture","pre","progress","q","rp","rt","ruby","s","samp","script","section","select","small","source","span","strong","style","sub","summary","sup","table","tbody","td","textarea","tfoot","th","thead","time","title","tr","track","u","ul","var","video","wbr","circle","clipPath","defs","ellipse","foreignObject","g","image","line","linearGradient","mask","path","pattern","polygon","polyline","radialGradient","rect","stop","svg","text","tspan"].forEach((function(e){wt[e]=wt(e)}));const xt=[0,100],kt=[0,100],Et=e=>`${1===e.length?"0":""}${e}`,St=(e,t,n)=>Math.max(Math.min(e,n),t),Ct=(e,t)=>Math.floor(Math.random()*(t-e+1))+e,Ot=(e,t,n)=>{const r=Ct(e,t);for(let a=0;a<n?.length;a++){const o=n[a];if(2===o?.length&&r>=o[0]&&r<=o[1])return Ot(e,t,n)}return r},Mt=(e,t)=>"number"==typeof t?t:e%Math.abs(t[1]-t[0])+t[0],At=(e,t)=>"number"==typeof e?St(Math.abs(e),...t):1===e.length||e[0]===e[1]?St(Math.abs(e[0]),...t):[Math.abs(St(e[0],...t)),St(Math.abs(e[1]),...t)],Tt=(e,t,n)=>(n<0?n+=1:n>1&&(n-=1),n<1/6?e+6*(t-e)*n:n<.5?t:n<2/3?e+(t-e)*(2/3-n)*6:e),Pt=(e,t,n)=>{let r,a,o;if(e/=360,n/=100,0==(t/=100))r=a=o=n;else{const i=n<.5?n*(1+t):n+t-n*t,l=2*n-i;r=Tt(l,i,e+1/3),a=Tt(l,i,e),o=Tt(l,i,e-1/3)}return[Math.round(255*r),Math.round(255*a),Math.round(255*o)]},$t=(e,t,n,r)=>(299*e+587*t+114*n)/1e3>=r,jt=(e,t,n)=>`hsl(${e}, ${t}%, ${n}%)`,Rt=(e,t,n,r)=>"rgb"===r?`rgb(${e}, ${t}, ${n})`:`#${Et(e.toString(16))}${Et(t.toString(16))}${Et(n.toString(16))}`,Nt=(e,{format:t="hex",saturation:n=[50,55],lightness:r=[50,60],differencePoint:a=130}={})=>{const o=Math.abs((e=>{const t=e.length;let n=0;for(let r=0;r<t;r++)n=(n<<5)-n+e.charCodeAt(r),n&=n;return n})(String(e))),i=Mt(o,[0,360]),l=Mt(o,At(n,xt)),s=Mt(o,At(r,kt)),[c,u,d]=Pt(i,l,s);return{color:"hsl"===t?jt(i,l,s):Rt(c,u,d,t),isLight:$t(c,u,d,a)}};Nt.random=({format:e="hex",saturation:t=[50,55],lightness:n=[50,60],differencePoint:r=130,excludeHue:a}={})=>{t=At(t,xt),n=At(n,kt);const o=a?Ot(0,359,a):Ct(0,359),i="number"==typeof t?t:Ct(...t),l="number"==typeof n?n:Ct(...n),[s,c,u]=Pt(o,i,l);return{color:"hsl"===e?jt(o,i,l):Rt(s,c,u,e),isLight:$t(s,c,u,r)}};const zt=Nt,Bt=wt.span`
	white-space: nowrap;
	border-radius: 200px;
	height: 24px;
	line-height: 24px;
	padding: 3px 9px;
	display: inline-block;
`,It=({text:e})=>{const{backgroundColor:t,color:n}=function(e){if(["subscribed","active","yes","true","1"].includes(e))return{backgroundColor:"#78c67a",color:"#111111"};if(["unsubscribed","inactive","no","false","0"].includes(e))return{backgroundColor:"#fbcfbd",color:"#241c15"};if(["pending","waiting","maybe","2"].includes(e))return{backgroundColor:"#fbeeca",color:"#241c15"};const t=zt(e,{saturation:[60,100],lightness:[30,45]});return{backgroundColor:t.color,color:t.isLight?"#111111":"#ffffff"}}(e);return(0,i.createElement)(Bt,{style:{backgroundColor:t,color:n}},e)},Ft=({actionUrl:e,buttonText:t,modalTitle:n,modalDescription:r,icon:a,isDestructive:o=!1})=>{const[s,c]=(0,i.useState)(!1),d=n&&r;return(0,i.createElement)(i.Fragment,null,(0,i.createElement)(l.Button,{icon:a,iconSize:16,size:"compact",showTooltip:!0,label:t,type:"button",onClick:d?()=>c(!0):void 0,href:d?void 0:e,variant:"tertiary"}),s&&(0,i.createElement)(l.Modal,{onRequestClose:()=>c(!1),title:n,size:"small"},(0,i.createElement)(l.__experimentalVStack,{spacing:4},(0,i.createElement)(l.__experimentalText,null,r),(0,i.createElement)(l.__experimentalHStack,{spacing:4,justify:"flex-start",alignment:"flex-start"},(0,i.createElement)(l.Button,{variant:"primary",text:t,type:"button",href:e,isDestructive:o}),(0,i.createElement)(l.Button,{variant:"secondary",text:(0,u.__)("Cancel","newsletter-optin-box"),type:"button",onClick:()=>c(!1)})))))},Ht=e=>{let t=e.label;return"future"===e.status&&(t=(0,u.__)("Scheduled")),(0,i.createElement)(l.__experimentalHStack,{alignment:"center",justify:"flex-start",spacing:1},(0,i.createElement)(It,{text:t}),e.action&&(0,i.createElement)(Ft,{...e.action}))},Lt=(0,i.memo)((({title:e,status:t,id:n})=>{const[r,a]=(0,i.useState)(e),[o,s]=(0,i.useState)(t),[c,d]=(0,i.useState)(!1),[p,f]=(0,i.useState)(""),h=(0,i.useCallback)((e=>{e.preventDefault(),r&&(f(""),d(!0),m()({path:`/wp/v2/noptin-campaign/${n}`,method:"POST",data:{title:r,status:o}}).then((e=>{e.noptin_campaign_type?.edit_url?window.location.href=e.noptin_campaign_type.edit_url:window.location.href=window.location.href})).catch((e=>{f(e.message),d(!1)})))}),[o,r,n]);return(0,i.createElement)(l.__experimentalVStack,{as:"form",onSubmit:h,style:{opacity:c?.5:1},spacing:4},(0,i.createElement)(l.__experimentalInputControl,{label:(0,u.__)("Title","newsletter-optin-box"),value:r,onChange:a,required:!0,disabled:c}),(0,i.createElement)(l.SelectControl,{label:(0,u.__)("Status","newsletter-optin-box"),value:o,options:[{label:(0,u.__)("Draft","newsletter-optin-box"),value:"draft"},{label:(0,u.__)("Pending","newsletter-optin-box"),value:"pending"},{label:(0,u.__)("Published","newsletter-optin-box"),value:"publish"}],onChange:s,disabled:c}),(0,i.createElement)("div",null,(0,i.createElement)(l.Button,{variant:"primary",type:"submit",disabled:c,isBusy:c,isPressed:c},(0,u.__)("Save","newsletter-optin-box"),c&&(0,i.createElement)(l.Spinner,null))),p&&(0,i.createElement)(l.Notice,{status:"error",onDismiss:()=>f("")},p))})),qt=({title:e,status:t,id:n,modalTitle:r})=>{const[a,o]=(0,i.useState)(!1);return(0,i.createElement)(i.Fragment,null,(0,i.createElement)(l.Button,{onClick:()=>o(!0),icon:"edit",label:(0,u.__)("Edit","newsletter-optin-box"),showTooltip:!0,size:"compact",variant:"primary"}),a&&(0,i.createElement)(l.Modal,{onRequestClose:()=>o(!1),title:r,size:"small"},(0,i.createElement)(Lt,{title:e,status:t,id:n})))},Dt=(e,t)=>{if(t){const n=t.getAttribute("data-app"),r=n?JSON.parse(n):{};O.createRoot?(0,O.createRoot)(t).render((0,i.createElement)(e,{...r})):(0,O.render)((0,i.createElement)(e,{...r}),t)}};window.noptin=window.noptin||{},window.noptin.viewCampaigns={components:r},o()((()=>{Dt(V,document.getElementById("noptin-email-campaigns__editor--add-new__in-table")),document.querySelectorAll(".noptin-email-campaigns__editor--add-new__button").forEach((e=>{Dt(W,e)})),document.querySelectorAll(".noptin-email-status__app").forEach((e=>{Dt(Ht,e)})),Dt(qt,document.getElementById("noptin-email-campaigns-parent_edit"))}))},942:(e,t)=>{var n;!function(){"use strict";var r={}.hasOwnProperty;function a(){for(var e="",t=0;t<arguments.length;t++){var n=arguments[t];n&&(e=i(e,o(n)))}return e}function o(e){if("string"==typeof e||"number"==typeof e)return e;if("object"!=typeof e)return"";if(Array.isArray(e))return a.apply(null,e);if(e.toString!==Object.prototype.toString&&!e.toString.toString().includes("[native code]"))return e.toString();var t="";for(var n in e)r.call(e,n)&&e[n]&&(t=i(t,n));return t}function i(e,t){return t?e?e+" "+t:e+t:e}e.exports?(a.default=a,e.exports=a):void 0===(n=function(){return a}.apply(t,[]))||(e.exports=n)}()}},n={};function r(e){var a=n[e];if(void 0!==a)return a.exports;var o=n[e]={exports:{}};return t[e](o,o.exports,r),o.exports}r.m=t,e=[],r.O=(t,n,a,o)=>{if(!n){var i=1/0;for(u=0;u<e.length;u++){for(var[n,a,o]=e[u],l=!0,s=0;s<n.length;s++)(!1&o||i>=o)&&Object.keys(r.O).every((e=>r.O[e](n[s])))?n.splice(s--,1):(l=!1,o<i&&(i=o));if(l){e.splice(u--,1);var c=a();void 0!==c&&(t=c)}}return t}o=o||0;for(var u=e.length;u>0&&e[u-1][2]>o;u--)e[u]=e[u-1];e[u]=[n,a,o]},r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},(()=>{var e={261:0,389:0};r.O.j=t=>0===e[t];var t=(t,n)=>{var a,o,[i,l,s]=n,c=0;if(i.some((t=>0!==e[t]))){for(a in l)r.o(l,a)&&(r.m[a]=l[a]);if(s)var u=s(r)}for(t&&t(n);c<i.length;c++)o=i[c],r.o(e,o)&&e[o]&&e[o][0](),e[o]=0;return r.O(u)},n=globalThis.webpackChunknoptin_premium=globalThis.webpackChunknoptin_premium||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var a=r.O(void 0,[389],(()=>r(453)));a=r.O(a)})();