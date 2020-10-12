"use strict";var _typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};!function(){var e=window.cache.lazyLoad,n=e&&[50,300,500][e.range-1]||50,t=window.VERSION&&"?v="+window.VERSION||"",r=function(){return function(e){var n=e.src,r=document.createElement("script");r.setAttribute("src",""+n+t),document.head.appendChild(r)}}(),o=function(){return function(e,n){var t=null;return n||(n=300),function(){var r=this,o=arguments;clearTimeout(t),t=setTimeout(function(){e.apply(r,o)},n)}}}(),i=function(){return function(e){var t=e.getBoundingClientRect(),r=window.innerHeight||document.documentElement.clientHeight,o=t.top-n<=r&&0<=t.top+t.height+n;return o}}(),u=['[class*="srcset"]','[class*="background-images"]',"img[data-src]","img[data-srcset]","source[data-srcset]","source[data-src]"],c=function(){return function(e){-1!==e.className.indexOf("srcset-block")&&e.classList.add("srcset-lazy");for(var n,t=e.querySelectorAll(u.join(",")),r=0;r<t.length;r++)if(n=t[r],!!i(n)){-1!==n.className.indexOf("srcset-widget")&&n.classList.add("srcset-lazy");var o=n.getAttribute("data-src");o&&(n.setAttribute("src",o),n.removeAttribute("data-src"));var c=n.getAttribute("data-srcset");c&&(n.setAttribute("srcset",c),n.removeAttribute("data-srcset"))}return!1}}(),s=!1,d=new Map,a=window.getSafeWidgetsData(),l=function(){return function(e){for(var n,t=e.querySelectorAll(".ul-widget"),r=function(){return function(e){var n=t[e];if(!i(n))return"continue";var r=n.dataset.widget,o=0;if(s){for(var u,c=0;c<a.length;c++)if(u=a[c],u.id===n.id&&!u.loaded){o=u;break}if(!o)return"continue";var l=window.widgetsDeps[r];return l?(o.loaded=!0,window.require([l],function(e){e.open(n.id)}),"continue"):"continue"}var f=window.widgetsDepsPaths[r];return f?"undefined"==typeof f.greenJs?(v(),d.clear(),{v:!0}):void d.set(n.id,f.greenJs):"continue"}}(),o=0;o<t.length;o++)switch(n=r(o)){case"continue":continue;default:if("object"===("undefined"==typeof n?"undefined":_typeof(n)))return n.v}return!1}}(),f=new function(){return{elements:[],selectors:[],loadAll:function(){return function(){for(var e,n=0;n<this.selectors.length;n++)if(e=this.selectors[n],this.load(e))return}}(),load:function(){return function(e){var n=document.querySelector(e);if(!n.children)return!1;for(var t,r=0;r<n.children.length;r++)if(t=n.children[r],!!i(t))for(var o,u=0;u<this.elements.length;u++)if(o=this.elements[u],"function"==typeof o&&o(t))return!0;return!1}}()}};e&&e.enabled&&f.elements.push(c),f.elements.push(l),window.cache.isScreenshotMode?document.querySelector("#body-fict")&&f.selectors.push("#body-fict"):(document.querySelector("header")&&f.selectors.push("header"),document.querySelector("#ul-content")&&f.selectors.push("#ul-content"),document.querySelector("footer")&&f.selectors.push("footer"));var w=o(function(){f.loadAll()},100),h=function(){return function(){window.require&&(!window.cache.isExistCustomHtml&&window.require(["ulErrorHandler"],function(e){return new e("/api/errors")}),window.cache.orderForms&&window.cache.orderForms.length&&window.require(["aDialogAppearOptions"],function(){})),r({src:"/js/ulib/viewportObserver.js"})}}(),v=function(){return function(){s||(s=!0,window.cache.isRequireConfLoaded?h():(window.addEventListener("requireConfReady",h,{once:!0}),r({src:"/js/requireConf.js"})),window.addEventListener("scroll",w))}}(),p=function(){return function(e){window.requireFullConfOnce(function(){for(var n=function(){return function(n){var t=e[n],r=window.widgetsDeps[t.type];window.require([r],function(e){e.open(t.id)})}}(),t=0;t<e.length;t++)n(t)})}}(),m=function(){return function(){if(f.loadAll(),s)window.requireFullConfOnce(function(){f.loadAll()});else{var e={};d.forEach(function(n){n.viewPath&&(e[n.viewName]=n.viewPath)});var n={baseUrl:"/",paths:e};window.VERSION&&(n.urlArgs="v="+window.VERSION),window.requirejs.config(n)}for(var t,r=[],o=function(){return function(e){var n=a[e];if(n.data&&n.data.abs&&r.push(n),s||!d.has(n.id))return"continue";var t=d.get(n.id),o=t.viewName;n.loaded=!0,window.require([o],function(e){e.open(n.id)})}}(),e=0;e<a.length;e++)t=o(e),"continue"===t;r.length&&(s?p(r):setTimeout(function(){p(r)},3e3))}}();window.addEventListener("load",m),window.cache.isRequireConfLoaded?v():(window.addEventListener("requireFullConf",v,{once:!0}),window.addEventListener("scroll",v,{once:!0}),window.addEventListener("resize",w))}();
//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map

//# sourceMappingURL=critical.js.map
