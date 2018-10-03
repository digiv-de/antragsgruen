var SiteCreateWizard=function(){function e(e){this.$root=e,this.firstPanel=$("#SiteCreateWizard").data("init-step"),this.mode=$("#SiteCreateWizard").data("mode"),this.initEvents()}return e.prototype.getRadioValue=function(e,n){var a=this.$root.find("fieldset."+e).find("input:checked");return 0<a.length?a.val():n},e.prototype.getWizardState=function(){return{language:this.getRadioValue("language",null),wording:this.getRadioValue("wording",1),singleMotion:this.getRadioValue("singleMotion",0),motionsInitiatedBy:this.getRadioValue("motionWho",1),motionsDeadlineExists:this.getRadioValue("motionDeadline",0),motionsDeadline:this.$root.find("fieldset.motionDeadline .date input").val(),motionScreening:this.getRadioValue("motionScreening",1),needsSupporters:this.getRadioValue("needsSupporters",0),minSupporters:this.$root.find("input.minSupporters").val(),hasAmendments:this.getRadioValue("hasAmendments",1),amendSinglePara:this.getRadioValue("amendSinglePara",0),amendMerging:this.getRadioValue("amendMerging",0),amendmentInitiatedBy:this.getRadioValue("amendmentWho",1),amendmentDeadlineExists:this.getRadioValue("amendmentDeadline",0),amendmentDeadline:this.$root.find("fieldset.amendmentDeadline .date input").val(),amendScreening:this.getRadioValue("amendScreening",1),hasComments:this.getRadioValue("hasComments",1),hasAgenda:this.getRadioValue("hasAgenda",0),openNow:this.getRadioValue("openNow",0),title:$("#siteTitle").val(),organization:$("#siteOrganization").val(),subdomain:$("#siteSubdomain").val(),contact:$("#siteContact").val()}},e.prototype.showPanel=function(e){this.data=this.getWizardState();var n=e.data("tab");this.$root.find(".wizard .steps li").removeClass("active"),this.$root.find(".wizard .steps ."+n).addClass("active"),this.$activePanel&&this.$activePanel.removeClass("active").addClass("inactive"),e.addClass("active").removeClass("inactive"),this.$activePanel=e;try{var a=window.location.hash=="#"+e.attr("id");""!=window.location.hash&&"#"!=window.location.hash||"#"+e.attr("id")!=this.firstPanel||(a=!0),a||(window.location.hash="#"+e.attr("id").substring(5))}catch(e){console.log(e)}},e.prototype.getNextPanel=function(){switch(this.data=this.getWizardState(),this.$activePanel.attr("id")){case"panelPurpose":case"panelLanguage":return"#panelSingleMotion";case"panelSingleMotion":return 1==this.data.singleMotion?"#panelHasAmendments":"#panelMotionWho";case"panelMotionWho":return 1==this.data.motionsInitiatedBy?"#panelHasAmendments":"#panelMotionDeadline";case"panelMotionDeadline":return"#panelMotionScreening";case"panelMotionScreening":return"#panelNeedsSupporters";case"panelNeedsSupporters":return"#panelHasAmendments";case"panelHasAmendments":return 1==this.data.hasAmendments?"#panelAmendSinglePara":"#panelComments";case"panelAmendSinglePara":return"#panelAmendWho";case"panelAmendWho":return 1==this.data.amendmentInitiatedBy?"#panelComments":"#panelAmendDeadline";case"panelAmendDeadline":return"#panelAmendMerging";case"panelAmendMerging":return"#panelAmendScreening";case"panelAmendScreening":return"#panelComments";case"panelComments":return 1==this.data.singleMotion?"#panelOpenNow":"#panelAgenda";case"panelAgenda":return"#panelOpenNow";case"panelOpenNow":return"#panelSiteData"}},e.prototype.subdomainChange=function(e){var n=this,a=$(e.currentTarget),t=a.val(),i=a.parents(".subdomainRow").first(),s=a.data("query-url").replace(/SUBDOMAIN/,t),o=i.find(".subdomainError");return""==t?(o.addClass("hidden"),void i.removeClass("has-error").removeClass("has-success")):t.match(/^[A-Z0-9äöü](?:[A-Z0-9äöü_\-]{0,61}[A-Z0-9äöü])?$/i)?void $.get(s,function(e){e.available?(o.addClass("hidden"),i.removeClass("has-error"),n.$root.find("button[type=submit]").prop("disabled",!1),e.subdomain==a.val()&&i.addClass("has-success")):(o.removeClass("hidden"),o.html(o.data("template").replace(/%SUBDOMAIN%/,e.subdomain)),i.removeClass("has-success"),e.subdomain==a.val()&&(n.$root.find("button[type=submit]").prop("disabled",!0),i.addClass("has-error")))}):(i.removeClass("has-success").addClass("has-error"),void this.$root.find("button[type=submit]").prop("disabled",!0))},e.prototype.initEvents=function(){var t=this,a=this.$root;this.$activePanel=null,this.data=this.getWizardState(),a.find("input").change(function(){t.data=t.getWizardState()}),a.find(".radio-label input").change(function(){var e=$(this).parents("fieldset").first();e.find(".radio-label").removeClass("active"),e.find(".radio-label input:checked").parents(".radio-label").first().addClass("active")}).trigger("change"),a.find("fieldset.wording input").change(function(){var e=a.find("fieldset.wording input:checked").data("wording-name");a.removeClass("wording_motion").removeClass("wording_manifesto").addClass("wording_"+e)}).trigger("change"),a.find(".input-group.date").each(function(){var e=$(this);e.datetimepicker({locale:e.find("input").data("locale")})}),a.find(".date.motionsDeadline").on("dp.change",function(){$("input.motionsDeadlineExists").prop("checked",!0).change()}),a.find(".date.amendmentDeadline").on("dp.change",function(){$("input.amendDeadlineExists").prop("checked",!0).change()}),a.find("input.minSupporters").change(function(){$("input.needsSupporters").prop("checked",!0).change()}),a.find("#siteSubdomain").on("keyup change",this.subdomainChange.bind(this)),a.find("#siteTitle").on("keyup change",function(){5<=$(this).val().length?$(this).parents(".form-group").first().addClass("has-success"):$(this).parents(".form-group").first().removeClass("has-success")}),a.find("#siteOrganization").on("keyup change",function(){5<=$(this).val().length?$(this).parents(".form-group").first().addClass("has-success"):$(this).parents(".form-group").first().removeClass("has-success")}),a.find("#panelSiteData input").on("keypress",function(e){var n=e.originalEvent;13!==n.charCode&&13!==n.keyCode||e.preventDefault()}),a.find("#panelLanguage input").on("change",function(){var e=a.find("#panelLanguage input:checked").val(),n=a.find("#panelLanguage").data("url").replace(/LNG/,e);window.location.href=n});var n=this;a.find(".navigation .btn-next").click(function(e){"submit"!==$(this).attr("type")&&(e.preventDefault(),n.showPanel($(n.getNextPanel())))}),a.find(".navigation .btn-prev").click(function(e){e.preventDefault(),""!=window.location.hash&&window.history.back()}),$(window).on("hashchange",function(e){var n;e.preventDefault(),n=0===parseInt(window.location.hash.substring(1))?t.firstPanel:"#panel"+window.location.hash.substring(1);var a=$(n);0<a.length&&t.showPanel(a)}),a.find(".step-pane").addClass("inactive"),this.showPanel($(this.firstPanel))},e}();
//# sourceMappingURL=SiteCreateWizard.js.map
