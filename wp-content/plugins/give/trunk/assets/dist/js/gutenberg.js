!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=867)}({10:function(e,t){function n(t){return"function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?e.exports=n=function(e){return typeof e}:e.exports=n=function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n(t)}e.exports=n},117:function(e,t){function n(t,r){return e.exports=n=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},n(t,r)}e.exports=n},20:function(e,t){e.exports=function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}},21:function(e,t){function n(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}e.exports=function(e,t,r){return t&&n(e.prototype,t),r&&n(e,r),e}},25:function(e,t,n){var r=n(117);e.exports=function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&r(e,t)}},33:function(e,t){function n(t){return e.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},n(t)}e.exports=n},48:function(e,t,n){var r=n(10),o=n(20);e.exports=function(e,t){return!t||"object"!==r(t)&&"function"!=typeof t?o(e):t}},698:function(e,t,n){},699:function(e,t,n){},7:function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},700:function(e,t){e.exports=lodash},701:function(e,t,n){},702:function(e,t,n){},867:function(e,t,n){"use strict";n.r(t);n(698);var r=function(e){var t,n=e.size,r=void 0===n?"24px":n,o=e.color,a=e.className;switch(o){case"white":t="#FFFFFF";break;case"grey":t="#555d66";break;default:t="#66BB6A"}return wp.element.createElement("svg",{id:"Layer_1",width:r,height:r,className:a,xmlns:"http://www.w3.org/2000/svg",xmlnsXlink:"http://www.w3.org/1999/xlink",viewBox:"100 0 404 400"},wp.element.createElement("g",{id:"Layer_2"},wp.element.createElement("circle",{fill:t,cx:"300",cy:"200",r:"200"}),wp.element.createElement("defs",null,wp.element.createElement("circle",{id:"SVGID_1_",cx:"300",cy:"200",r:"200"})),wp.element.createElement("clippath",{id:"SVGID_2_"},wp.element.createElement("use",{xlinkHref:"#SVGID_1_",overflow:"visible"})),wp.element.createElement("path",{clipPath:"url(#SVGID_2_)",fill:"#FFF",d:"M328.5,214.2c0.8,1.8,2.5,3.3,2.5,3.3c35.4,4.3,85.5-0.5,123.7-5.6 c-21.9,47.1-61.1,78.4-96.9,78.4c-67.4,0-119.3-81.7-119.3-81.7c20.9-18.3,55.2-78.4,104.8-78.4s71.2,27.2,71.2,27.2l5.6-8.9 c0,0-23.2-81.2-88.8-81.2S195.9,175.1,155.2,199.7c0,0,56,132.8,178.6,132.8c102.8,0,128.8-98.2,133.6-122.6 c13.7-2,25.2-4.1,32.6-5.3c2.5-5.6,5.3-15.5,3.3-28.8c-41,15.8-103.1,33.6-175.8,33.6C327.2,209.4,327.5,212,328.5,214.2z"})))},o={id:{type:"number",default:0},prevId:{type:"number"},displayStyle:{type:"string",default:"onpage"},continueButtonTitle:{type:"string",default:""},showTitle:{type:"boolean",default:!0},showGoal:{type:"boolean",default:!0},contentDisplay:{type:"boolean",default:!0},showContent:{type:"string",default:"above"}},a=n(7),l=n.n(a),i=(n(699),n(700),n(701),wp.i18n.__),c=function(){return wp.element.createElement("p",{className:"give-blank-slate__help"},"Need help? Get started with ",wp.element.createElement("a",{href:"http://docs.givewp.com/give101/",target:"_blank",rel:"noopener noreferrer"},i("GiveWP 101")))},s=(n(702),function(){return wp.element.createElement("div",{className:"placeholder-animation"},wp.element.createElement("div",{className:"timeline-wrapper"},wp.element.createElement("div",{className:"timeline-item"},wp.element.createElement("div",{className:"animated-background"},wp.element.createElement("div",{className:"layer label layer-4"},wp.element.createElement("div",{className:"layer-item"}),wp.element.createElement("div",{className:"layer-item opaque"}),wp.element.createElement("div",{className:"layer-item opaque"}),wp.element.createElement("div",{className:"layer-item"}),wp.element.createElement("div",{className:"layer-item opaque"})),wp.element.createElement("div",{className:"layer-gap small"}),wp.element.createElement("div",{className:"layer h2 layer-5"},wp.element.createElement("div",{className:"layer-item"}),wp.element.createElement("div",{className:"layer-item opaque"}),wp.element.createElement("div",{className:"layer-item opaque"}),wp.element.createElement("div",{className:"layer-item"}),wp.element.createElement("div",{className:"layer-item opaque"})),wp.element.createElement("div",{className:"layer-gap medium"}),wp.element.createElement("div",{className:"layer label layer-6"},wp.element.createElement("div",{className:"layer-item"}),wp.element.createElement("div",{className:"layer-item opaque"})),wp.element.createElement("div",{className:"layer-gap small"}),wp.element.createElement("div",{className:"layer h2 layer-7"},wp.element.createElement("div",{className:"layer-item"}),wp.element.createElement("div",{className:"layer-item opaque"})),wp.element.createElement("div",{className:"layer-gap medium"}),wp.element.createElement("div",{className:"layer-gap medium"}),wp.element.createElement("div",{className:"layer h1 layer-8"},wp.element.createElement("div",{className:"layer-item opaque"}),wp.element.createElement("div",{className:"layer-item"}),wp.element.createElement("div",{className:"layer-item opaque"}))))))}),u=(wp.i18n.__,function(e){var t=e.noIcon,n=e.isLoader,o=e.title,a=e.description,l=e.children,i=e.helpLink,u=wp.element.createElement(s,null),m=wp.element.createElement("div",{className:"block-loaded"},!!o&&wp.element.createElement("h3",{className:"give-blank-slate__heading"},o),!!a&&wp.element.createElement("p",{className:"give-blank-slate__message"},a),l,!!i&&wp.element.createElement(c,null));return wp.element.createElement("div",{className:"give-blank-slate"},!t&&wp.element.createElement(r,{size:"80",className:"give-blank-slate__image"}),n?u:m)}),m=wp.i18n.__;function p(e){var t=[];return e&&(t=e.map((function(e){var t=e.id,n=e.title.rendered;return{value:t,label:""===n?"".concat(t," : ").concat(m("No form title")):n}}))),t.unshift({value:"0",label:m("-- Select Form --")}),t}var d=wp.i18n.__,f=wp.components.Button,w=function(){return wp.element.createElement(u,{title:d("No donation forms found."),description:d("The first step towards accepting online donations is to create a form."),helpLink:!0},wp.element.createElement(f,{isPrimary:!0,isLarge:!0,className:"give-blank-slate__cta",href:"".concat(wpApiSettings.root.replace("/wp-json/",""),"/wp-admin/post-new.php?post_type=give_forms")},d("Create Donation Form")))},y=n(9),h=n.n(y),v=n(21),g=n.n(v),b=n(20),E=n.n(b),C=n(25),_=n.n(C),S=n(48),O=n.n(S),D=n(33),k=n.n(D);function P(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=k()(e);if(t){var o=k()(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return O()(this,n)}}var T=wp.element.Component,N=wp.components.BaseControl,j=function(e){_()(n,e);var t=P(n);function n(e){var r;return h()(this,n),(r=t.call(this,e)).saveSetting=r.saveSetting.bind(E()(r)),r.saveState=r.saveState.bind(E()(r)),r}return g()(n,[{key:"saveSetting",value:function(e,t){this.props.setAttributes(l()({},e,t))}},{key:"saveState",value:function(e,t){this.setState(l()({},e,t))}},{key:"componentDidMount",value:function(){var e=this.props.value;this.$el=jQuery(this.el),this.$el.val(e),this.$input=this.$el.chosen({width:"100%"}).data("chosen"),this.handleChange=this.handleChange.bind(this),this.$el.on("change",this.handleChange)}},{key:"componentWillUnmount",value:function(){this.$el.off("change",this.handleChange),this.$el.chosen("destroy")}},{key:"handleChange",value:function(e){this.props.onChange(e.target.value)}},{key:"componentDidUpdate",value:function(){var e=jQuery(".chosen-base-control").closest(".chosen-container").find(".chosen-search-input");this.$input.search_field.autocomplete({source:function(t,n){var r={action:"give_block_donation_form_search_results",search:t.term};jQuery.post(ajaxurl,r,(function(r){jQuery(".give-block-chosen-select").empty(),(r=JSON.parse(r)).length>0&&(n(jQuery.map(r,(function(e){jQuery(".give-block-chosen-select").append('<option value="'+e.id+'">'+e.name+"</option>")}))),jQuery(".give-block-chosen-select").trigger("chosen:updated"),e.val(t.term))}))}})}},{key:"render",value:function(){var e=this;return wp.element.createElement(N,{className:"give-chosen-base-control"},wp.element.createElement("select",{className:"give-select give-select-chosen give-block-chosen-select",ref:function(t){return e.el=t}},this.props.options.map((function(e,t){return wp.element.createElement("option",{key:"".concat(e.label,"-").concat(e.value,"-").concat(t),value:e.value},e.label)}))))}}]),n}(T),x=wp.i18n.__,F=wp.data.withSelect,B=wp.components,I=B.Placeholder,A=B.Spinner,G=F((function(e){return{forms:e("core").getEntityRecords("postType","give_forms",{per_page:30})}}))((function(e){var t=e.forms,n=e.setAttributes;return t?t&&0===t.length?wp.element.createElement(w,null):wp.element.createElement(u,{title:x("Donation Form")},wp.element.createElement(j,{className:"give-blank-slate__select",options:p(t),onChange:function(e){n({id:Number(e)})},value:0})):wp.element.createElement(I,null,wp.element.createElement(A,null))})),R=wp.i18n.__,M={};M.displayStyles=[{value:"onpage",label:R("Full Form")},{value:"modal",label:R("Modal")},{value:"reveal",label:R("Reveal")},{value:"button",label:R("One Button Launch")}],M.contentPosition=[{value:"above",label:R("Above")},{value:"below",label:R("Below")}];var L=M;function q(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=k()(e);if(t){var o=k()(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return O()(this,n)}}var W=wp.i18n.__,$=wp.blockEditor.InspectorControls,z=wp.components,Q=z.Dashicon,V=z.Button,U=z.PanelBody,H=z.SelectControl,J=z.ToggleControl,X=z.TextControl,K=wp.element.Component,Y=wp.data.withSelect,Z=function(e){_()(n,e);var t=q(n);function n(e){var r;return h()(this,n),(r=t.call(this,e)).state={continueButtonTitle:r.props.attributes.continueButtonTitle},r.saveSetting=r.saveSetting.bind(E()(r)),r.saveState=r.saveState.bind(E()(r)),r}return g()(n,[{key:"saveSetting",value:function(e,t){this.props.setAttributes(l()({},e,t))}},{key:"saveState",value:function(e,t){this.setState(l()({},e,t))}},{key:"render",value:function(){var e=this,t=this.props.forms,n=this.props.attributes,r=n.id,o=n.displayStyle,a=n.showTitle,l=n.showGoal,i=n.showContent,c=n.contentDisplay;return wp.element.createElement($,{key:"inspector"},wp.element.createElement(U,{title:W("Donation Form Settings")},wp.element.createElement(V,{isDefault:!0,onClick:function(){return e.saveSetting("id",0)},className:"give-change-donation-form-btn"},wp.element.createElement(Q,{icon:"edit"})," ",W("Change Donation Form"))),function(e,t){if(e){var n=e.find((function(e){return parseInt(e.id)===parseInt(t)}));return n&&(!n.formTemplate||"legacy"===n.formTemplate)}return!1}(t,r)&&wp.element.createElement("div",null,wp.element.createElement(U,{title:W("Display")},wp.element.createElement(H,{label:W("Form Format"),name:"displayStyle",value:o,options:L.displayStyles,onChange:function(t){return e.saveSetting("displayStyle",t)}}),"reveal"===o&&wp.element.createElement(X,{name:"continueButtonTitle",label:W("Continue Button Title"),value:this.state.continueButtonTitle,onChange:function(t){return e.saveState("continueButtonTitle",t)},onBlur:function(t){return e.saveSetting("continueButtonTitle",t.target.value)}})),wp.element.createElement(U,{title:W("Settings")},wp.element.createElement(J,{label:W("Title"),name:"showTitle",checked:!!a,onChange:function(t){return e.saveSetting("showTitle",t)}}),wp.element.createElement(J,{label:W("Goal"),name:"showGoal",checked:!!l,onChange:function(t){return e.saveSetting("showGoal",t)}}),wp.element.createElement(J,{label:W("Content"),name:"contentDisplay",checked:!!c,onChange:function(t){return e.saveSetting("contentDisplay",t)}}),c&&wp.element.createElement(H,{label:W("Content Position"),name:"showContent",value:i,options:L.contentPosition,onChange:function(t){return e.saveSetting("showContent",t)}}))))}}]),n}(K),ee=Y((function(e){return{forms:e("core").getEntityRecords("postType","give_forms",{per_page:30})}}))(Z);function te(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function ne(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?te(Object(n),!0).forEach((function(t){l()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):te(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var re=wp.serverSideRender,oe=function(e){var t=e.attributes,n=e.isSelected,r=e.className;return t.id?wp.element.createElement("div",{className:n?"".concat(r," isSelected"):r},wp.element.createElement(ee,ne({},e)),wp.element.createElement(re,{block:"give/donation-form",attributes:t})):wp.element.createElement(G,ne({},e))},ae=wp.i18n.__,le=((0,wp.blocks.registerBlockType)("give/donation-form",{title:ae("Donation Form"),description:ae("The GiveWP Donation Form block inserts an existing donation form into the page. Each donation form's presentation can be customized below."),category:"give",icon:wp.element.createElement(r,{color:"grey"}),keywords:[ae("donation")],supports:{html:!1},attributes:o,edit:oe,save:function(){return null}}),{formsPerPage:{type:"string",default:"12"},formIDs:{type:"string",default:""},excludedFormIDs:{type:"string",default:""},orderBy:{type:"string",default:"date"},order:{type:"string",default:"DESC"},categories:{type:"string",default:""},tags:{type:"string",default:""},columns:{type:"string",default:"best-fit"},showTitle:{type:"boolean",default:!0},showExcerpt:{type:"boolean",default:!0},showGoal:{type:"boolean",default:!0},showFeaturedImage:{type:"boolean",default:!0},displayType:{type:"string",default:"redirect"}}),ie=wp.i18n.__,ce={};ce.orderBy=[{value:"date",label:ie("Date Created")},{value:"title",label:ie("Form Name")},{value:"amount_donated",label:ie("Amount Donated")},{value:"number_donations",label:ie("Number of Donations")},{value:"menu_order",label:ie("Menu Order")},{value:"post__in",label:ie("Provided Form IDs")},{value:"closest_to_goal",label:ie("Closest To Goal")}],ce.order=[{value:"DESC",label:ie("Descending")},{value:"ASC",label:ie("Ascending")}],ce.columns=[{value:"best-fit",label:ie("Best Fit")},{value:"1",label:"1"},{value:"2",label:"2"},{value:"3",label:"3"},{value:"4",label:"4"}],ce.displayType=[{value:"redirect",label:ie("Redirect")},{value:"modal_reveal",label:ie("Modal")}];var se=ce,ue=wp.i18n.__,me=wp.blockEditor.InspectorControls,pe=wp.components,de=pe.PanelBody,fe=pe.SelectControl,we=pe.ToggleControl,ye=pe.TextControl,he=function(e){var t=e.attributes,n=e.setAttributes,r=t.formsPerPage,o=t.formIDs,a=t.excludedFormIDs,i=t.orderBy,c=t.order,s=t.categories,u=t.tags,m=t.columns,p=t.showTitle,d=t.showExcerpt,f=t.showGoal,w=t.showFeaturedImage,y=t.displayType,h=function(e,t){n(l()({},e,t))};return wp.element.createElement(me,{key:"inspector"},wp.element.createElement(de,{title:ue("Form Grid Settings")},wp.element.createElement(ye,{name:"formsPerPage",label:ue("Forms Per Page"),value:r,onChange:function(e){return h("formsPerPage",e)}}),wp.element.createElement(ye,{name:"formIDs",label:ue("Form IDs"),value:o,onChange:function(e){return h("formIDs",e)}}),wp.element.createElement(ye,{name:"excludedFormIDs",label:ue("Excluded Form IDs"),value:a,onChange:function(e){return h("excludedFormIDs",e)}}),wp.element.createElement(fe,{label:ue("Order By"),name:"orderBy",value:i,options:se.orderBy,onChange:function(e){return h("orderBy",e)}}),wp.element.createElement(fe,{label:ue("Order"),name:"order",value:c,options:se.order,onChange:function(e){return h("order",e)}}),wp.element.createElement(ye,{name:"categories",label:ue("Categories"),value:s,onChange:function(e){return h("categories",e)}}),wp.element.createElement(ye,{name:"tags",label:ue("Tags"),value:u,onChange:function(e){return h("tags",e)}}),wp.element.createElement(fe,{label:ue("Columns"),name:"columns",value:m,options:se.columns,onChange:function(e){return h("columns",e)}}),wp.element.createElement(we,{name:"showTitle",label:ue("Show Title"),checked:!!p,onChange:function(e){return h("showTitle",e)}}),wp.element.createElement(we,{name:"showExcerpt",label:ue("Show Excerpt"),checked:!!d,onChange:function(e){return h("showExcerpt",e)}}),wp.element.createElement(we,{name:"showGoal",label:ue("Show Goal"),checked:!!f,onChange:function(e){return h("showGoal",e)}}),wp.element.createElement(we,{name:"showFeaturedImage",label:ue("Show Featured Image"),checked:!!w,onChange:function(e){return h("showFeaturedImage",e)}}),wp.element.createElement(fe,{label:ue("Display Type"),name:"displayType",value:y,options:se.displayType,onChange:function(e){return h("displayType",e)}})))};function ve(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}var ge=wp.element.Fragment,be=wp.serverSideRender,Ee=(0,wp.data.withSelect)((function(e){return{forms:e("core").getEntityRecords("postType","give_forms")}}))((function(e){var t=e.attributes;return wp.element.createElement(ge,null,wp.element.createElement(he,function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?ve(Object(n),!0).forEach((function(t){l()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):ve(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({},e)),wp.element.createElement(be,{block:"give/donation-form-grid",attributes:t}))})),Ce=wp.i18n.__,_e=((0,wp.blocks.registerBlockType)("give/donation-form-grid",{title:Ce("Donation Form Grid"),description:Ce("The GiveWP Donation Form Grid block insert an existing donation form into the page. Each form's presentation can be customized below."),category:"give",icon:wp.element.createElement(r,{color:"grey"}),keywords:[Ce("donation"),Ce("grid")],supports:{html:!1},attributes:le,edit:Ee,save:function(){return null}}),wp.i18n.__),Se={donorsPerPage:{type:"string",default:"12"},formID:{type:"string",default:"0"},ids:{type:"string",default:""},orderBy:{type:"string",default:"post_date"},order:{type:"string",default:"DESC"},paged:{type:"string",default:"1"},columns:{type:"string",default:"best-fit"},showAvatar:{type:"boolean",default:!0},showName:{type:"boolean",default:!0},showTotal:{type:"boolean",default:!0},showDate:{type:"boolean",default:!0},showComments:{type:"boolean",default:!0},showAnonymous:{type:"boolean",default:!0},onlyComments:{type:"boolean",default:!1},commentLength:{type:"string",default:"140"},readMoreText:{type:"string",default:_e("Read more")},loadMoreText:{type:"string",default:_e("Load more")},avatarSize:{type:"string",default:"60"}},Oe=wp.i18n.__,De={};De.columns=[{value:"best-fit",label:Oe("Best Fit")},{value:"1",label:"1"},{value:"2",label:"2"},{value:"3",label:"3"},{value:"4",label:"4"}],De.order=[{value:"DESC",label:Oe("Descending")},{value:"ASC",label:Oe("Ascending")}],De.orderBy=[{value:"donation_amount",label:Oe("Donation Amount")},{value:"post_date",label:Oe("Date Created")}];var ke=De,Pe=wp.i18n.__,Te=wp.blockEditor.InspectorControls,Ne=wp.components,je=Ne.PanelBody,xe=Ne.SelectControl,Fe=Ne.ToggleControl,Be=Ne.TextControl,Ie=function(e){var t=e.attributes,n=e.setAttributes,r=t.donorsPerPage,o=t.ids,a=t.formID,i=t.orderBy,c=t.order,s=t.columns,u=t.showAvatar,m=t.showName,p=t.showTotal,d=t.showDate,f=t.showComments,w=t.showAnonymous,y=t.onlyComments,h=t.commentLength,v=t.readMoreText,g=t.loadMoreText,b=function(e,t){n(l()({},e,t))};return wp.element.createElement(Te,{key:"inspector"},wp.element.createElement(je,{title:Pe("Donor Wall Settings")},wp.element.createElement(Be,{name:"donorsPerPage",label:Pe("Donors Per Page"),value:r,onChange:function(e){return b("donorsPerPage",e)}}),wp.element.createElement(Be,{name:"ids",label:Pe("Donor IDs"),value:o,onChange:function(e){return b("ids",e)}}),wp.element.createElement(Be,{name:"formID",label:Pe("Form ID"),value:a,onChange:function(e){return b("formID",e)}}),wp.element.createElement(xe,{label:Pe("Order By"),name:"orderBy",value:i,options:ke.orderBy,onChange:function(e){return b("orderBy",e)}}),wp.element.createElement(xe,{label:Pe("Order"),name:"order",value:c,options:ke.order,onChange:function(e){return b("order",e)}}),wp.element.createElement(xe,{label:Pe("Columns"),name:"columns",value:s,options:ke.columns,onChange:function(e){return b("columns",e)}}),wp.element.createElement(Fe,{name:"showAvatar",label:Pe("Show Avatar"),checked:!!u,onChange:function(e){return b("showAvatar",e)}}),wp.element.createElement(Fe,{name:"showName",label:Pe("Show Name"),checked:!!m,onChange:function(e){return b("showName",e)}}),wp.element.createElement(Fe,{name:"showTotal",label:Pe("Show Total"),checked:!!p,onChange:function(e){return b("showTotal",e)}}),wp.element.createElement(Fe,{name:"showDate",label:Pe("Show Time"),checked:!!d,onChange:function(e){return b("showDate",e)}}),wp.element.createElement(Fe,{name:"showComments",label:Pe("Show Comments"),checked:!!f,onChange:function(e){return b("showComments",e)}}),wp.element.createElement(Fe,{name:"showAnonymous",label:Pe("Show Anonymous"),checked:!!w,onChange:function(e){return b("showAnonymous",e)}}),wp.element.createElement(Fe,{name:"onlyComments",label:Pe("Only Donors with Comments"),checked:!!y,onChange:function(e){return b("onlyComments",e)}}),wp.element.createElement(Be,{name:"commentLength",label:Pe("Comment Length"),value:h,onChange:function(e){return b("commentLength",e)}}),wp.element.createElement(Be,{name:"readMoreText",label:Pe("Read More Text"),value:v,onChange:function(e){return b("readMoreText",e)}}),wp.element.createElement(Be,{name:"loadMoreText",label:Pe("Load More Text"),value:g,onChange:function(e){return b("loadMoreText",e)}})))};function Ae(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}var Ge=wp.element.Fragment,Re=wp.serverSideRender,Me=function(e){var t=e.attributes;return wp.element.createElement(Ge,null,wp.element.createElement(Ie,function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?Ae(Object(n),!0).forEach((function(t){l()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):Ae(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({},e)),wp.element.createElement(Re,{block:"give/donor-wall",attributes:t}))},Le=wp.i18n.__;(0,wp.blocks.registerBlockType)("give/donor-wall",{title:Le("Donor Wall"),description:Le("The GiveWP Donor Wall block inserts an existing donation form into the page. Each form's presentation can be customized below."),category:"give",icon:wp.element.createElement(r,{color:"grey"}),keywords:[Le("donation"),Le("wall")],supports:{html:!1},attributes:Se,edit:Me,save:function(){return null}})},9:function(e,t){e.exports=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}}});