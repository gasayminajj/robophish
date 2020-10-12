"use strict";window.viewportObserver={containers:[],addContainerNode:function(){return function(n,t){n.addEventListener("inviewport",t),this.containers.push(n),this.checkViewport()}}(),addContainers:function(){return function(n,t){var e=[];try{e=Array.prototype.slice.call(window.document.querySelectorAll(n))}catch(i){console.log("viewportObserver Error:",i)}e.length&&(e.forEach(function(n){n.addEventListener("inviewport",t)}),this.containers=this.containers.concat(e),this.checkViewport())}}(),showContainer:function(){return function(n){n.showed||(n.showed=!0,n.dispatchEvent(new Event("inviewport")))}}(),checkViewport:function(){return function(){var n=this;n.containers.forEach(function(t){n.isElementInViewport(t)&&n.showContainer(t)})}}(),addEvents:function(){return function(){var n=this,t=n.limiter(n.checkViewport,100);window.addEventListener("scroll",function(){t.call(n)})}}(),limiter:function(){return function(n,t){var e=null;return t||(t=300),function(){var i=this,r=arguments;clearTimeout(e),e=setTimeout(function(){n.apply(i,r)},t)}}}(),isElementInViewport:function(){return function(n){var t=1<arguments.length&&void 0!==arguments[1]?arguments[1]:50,e=n.getBoundingClientRect(),i=window.innerHeight||document.documentElement.clientHeight,r=e.top-t<=i&&0<=e.top+e.height+t;return r}}()},window.viewportObserver.addEvents();
//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map

//# sourceMappingURL=viewportObserver.js.map
