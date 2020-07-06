var CONTACT_NONE=0,CONTACT_OPTIONAL=1,CONTACT_REQUIRED=2,SUPPORTER_ONLY_INITIATOR=0,SUPPORTER_GIVEN_BY_INITIATOR=1,SUPPORTER_COLLECTING_SUPPORTERS=2,TYPE_TITLE=0,TYPE_TEXT_SIMPLE=1,TYPE_TEXT_HTML=2,TYPE_IMAGE=3,TYPE_TABULAR=4,TYPE_PDF_ATTACHMENT=5,TYPE_PDF_ALTERNATIVE=6,MotionTypeEdit=function(){function e(){$(".deleteTypeOpener button").on("click",function(){$(".deleteTypeForm").removeClass("hidden"),$(".deleteTypeOpener").addClass("hidden")}),$('[data-toggle="tooltip"]').tooltip(),this.initSectionList(),this.initDeadlines(),this.initInitiatorForm($("#motionSupportersForm")),this.initInitiatorForm($("#amendmentSupportersForm"));var e=$("#sameInitiatorSettingsForAmendments input");e.on("change",function(){e.prop("checked")?$("section.amendmentSupporters").addClass("hidden"):$("section.amendmentSupporters").removeClass("hidden")}).trigger("change")}return e.prototype.initInitiatorForm=function(n){var a=this,t=n.find(".contactGender input"),i=n.find(".supportType"),r=n.find(".formGroupAllowMore input");r.on("change",function(){var e=parseInt(i.find("input").val(),10);(e===SUPPORTER_COLLECTING_SUPPORTERS||e)===SUPPORTER_GIVEN_BY_INITIATOR&&r.is(":checked")?n.find(".formGroupAllowAfterPub").removeClass("hidden"):n.find(".formGroupAllowAfterPub").addClass("hidden")}).trigger("change"),i.on("changed.fu.selectlist",function(){var e=i.find("input").val();i.find('li[data-value="'+e+'"]').data("has-supporters")?(n.find(".formGroupMinSupporters").removeClass("hidden"),n.find(".formGroupAllowMore").removeClass("hidden"),a.motionsHaveSupporters=!0):(n.find(".formGroupMinSupporters").addClass("hidden"),n.find(".formGroupAllowMore").addClass("hidden"),a.motionsHaveSupporters=!1),t.trigger("change"),r.trigger("change"),a.setMaxPdfSupporters()}).trigger("changed.fu.selectlist");var e=n.find("input[name=initiatorCanBePerson]"),d=n.find("input[name=initiatorCanBeOrganization]");e.on("change",function(){e.prop("checked")?n.find(".formGroupGender").removeClass("hidden"):(n.find(".formGroupGender").addClass("hidden"),d.prop("checked")||d.prop("checked",!0).trigger("change"))}),d.on("change",function(){d.prop("checked")?n.find(".formGroupResolutionDate").removeClass("hidden"):(n.find(".formGroupResolutionDate").addClass("hidden"),e.prop("checked")||e.prop("checked",!0).trigger("change"))}),t.on("change",function(){var e=parseInt(t.filter(":checked").val(),10),a=parseInt(i.find("input").val(),10);e!==CONTACT_NONE&&a===SUPPORTER_COLLECTING_SUPPORTERS?n.find(".formGroupMinFemale").removeClass("hidden"):n.find(".formGroupMinFemale").addClass("hidden")}).trigger("change")},e.prototype.setMaxPdfSupporters=function(){this.amendmentsHaveSupporters||this.motionsHaveSupporters?$("#typeMaxPdfSupportersRow").removeClass("hidden"):$("#typeMaxPdfSupportersRow").addClass("hidden")},e.prototype.initDeadlines=function(){$("#deadlineFormTypeComplex").on("change",function(e){$(e.currentTarget).prop("checked")?($(".deadlineTypeSimple").addClass("hidden"),$(".deadlineTypeComplex").removeClass("hidden")):($(".deadlineTypeSimple").removeClass("hidden"),$(".deadlineTypeComplex").addClass("hidden"))}).trigger("change"),$(".datetimepicker").each(function(e,a){var n=$(a);n.datetimepicker({locale:n.find("input").data("locale")})});var i=function(e){var n=e.find(".datetimepickerFrom"),t=e.find(".datetimepickerTo");n.datetimepicker({locale:n.find("input").data("locale")}),t.datetimepicker({locale:t.find("input").data("locale"),useCurrent:!1});var a=function(){!function(){var e=n.data("DateTimePicker").date(),a=t.data("DateTimePicker").date();return e&&a&&a.isBefore(e)}()?(n.removeClass("has-error"),t.removeClass("has-error")):(n.addClass("has-error"),t.addClass("has-error"))};n.on("dp.change",a),t.on("dp.change",a)};$(".deadlineEntry").each(function(e,a){i($(a))}),$(".deadlineHolder").each(function(e,a){var n=$(a),t=function(){var e=$(".deadlineRowTemplate").html();e=e.replace(/TEMPLATE/g,n.data("type"));var a=$(e);n.find(".deadlineList").append(a),i(a)};n.find(".deadlineAdder").on("click",t),n.on("click",".delRow",function(e){$(e.currentTarget).parents(".deadlineEntry").remove()}),0===n.find(".deadlineList").children().length&&t()})},e.prototype.initSectionList=function(){var i=$("#sectionsList"),r=0;i.data("sortable",Sortable.create(i[0],{handle:".drag-handle",animation:150})),i.on("click","a.remover",function(e){e.preventDefault();var a=$(this).parents("li").first(),n=a.data("id");bootbox.confirm(__t("admin","deleteMotionSectionConfirm"),function(e){e&&($(".adminTypeForm").append('<input type="hidden" name="sectionsTodelete[]" value="'+n+'">'),a.remove())})}),i.on("change",".sectionType",function(){var e=$(this).parents("li").first(),a=parseInt($(this).val());e.removeClass("title textHtml textSimple image tabularData pdfAlternative pdfAttachment"),a===TYPE_TITLE?e.addClass("title"):a===TYPE_TEXT_SIMPLE?e.addClass("textSimple"):a===TYPE_TEXT_HTML?e.addClass("textHtml"):a===TYPE_IMAGE?e.addClass("image"):a===TYPE_TABULAR?(e.addClass("tabularData"),0==e.find(".tabularDataRow ul > li").length&&e.find(".tabularDataRow .addRow").trigger("click").trigger("click").trigger("click")):a===TYPE_PDF_ATTACHMENT?e.addClass("pdfAttachment"):a===TYPE_PDF_ALTERNATIVE&&e.addClass("pdfAlternative")}),i.find(".sectionType").trigger("change"),i.on("change",".maxLenSet",function(){var e=$(this).parents("li").first();$(this).prop("checked")?e.addClass("maxLenSet").removeClass("no-maxLenSet"):e.addClass("no-maxLenSet").removeClass("maxLenSet")}),i.find(".maxLenSet").trigger("change"),$(".sectionAdder").on("click",function(e){e.preventDefault();var a=$("#sectionTemplate").html();a=a.replace(/#NEW#/g,"new"+r);var n=$(a);i.append(n),r+=1,i.find(".sectionType").trigger("change"),i.find(".maxLenSet").trigger("change");var t=n.find(".tabularDataRow ul");t.data("sortable",Sortable.create(t[0],{handle:".drag-data-handle",animation:150}))});var d=0;i.on("click",".tabularDataRow .addRow",function(e){e.preventDefault();var a=$(this),n=a.parent().find("ul"),t=$(a.data("template").replace(/#NEWDATA#/g,"new"+d));d+=1,t.removeClass("no0").addClass("no"+n.children().length),n.append(t),t.find("input").trigger("focus")}),i.on("click",".tabularDataRow .delRow",function(e){var a=$(this);e.preventDefault(),bootbox.confirm(__t("admin","deleteDataConfirm"),function(e){e&&a.parents("li").first().remove()})}),i.find(".tabularDataRow ul").each(function(){$(this).data("sortable",Sortable.create(this,{handle:".drag-data-handle",animation:150}))})},e}();new MotionTypeEdit;
//# sourceMappingURL=MotionTypeEdit.js.map
