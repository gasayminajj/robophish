'use strict';

define('eventDispatcher',[],function(){

var a=function(){return function(d){
this.name=d,
this.callbacks=[];
}}();

a.prototype.registerCallback=function(c){
this.callbacks.push(c);
};

var b=function(){return function(){
this.events={};
}}();


















return b.prototype.registerEvent=function(c){return this.events[c]=new a(c),this},b.prototype.dispatchEvent=function(c,d){return this.events[c].callbacks.forEach(function(e){e(d)}),this},b.prototype.addEventListener=function(c,d){return this.events[c].registerCallback(d),this},b;
});
//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map

//# sourceMappingURL=eventDispatcher.js.map
