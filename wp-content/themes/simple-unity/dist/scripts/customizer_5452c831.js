!function(t){var n={};function e(o){if(n[o])return n[o].exports;var r=n[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,e),r.l=!0,r.exports}e.m=t,e.c=n,e.d=function(t,n,o){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:o})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},e.p="/wp-content/themes/simple-unity/dist/",e(e.s=13)}({0:function(t,n){t.exports=jQuery},13:function(t,n,e){t.exports=e(14)},14:function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var o=e(0),r=e.n(o);wp.customize("blogname",function(t){t.bind(function(t){return r()(".brand").text(t)})}),wp.customize("theme_fonts",function(t){t.bind(function(t){return r()("body").attr("data-font",t)})}),wp.customize("theme_color",function(t){t.bind(function(t){return r()("body").attr("data-color",t)})})}});