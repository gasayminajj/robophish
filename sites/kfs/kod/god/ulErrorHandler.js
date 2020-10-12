"use strict";define("ulErrorHandler",["jquery","eventDispatcher","error-stack-parser"],function(r,t,e){var o=function(){return function(r){t.apply(this,{}),this._url=r,this.isDevelopmentMode=window.developmentMode,this.errorSendCount=0,this.init()}}();return o.prototype=Object.create(t.prototype),o.prototype.constructor=t,o.prototype.init=function(){this.registerEvent("onWindowError"),this.registerEvent("onAjaxError"),this.registerEvent("sendError"),this.initWindowError(),this.initRequireError(),this.initAjaxError(),this.initOtherError()},o.prototype.initWindowError=function(){var r=this;window.onerror=function(t,o,n,i,s){r.dispatchEvent("onWindowError",{msg:t,url:o,line:n,col:i,error:s});var p=null;try{p=s?e.parse(s):null}catch(a){}r._sendErrorOnServer({type:"runtime error",msg:t,stack:p},s)}},o.prototype.initRequireError=function(){require.onError=function(r){throw r}},o.prototype.initAjaxError=function(){var t=this;r(document).ajaxError(function(r,e,o,n){return o.url.match(/\/admin\/reg_day/)?(r.preventDefault(),!1):(t.dispatchEvent("onAjaxError",{event:r,jqXHR:e,ajaxSettings:o,errorThrown:n}),void t._sendErrorOnServer({type:"ajax error",msg:n,status:e.status,method:o.type,url:o.url,body:o.data},new Error(n)))})},o.prototype.initOtherError=function(){},o.prototype.sendErrorOnServer=function(r){this._sendErrorOnServer(r)},o.prototype._sendErrorOnServer=function(r,t){if(this.dispatchEvent("sendError",r),!(this.isDevelopmentMode||window.browserSupportIgnored||this.errorSendCount>5)){window.dispatch&&window.actions.reportError&&window.dispatch(window.actions.reportError(t||r));var e=new XMLHttpRequest;e.open("POST",this._url,!0),e.setRequestHeader("Content-Type","application/json"),e.send(JSON.stringify(r)),++this.errorSendCount}},o});
//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map

//# sourceMappingURL=ulErrorHandler.js.map
