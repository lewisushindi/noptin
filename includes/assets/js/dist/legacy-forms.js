(()=>{"use strict";var e;e=function(){document.querySelectorAll(".noptin-optin-form-wrapper form, .wp-block-noptin-email-optin form, .noptin-email-optin-widget form").forEach((function(e){!function(e){var n=document.createElement("label");n.style.display="none",n.innerHTML='<input type="checkbox" name="noptin_confirm_submit"/>Are you sure?',e.prepend(n),e.addEventListener("submit",(function(n){n.preventDefault(),e.style.opacity=.5,e.querySelector(".noptin_feedback_success").innerHTML="",e.querySelector(".noptin_feedback_error").innerHTML="",e.querySelector(".noptin_feedback_success").style.display="none",e.querySelector(".noptin_feedback_error").style.display="none";var t={};new FormData(e).forEach((function(e,n){t[n]=e})),t.action="noptin_new_subscriber",t.nonce=noptin.nonce,t.conversion_page=window.location.href,window.fetch(noptin.ajaxurl,{method:"POST",body:new URLSearchParams(t),credentials:"same-origin",headers:{Accept:"application/json","Content-Type":"application/x-www-form-urlencoded"}}).then((function(e){if(e.status>=200&&e.status<300)return e;throw e.text()})).then((function(e){return e.json()})).then((function(n){if(!n)throw noptin.connect_err;if(!1===n.success)throw n.data;if(!0!==n.success)throw"Invalid response";"redirect"===n.data.action&&(window.location.href=n.data.redirect),n.data.msg&&(e.innerHTML='<div class="noptin-big noptin-padded">'+n.data.msg+"</div>",e.style.opacity=1,e.style.display="flex",e.style.justifyContent="center",setTimeout((function(){document.querySelector(".noptin-showing")&&document.querySelector(".noptin-showing").classList.remove("noptin-showing")}),2e3))})).then((function(){try{"function"==typeof window.gtag?window.gtag("event","subscribe",{method:"Noptin Form"}):"function"==typeof window.ga&&window.ga("send","event","Noptin Form","Subscribe","Noptin")}catch(e){console.error(e.message)}})).catch((function(n){var t;console.log(n),t="string"==typeof n?n:noptin.connect_err,e.querySelector(".noptin_feedback_error").innerHTML=t,e.querySelector(".noptin_feedback_error").style.display="block",e.querySelector(".noptin_feedback_success").style.display="none",e.style.opacity=1}))}))}(e)})),document.querySelectorAll(".wp-block-noptin-email-optin form input[type=email], .noptin-email-optin-widget form input[type=email]").forEach((function(e){e.setAttribute("name","email")})),"undefined"!=typeof jQuery&&jQuery(".noptin-popup-close").on("click",(function(e){e.preventDefault(),jQuery(this).closest(".noptin-showing").removeClass("noptin-showing")})),document.addEventListener("click",(function(e){var n=document.querySelector(".noptin-showing");if(e.target.matches(".noptin-mark-as-existing-subscriber")){e.preventDefault();var t=function(e){var n=new Date;n.setTime(n.getTime()+2592e6);var t="expires="+n.toUTCString();document.cookie="".concat(e,"=1;").concat(t,";path=").concat(noptin.cookie_path)};noptin.cookie&&t(noptin.cookie),t("noptin_email_subscribed"),n&&jQuery&&jQuery(this).closest(".noptin-showing").removeClass("noptin-showing"),window.noptin_popups&&(window.noptin_popups.subscribed=!0)}}))},"undefined"!=typeof document&&("complete"!==document.readyState&&"interactive"!==document.readyState?document.addEventListener("DOMContentLoaded",e):e())})();