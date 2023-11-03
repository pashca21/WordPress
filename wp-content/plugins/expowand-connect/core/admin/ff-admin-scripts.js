// noconflict mode
var ff = jQuery.noConflict();

jQuery( document ).ready(function() {
	jQuery( ".ff-settings-opener" ).click(function() {
		jQuery(this).closest(".ff-setting-module").children(".ff-setting-close").toggle();
	});
});


jQuery( document ).ready(function() {
	jQuery(document).on('keyup', '.text1, .text2', function() {
		jQuery('input[name="picker_ff-primary-color"], input[name="ff-primary-color"]').not(this).val(this.value);
	});
});



jQuery( document ).ready(function() {

	jQuery("#next-step").closest(".ff-setting-module").children(".ff-setting-close").toggle();
	
	if(jQuery("#next-step") && jQuery("#next-step").offset() &&  jQuery("#next-step").offset().top)
	{
		var position = jQuery("#next-step").offset().top;
		console.log(position);
		jQuery([document.documentElement, document.body]).animate({
			scrollTop: position
		}, 2000);
	}

});

/*! tinyColorPicker - v1.1.1 2016-08-30 */

!function(a,b){"object"==typeof exports?module.exports=b(a):"function"==typeof define&&define.amd?define("colors",[],function(){return b(a)}):a.Colors=b(a)}(this,function(a,b){"use strict";function c(a,c,d,f,g){if("string"==typeof c){var c=v.txt2color(c);d=c.type,p[d]=c[d],g=g!==b?g:c.alpha}else if(c)for(var h in c)a[d][h]=k(c[h]/l[d][h][1],0,1);return g!==b&&(a.alpha=k(+g,0,1)),e(d,f?a:b)}function d(a,b,c){var d=o.options.grey,e={};return e.RGB={r:a.r,g:a.g,b:a.b},e.rgb={r:b.r,g:b.g,b:b.b},e.alpha=c,e.equivalentGrey=n(d.r*a.r+d.g*a.g+d.b*a.b),e.rgbaMixBlack=i(b,{r:0,g:0,b:0},c,1),e.rgbaMixWhite=i(b,{r:1,g:1,b:1},c,1),e.rgbaMixBlack.luminance=h(e.rgbaMixBlack,!0),e.rgbaMixWhite.luminance=h(e.rgbaMixWhite,!0),o.options.customBG&&(e.rgbaMixCustom=i(b,o.options.customBG,c,1),e.rgbaMixCustom.luminance=h(e.rgbaMixCustom,!0),o.options.customBG.luminance=h(o.options.customBG,!0)),e}function e(a,b){var c,e,k,q=b||p,r=v,s=o.options,t=l,u=q.RND,w="",x="",y={hsl:"hsv",rgb:a},z=u.rgb;if("alpha"!==a){for(var A in t)if(!t[A][A]){a!==A&&(x=y[A]||"rgb",q[A]=r[x+"2"+A](q[x])),u[A]||(u[A]={}),c=q[A];for(w in c)u[A][w]=n(c[w]*t[A][w][1])}z=u.rgb,q.HEX=r.RGB2HEX(z),q.equivalentGrey=s.grey.r*q.rgb.r+s.grey.g*q.rgb.g+s.grey.b*q.rgb.b,q.webSave=e=f(z,51),q.webSmart=k=f(z,17),q.saveColor=z.r===e.r&&z.g===e.g&&z.b===e.b?"web save":z.r===k.r&&z.g===k.g&&z.b===k.b?"web smart":"",q.hueRGB=v.hue2RGB(q.hsv.h),b&&(q.background=d(z,q.rgb,q.alpha))}var B,C,D,E=q.rgb,F=q.alpha,G="luminance",H=q.background;return B=i(E,{r:0,g:0,b:0},F,1),B[G]=h(B,!0),q.rgbaMixBlack=B,C=i(E,{r:1,g:1,b:1},F,1),C[G]=h(C,!0),q.rgbaMixWhite=C,s.customBG&&(D=i(E,H.rgbaMixCustom,F,1),D[G]=h(D,!0),D.WCAG2Ratio=j(D[G],H.rgbaMixCustom[G]),q.rgbaMixBGMixCustom=D,D.luminanceDelta=m.abs(D[G]-H.rgbaMixCustom[G]),D.hueDelta=g(H.rgbaMixCustom,D,!0)),q.RGBLuminance=h(z),q.HUELuminance=h(q.hueRGB),s.convertCallback&&s.convertCallback(q,a),q}function f(a,b){var c={},d=0,e=b/2;for(var f in a)d=a[f]%b,c[f]=a[f]+(d>e?b-d:-d);return c}function g(a,b,c){return(m.max(a.r-b.r,b.r-a.r)+m.max(a.g-b.g,b.g-a.g)+m.max(a.b-b.b,b.b-a.b))*(c?255:1)/765}function h(a,b){for(var c=b?1:255,d=[a.r/c,a.g/c,a.b/c],e=o.options.luminance,f=d.length;f--;)d[f]=d[f]<=.03928?d[f]/12.92:m.pow((d[f]+.055)/1.055,2.4);return e.r*d[0]+e.g*d[1]+e.b*d[2]}function i(a,c,d,e){var f={},g=d!==b?d:1,h=e!==b?e:1,i=g+h*(1-g);for(var j in a)f[j]=(a[j]*g+c[j]*h*(1-g))/i;return f.a=i,f}function j(a,b){var c=1;return c=a>=b?(a+.05)/(b+.05):(b+.05)/(a+.05),n(100*c)/100}function k(a,b,c){return a>c?c:b>a?b:a}var l={rgb:{r:[0,255],g:[0,255],b:[0,255]},hsv:{h:[0,360],s:[0,100],v:[0,100]},hsl:{h:[0,360],s:[0,100],l:[0,100]},alpha:{alpha:[0,1]},HEX:{HEX:[0,16777215]}},m=a.Math,n=m.round,o={},p={},q={r:.298954,g:.586434,b:.114612},r={r:.2126,g:.7152,b:.0722},s=function(a){this.colors={RND:{}},this.options={color:"rgba(0,0,0,0)",grey:q,luminance:r,valueRanges:l},t(this,a||{})},t=function(a,d){var e,f=a.options;u(a);for(var g in d)d[g]!==b&&(f[g]=d[g]);e=f.customBG,f.customBG="string"==typeof e?v.txt2color(e).rgb:e,p=c(a.colors,f.color,b,!0)},u=function(a){o!==a&&(o=a,p=a.colors)};s.prototype.setColor=function(a,d,f){return u(this),a?c(this.colors,a,d,b,f):(f!==b&&(this.colors.alpha=k(f,0,1)),e(d))},s.prototype.setCustomBackground=function(a){return u(this),this.options.customBG="string"==typeof a?v.txt2color(a).rgb:a,c(this.colors,b,"rgb")},s.prototype.saveAsBackground=function(){return u(this),c(this.colors,b,"rgb",!0)},s.prototype.toString=function(a,b){return v.color2text((a||"rgb").toLowerCase(),this.colors,b)};var v={txt2color:function(a){var b={},c=a.replace(/(?:#|\)|%)/g,"").split("("),d=(c[1]||"").split(/,\s*/),e=c[1]?c[0].substr(0,3):"rgb",f="";if(b.type=e,b[e]={},c[1])for(var g=3;g--;)f=e[g]||e.charAt(g),b[e][f]=+d[g]/l[e][f][1];else b.rgb=v.HEX2rgb(c[0]);return b.alpha=d[3]?+d[3]:1,b},color2text:function(a,b,c){var d=c!==!1&&n(100*b.alpha)/100,e="number"==typeof d&&c!==!1&&(c||1!==d),f=b.RND.rgb,g=b.RND.hsl,h="hex"===a&&e,i="hex"===a&&!h,j="rgb"===a||h,k=j?f.r+", "+f.g+", "+f.b:i?"#"+b.HEX:g.h+", "+g.s+"%, "+g.l+"%";return i?k:(h?"rgb":a)+(e?"a":"")+"("+k+(e?", "+d:"")+")"},RGB2HEX:function(a){return((a.r<16?"0":"")+a.r.toString(16)+(a.g<16?"0":"")+a.g.toString(16)+(a.b<16?"0":"")+a.b.toString(16)).toUpperCase()},HEX2rgb:function(a){return a=a.split(""),{r:+("0x"+a[0]+a[a[3]?1:0])/255,g:+("0x"+a[a[3]?2:1]+(a[3]||a[1]))/255,b:+("0x"+(a[4]||a[2])+(a[5]||a[2]))/255}},hue2RGB:function(a){var b=6*a,c=~~b%6,d=6===b?0:b-c;return{r:n(255*[1,1-d,0,0,d,1][c]),g:n(255*[d,1,1,1-d,0,0][c]),b:n(255*[0,0,d,1,1,1-d][c])}},rgb2hsv:function(a){var b,c,d,e=a.r,f=a.g,g=a.b,h=0;return g>f&&(f=g+(g=f,0),h=-1),c=g,f>e&&(e=f+(f=e,0),h=-2/6-h,c=m.min(f,g)),b=e-c,d=e?b/e:0,{h:1e-15>d?p&&p.hsl&&p.hsl.h||0:b?m.abs(h+(f-g)/(6*b)):0,s:e?b/e:p&&p.hsv&&p.hsv.s||0,v:e}},hsv2rgb:function(a){var b=6*a.h,c=a.s,d=a.v,e=~~b,f=b-e,g=d*(1-c),h=d*(1-f*c),i=d*(1-(1-f)*c),j=e%6;return{r:[d,h,g,g,i,d][j],g:[i,d,d,h,g,g][j],b:[g,g,i,d,d,h][j]}},hsv2hsl:function(a){var b=(2-a.s)*a.v,c=a.s*a.v;return c=a.s?1>b?b?c/b:0:c/(2-b):0,{h:a.h,s:a.v||c?c:p&&p.hsl&&p.hsl.s||0,l:b/2}},rgb2hsl:function(a,b){var c=v.rgb2hsv(a);return v.hsv2hsl(b?c:p.hsv=c)},hsl2rgb:function(a){var b=6*a.h,c=a.s,d=a.l,e=.5>d?d*(1+c):d+c-c*d,f=d+d-e,g=e?(e-f)/e:0,h=~~b,i=b-h,j=e*g*i,k=f+j,l=e-j,m=h%6;return{r:[e,l,f,f,k,e][m],g:[k,e,e,l,f,f][m],b:[f,f,k,e,e,l][m]}}};return s}),function(a,b){"object"==typeof exports?module.exports=b(a,require("jquery"),require("colors")):"function"==typeof define&&define.amd?define(["jquery","colors"],function(c,d){return b(a,c,d)}):b(a,a.jQuery,a.Colors)}(this,function(a,b,c,d){"use strict";function e(a){return a.value||a.getAttribute("value")||b(a).css("background-color")||"#FFF"}function f(a){return a=a.originalEvent&&a.originalEvent.touches?a.originalEvent.touches[0]:a,a.originalEvent?a.originalEvent:a}function g(a){return b(a.find(r.doRender)[0]||a[0])}function h(c){var d=b(this),f=d.offset(),h=b(a),k=r.gap;c?(s=g(d),s._colorMode=s.data("colorMode"),p.$trigger=d,(t||i()).css(r.positionCallback.call(p,d)||{left:(t._left=f.left)-((t._left+=t._width-(h.scrollLeft()+h.width()))+k>0?t._left+k:0),top:(t._top=f.top+d.outerHeight())-((t._top+=t._height-(h.scrollTop()+h.height()))+k>0?t._top+k:0)}).show(r.animationSpeed,function(){c!==!0&&(y.toggle(!!r.opacity)._width=y.width(),v._width=v.width(),v._height=v.height(),u._height=u.height(),q.setColor(e(s[0])),n(!0))}).off(".tcp").on(D,".cp-xy-slider,.cp-z-slider,.cp-alpha",j)):p.$trigger&&b(t).hide(r.animationSpeed,function(){n(!1),p.$trigger=null}).off(".tcp")}function i(){return b("head")[r.cssPrepend?"prepend":"append"]('<style type="text/css" id="tinyColorPickerStyles">'+(r.css||I)+(r.cssAddon||"")+"</style>"),b(H).css({margin:r.margin}).appendTo("body").show(0,function(){p.$UI=t=b(this),F=r.GPU&&t.css("perspective")!==d,u=b(".cp-z-slider",this),v=b(".cp-xy-slider",this),w=b(".cp-xy-cursor",this),x=b(".cp-z-cursor",this),y=b(".cp-alpha",this),z=b(".cp-alpha-cursor",this),r.buildCallback.call(p,t),t.prepend("<div>").children().eq(0).css("width",t.children().eq(0).width()),t._width=this.offsetWidth,t._height=this.offsetHeight}).hide()}function j(a){var c=this.className.replace(/cp-(.*?)(?:\s*|$)/,"$1").replace("-","_");(a.button||a.which)>1||(a.preventDefault&&a.preventDefault(),a.returnValue=!1,s._offset=b(this).offset(),(c="xy_slider"===c?k:"z_slider"===c?l:m)(a),n(),A.on(E,function(){A.off(".tcp")}).on(C,function(a){c(a),n()}))}function k(a){var b=f(a),c=b.pageX-s._offset.left,d=b.pageY-s._offset.top;q.setColor({s:c/v._width*100,v:100-d/v._height*100},"hsv")}function l(a){var b=f(a).pageY-s._offset.top;q.setColor({h:360-b/u._height*360},"hsv")}function m(a){var b=f(a).pageX-s._offset.left,c=b/y._width;q.setColor({},"rgb",c)}function n(a){var b=q.colors,c=b.hueRGB,e=(b.RND.rgb,b.RND.hsl,r.dark),f=r.light,g=q.toString(s._colorMode,r.forceAlpha),h=b.HUELuminance>.22?e:f,i=b.rgbaMixBlack.luminance>.22?e:f,j=(1-b.hsv.h)*u._height,k=b.hsv.s*v._width,l=(1-b.hsv.v)*v._height,m=b.alpha*y._width,n=F?"translate3d":"",p=s[0].value,t=s[0].hasAttribute("value")&&""===p&&a!==d;v._css={backgroundColor:"rgb("+c.r+","+c.g+","+c.b+")"},w._css={transform:n+"("+k+"px, "+l+"px, 0)",left:F?"":k,top:F?"":l,borderColor:b.RGBLuminance>.22?e:f},x._css={transform:n+"(0, "+j+"px, 0)",top:F?"":j,borderColor:"transparent "+h},y._css={backgroundColor:"#"+b.HEX},z._css={transform:n+"("+m+"px, 0, 0)",left:F?"":m,borderColor:i+" transparent"},s._css={backgroundColor:t?"":g,color:t?"":b.rgbaMixBGMixCustom.luminance>.22?e:f},s.text=t?"":p!==g?g:"",a!==d?o(a):G(o)}function o(a){v.css(v._css),w.css(w._css),x.css(x._css),y.css(y._css),z.css(z._css),r.doRender&&s.css(s._css),s.text&&s.val(s.text),r.renderCallback.call(p,s,"boolean"==typeof a?a:d)}var p,q,r,s,t,u,v,w,x,y,z,A=b(document),B=b(),C="touchmove.tcp mousemove.tcp pointermove.tcp",D="touchstart.tcp mousedown.tcp pointerdown.tcp",E="touchend.tcp mouseup.tcp pointerup.tcp",F=!1,G=a.requestAnimationFrame||a.webkitRequestAnimationFrame||function(a){a()},H='<div class="cp-color-picker"><div class="cp-z-slider"><div class="cp-z-cursor"></div></div><div class="cp-xy-slider"><div class="cp-white"></div><div class="cp-xy-cursor"></div></div><div class="cp-alpha"><div class="cp-alpha-cursor"></div></div></div>',I=".cp-color-picker{position:absolute;overflow:hidden;padding:6px 6px 0;background-color:#444;color:#bbb;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:400;cursor:default;border-radius:5px}.cp-color-picker>div{position:relative;overflow:hidden}.cp-xy-slider{float:left;height:128px;width:128px;margin-bottom:6px;background:linear-gradient(to right,#FFF,rgba(255,255,255,0))}.cp-white{height:100%;width:100%;background:linear-gradient(rgba(0,0,0,0),#000)}.cp-xy-cursor{position:absolute;top:0;width:10px;height:10px;margin:-5px;border:1px solid #fff;border-radius:100%;box-sizing:border-box}.cp-z-slider{float:right;margin-left:6px;height:128px;width:20px;background:linear-gradient(red 0,#f0f 17%,#00f 33%,#0ff 50%,#0f0 67%,#ff0 83%,red 100%)}.cp-z-cursor{position:absolute;margin-top:-4px;width:100%;border:4px solid #fff;border-color:transparent #fff;box-sizing:border-box}.cp-alpha{clear:both;width:100%;height:16px;margin:6px 0;background:linear-gradient(to right,#444,rgba(0,0,0,0))}.cp-alpha-cursor{position:absolute;margin-left:-4px;height:100%;border:4px solid #fff;border-color:#fff transparent;box-sizing:border-box}",J=function(a){q=this.color=new c(a),r=q.options,p=this};J.prototype={render:n,toggle:h},b.fn.colorPicker=function(c){var d=this,f=function(){};return c=b.extend({animationSpeed:150,GPU:!0,doRender:!0,customBG:"#FFF",opacity:!0,renderCallback:f,buildCallback:f,positionCallback:f,body:document.body,scrollResize:!0,gap:4,dark:"#222",light:"#DDD"},c),!p&&c.scrollResize&&b(a).on("resize.tcp scroll.tcp",function(){p.$trigger&&p.toggle.call(p.$trigger[0],!0)}),B=B.add(this),this.colorPicker=p||new J(c),this.options=c,b(c.body).off(".tcp").on(D,function(a){-1===B.add(t).add(b(t).find(a.target)).index(a.target)&&h()}),this.on("focusin.tcp click.tcp",function(a){p.color.options=b.extend(p.color.options,r=d.options),h.call(this,a)}).on("change.tcp",function(){q.setColor(this.value||"#FFF"),d.colorPicker.render(!0)}).each(function(){var a=e(this),d=a.split("("),f=g(b(this));f.data("colorMode",d[1]?d[0].substr(0,3):"HEX").attr("readonly",r.preventFocus),c.doRender&&f.css({"background-color":a,color:function(){return q.setColor(a).rgbaMixBGMixCustom.luminance>.22?c.dark:c.light}})})},b.fn.colorPicker.destroy=function(){b("*").off(".tcp"),p.toggle(!1),B=b()}});
//# sourceMappingURL=jqColorPicker.js.map

// upload picture
jQuery(document).ready(function($){

	var custom_uploader
		, click_elem = jQuery('.wpse-228085-upload')
		, target = jQuery('input[name="ff-valuationMaster-calltoaction-agent-img"]')

	click_elem.click(function(e) {
			e.preventDefault();
			//If the uploader object has already been created, reopen the dialog
			if (custom_uploader) {
					custom_uploader.open();
					return;
			}
			//Extend the wp.media object
			custom_uploader = wp.media.frames.file_frame = wp.media({
					title: 'Choose Image',
					button: {
							text: 'Choose Image'
					},
					multiple: false
			});
			//When a file is selected, grab the URL and set it as the text field's value
			custom_uploader.on('select', function() {
					attachment = custom_uploader.state().get('selection').first().toJSON();
					target.val(attachment.url);
					if (jQuery('#profilepicture')) {
						jQuery('#profilepicture').attr('src', attachment.url);
					}
			});
			//Open the uploader dialog
			custom_uploader.open();
	});      
});


jQuery(document).ready(function($){
	var maps = jQuery("select[name='ff-maps-default']").find(":selected").val()

	if(maps == 1) {
		console.log('maps set to : google maps')
		jQuery('input[name="ff-gg-api-maps"]').prop('disabled', false);
	} else {
		jQuery('input[name="ff-gg-api-maps"]').prop('disabled', true);
	}

	console.log('onload maps val: ' + maps )

	jQuery("select[name='ff-maps-default']").on('change', function() {
		if(this.value == 1) {
			console.log('maps set to : google maps')
			jQuery('input[name="ff-gg-api-maps"]').prop('disabled', false);
		} else {
			jQuery('input[name="ff-gg-api-maps"]').prop('disabled', true);
		}
	});
});

// team members hiding & draggable
jQuery(window).on('load', function() {
  // code to run when the window loads
	 teamMembersListStart()
	//startDragAdv()
});

jQuery(window).resize(function() {
  // Code to execute on window resize
	 teamMembersListStart()
	//startDragAdv()
});

function teamMembersListStart() {

	const sortableList = document.querySelector(".ff-setting-field-type-user");
	const items = sortableList.querySelectorAll(".ff-teamoverview-user");

	items.forEach(item => {
			item.addEventListener("dragstart", () => {
					// Adding dragging class to item after a delay
					setTimeout(() => item.classList.add("dragging"), 0);
			});
			// Removing dragging class from item on dragend event
			item.addEventListener("dragend", () => item.classList.remove("dragging"));
	});

	const initSortableList = (e) => {
			e.preventDefault();
			const draggingItem = document.querySelector(".dragging");
			// Getting all items except currently dragging and making array of them
			let siblings = [...sortableList.querySelectorAll(".ff-teamoverview-user:not(.dragging)")];

			// Finding the sibling after which the dragging item should be placed
			let nextSibling = siblings.find(sibling => {
					return e.clientY <= sibling.offsetTop + sibling.offsetHeight / 2;
			});

			console.log(nextSibling)

			// Inserting the dragging item before the found sibling
			sortableList.insertBefore(draggingItem, nextSibling);
	}

	sortableList.addEventListener("dragover", initSortableList);
	sortableList.addEventListener("dragenter", e => e.preventDefault());

}

function startDragAdv() {
	const sortableList = document.querySelector(".ff-setting-field-type-user");
	const items = sortableList.querySelectorAll(".ff-teamoverview-user");

	const element = document.querySelector('.ff-false');
	element.classList.add('ff-gap');

	items.forEach(item => {
		item.addEventListener("dragstart", () => {
			// Adding dragging class to item after a delay
			setTimeout(() => item.classList.add("dragging"), 0);
		});
		
		// Removing dragging class from item on dragend event
		item.addEventListener("dragend", () => {
			item.classList.remove("dragging");
			
			// Update data-order attributes of all items
			const items = sortableList.querySelectorAll(".ff-teamoverview-user");
			items.forEach((item, index) => {
				item.dataset.order = index + 1;
			});

			// Create data array after updating the order of list items
			const dataArray = createDataArray();
			console.log(dataArray); // or do whatever you want with the data array
			// save data array 
			jQuery('#ff-teamoverview-user').val(JSON.stringify(dataArray));
		});
	});

	const initSortableList = (e) => {
  e.preventDefault();
  
  const draggingItem = document.querySelector(".dragging");
  
  // Getting all items except currently dragging and making array of them
  const siblings = [...sortableList.querySelectorAll(".ff-teamoverview-user:not(.dragging)")];
  
  // Finding the sibling after which the dragging item should be placed
  const nextSibling = siblings.find(sibling => {
    return e.clientY <= sibling.offsetTop + sibling.offsetHeight / 1.5;
  });
  
  // Inserting the dragging item before the found sibling
  sortableList.insertBefore(draggingItem, nextSibling);
  
  // Update data-order attributes of all items
  const items = sortableList.querySelectorAll(".ff-teamoverview-user");
		items.forEach((item, index) => {
			item.dataset.order = index + 1;
		});
	}

	sortableList.addEventListener("dragover", initSortableList);
	sortableList.addEventListener("dragenter", e => e.preventDefault());
};



// new team members sorting 

jQuery(document).ready(function($){
	const userlist = document.getElementById('user-list');

	let dragSrcEl = null;
	
	const listItems = document.querySelectorAll('.user-item');
	listItems.forEach(item => {
		item.addEventListener('dragstart', handleDragStart, false);
		item.addEventListener('dragover', handleDragOver, false);
		item.addEventListener('drop', handleDrop, false);
		item.addEventListener('dragend', handleDragEnd, false);
		item.addEventListener('click', function() {
			const isFalse = $(this).find('.ff-teamoverview-user').hasClass('ff-false');
			const user = $(this).find('.ff-teamoverview-user');
		
			user.toggleClass('ff-true ff-false');
		
			if (user.hasClass('ff-true')) {
				$(this).attr('draggable', true);
			} else {
				$(this).removeAttr('draggable');
			}
		
			if (isFalse) {
				// Select all elements with class 'ff-false'
				const ffFalseElements = document.querySelectorAll('.ff-false');

				if(ffFalseElements.length > 0) {
					// Get the parent of the first element with class 'ff-false'
					const parentElement = ffFalseElements[0].parentNode;

					// Create a constant variable that points to the parent element
					const parentOfFirstFFFalse = parentElement;

					// Move 'this' before 'parentOfFirstFFFalse'
					if (parentOfFirstFFFalse.parentNode) {
						parentOfFirstFFFalse.parentNode.insertBefore(this, parentOfFirstFFFalse);
					}
				}
			} else {
				$(this).appendTo('#user-list');
			}
		
			createDataArray();
		});
	});
			 
});

function handleDragStart(e) {
	if (this.querySelector('.ff-false')) {
		return;
	}
	dragSrcEl = this;
	e.dataTransfer.effectAllowed = 'move';
	e.dataTransfer.setData('text/html', this.innerHTML);
}

function handleDragOver(e) {
  if (e.preventDefault) {
    e.preventDefault();
  }

  // Check if the target element or its children have the .ff-false class
  const target = e.target;
  const hasFalseClass = target.classList.contains('ff-false') || Array.from(target.querySelectorAll('.ff-false')).length > 0;

  if (hasFalseClass) {
    e.dataTransfer.dropEffect = 'none';
    return false;
  } else {
    e.dataTransfer.dropEffect = 'move';
    return true;
  }
}


function handleDrop(e) {
	if (e.stopPropagation) {
		e.stopPropagation();
	}
	if (dragSrcEl !== this) {
		dragSrcEl.innerHTML = this.innerHTML;
		this.innerHTML = e.dataTransfer.getData('text/html');
	}
	return false;
}

function handleDragEnd() {
	const listItems = document.querySelectorAll('.user-item');
	listItems.forEach(item => {
		item.classList.remove('over');
	});

	const userItem = document.querySelectorAll(".ff-teamoverview-user");
	userItem.forEach((item, index) => {
		item.dataset.order = index + 1;
	});

	// Create data array after updating the order of list items
	createDataArray();
}

function createDataArray() {
  const dataArray = [];
  jQuery('.ff-teamoverview-user').each(function(index) {
    const dataId = jQuery(this).attr('data-id');
    const dataOrder = index + 1;
    const dataClass = jQuery(this).hasClass('ff-true') ? 'ff-true' : 'ff-false';
    dataArray.push({ 'id': dataId, 'order': dataOrder, 'class': dataClass });
  });

	console.log(dataArray)
	// save data array 
	jQuery('#ff-teamoverview-user').val(JSON.stringify(dataArray));
}
