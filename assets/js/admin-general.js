/*! For license information please see admin-general.js.LICENSE.txt */
(()=>{var e={486:(e,t,i)=>{var n,s,o;o=function(){function e(){for(var e=0,t={};e<arguments.length;e++){var i=arguments[e];for(var n in i)t[n]=i[n]}return t}function t(e){return e.replace(/(%[0-9A-Z]{2})+/g,decodeURIComponent)}return function i(n){function s(){}function o(t,i,o){if("undefined"!=typeof document){"number"==typeof(o=e({path:"/"},s.defaults,o)).expires&&(o.expires=new Date(1*new Date+864e5*o.expires)),o.expires=o.expires?o.expires.toUTCString():"";try{var r=JSON.stringify(i);/^[\{\[]/.test(r)&&(i=r)}catch(e){}i=n.write?n.write(i,t):encodeURIComponent(String(i)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),t=encodeURIComponent(String(t)).replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent).replace(/[\(\)]/g,escape);var l="";for(var c in o)o[c]&&(l+="; "+c,!0!==o[c]&&(l+="="+o[c].split(";")[0]));return document.cookie=t+"="+i+l}}function r(e,i){if("undefined"!=typeof document){for(var s={},o=document.cookie?document.cookie.split("; "):[],r=0;r<o.length;r++){var l=o[r].split("="),c=l.slice(1).join("=");i||'"'!==c.charAt(0)||(c=c.slice(1,-1));try{var a=t(l[0]);if(c=(n.read||n)(c,a)||t(c),i)try{c=JSON.parse(c)}catch(e){}if(s[a]=c,e===a)break}catch(e){}}return e?s[e]:s}}return s.set=o,s.get=function(e){return r(e,!1)},s.getJSON=function(e){return r(e,!0)},s.remove=function(t,i){o(t,"",e(i,{expires:-1}))},s.defaults={},s.withConverter=i,s}((function(){}))},void 0===(s="function"==typeof(n=o)?n.call(t,i,t,e):n)||(e.exports=s),e.exports=o()},311:e=>{"use strict";e.exports=jQuery}},t={};function i(n){var s=t[n];if(void 0!==s)return s.exports;var o=t[n]={exports:{}};return e[n](o,o.exports,i),o.exports}(()=>{"use strict";let e=i(486);class t{constructor(e){this.element=e,this.init()}init(){if(this.element.classList.contains("-closable")){const t=this.element.querySelector(".ac-section__header");if(t&&t.addEventListener("click",(()=>{this.toggle()})),this.isStorable()){let t=e.get(this.getCookieKey());void 0!==t&&(1===parseInt(t)?this.open:this.close())}}}getCookieKey(){return`ac-section_${this.getSectionId()}`}getSectionId(){return this.element.dataset.section}isStorable(){return void 0!==this.element.dataset.section}toggle(){this.isOpen()?this.close():this.open()}isOpen(){return!this.element.classList.contains("-closed")}open(){this.element.classList.remove("-closed"),this.isStorable()&&e.set(this.getCookieKey(),1)}close(){this.element.classList.add("-closed"),this.isStorable()&&e.set(this.getCookieKey(),0)}}class n{constructor(e,t=""){this.element=e,this.content=t||e.dataset.acTip,this.tip=s(this.content),this.initEvents()}initEvents(){"1"!==this.element.dataset.acTooltipInit&&(this.element.dataset.acTooltipInit="1",this.element.addEventListener("mouseenter",(()=>{const e=document.body.getBoundingClientRect(),t=this.element.getBoundingClientRect();document.body.appendChild(this.tip),this.tip.style.left=t.left-e.left+this.element.offsetWidth/2+"px",this.tip.style.top=t.top-e.top+this.element.offsetHeight+"px",this.tip.classList.add("hover")})),this.element.addEventListener("mouseleave",(()=>{this.tip.classList.remove("hover"),document.body.removeChild(this.tip)})))}}const s=e=>{let t=document.createElement("div");return t.classList.add("ac-tooltip"),t.innerHTML=e,t},o=i(311);class r{constructor(e){this.element=e,this.settings=this.getDefaults(),this.init(),this.setInitialized()}setInitialized(){l.add(this.element)}getDefaults(){return{width:this.element.getAttribute("data-width")?this.element.getAttribute("data-width"):250,noclick:!!this.element.getAttribute("data-noclick")&&this.element.getAttribute("data-noclick"),position:this.getPosition()}}isInitialized(){return l.isInitialized(this.element)}init(){this.isInitialized()||(o(this.element).mouseenter((()=>{o(this.element).pointer({content:this.getRelatedHTML(),position:this.settings.position,pointerWidth:this.settings.width,pointerClass:this.getPointerClass()})})),this.initEvents())}getPosition(){let e={at:"left top",my:"right top",edge:"right"},t=this.element.getAttribute("data-pos"),i=this.element.getAttribute("data-pos_edge");return"right"===t&&(e={at:"right middle",my:"left middle",edge:"left"}),"right_bottom"===t&&(e={at:"right middle",my:"left bottom",edge:"none"}),"left"===t&&(e={at:"left middle",my:"right middle",edge:"right"}),i&&(e.edge=i),e}getPointerClass(){let e=["ac-wp-pointer","wp-pointer","wp-pointer-"+this.settings.position.edge];return this.settings.noclick&&e.push("noclick"),e.join(" ")}getRelatedHTML(){let e=document.getElementById(this.element.getAttribute("rel"));return e?e.innerHTML:""}initEvents(){let e=o(this.element);this.settings.noclick||e.click((function(){e.hasClass("open")?e.removeClass("open"):e.addClass("open")})),e.click((function(){e.pointer("open")})),e.mouseenter((function(){e.pointer("open"),setTimeout((()=>{e.pointer("open")}),2)})),e.mouseleave((function(){setTimeout((()=>{e.hasClass("open")||0!==o(".ac-wp-pointer.hover").length||e.pointer("close")}),1)})),e.on("close",(()=>{setTimeout((()=>{e.hasClass("open")||e.pointer("close")}))}))}}class l{static isInitialized(e){return this.initElements.filter((t=>t===e)).length>0}static add(e){this.initElements.push(e)}}l.initElements=[];const c=(e=null)=>{e||(e=document.querySelectorAll(".ac-pointer")),e.forEach((e=>{new r(e)})),o(".ac-wp-pointer").hover((function(){o(this).addClass("hover")}),(function(){o(this).removeClass("hover"),o(".ac-pointer").trigger("close")})).on("click",".close",(function(){o(".ac-pointer").removeClass("open")})),new class{constructor(){this.init()}init(){document.querySelectorAll("[data-ac-tip]").forEach((e=>{new n(e)}))}}},a=i(311);a(document).ready((()=>{c(),document.querySelectorAll(".ac-section").forEach((e=>{new t(e)})),a(document).on("select2:open",(()=>{let e=document.querySelector(".select2-container--open .select2-search__field");e&&e.focus()}))}))})()})();