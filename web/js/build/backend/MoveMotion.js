var __awaiter=this&&this.__awaiter||function(t,a,s,u){return new(s||(s=Promise))(function(n,e){function i(t){try{r(u.next(t))}catch(t){e(t)}}function o(t){try{r(u.throw(t))}catch(t){e(t)}}function r(t){t.done?n(t.value):function(n){return n instanceof s?n:new s(function(t){t(n)})}(t.value).then(i,o)}r((u=u.apply(t,a||[])).next())})},__generator=this&&this.__generator||function(e,i){var o,r,a,t,s={label:0,sent:function(){if(1&a[0])throw a[1];return a[1]},trys:[],ops:[]};return t={next:n(0),throw:n(1),return:n(2)},"function"==typeof Symbol&&(t[Symbol.iterator]=function(){return this}),t;function n(n){return function(t){return function(n){if(o)throw new TypeError("Generator is already executing.");for(;s;)try{if(o=1,r&&(a=2&n[0]?r.return:n[0]?r.throw||((a=r.return)&&a.call(r),0):r.next)&&!(a=a.call(r,n[1])).done)return a;switch(r=0,a&&(n=[2&n[0],a.value]),n[0]){case 0:case 1:a=n;break;case 4:return s.label++,{value:n[1],done:!1};case 5:s.label++,r=n[1],n=[0];continue;case 7:n=s.ops.pop(),s.trys.pop();continue;default:if(!(a=0<(a=s.trys).length&&a[a.length-1])&&(6===n[0]||2===n[0])){s=0;continue}if(3===n[0]&&(!a||n[1]>a[0]&&n[1]<a[3])){s.label=n[1];break}if(6===n[0]&&s.label<a[1]){s.label=a[1],a=n;break}if(a&&s.label<a[2]){s.label=a[2],s.ops.push(n);break}a[2]&&s.ops.pop(),s.trys.pop();continue}n=i.call(e,s)}catch(t){n=[6,t],r=0}finally{o=a=0}if(5&n[0])throw n[1];return{value:n[0]?n[1]:void 0,done:!0}}([n,t])}}};define(["require","exports"],function(t,n){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.MoveMotion=void 0;var e=function(){function t(t){this.$form=t,this.checkBackend=t.data("check-backend"),this.initTarget(),this.initConsultation(),this.initButtonEnabled()}return t.prototype.initTarget=function(){var n=this,e=this.$form.find("input[name=target]");e.on("change",function(){var t=e.filter(":checked").val();"agenda"===t?n.$form.find(".moveToAgendaItem").removeClass("hidden"):n.$form.find(".moveToAgendaItem").addClass("hidden"),"consultation"===t?n.$form.find(".moveToConsultationItem").removeClass("hidden"):n.$form.find(".moveToConsultationItem").addClass("hidden"),n.rebuildMotionTypes()}).trigger("change")},t.prototype.initConsultation=function(){$("#consultationId").on("changed.fu.selectlist",this.rebuildMotionTypes.bind(this))},t.prototype.rebuildMotionTypes=function(){var t=$("#consultationId").find("input[name=consultation]").val();$(".moveToMotionTypeId").addClass("hidden"),"consultation"===this.$form.find("input[name=target]:checked").val()&&$(".moveToMotionTypeId"+t).removeClass("hidden")},t.prototype.isPrefixAvailable=function(t,n){return $.get(this.checkBackend,{checkType:"prefix",newMotionPrefix:t,newConsultationId:n}).then(function(t){return console.log(t),t})},t.prototype.rebuildButtonEnabled=function(){return __awaiter(this,void 0,void 0,function(){var n,e;return __generator(this,function(t){switch(t.label){case 0:return n=!0,e="consultation"===this.$form.find("input[name=target]:checked").val()?parseInt(this.$form.find("input[name=consultation]").val()):null,[4,this.isPrefixAvailable(this.$form.find("#motionTitlePrefix").val(),e)];case 1:return t.sent()?this.$form.find(".prefixAlreadyTaken").addClass("hidden"):(this.$form.find(".prefixAlreadyTaken").removeClass("hidden"),n=!1),this.$form.find("input[name=operation]:checked").val()||(n=!1),this.$form.find("input[name=target]:checked").val()||(n=!1),this.$form.find("button[type=submit]").prop("disabled",!n),[2]}})})},t.prototype.initButtonEnabled=function(){this.$form.find("#motionTitlePrefix").on("change keyup",this.rebuildButtonEnabled.bind(this)),this.$form.find("input[name=operation]").on("change",this.rebuildButtonEnabled.bind(this)),this.$form.find("input[name=target]").on("change",this.rebuildButtonEnabled.bind(this)),$("#consultationId").on("changed.fu.selectlist",this.rebuildButtonEnabled.bind(this)),this.rebuildButtonEnabled()},t}();n.MoveMotion=e});
//# sourceMappingURL=MoveMotion.js.map
