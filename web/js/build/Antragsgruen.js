!function(t){t("[data-antragsgruen-load-class]").each(function(){var a=t(this).data("antragsgruen-load-class");requirejs([a])}),t("[data-antragsgruen-widget]").each(function(){var a=t(this),n=a.data("antragsgruen-widget");requirejs([n],function(t){var e=n.split("/");new t[e[e.length-1]](a)})}),t(".jsProtectionHint").each(function(){var a=t(this);t('<input type="hidden" name="jsprotection">').attr("value",a.data("value")).appendTo(a.parent()),a.remove()}),t(document).on("click",".amendmentAjaxTooltip",function(a){var n=t(a.currentTarget);"0"==n.data("initialized")&&(n.data("initialized","1"),n.popover({html:!0,trigger:"manual",container:"body",template:'<div class="popover popover-amendment-ajax" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',content:function(){var a="pop_"+(new Date).getTime(),e='<div id="'+a+'">Loading...</div>',o=n.data("url");return t.get(o,function(n){t("#"+a).html(n)}),e}})),t(".amendmentAjaxTooltip").not(n).popover("hide"),n.popover("toggle")}),t(document).on("click",function(a){var n=t(a.target);n.hasClass("amendmentAjaxTooltip")||n.hasClass("popover")||0!=n.parents(".amendmentAjaxTooltip").length||0!=n.parents(".popover").length||t(".amendmentAjaxTooltip").popover("hide")});var a=function(n){var e="0.";n.find("> li.agendaItem").each(function(){var n=t(this),o=n.data("code"),i="",r=n.find("> ol");if("#"==o){var d=e.split("."),p=d[0].match(/^(.*[^0-9])?([0-9]*)$/),c=void 0===p[1]?"":p[1],l=parseInt(""==p[2]?"1":p[2]);d[0]=c+ ++l,e=i=d.join(".")}else i=e=o;n.find("> div > h3 .code").text(i),r.length>0&&a(r)})};t("ol.motionListAgenda").on("antragsgruen:agenda-change",function(){a(t(this))}).trigger("antragsgruen:agenda-change"),window.__t=function(t,a){return"undefined"==typeof ANTRAGSGRUEN_STRINGS?"@TRANSLATION STRINGS NOT LOADED":void 0===ANTRAGSGRUEN_STRINGS[t]?"@UNKNOWN CATEGORY: "+t:void 0===ANTRAGSGRUEN_STRINGS[t][a]?"@UNKNOWN STRING: "+t+" / "+a:ANTRAGSGRUEN_STRINGS[t][a]}}(jQuery);
//# sourceMappingURL=Antragsgruen.js.map