(()=>{var t;(t=jQuery)("#noptin-form-editor-nav-tab-wrapper .nav-tab").on("click",(function(n){n.preventDefault();var e=t(this).data("id");t("#noptin-form-editor-nav-tab-wrapper .nav-tab-active:not(.noptin-form-tab-".concat(e,")")).removeClass("nav-tab-active"),t(this).addClass("nav-tab-active").blur(),t(".noptin-form-tab-content-active:not(.noptin-form-tab-content-".concat(e,")")).removeClass("noptin-form-tab-content-active"),t(".noptin-form-tab-content-".concat(e)).addClass("noptin-form-tab-content-active");var i=t(".noptin-form-tab-content-active h2:first-of-type").text();if(i){var o=document.title.split("-");document.title=document.title.replace(o[0],i+" ")}})),t("#noptin-form-editor-app").on("click","#noptin-form-editor-container .noptin-accordion-trigger",(function(n){n.preventDefault();var e=t(this).closest(".noptin-settings-panel"),i=e.find(".noptin-accordion-trigger");"true"===i.attr("aria-expanded")?(i.attr("aria-expanded","false"),e.addClass("noptin-settings-panel__hidden",!0)):(i.attr("aria-expanded","true"),e.removeClass("noptin-settings-panel__hidden",!1))})),t("#noptin-form-fields-panel-fields .noptin-button-add-field").on("click",(function(n){n.preventDefault(),t("#noptin-form-fields-panel-fields .form-fields-inner").append(t("#noptin-form-fields-panel-new-field-template").html())})),t("#noptin-form-editor-app").on("change",".noptin-form-settings-field-type",(function(n){n.preventDefault();var e=t(this).val(),i=t(this).closest(".noptin-settings-panel__content");i.html(t("#noptin-form-fields-panel-".concat(e,"-template")).html()).attr("id","noptin-form-fields-panel-fields-".concat(e,"-content")).closest(".noptin-settings-panel").attr("id","noptin-form-fields-panel-fields-".concat(e)).find(".noptin-accordion-trigger").first().attr("aria-controls","noptin-form-fields-panel-fields-".concat(e,"-content")).find(".badge").text(e).show(),i.find(".noptin-form-field-label").trigger("input")})),t("#noptin-form-fields-panel-fields").on("input",".noptin-form-field-label",(function(){t(this).closest(".noptin-settings-panel").find(".noptin-accordion-trigger .title").first().text(t(this).val())})),t("#noptin-form-fields-panel-fields").on("click",".noptin-field-editor-delete",(function(n){var e=this;n.preventDefault(),t(this).closest(".noptin-settings-panel").fadeOut(400,(function(){t(e).delete()}))})),t(".noptin-color-picker").wpColorPicker(),t("#noptin-form-border-style").on("change",(function(){t(".form-field-row.form-field-row-form-border").toggle("none"!=t(this).val())})),t("#noptin-form-border-style").trigger("change"),t(".noptin-preview-form-button").on("change",(function(n){var e=this;n.preventDefault(),jQuery.post(t(this).data("url"),t("#noptin-form-editor-app").serialize()).done((function(){e.showSuccess(e.savingSuccess)})).fail((function(){e.showError(e.savingError)})).always((function(){e.isSaving=!1}))}))})();