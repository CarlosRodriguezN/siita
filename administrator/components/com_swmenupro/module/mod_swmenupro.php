<?php
/**
* swmenupro v7.3
* http://www.swmenupro.com
* Copyright 2006 Sean White
**/

//error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
defined( '_JEXEC' ) or die( 'Restricted access' );


global  $my, $Itemid,$mainframe;
$absolute_path=JPATH_ROOT;
$live_site=JURI::base();
if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
$config=&JFactory::getConfig();
$database = &JFactory::getDBO();
require_once($absolute_path."/modules/mod_swmenupro/styles.php");
require_once($absolute_path."/modules/mod_swmenupro/functions.php");
//echo "test";
$do_menu=1;
$template = @$params->get( 'template' ) ? strval( $params->get('template') ) :  "All";
$language = @$params->get( 'language' ) ? strval( $params->get('language') ) :  "All";
$component = @$params->get( 'component' ) ? strval( $params->get('component') ) :  "All";
//echo $language;
if($template!=""  && $template!="All"  ){
	$app = & JFactory::getApplication();
    $current_template = $app->getTemplate();
	if($current_template!=$template){$do_menu=0;}
}
if($language!=""  && $language!="All" && $language!="*"){
	//$lang=$config->getValue('language');
    $langsw = trim( JRequest::getVar( 'lang', '' ) );
    if ($langsw==""){$langsw=$config->getValue('language');}
    if(((substr($langsw,0,2))!=(substr($language,0,2)))){$do_menu=0;}
}

if($component!=""  && $component!="All" ){

	if(trim( JRequest::getVar( 'option', '' ) )!=$component){$do_menu=0;}
}
//echo "test";

if($do_menu){
	//echo "test";

$menu = @$params->get( 'menutype' ) ? strval( $params->get('menutype') ) :  "mainmenu";
$id = @$params->get( 'moduleID' )?intval( $params->get('moduleID') ) :  0;
$menustyle = @$params->get( 'menustyle' )? strval( $params->get('menustyle') ) :  "popoutmenu";
$parent_level = @$params->get('parent_level') ? intval( $params->get('parent_level') ) :  0;
$levels = @$params->get('levels') ? intval( $params->get('levels') ) :  25;
$parent_id = @$params->get('parentid') ? intval( $params->get('parentid') ) :  1;
$active_menu = @$params->get('active_menu') ? intval( $params->get('active_menu') ) :  0;
$hybrid = @$params->get('hybrid') ? intval( $params->get('hybrid') ) :  0;
$editor_hack = @$params->get('editor_hack') ? intval( $params->get('editor_hack') ) :  0;
$sub_indicator = @$params->get('sub_indicator') ? intval( $params->get('sub_indicator') ) :  0;
$css_load = @$params->get('cssload') ? $params->get('cssload'): 0 ;
$use_table = @$params->get('tables') ? $params->get('tables'): 0 ;
$cache = @$params->get('cache') ? $params->get('cache'): 0 ;
$cache_time = @$params->get('cache_time') ? $params->get('cache_time'): "1 hour" ;
$selectbox_hack = @$params->get('selectbox_hack') ? intval( $params->get('selectbox_hack') ) :  0;
$padding_hack = @$params->get('padding_hack') ? intval( $params->get('padding_hack') ) :  0;
$overlay_hack = @$params->get('overlay_hack') ? intval( $params->get('overlay_hack') ) :  0;
$auto_position = @$params->get('auto_position') ? intval( $params->get('auto_position') ) :  0;
$show_shadow = @$params->get('show_shadow') ? intval( $params->get('show_shadow') ) :  0;

$my_task = trim( JRequest::getVar( 'task', '' ) );
if(($my_task=="edit" || $my_task=="new") && $editor_hack) {
  $editor_hack=0;
}else{
  $editor_hack=1;	
}

$sub_active_menu=0;

//echo $menu;

$query = "SELECT * FROM #__swmenu_config WHERE id = ".$id;
$database->setQuery( $query );
$result = $database->loadObjectList();
$swmenupro=array();
while (list ($key, $val) = each ($result[0]))
{
    $swmenupro[$key]=$val;
}

$content= "\n<!--SWmenuPro7.3 J1.6 ".$menustyle." by http://www.swmenupro.com-->\n";   

if($menu && $id && $menustyle){
	if($css_load==1){
    	$headtag= "<link type='text/css' href='".$live_site."/modules/mod_swmenupro/styles/menu".$id.".css' rel='stylesheet' />\n";	
		$doc =& JFactory::getDocument();
$doc->addCustomTag( $headtag );
	}

	//echo $id.$menu.$hybrid.$parent_id.$levels;
	
	$ordered=swGetMenu($menu,$id,$hybrid,$cache,$cache_time,$use_table,$parent_id,$levels);
	//echo count($ordered);
	if (count($ordered)){
 		if ($parent_level){ 
 		//	echo "pl ".$parent_level;
          $ordered=sw_getsubmenu($ordered,$parent_level,25,$menu);
             if($active_menu){
             	$active_menu=sw_getactive($ordered);
             }
            // echo "count".count($ordered);
             if (count($ordered)){   
             $ordered = chain('ID', 'PARENT', 'ORDER', $ordered, $ordered[0]['mid'], $levels);
             $parent_id=$ordered[0]['mid'];
             }
       }elseif($active_menu){   
 	    	$active_menu=sw_getactive($ordered);
 	    	$sub_ordered=sw_getsubmenu($ordered,1,25,$menu);
 	    	//$sub_ordered = chain('ID', 'PARENT', 'ORDER', $ordered, $active_menu, $levels); 
 	    	if($sub_ordered){$sub_active_menu=sw_getactive($sub_ordered);}
 	    	$ordered = chain('ID', 'PARENT', 'ORDER', $ordered, $parent_id, $levels); 
 		}else{
 			$ordered = chain('ID', 'PARENT', 'ORDER', $ordered, $parent_id, $levels); 
 		}
 		
	}
	if(count($ordered)&&($swmenupro['orientation']=='horizontal/left')){
      $topcount=count(chain('ID', 'PARENT', 'ORDER', $ordered, $parent_id, 1));
      for($i=0;$i<count($ordered);$i++){
        $swmenu=$ordered[$i];
        if($swmenu['indent']==0){
          $ordered[$i]['ORDER']=$topcount;
          $topcount--;
        }
      }  
      $ordered = chain('ID', 'PARENT', 'ORDER', $ordered, $parent_id, $levels);     
   }
//   echo count($ordered)." ordered";
   
  //echo $sub_active_menu;
	if(count($ordered)){
		if ($menustyle == "clickmenu"){$content.= doClickMenu($ordered, $swmenupro, $css_load,$active_menu,$selectbox_hack,$padding_hack);}
		if ($menustyle == "treemenu"){$content.= doTreeMenu($ordered, $swmenupro, $css_load,$active_menu,$auto_position);}
		if ($menustyle == "popoutmenu"){$content.= doPopoutMenu($ordered, $swmenupro, $css_load, $active_menu,$overlay_hack);}
		if ($menustyle == "gosumenu" && $editor_hack){$content.= doGosuMenu($ordered, $swmenupro, $active_menu, $css_load,$selectbox_hack,$padding_hack,$auto_position,$show_shadow,$sub_indicator,$overlay_hack);}
		if ($menustyle == "superfishmenu" && $editor_hack){$content.= doSuperfishMenu($ordered, $swmenupro, $active_menu, $css_load,$selectbox_hack,$padding_hack,$auto_position,$show_shadow, $sub_indicator,$overlay_hack);}
		if ($menustyle == "transmenu"){$content.= doTransMenu($ordered, $swmenupro, $active_menu, $sub_indicator, $parent_id, $css_load,$selectbox_hack,$show_shadow,$padding_hack,$auto_position,$overlay_hack);}
		if ($menustyle == "tabmenu"){$content.= doTabMenu($ordered, $swmenupro, $parent_id, $css_load,$active_menu,$sub_active_menu);}
		if ($menustyle == "dynamictabmenu"){$content.= doDynamicTabMenu($ordered, $swmenupro, $parent_id, $css_load,$active_menu,$padding_hack,$auto_position,$sub_active_menu);}
		if ($menustyle == "columnmenu"){$content.= doColumnMenu($ordered, $swmenupro, $active_menu, $css_load,$selectbox_hack,$padding_hack,0,$show_shadow, $sub_indicator);}
		if ($menustyle == "slideclick"){$content.= doSlideClick($ordered, $swmenupro, $css_load,$active_menu,$selectbox_hack,$padding_hack,$parent_id);}
		if ($menustyle == "accordian"){$content.= doAccordian($ordered, $swmenupro, $css_load,$active_menu,$selectbox_hack,$padding_hack,$parent_id,$auto_position);}
		if ($menustyle == "clicktransmenu"){$content.= doClickTransMenu($ordered, $swmenupro, $css_load,$active_menu,$selectbox_hack,$padding_hack,$parent_id,$overlay_hack);}
	}
}
$content.="\n<!--End SWmenuPro menu module-->\n";

return $content;
}
?>



