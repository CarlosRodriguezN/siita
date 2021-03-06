/*  
 * JCE Editor                 2.1.0
 * @package                 JCE
 * @url                     http://www.joomlacontenteditor.net
 * @copyright               Copyright (C) 2006 - 2012 Ryan Demmer. All rights reserved
 * @license                 GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 * @date                    14 May 2012
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * NOTE : Javascript files have been compressed for speed and can be uncompressed using http://jsbeautifier.org/
 */
(function(){tinyMCEPopup.requireLangPack();var ImageManagerDialog={settings:{},init:function(){var ed=tinyMCEPopup.editor,n=ed.selection.getNode(),self=this,br,el;$('#insert').click(function(e){self.insert();e.preventDefault();});tinyMCEPopup.resizeToInnerSize();tinyMCEPopup.restoreSelection();var src=ed.convertURL(ed.dom.getAttrib(n,'src'));src=decodeURIComponent(src);TinyMCE_Utils.fillClassList('classlist');$.each(this.settings.attributes,function(k,v){if(!parseFloat(v)){$('#attributes-'+k).hide();}});if(this.settings.disable_dimensions){$('#width, #height').prop('disabled',true);}
$('#onmouseover').click(function(){$('#onmouseout').removeClass('focus');$(this).addClass('focus');});$('#onmouseout').click(function(){$('#onmouseover').removeClass('focus');$(this).addClass('focus');});$.Plugin.init({selectChange:function(){ImageManagerDialog.updateStyles();}});if(n.nodeName=='IMG'){$('#insert').button('option','label',tinyMCEPopup.getLang('update','Update',true));$('#src').val(src);$('#sample').attr({'src':n.src}).attr($.Plugin.sizeToFit(n,{width:80,height:60}));var w=this.getAttrib(n,'width'),h=this.getAttrib(n,'height');$('#width, #tmp_width').val(w);$('#height, #tmp_height').val(h);$('#alt').val(ed.dom.getAttrib(n,'alt'));$('#title').val(ed.dom.getAttrib(n,'title'));$.each(['top','right','bottom','left'],function(){$('#margin_'+this).val(ImageManagerDialog.getAttrib(n,'margin-'+this));});$('#border_width').val(function(){var v=self.getAttrib(n,'border-width');if($('option[value="'+v+'"]',this).length==0){$(this).append(new Option(v,v));}
return v;});$('#border_style').val(this.getAttrib(n,'border-style'));$('#border_color').val(this.getAttrib(n,'border-color')).change();if(!$('#border').is(':checked')){$.each(['border_width','border_style','border_color'],function(i,k){$('#'+k).val(self.settings.defaults[k]).change();});}
$('#align').val(this.getAttrib(n,'align'));$('#classes').val(ed.dom.getAttrib(n,'class'));$('#classlist').val(ed.dom.getAttrib(n,'class'));$('#style').val(ed.dom.getAttrib(n,'style'));$('#id').val(ed.dom.getAttrib(n,'id'));$('#dir').val(ed.dom.getAttrib(n,'dir'));$('#lang').val(ed.dom.getAttrib(n,'lang'));$('#usemap').val(ed.dom.getAttrib(n,'usemap'));$('#insert').button('option','label',ed.getLang('update','Update'));$('#longdesc').val(ed.convertURL(ed.dom.getAttrib(n,'longdesc')));$('#onmouseout').val(src);$.each(['onmouseover','onmouseout'],function(){v=ed.dom.getAttrib(n,this);v=$.trim(v);v=v.replace(/^\s*this.src\s*=\s*\'([^\']+)\';?\s*$/,'$1');v=ed.convertURL(v);$('#'+this).val(v);});br=n.nextSibling;if(br&&br.nodeName=='BR'&&br.style.clear){$('#clear').val(br.style.clear);}}else{$.Plugin.setDefaults(this.settings.defaults);}
WFFileBrowser.init($('#src'),{onFileClick:function(e,file){ImageManagerDialog.selectFile(file);},onFileInsert:function(e,file){ImageManagerDialog.selectFile(file);}});this.setBorder();this.setMargins(true);this.updateStyles();},insert:function(){var ed=tinyMCEPopup.editor,t=this;AutoValidator.validate(document);if($('#src').val()===''){$.Dialog.alert(tinyMCEPopup.getLang('imgmanager_dlg.no_src','Please enter a url for the image'));return false;}
if($('#alt').val()===''){$.Dialog.confirm(tinyMCEPopup.getLang('imgmanager_dlg.missing_alt'),function(state){if(state){t.insertAndClose();}},{width:300,height:200});}else{this.insertAndClose();}},insertAndClose:function(){var ed=tinyMCEPopup.editor,t=this,v,args={},el,br='';this.updateStyles();tinyMCEPopup.restoreSelection();if(tinymce.isWebKit)
ed.getWin().focus();if(!ed.settings.inline_styles){args={vspace:$('#margin_top').val()||$('#margin_bottom').val(),hspace:$('#margin_left').val()||$('#margin_right').val(),border:$('#border_width').val(),align:$('#align').val()};var img=$('#sample');var style=ed.dom.parseStyle($(img).attr('style'));tinymce.each(['margin','float','border','vertical-align'],function(s){delete style[s];});$('#style').val(ed.dom.serializeStyle(style));}else{args={vspace:'',hspace:'',border:'',align:''};}
tinymce.each(['src','width','height','alt','title','classes','style','id','dir','lang','usemap','longdesc'],function(k){v=$('#'+k+':enabled').val();if(k=='src'){v=$.String.buildURI(v);}
if(k=='classes')
k='class';args[k]=v;});args.onmouseover=args.onmouseout='';var over=$('#onmouseover').val(),out=$('#onmouseout').val();if(over&&out){args.onmouseover="this.src='"+ed.convertURL(over)+"';";args.onmouseout="this.src='"+ed.convertURL(out)+"';";}
el=ed.selection.getNode();br=el.nextSibling;if(el&&el.nodeName=='IMG'){ed.dom.setAttribs(el,args);if(br&&br.nodeName=='BR'){if($('#clear').is(':disabled')||$('#clear').val()===''){ed.dom.remove(br);}
if(!$('#clear').is(':disabled')&&$('#clear').val()!==''){ed.dom.setStyle(br,'clear',$('#clear').val());}}else{if(!$('#clear').is(':disabled')&&$('#clear').val()!==''){br=ed.dom.create('br');ed.dom.setStyle(br,'clear',$('#clear').val());ed.dom.insertAfter(br,el);}}}else{ed.execCommand('mceInsertContent',false,'<img id="__mce_tmp" src="" />',{skip_undo:1});el=ed.dom.get('__mce_tmp');if(!$('#clear').is(':disabled')&&$('#clear').val()!==''){br=ed.dom.create('br');ed.dom.setStyle(br,'clear',$('#clear').val());ed.dom.insertAfter(br,el);}
ed.dom.setAttribs('__mce_tmp',args);ed.dom.setAttrib('__mce_tmp','id','');ed.undoManager.add();}
tinyMCEPopup.close();},getAttrib:function(e,at){var ed=tinyMCEPopup.editor,v,v2;switch(at){case'width':case'height':return ed.dom.getAttrib(e,at)||ed.dom.getStyle(e,at)||'';break;case'align':if(v=ed.dom.getAttrib(e,'align')){return v;}
if(v=ed.dom.getStyle(e,'float')){return v;}
if(v=ed.dom.getStyle(e,'vertical-align')){return v;}
break;case'margin-top':case'margin-bottom':if(v=ed.dom.getStyle(e,at)){if(/auto|inherit/.test(v)){return v;}
return parseInt(v.replace(/[^-0-9]/g,''));}
if(v=ed.dom.getAttrib(e,'vspace')){return parseInt(v.replace(/[^-0-9]/g,''));}
break;case'margin-left':case'margin-right':if(v=ed.dom.getStyle(e,at)){if(/auto|inherit/.test(v)){return v;}
return parseInt(v.replace(/[^-0-9]/g,''));}
if(v=ed.dom.getAttrib(e,'hspace')){return parseInt(v.replace(/[^-0-9]/g,''));}
break;case'border-width':case'border-style':case'border-color':v='';tinymce.each(['top','right','bottom','left'],function(n){s=at.replace(/-/,'-'+n+'-');sv=ed.dom.getStyle(e,s);if(sv!==''||(sv!=v&&v!=='')){v='';}
if(sv){v=sv;}});if(v!==''){$('#border').prop('checked',true);}
if((at=='border-width'||at=='border-style')&&v===''){v='inherit';}
if(at=='border-color'){v=$.String.toHex(v);}
if(at=='border-width'){if(/[0-9][a-z]/.test(v)){v=parseFloat(v);}}
return v;break;}},setMargins:function(init){var x=0,s=false;var v=$('#margin_top').val();var $elms=$('#margin_right, #margin_bottom, #margin_left');if(init){$elms.each(function(){if($(this).val()===v){x++;}});s=(x==$elms.length);$elms.prop('disabled',s).prev('label').toggleClass('disabled',s);$('#margin_check').prop('checked',s);}else{s=$('#margin_check').is(':checked');$elms.each(function(){if(s){$(this).val(v);}
$(this).prop('disabled',s).prev('label').toggleClass('disabled',s);});$('#margin_top').val(v);this.updateStyles();}},setBorder:function(){var s=$('#border').is(':checked');$('#border~:input, #border~span, #border~label').attr('disabled',!s).toggleClass('disabled',!s);this.updateStyles();},setClasses:function(v){return $.Plugin.setClasses(v);},setDimensions:function(a,b){return $.Plugin.setDimensions(a,b);},setStyles:function(){var self=this,ed=tinyMCEPopup,$img=$('#sample');$img.attr('style',$('#style').val());$.each(['top','right','bottom','left'],function(i,k){var v=ed.dom.getStyle($img.get(0),'margin-'+k);if(v.indexOf('px')!=-1){v=parseInt(v);}
$('#margin_'+k).val(v);});this.setMargins(true);var border=false;$.each(['width','color','style'],function(i,k){var v=ed.dom.getStyle($img.get(0),'border-'+k);if(v==''){$.each(['top','right','bottom','left'],function(i,n){var sv=ed.dom.getStyle($img.get(0),'border-'+n+'-'+k);if(sv!==''||(sv!=v&&v!=='')){v='';}
if(sv){v=sv;}});}
if(v!==''){border=true;}
if(k=='width'){v=/[0-9][a-z]/.test(v)?parseInt(v):v;}
if(k=='color'){v=$.String.toHex(v);}
if(border){$('#border').attr('checked','checked');$('#border_'+k).val(v);$('#border~:input, #border~span, #border~label').attr('disabled',false).toggleClass('disabled',false);if(k=='color'){$('#border_'+k).trigger('pick');}}});$('#align').val($img.css('float')||$img.css('vertical-align')||'');},updateStyles:function(){var ed=tinyMCEPopup,st,v,br,img=$('#sample');$(img).attr('style',$('#style').val());$(img).attr('dir',$('#dir').val());$(img).css('float','');$(img).css('vertical-align','');v=$('#align').val();if(v=='left'||v=='right'){if(ed.editor.settings.inline_styles){$('#clear').attr('disabled',false);}
$(img).css('float',v);}else{$(img).css('vertical-align',v);$('#clear').attr('disabled',true);}
v=$('#clear:enabled').val();if(v){if(!$('#sample-br').get(0)){$(img).after('<br id="sample-br" />');}
$('#sample-br').css('clear',v);}else{$('#sample-br').remove();}
$.each(['width','color','style'],function(i,k){if($('#border').is(':checked')){v=$('#border_'+k).val();}else{v='';}
if(v=='inherit'){v='';}
if(k=='width'&&/[^a-z]/i.test(v)){v+='px';}
$(img).css('border-'+k,v);});$.each(['top','right','bottom','left'],function(i,k){v=$('#margin_'+k).val();$(img).css('margin-'+k,/[^a-z]/i.test(v)?v+'px':v);});var styles=ed.dom.parseStyle($(img).attr('style'));function compressBorder(target,a,b,c){function check(s){return s in styles&&styles[s]!=='';}
if(!check(a)||!check(b)||!check(c)){return;}
styles[target]=styles[a]+' '+styles[b]+' '+styles[c];delete styles[a];delete styles[b];delete styles[c];}
compressBorder('border','border-width','border-style','border-color');for(k in styles){if(k.indexOf('-moz-')!==-1||k.indexOf('-webkit-')!==-1){delete styles[k];}}
$('#style').val(ed.dom.serializeStyle(styles));},_setRollover:function(src){if($('#onmouseout').hasClass('focus')){$('#onmouseout').val(src);}
if($('#onmouseover').hasClass('focus')){$('#onmouseover').val(src);}
if(!$('#onmouseover, #onmouseout').hasClass('focus')){if($('#onmouseout').val()==''){$('#onmouseout').val(src).addClass('focus').focus();$('#src').val(src);}else{$('#onmouseover').val(src).addClass('focus').focus();}}},selectFile:function(file){var self=this;var name=$(file).attr('title');var src=$(file).data('url');if(!$('#rollover_tab').is('.ui-tabs-hide')){this._setRollover(src);}else{name=$.String.stripExt(name);name=name.replace(/_/g,' ');$('#alt').val(name);$('#onmouseout').val(src);$('#src').val(src);if(!$(file).data('width')||!$(file).data('height')){var img=new Image();img.onload=function(){$.each(['width','height'],function(i,k){$('#'+k+', #tmp_'+k).val(img[k]);});};img.src=src;}else{$.each(['width','height'],function(i,k){$('#'+k+', #tmp_'+k).val($(file).data(k));});}
$('#sample').attr({'src':$(file).data('preview')}).attr($.Plugin.sizeToFit({width:$(file).data('width'),height:$(file).data('height')},{width:80,height:60}));}}};window.ImageManagerDialog=ImageManagerDialog;tinyMCEPopup.onInit.add(ImageManagerDialog.init,ImageManagerDialog);})();