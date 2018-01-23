define(["require","exports"],function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=function(){function t(t){this.$widget=t,this.interval=null,this.csrf=this.$widget.find("input[name=_csrf]").val(),this.$widget.on("change","input[name=visible]",this.onVisibleChanged.bind(this)),this.initComments(),this.initUpdateWidget(),this.onContentUpdated(),this.$widget.on("click",".contactShow",function(t){t.preventDefault(),$(t.currentTarget).next(".contactDetails").removeClass("hidden")})}return t.prototype.onContentUpdated=function(){this.$widget.find(".commentList").each(function(t,e){e.scrollTop=e.scrollHeight})},t.prototype.onVisibleChanged=function(t){var e=$(t.currentTarget),i={_csrf:this.csrf,visible:e.prop("checked")?1:0,id:e.parents(".item").first().data("id")};$.post(e.data("save-url"),i,function(t){t.success||alert(t.error)})},t.prototype.initComments=function(){var t=this;this.$widget.on("click",".writingOpener",this.openWriting.bind(this)),this.$widget.on("click",".submitComment",function(e){e.preventDefault();var i=$(e.currentTarget);t.submitComment(i.parents("td").first())}),this.$widget.on("click",".cancelWriting",function(t){t.preventDefault(),$(t.currentTarget).parents("td").first().removeClass("writing")}),this.$widget.on("keypress","textarea",function(e){if(e.originalEvent.metaKey&&13===e.originalEvent.keyCode){var i=$(e.currentTarget);t.submitComment(i.parents("td").first())}})},t.prototype.openWriting=function(t){t.preventDefault();var e=$(t.currentTarget).parents("td").first();e.addClass("writing"),e.find("textarea").focus()},t.prototype.submitComment=function(t){var e={_csrf:this.csrf,comment:t.find("textarea").val(),id:t.parents(".item").data("id")};$.post(t.data("post-url"),e,function(e){if(e.success){var i=t.find(".template").clone();i.find(".date").text(e.date_str),i.find(".name").text(e.user_str),i.find(".comment").html(e.text),i.removeClass("template"),i.insertBefore(t.find(".template")),window.setTimeout(function(){t.find(".commentList")[0].scrollTop=t.find(".commentList")[0].scrollHeight},1),t.find("textarea").val(""),t.removeClass("writing")}else alert(e.error)})},t.prototype.skipReload=function(){return this.$widget.find(".comment.writing").length>0},t.prototype.reload=function(){var t=this;this.skipReload()?console.log("No reload, as comment writing is active"):$.get(this.updateUrl,function(e){e.success?(t.$dateField.text(e.date),t.$proposalList.html(e.html),t.onContentUpdated()):alert(e.error)})},t.prototype.startInterval=function(){null===this.interval&&(this.interval=window.setInterval(this.reload.bind(this),5e3))},t.prototype.stopInterval=function(){null!==this.interval&&(window.clearInterval(this.interval),this.interval=null)},t.prototype.initUpdateWidget=function(){var t=this;this.$updateWidget=this.$widget.find(".autoUpdateWidget"),this.$proposalList=this.$widget.find(".reloadContent"),this.$dateField=this.$widget.find(".currentDate .date"),this.updateUrl=this.$widget.data("reload-url");var e=this.$updateWidget.find("#autoUpdateToggle");e.change(function(){e.prop("checked")?(t.reload(),t.startInterval()):t.stopInterval()}).trigger("change")},t}();e.ProposedProcedureOverview=i});
//# sourceMappingURL=ProposedProcedureOverview.js.map
