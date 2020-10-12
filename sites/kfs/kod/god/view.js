"use strict";define("widget-imagezoom-view",[],function(){var e=window.cache.imagezoom&&window.cache.imagezoom.i18n;return{name:"imagezoom-view",opened:!1,init:function(){return function(){window.constructorMode||!window.MSInputMethodContext||!document.documentMode||require(["js/lib/picturefill.min.js"])}}(),open:function(){return function(e){this.opened||(this.init(),this.opened=!0);var t=document.getElementById(e);if(t){var n={};try{n=JSON.parse(t.dataset.options)}catch(i){return void console.log("Error on JSON.parse",i)}n.el=t,this.eventLightBox(n)}}}(),eventLightBox:function(){return function(e){var t=this,n=e.el,i=n.querySelector(".js-image-lightbox"),o=n.querySelector(".ul-w-imagezoom-img"),r=this.lightBoxOpen,c=this.lightBoxOpenOld;if("function"==typeof window.requireFullConfOnce&&(r=function(){return function(e){window.requireFullConfOnce(function(){t.lightBoxOpen(e)})}}(),c=function(){return function(e){window.requireFullConfOnce(function(){t.lightBoxOpenOld(e)})}}()),i&&("lightbox"===e.click?i.addEventListener("click",r):i.removeEventListener("click",r)),o)if("lightbox"===e.click){var u=o.querySelector("img");u.addEventListener("click",c),o.dataset.lightbox||(o.dataset.lightbox=!0)}else o.removeEventListener("click",c)}}(),lightBoxOpen:function(){return function(t){var n=t.target,i=n.dataset.lightbox,o=n.querySelector(".js-alt"),r="";o&&(r=o.textContent),require(["lightbox","jquery"],function(t){t.show({srcList:[{src:i,title:r}],i18n:e})})}}(),lightBoxOpenOld:function(){return function(t){require(["lightbox","jquery"],function(n,i){var o=i(this);o.find("img").parent().is("a")||i(t.target).hasClass("ul-handler-resize")||n.show({srcList:[{src:o.attr("data-lightbox")||o.attr("data-img"),title:o.parents(".ul-w-imagezoom").find(".js-alt").text()}],i18n:e})})}}(),destroy:function(){return function(e){var t=document.getElementById(e);t&&t.parentNode.removeChild(t)}}()}});
//# sourceMappingURL=view.js.map

//# sourceMappingURL=view.js.map

//# sourceMappingURL=view.js.map

//# sourceMappingURL=view.js.map

//# sourceMappingURL=view.js.map

//# sourceMappingURL=view.js.map

//# sourceMappingURL=view.js.map

//# sourceMappingURL=view.js.map

//# sourceMappingURL=view.js.map

//# sourceMappingURL=view.js.map

//# sourceMappingURL=view.js.map
