/*----------------------------------------------------------------------
 # mod_ot_lightboxlogin - OT Lightbox Login Module For Joomla! 1.7
 #----------------------------------------------------------------------
 # author OmegaTheme.com
 # copyright Copyright(C) 2008 - 2011 OmegaTheme.com. All Rights Reserved.
 # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Website: http://omegatheme.com
 # Technical support: Forum - http://omegatheme.com/forum/
------------------------------------------------------------------------*/

window.addEvent('domready', function() {
	var overlay = new Element('div', {
			id: 'overlay',
			styles: {display: 'none', zIndex: 99990}
		});
	var win = new Element('div', {
			id: 'container',
			styles: {display: 'none', zIndex: 99992}
		});
	var closeBtn = new Element('a', {id: 'ot-closebox', href: 'javascript:void(0);'}).inject(win);
	$(document.body).adopt(overlay, win);
		
	$('ot-login-label').addEvent('click', function(){
		$$('.ot-tab-content').setStyle('display', 'none');
		$$('.ot-tab').removeClass('ot-tab-actived');
		$('ot-login-tab').addClass('ot-tab-actived');
		$('ot-tab-login-main').setStyle('display', 'block');
		$('ot-lightbox-wrapper').setStyle('display', 'block');
		$('overlay').setStyle('display', 'block').tween('opacity', 0.7);
		$('container').setStyle('display', 'block').tween('opacity', 1);
		$('ot-lightbox-wrapper').inject(win);
		$('ot-lightbox-wrap').setStyle('display', 'block');
		
		var containerSize = $('container').getSize();
		var windowView = $(document.body).getSize();
		var windowScroll = $(document.body).getScroll();
		
		$('container').setStyles({
			'left': '50%',
			'top': windowScroll.y + windowView.y/2 + 'px',
			'margin-top': -(containerSize.y/2),
			'margin-left': -(containerSize.x/2)
		}).tween('opacity', 1);
	});
	
	if ($('ot-register-label')) {
		$('ot-register-label').addEvent('click', function(){
			$$('.ot-tab-content').setStyle('display', 'none');
			$$('.ot-tab').removeClass('ot-tab-actived');
			$('ot-signup-tab').addClass('ot-tab-actived');
			$('ot-tab-signup-main').setStyle('display', 'block');
			$('ot-lightbox-wrapper').setStyle('display', 'block');
			
			$('overlay').setStyle('display', 'block').tween('opacity', 0.7);
			$('container').setStyle('display', 'block').tween('opacity', 1);
			$('ot-lightbox-wrapper').inject(win);
			$('ot-lightbox-wrap').setStyle('display', 'block');
			
			var containerSize = $('container').getSize();
			var windowView = $(document.body).getSize();
			var windowScroll = $(document.body).getScroll();
			
			$('container').setStyles({
				'left': '50%',
				'top': windowScroll.y + windowView.y/2 + 'px',
				'margin-top': -(containerSize.y/2),
				'margin-left': -(containerSize.x/2)
			}).tween('opacity', 1);
		});
	};
	
	$('ot-login-tab').addEvent('click', function(){
		if (this.hasClass('ot-tab-actived')) {
			return;
		} else {
			this.addClass('ot-tab-actived');
			$('ot-tab-login-main').setStyle('display', 'block');
		}
		if ($('ot-signup-tab').hasClass('ot-tab-actived')) {
			$('ot-signup-tab').removeClass('ot-tab-actived');
			$('ot-tab-signup-main').setStyle('display', 'none');
		}
	});
	
	if ($('ot-signup-tab')) {
		$('ot-signup-tab').addEvent('click', function(){
			if (this.hasClass('ot-tab-actived')) {
				return;
			} else {
				this.addClass('ot-tab-actived');
				$('ot-tab-signup-main').setStyle('display', 'block');
			}
			if ($('ot-login-tab').hasClass('ot-tab-actived')) {
				$('ot-login-tab').removeClass('ot-tab-actived');
				$('ot-tab-login-main').setStyle('display', 'none');
			}
		});
	};
	$('ot-closebox').addEvent('click', function(){
		$$('div#overlay', 'div#container').setStyle('display', 'none');
	});
});
