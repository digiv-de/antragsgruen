define(["require","exports"],function(t,i){"use strict";Object.defineProperty(i,"__esModule",{value:!0});var e=function(){function t(t){this.$widget=t,this.initExportRow()}return t.prototype.recalcLinks=function(){var n=this.$widget.find("input[name=comments]").prop("checked")?1:0;this.$widget.find("a.odsLink").each(function(t,i){var e=$(i).data("href-tpl");e=e.replace("COMMENTS",n),$(i).attr("href",e)})},t.prototype.initExportRow=function(){this.$widget.find("li.checkbox").on("click",function(t){t.stopPropagation()}),this.$widget.find("input[type=checkbox]").change(this.recalcLinks.bind(this)).trigger("change")},t}();i.ProposedProcedureExport=e});
//# sourceMappingURL=ProposedProcedureExport.js.map
