"use strict";(self.webpackChunk_immonex_kickstart=self.webpackChunk_immonex_kickstart||[]).push([[91],{6764:(n,r,t)=>{t.r(r),t.d(r,{addBacklinkURL:()=>f,getBacklinkURL:()=>l,init:()=>h});var e,i=t(5861),a=t(4687),o=t.n(a);function c(n,r){var t="undefined"!=typeof Symbol&&n[Symbol.iterator]||n["@@iterator"];if(!t){if(Array.isArray(n)||(t=function(n,r){if(!n)return;if("string"==typeof n)return u(n,r);var t=Object.prototype.toString.call(n).slice(8,-1);"Object"===t&&n.constructor&&(t=n.constructor.name);if("Map"===t||"Set"===t)return Array.from(n);if("Arguments"===t||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t))return u(n,r)}(n))||r&&n&&"number"==typeof n.length){t&&(n=t);var e=0,i=function(){};return{s:i,n:function(){return e>=n.length?{done:!0}:{done:!1,value:n[e++]}},e:function(n){throw n},f:i}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var a,o=!0,c=!1;return{s:function(){t=t.call(n)},n:function(){var n=t.next();return o=n.done,n},e:function(n){c=!0,a=n},f:function(){try{o||null==t.return||t.return()}finally{if(c)throw a}}}}function u(n,r){(null==r||r>n.length)&&(r=n.length);for(var t=0,e=new Array(r);t<r;t++)e[t]=n[t];return e}var s=jQuery;function l(){if(inx_state.search.backlink_url)return inx_state.search.backlink_url;var n,r=new URLSearchParams(window.location.search),t=new URLSearchParams,e=c(r);try{for(e.s();!(n=e.n()).done;){var i=n.value;"inx-r-"!==i[0].substring(0,6)&&t.append(i[0],i[1])}}catch(n){e.e(n)}finally{e.f()}var a=window.location.origin+window.location.pathname;return Array.from(t).length>0&&(a+="?"+t.toString()),a}function f(n){var r=arguments.length>1&&void 0!==arguments[1]&&arguments[1];r||(r=l());var t=new URL(n);return t.searchParams.has("inx-backlink-url")&&t.searchParams.delete("inx-backlink-url"),t.searchParams.append("inx-backlink-url",encodeURIComponent(r)),t.toString()}function h(){return(e=e||(0,i.Z)(o().mark((function n(){return o().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:s(window).on("resize",(function(){s(".inx-squared-image").each((function(){s(this).height(s(this).width())}))})),window.setTimeout((function(){s(window).trigger("resize")}),0);case 2:case"end":return n.stop()}}),n)})))).apply(this,arguments)}Function.prototype.inxThrottle=function(n){var r,t=0,e=this;function i(){var i=this;function o(n){t=Date.now(),e.apply(i,n)}var c=n-(Date.now()-t);a(),c<0?o(arguments):r=setTimeout(o,c,arguments)}function a(){r&&(clearTimeout(r),r=void 0)}return i.reset=function(){a(),t=0},i}}}]);