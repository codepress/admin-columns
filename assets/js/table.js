(()=>{var e={513:e=>{function t(e,t){if(!e)throw new Error(t||"AssertionError")}t.notEqual=function(e,s,n){t(e!=s,n)},t.notOk=function(e,s){t(!e,s)},t.equal=function(e,s,n){t(e==s,n)},t.ok=t,e.exports=t},891:(e,t,s)=>{var n=s(212),i=s(595),r=s(513);function l(e){if(!(this instanceof l))return new l(e);this._name=e||"nanobus",this._starListeners=[],this._listeners={}}e.exports=l,l.prototype.emit=function(e){r.ok("string"==typeof e||"symbol"==typeof e,"nanobus.emit: eventName should be type string or symbol");for(var t=[],s=1,n=arguments.length;s<n;s++)t.push(arguments[s]);var l=i(this._name+"('"+e.toString()+"')"),o=this._listeners[e];return o&&o.length>0&&this._emit(this._listeners[e],t),this._starListeners.length>0&&this._emit(this._starListeners,e,t,l.uuid),l(),this},l.prototype.on=l.prototype.addListener=function(e,t){return r.ok("string"==typeof e||"symbol"==typeof e,"nanobus.on: eventName should be type string or symbol"),r.equal(typeof t,"function","nanobus.on: listener should be type function"),"*"===e?this._starListeners.push(t):(this._listeners[e]||(this._listeners[e]=[]),this._listeners[e].push(t)),this},l.prototype.prependListener=function(e,t){return r.ok("string"==typeof e||"symbol"==typeof e,"nanobus.prependListener: eventName should be type string or symbol"),r.equal(typeof t,"function","nanobus.prependListener: listener should be type function"),"*"===e?this._starListeners.unshift(t):(this._listeners[e]||(this._listeners[e]=[]),this._listeners[e].unshift(t)),this},l.prototype.once=function(e,t){r.ok("string"==typeof e||"symbol"==typeof e,"nanobus.once: eventName should be type string or symbol"),r.equal(typeof t,"function","nanobus.once: listener should be type function");var s=this;return this.on(e,(function n(){t.apply(s,arguments),s.removeListener(e,n)})),this},l.prototype.prependOnceListener=function(e,t){r.ok("string"==typeof e||"symbol"==typeof e,"nanobus.prependOnceListener: eventName should be type string or symbol"),r.equal(typeof t,"function","nanobus.prependOnceListener: listener should be type function");var s=this;return this.prependListener(e,(function n(){t.apply(s,arguments),s.removeListener(e,n)})),this},l.prototype.removeListener=function(e,t){return r.ok("string"==typeof e||"symbol"==typeof e,"nanobus.removeListener: eventName should be type string or symbol"),r.equal(typeof t,"function","nanobus.removeListener: listener should be type function"),"*"===e?(this._starListeners=this._starListeners.slice(),s(this._starListeners,t)):(void 0!==this._listeners[e]&&(this._listeners[e]=this._listeners[e].slice()),s(this._listeners[e],t));function s(e,t){if(e){var s=e.indexOf(t);return-1!==s?(n(e,s,1),!0):void 0}}},l.prototype.removeAllListeners=function(e){return e?"*"===e?this._starListeners=[]:this._listeners[e]=[]:(this._starListeners=[],this._listeners={}),this},l.prototype.listeners=function(e){var t="*"!==e?this._listeners[e]:this._starListeners,s=[];if(t)for(var n=t.length,i=0;i<n;i++)s.push(t[i]);return s},l.prototype._emit=function(e,t,s,n){if(void 0!==e&&0!==e.length){void 0===s&&(s=t,t=null),t&&(s=void 0!==n?[t].concat(s,n):[t].concat(s));for(var i=e.length,r=0;r<i;r++){var l=e[r];l.apply(l,s)}}}},746:(e,t,s)=>{var n=s(513),i="undefined"!=typeof window;function r(e){this.hasWindow=e,this.hasIdle=this.hasWindow&&window.requestIdleCallback,this.method=this.hasIdle?window.requestIdleCallback.bind(window):this.setTimeout,this.scheduled=!1,this.queue=[]}r.prototype.push=function(e){n.equal(typeof e,"function","nanoscheduler.push: cb should be type function"),this.queue.push(e),this.schedule()},r.prototype.schedule=function(){if(!this.scheduled){this.scheduled=!0;var e=this;this.method((function(t){for(;e.queue.length&&t.timeRemaining()>0;)e.queue.shift()(t);e.scheduled=!1,e.queue.length&&e.schedule()}))}},r.prototype.setTimeout=function(e){setTimeout(e,0,{timeRemaining:function(){return 1}})},e.exports=function(){var e;return i?(window._nanoScheduler||(window._nanoScheduler=new r(!0)),e=window._nanoScheduler):e=new r,e}},595:(e,t,s)=>{var n,i=s(746)(),r=s(513);l.disabled=!0;try{n=window.performance,l.disabled="true"===window.localStorage.DISABLE_NANOTIMING||!n.mark}catch(e){}function l(e){if(r.equal(typeof e,"string","nanotiming: name should be type string"),l.disabled)return o;var t=(1e4*n.now()).toFixed()%Number.MAX_SAFE_INTEGER,s="start-"+t+"-"+e;function a(r){var l="end-"+t+"-"+e;n.mark(l),i.push((function(){var i=null;try{var o=e+" ["+t+"]";n.measure(o,s,l),n.clearMarks(s),n.clearMarks(l)}catch(e){i=e}r&&r(i,e)}))}return n.mark(s),a.uuid=t,a}function o(e){e&&i.push((function(){e(new Error("nanotiming: performance API unavailable"))}))}e.exports=l},212:e=>{"use strict";e.exports=function(e,t,s){var n,i=e.length;if(!(t>=i||0===s)){var r=i-(s=t+s>i?i-t:s);for(n=t;n<r;++n)e[n]=e[n+s];e.length=r}}},311:e=>{"use strict";e.exports=jQuery}},t={};function s(n){var i=t[n];if(void 0!==i)return i.exports;var r=t[n]={exports:{}};return e[n](r,r.exports,s),r.exports}s.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return s.d(t,{a:t}),t},s.d=(e,t)=>{for(var n in t)s.o(t,n)&&!s.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},s.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{"use strict";const e=(e,t)=>{t.parentNode.insertBefore(e,t.nextSibling)};var t=s(891);class n{constructor(e){this.container=e,this.events=t(),this.init()}init(){this.container.addEventListener("update",(()=>{this.refresh()}));let t=document.querySelectorAll(".tablenav.top .actions");t&&t.length&&(e(this.container,t[t.length-1]),this.container.classList.add("-init"),this.container.dispatchEvent(new CustomEvent("update")))}refresh(){this.container.querySelectorAll(".ac-table-actions-buttons > a").forEach((e=>{e.classList.remove("last")}));let e=[].slice.call(this.container.querySelectorAll(".ac-table-actions-buttons > a"),0);e.reverse();for(var t=0;t<e.length;t++)if(e[t].offsetParent){e[t].classList.add("last");break}}}class i{constructor(){this.cells={}}add(e,t){this.cells.hasOwnProperty(e)||(this.cells[e]={}),this.cells[e][t.getName()]=t}getByID(e){let t=[];if(!this.cells.hasOwnProperty(e.toString()))return t;let s=this.cells[e.toString()];return Object.keys(s).forEach((e=>t.push(s[e]))),t}getAll(){let e=[];return Object.keys(this.cells).forEach((t=>{let s=this.cells[t];Object.keys(s).forEach((t=>e.push(s[t])))})),e}getByName(e){let t=[];return Object.keys(this.cells).forEach((s=>{let n=this.cells[s];Object.keys(n).forEach((s=>{e===s&&t.push(n[s])}))})),t}get(e,t){return this.cells.hasOwnProperty(e.toString())?this.cells[e][t]:null}}class r{constructor(e){this.table=e,this.columns={},this.init()}init(){let e=this,t=this.table.querySelector("thead").querySelectorAll("th");for(let s=0;s<t.length;s++){let n=t[s].id;e.columns[t[s].id]=new l(n,AC.column_types[n],this.sanitizeLabel(t[s]))}}getColumns(){return this.columns}getColumnsMap(){let e=new Map,t=this.getColumns();return Object.keys(t).forEach((s=>{e.set(s,t[s])})),e}getColumnNames(){return Object.keys(this.columns)}get(e){return this.columns.hasOwnProperty(e)?this.columns[e]:null}sanitizeLabel(e){let t=e.querySelector("a"),s=e.innerHTML;if(t){let e=t.getElementsByTagName("span");e.length>0&&(s=e[0].innerHTML)}return s}}class l{constructor(e,t,s){this.name=e,this.type=t,this.label=s,this.services={}}setService(e,t){this.services[e]=t}getService(e){return this.hasService(e)?this.services[e]:null}hasService(e){return this.services.hasOwnProperty(e)}}var o=s(891),a=s.n(o);class h{constructor(e,t,s){this.object_id=e,this.column_name=t,this.original_value=s.innerHTML,this.el=s,this.services={},this.events=new(a())}getOriginalValue(){return this.original_value}getObjectID(){return this.object_id}getName(){return this.column_name}getElement(){return this.el}setElement(e){this.el=e}getRow(){return this.el.parentElement}getSettings(){return AC_SERVICES.getService("Table").Columns.get(this.getName())}hasChanged(e){return this.original_value!==e}setValue(e){let t=this.el.querySelector(".row-actions");return this.original_value=e,this.el.innerHTML=e,t&&this.el.append(t),this.original_value=e,this.events.emit("setValue",this),this}setService(e,t){this.services[e]=t}getService(e){return this.hasService(e)?this.services[e]:null}hasService(e){return this.services.hasOwnProperty(e)}}class c{constructor(e){this.Table=e}getIDs(){let e=[],t=this.Table.getElement().querySelectorAll("tbody th.check-column input[type=checkbox]:checked");if(0===t.length)return e;for(let s=0;s<t.length;s++)e.push(parseInt(t[s].value));return e}getSelectedCells(e){let t=this.getIDs();if(0===t.length)return null;let s=[];return t.forEach((t=>{let n=this.Table.Cells.get(t,e);n&&s.push(n)})),s}getCount(){return this.getIDs().length}isAllSelected(){return!!this.Table.getElement().querySelector("thead #cb input:checked")}}const u=e=>{if(e.classList.contains("no-items"))return 0;let t=d(e.id);if(!t){let s=e.querySelector(".check-column input[type=checkbox]");s&&(t=d(s.id))}if(!t){let n=e.parentElement.querySelector(".edit a");if(n){let e=n.getAttribute("href");e&&(t=parseInt(("id",(s=e).includes("?")?new URLSearchParams(s.split("?")[1]).get("id"):null)))}}var s;return e.dataset.id=t.toString(),t},d=e=>{let t=e.split(/[_,\-]+/);return parseInt(t[t.length-1])},m="Table.Ready";class p{constructor(){this.services={}}setService(e,t){this.services[e]=t}getService(e){return this.hasService(e)?this.services[e]:null}hasService(e){return this.services.hasOwnProperty(e)}}class g{constructor(e,t){this.el=e,this.AcServices=t,this.Services=new p,this.Columns=new r(e),this.Cells=new i,this.Actions=document.getElementById("ac-table-actions")?new n(document.getElementById("ac-table-actions")):null,this.Selection=new c(this)}getElement(){return this.el}getIdsFromTable(){let e=[];return this.el.getElementsByTagName("tbody")[0].querySelectorAll("tr").forEach((t=>{e.push(u(t))})),e}init(){return this.initTable(),this.addCellClasses(),document.dispatchEvent(new CustomEvent("AC_Table_Ready",{detail:{table:this}})),this.AcServices.emitEvent(m,{table:this}),this}addCellClasses(){this.Columns.getColumnNames().forEach((e=>{let t=this.Columns.get(e).type;this.Cells.getByName(e).forEach((e=>{e.getElement().classList.add(t)}))}))}initTable(){this.el.getElementsByTagName("tbody")[0].querySelectorAll("tr").forEach((e=>{this.updateRow(e)}))}updateRow(e){let t=u(e);e.dataset.id=t.toString(),this.setCellsForRow(e)}setCellsForRow(e){let t=u(e);this.Columns.getColumnNames().forEach((s=>{let n=s.replace(/\./g,"\\."),i=e.querySelector("td.column-"+n);if(i){let e=new h(t,s,i);this.Cells.add(t,e)}}))}}class f{constructor(e,t=""){this.element=e,this.content=t||e.dataset.acTip,this.tip=v(this.content),this.initEvents()}initEvents(){"1"!==this.element.dataset.acTooltipInit&&(this.element.dataset.acTooltipInit="1",this.element.addEventListener("mouseenter",(()=>{const e=document.body.getBoundingClientRect(),t=this.element.getBoundingClientRect();document.body.appendChild(this.tip),this.tip.style.left=t.left-e.left+this.element.offsetWidth/2+"px",this.tip.style.top=t.top-e.top+this.element.offsetHeight+"px",this.tip.classList.add("hover")})),this.element.addEventListener("mouseleave",(()=>{this.tip.classList.remove("hover"),document.body.removeChild(this.tip)})))}}const v=e=>{let t=document.createElement("div");return t.classList.add("ac-tooltip"),t.innerHTML=e,t},y=class{constructor(){this.init()}init(){document.querySelectorAll("[data-ac-tip]").forEach((e=>{new f(e)}))}},b=e=>E.create(e);class E{constructor(e){this.element=e instanceof HTMLElement?e:document.createElement(e)}static create(e){return new E(e)}addId(e){return this.element.id=e,this}addClass(e){return this.element.classList.add(e),this}addClasses(...e){return e.forEach((e=>this.addClass(e))),this}setAttribute(e,t){return this.element.setAttribute(e,t),this}setAttributes(e){return Object.keys(e).forEach((t=>this.setAttribute(t,e[t]))),this}addHtml(e){return this.element.innerHTML=e,this}append(e){return this.element.appendChild(e),this}css(e,t){return this.element.style[e]=t,this}insertAfter(e){try{this.element.parentElement.insertBefore(e,this.element.nextElementSibling)}catch(e){console.error("Not able to insert element after current node",this.element)}}insertSelfBefore(e){try{e.parentElement.insertBefore(this.element,e)}catch(e){console.error("Not able to insert element before current node",this.element)}return this}insertBefore(e){try{this.element.parentElement.insertBefore(e,this.element)}catch(e){console.error("Not able to insert element before current node",this.element)}return this}addEventListener(e,t){return this.element.addEventListener(e,t),this}addEventListeners(e,t){return e.forEach((e=>this.addEventListener(e,t))),this}}class w{constructor(e){this.columns=e,e.getColumnNames().forEach((t=>{let s=e.get(t),n=w.getInputByName(s.name);n&&0===n.parentElement.textContent.length&&n.parentElement.appendChild(E.create("span").addHtml(s.label).element)}))}static getInputByName(e){return document.querySelector(`input[name='${e}-hide']`)||!1}}const C=s(311);class L{constructor(e){this.element=e,this.initEvents(),this.contentBox=this.element.parentElement.querySelector(".ac-toggle-box-contents"),this.contentBox||this.createContenBox()}isAjax(){return 1===parseInt(this.element.dataset.ajaxPopulate)}isInited(){return this.element.dataset.toggleBoxInit}createContenBox(){let t=document.createElement("div");return t.classList.add("ac-toggle-box-contents"),e(t,this.element),this.contentBox=t,this.contentBox}initEvents(){this.isInited()||(this.element.addEventListener("click",(e=>{e.preventDefault(),this.isAjax()&&!this.hasContent()&&this.manageAjaxValue(),this.toggleContentBox()})),this.element.dataset.toggleBoxInit="true")}hasContent(){return this.getContentBox().innerHTML.length>0}setContent(e){this.getContentBox().innerHTML=e}getContentBox(){return this.contentBox?this.contentBox:this.createContenBox()}setLabel(e){let t=this.element.dataset.label;e&&this.element.dataset.labelClose&&(t=this.element.dataset.labelClose),this.element.innerHTML=t+'<span class="spinner"></span>'}toggleContentBox(){this.getContentBox().classList.contains("-open")?(this.getContentBox().classList.remove("-open"),this.setLabel(!1)):(this.getContentBox().classList.add("-open"),this.setLabel(!0))}manageAjaxValue(){this.element.classList.add("loading"),this.retrieveAjaxValue().done((e=>{this.setContent(e),C(this.element.parentElement).trigger("ajax_column_value_ready"),AC_SERVICES.getService("Tooltips").init()})).always((()=>{this.element.classList.remove("loading")}))}retrieveAjaxValue(){return C.ajax({url:ajaxurl,method:"POST",data:{action:"ac_get_column_value",list_screen:AC.list_screen,layout:AC.layout,column:this.element.dataset.column,pk:this.element.dataset.itemId,_ajax_nonce:AC.ajax_nonce}})}}var S=s(311),_=s.n(S);const A=()=>{document.querySelectorAll(".ac-show-more").forEach((e=>{new T(e)}))};class T{constructor(e){this.element=e,this.initEvents()}initEvents(){this.isInited()||(this.getToggler()&&this.getToggler().addEventListener("click",(e=>{e.preventDefault(),e.stopPropagation(),this.toggle()})),this.element.dataset.showMoreInit="true")}getToggler(){return this.element.querySelector(".ac-show-more__toggle")}isInited(){return"true"===this.element.dataset.showMoreInit}toggle(){this.element.classList.contains("-on")?this.hide():this.show()}show(){this.element.classList.add("-on"),this.getToggler().innerHTML=this.getToggler().dataset.less}hide(){this.element.classList.remove("-on"),this.getToggler().innerHTML=this.getToggler().dataset.more}}class x{constructor(e){e&&(this.el=e,this.dialog=e.querySelector(".ac-modal__dialog"),this.initEvents())}getElement(){return this.el}initEvents(){let e=this;document.addEventListener("keydown",(e=>{const t=e.key;this.isOpen()&&"Escape"===t&&this.close()}));let t=this.el.querySelectorAll('[data-dismiss="modal"], .ac-modal__dialog__close');t.length>0&&t.forEach((t=>{t.addEventListener("click",(t=>{t.preventDefault(),e.close()}))})),this.el.addEventListener("click",(t=>{t.target.classList.contains("ac-modal")&&e.close()}))}isOpen(){return this.el.classList.contains("-active")}close(){this.onClose(),this.el.classList.remove("-active")}open(){setTimeout((()=>{this.onOpen(),this.el.removeAttribute("style"),this.el.classList.add("-active")}))}destroy(){this.el.remove()}onClose(){}onOpen(){}}const k=s(311);class I{constructor(e){this.element=e,this.settings=this.getDefaults(),this.init(),this.setInitialized()}setInitialized(){q.add(this.element)}getDefaults(){return{width:this.element.getAttribute("data-width")?this.element.getAttribute("data-width"):250,noclick:!!this.element.getAttribute("data-noclick")&&this.element.getAttribute("data-noclick"),position:this.getPosition()}}isInitialized(){return q.isInitialized(this.element)}init(){this.isInitialized()||(k(this.element).mouseenter((()=>{k(this.element).pointer({content:this.getRelatedHTML(),position:this.settings.position,pointerWidth:this.settings.width,pointerClass:this.getPointerClass()})})),this.initEvents())}getPosition(){let e={at:"left top",my:"right top",edge:"right"},t=this.element.getAttribute("data-pos"),s=this.element.getAttribute("data-pos_edge");return"right"===t&&(e={at:"right middle",my:"left middle",edge:"left"}),"right_bottom"===t&&(e={at:"right middle",my:"left bottom",edge:"none"}),"left"===t&&(e={at:"left middle",my:"right middle",edge:"right"}),s&&(e.edge=s),e}getPointerClass(){let e=["ac-wp-pointer","wp-pointer","wp-pointer-"+this.settings.position.edge];return this.settings.noclick&&e.push("noclick"),e.join(" ")}getRelatedHTML(){let e=document.getElementById(this.element.getAttribute("rel"));return e?e.innerHTML:""}initEvents(){let e=k(this.element);this.settings.noclick||e.click((function(){e.hasClass("open")?e.removeClass("open"):e.addClass("open")})),e.click((function(){e.pointer("open")})),e.mouseenter((function(){e.pointer("open"),setTimeout((()=>{e.pointer("open")}),2)})),e.mouseleave((function(){setTimeout((()=>{e.hasClass("open")||0!==k(".ac-wp-pointer.hover").length||e.pointer("close")}),1)})),e.on("close",(()=>{setTimeout((()=>{e.hasClass("open")||e.pointer("close")}))}))}}class q{static isInitialized(e){return this.initElements.filter((t=>t===e)).length>0}static add(e){this.initElements.push(e)}}q.initElements=[];const B=(e=null)=>{e||(e=document.querySelectorAll(".ac-pointer")),e.forEach((e=>{new I(e)})),k(".ac-wp-pointer").hover((function(){k(this).addClass("hover")}),(function(){k(this).removeClass("hover"),k(".ac-pointer").trigger("close")})).on("click",".close",(function(){k(".ac-pointer").removeClass("open")})),new y};let O=(window.AC_SERVICES||(window.AC_SERVICES=new class{constructor(){this.services={},this.events=new(a()),this.$=b}registerService(e,t){return this.services[e]=t,this}getService(e){return this.hasService(e)?this.services[e]:null}hasService(e){return this.services.hasOwnProperty(e)}addListener(e,t){this.events.addListener(e,t)}emitEvent(e,t){this.events.emit(e,t)}}),window.AC_SERVICES);O.registerService("Modals",new class{constructor(){this.modals={},this.number=0,this.defaults={modal:x},this.initGlobalEvents()}register(e,t=""){return t||(t="m"+this.number),this.modals[t]=e,this.number++,e}get(e){return this.modals.hasOwnProperty(e)?this.modals[e]:null}open(e){this.get(e)&&this.get(e).open()}close(e){this.get(e)&&this.get(e).close()}closeAll(){for(let e in this.modals)this.close(e)}initGlobalEvents(){document.addEventListener("click",(e=>{let t=e.target;t.dataset.acModal&&(e.preventDefault(),this.open(t.dataset.acModal))}))}}),document.addEventListener("DOMContentLoaded",(()=>{let e=(e=>{let t=document.querySelector(e);return t?"TABLE"===t.tagName?t:"TBODY"===t.tagName?t.closest("table"):t.querySelector("table.wp-list-table")?t.querySelector("table.wp-list-table"):null:null})(AC.table_id);if(B(),e){const t=new g(e,O).init();O.registerService("Table",t),O.registerService("ScreenOptionsColumns",new w(t.Columns))}O.registerService("Tooltips",new y),document.querySelectorAll(".ac-toggle-box-link").forEach((e=>{new L(e)})),_()(".wp-list-table").on("updated","tr",(function(){O.getService("Table").addCellClasses(),A()}))})),O.addListener(m,(e=>{A(),document.querySelectorAll(".cpac_use_icons").forEach((e=>{e.parentElement.querySelectorAll(".row-actions a").forEach((e=>{new f(e,e.innerText)}))})),new MutationObserver((e=>{e.forEach((e=>{e.addedNodes.forEach((e=>{"TR"===e.tagName&&e.classList.contains("iedit")&&_()(e).trigger("updated",{id:u(e),row:e})}))}))})).observe(e.table.getElement(),{childList:!0,subtree:!0}),e.table.Cells.getAll().forEach((e=>{e.events.addListener("setValue",(()=>{A()}))}))}))})()})();