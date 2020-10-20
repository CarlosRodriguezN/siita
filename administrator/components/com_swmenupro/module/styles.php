<?php
/**
* swmenupro v6.0
* http://swmenupro.com
* Copyright 2006 Sean White
**/
defined( '_JEXEC' ) or die( 'Restricted access' );

function ClickMenuStyle($swmenupro,$ordered){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
 $live_site =  JURI::base();
 if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}


	//<style type="text/css">
	//<!--
	$str="";
	$str.=".click-menu". $swmenupro['id']." { \n";
	$str.=" width: ". $swmenupro['main_width']."px"."; \n";
	$str.=" border: ". $swmenupro['main_border']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1 {\n";
	$str.=" border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1-hover{ \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']."  !important  ; \n":"";
	$str.=" border: ". $swmenupro['main_border_over']."  !important  ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .inbox1,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox1:visited ,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox1:active ,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox1:link \n";
	$str.="{\n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.=" padding: ". $swmenupro['main_padding']."  !important  ; \n";
	if ($swmenupro['main_width']!=0){$str.= "width:".$swmenupro['main_width']."px  !important  ; \n";}
	if ($swmenupro['main_height']!=0){$str.= "height:".$swmenupro['main_height']."px  !important  ; \n";}
	$str.=" font-size: ". $swmenupro['main_font_size']."px  !important  ; \n";
	$str.=" font-family: ". $swmenupro['font_family']."  !important  ; \n";
	$str.=" text-align: ". $swmenupro['main_align']."  !important  ; \n";
	$str.=" font-weight: ". $swmenupro['font_weight']."  !important  ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']."  !important  ; \n":"";
	$str.=" text-decoration: none  !important  ; \n";
	$str.=" margin-bottom:0px  !important  ; \n";
	$str.=" display:block  !important  ; \n";
	$str.=" white-space:nowrap  !important  ; \n";
	$str.="}\n";
	
	$str.=".click-menu". $swmenupro['id']." .inbox1:hover { \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']."  !important  ; \n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1-open .inbox1,\n";
	$str.=".click-menu". $swmenupro['id']." .box1-open {\n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.=" border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.="}\n";
	
	$str.=".click-menu". $swmenupro['id']." .box1-open a.inbox1 { \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1-open a{\n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1-open-hover { \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.=" border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .section { \n";
	//$str.=" width: ". $swmenupro['sub_width'].'px'." !important ; \n";
	$str.=" border: ". $swmenupro['sub_border']." !important ; \n";
	$str.=" display: none; \n";
	$str.=" filter: alpha(opacity=". $swmenupro['specialA'].")\n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .section a {\n";
	$str.=" width: ". $swmenupro['sub_width']."px; \n"; 
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.=" padding: ". $swmenupro['sub_padding']." !important ; \n";
	//$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.=" text-decoration: none !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .section a:hover { \n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.=" text-decoration: none !important ; \n";
	//$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .inbox2,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:visited ,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:active \n";
	$str.="{ \n";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.=" display:block; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']."#click-sub-active". $swmenupro['id']." ,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:hover {\n";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box2 { \n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." #click-sub-active". $swmenupro['id']." ,\n";
	$str.=".click-menu". $swmenupro['id']." .box2-hover {\n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1 .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .box2 .seq1 \n";
	$str.="{\n";
	$str.=" display:    block; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1-hover .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .box1-active .seq2, \n";
	$str.=".click-menu". $swmenupro['id']." .box2-hover .seq2, \n";
	$str.=".click-menu". $swmenupro['id']." .box2-active .seq2 \n";
	$str.="{ \n";
	$str.=" display:    block; \n";
	$str.="} \n";

	$str.=".click-menu". $swmenupro['id']." .box1-hover .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .box1-open .seq1, \n";
	$str.=".click-menu". $swmenupro['id']." .box1-open-hover .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .box1 .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .box2-hover .seq1, \n";
	$str.=".click-menu". $swmenupro['id']." .box2 .seq2  \n";
	$str.="{ \n";
	$str.=" display:    none; \n";
	$str.="} \n";
	//-->
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			$str.=click_customcss($ordered,$ordered[$i],$swmenupro['id'],"");
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			$str.=click_customcss($ordered,$ordered[$i],$swmenupro['id'],":hover");
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}

function click_customcss($ordered,$item,$id,$class){
	$str="";
	
	if($item['indent']==0){
		if($class){
			$str.="#click-menu".$id."-".($item['ORDER']-1).".box1-open .inbox1 ,\n";
			$str.="#click-menu".$id."-".($item['ORDER']-1).".box1-open  ,\n";
			$str.="#click-menu".$id."-".($item['ORDER']-1)." a:hover {\n";
		}else{
			$str.=" #click-menu".$id."-".($item['ORDER']-1)."  {\n";
		}
	}else{
		$topcount=0;	
		$subcount=0;	
		$k="";
			for($i=0;$i<(count($ordered));$i++){
				if($ordered[$i]['ID']==$item['ID']){
					$k="-".($topcount-1)."-".$subcount;
				}
				if($ordered[$i]['indent']){
					$subcount++;
				}else{
					$topcount++;
					$subcount=0;
				}	
			}
		if($class){
		$str.="#click-menu".$id.$k." .inbox2-active ,\n";
		$str.="#click-menu".$id.$k." #click-sub-active". $id." ,\n";
		$str.="#click-menu".$id.$k." .inbox2".$class." {\n";
		}else{
		$str.="#click-menu".$id.$k." .inbox2{\n";
		}
	}
	return $str;
}


function ClickTransMenuStyle($swmenupro,$ordered){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site = JURI::base();
  if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}

	//<style type="text/css">
	//<!--
	$str="";
	$str.=".click-menu". $swmenupro['id']." { \n";
	$str.=" width: ". $swmenupro['main_width']."px"."; \n";
	$str.=" border: ". $swmenupro['main_border']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1 {\n";
	$str.=" border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1-hover{ \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']."  !important  ; \n":"";
	$str.=" border: ". $swmenupro['main_border_over']."  !important  ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .inbox1,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox1:visited ,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox1:active ,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox1:link \n";
	$str.="{\n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.=" padding: ". $swmenupro['main_padding']."  !important  ; \n";
	if ($swmenupro['main_width']!=0){$str.= "width:".$swmenupro['main_width']."px  !important  ; \n";}
	if ($swmenupro['main_height']!=0){$str.= "height:".$swmenupro['main_height']."px  !important  ; \n";}
	$str.=" font-size: ". $swmenupro['main_font_size']."px  !important  ; \n";
	$str.=" font-family: ". $swmenupro['font_family']."  !important  ; \n";
	$str.=" text-align: ". $swmenupro['main_align']."  !important  ; \n";
	$str.=" font-weight: ". $swmenupro['font_weight']."  !important  ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']."  !important  ; \n":"";
	$str.=" text-decoration: none  !important  ; \n";
	$str.=" margin-bottom:0px  !important  ; \n";
	$str.=" display:block  !important  ; \n";
	$str.=" white-space:nowrap  !important  ; \n";
	$str.="}\n";
	
	$str.=".click-menu". $swmenupro['id']." .inbox1:hover { \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']."  !important  ; \n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1-open {\n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.=" border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.="}\n";
	
	$str.=".click-menu". $swmenupro['id']." .box1-open a.inbox1 { \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1-open a{\n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1-open-hover { \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.=" border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .section { \n";
	//$str.=" width: ". $swmenupro['sub_width'].'px'." !important ; \n";
	$str.=" border: ". $swmenupro['sub_border']." !important ; \n";
	$str.=" display: none; \n";
	$str.=" filter: alpha(opacity=". $swmenupro['specialA'].")\n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .section a {\n";
	$str.=" width: ". $swmenupro['sub_width']."px; \n"; 
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.=" padding: ". $swmenupro['sub_padding']." !important ; \n";
	//$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.=" text-decoration: none !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .section a:hover { \n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.=" text-decoration: none !important ; \n";
	//$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." a.inbox2,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:visited ,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:active \n";
	$str.="{ \n";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.=" display:block; \n";
	$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." a.inbox2-active ,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:hover {\n";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box2 { \n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	//$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." a.inbox2-active ,\n";
	$str.=".click-menu". $swmenupro['id']." .box2-hover {\n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	//$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.=" display:block; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .box1 .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .box2 .seq1 \n";
	$str.="{\n";
	$str.=" display:    block; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." #click-sub-active". $swmenupro['id']." .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .box1-hover .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .box1-active .seq2, \n";
	$str.=".click-menu". $swmenupro['id']." .box2-hover .seq2, \n";
	$str.=".click-menu". $swmenupro['id']." .box2-active .seq2 \n";
	$str.="{ \n";
	$str.=" display:    block; \n";
	$str.="} \n";

	$str.=".click-menu". $swmenupro['id']." #click-sub-active". $swmenupro['id']." .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .box1-hover .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .box1-open .seq1, \n";
	$str.=".click-menu". $swmenupro['id']." .box1-open-hover .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .box1 .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .box2-hover .seq1, \n";
	$str.=".click-menu". $swmenupro['id']." .box2 .seq2  \n";
	$str.="{ \n";
	$str.=" display:    none; \n";
	$str.="} \n";
	
	$str.= ".transMenu".$swmenupro['id']." {\n";
	$str.= " position:absolute ; \n";
	$str.= " overflow:hidden; \n";
	$str.= " left:-1000px; \n";
	$str.= " top:-1000px; \n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .content {\n";
	$str.= " position:absolute  ; \n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .items {\n";
	$str.= $swmenupro['sub_width']?" width: ". $swmenupro['sub_width']."px;":"";
	$str.= " border: ". $swmenupro['sub_border']." ; \n";
	$str.= " position:relative ; \n";
	$str.= " left:0px; top:0px; \n";
	$str.= " z-index:2; \n";
	$str.= "}\n";

	//$str.= ".transMenu". $swmenupro['id'].".top .items {\n";
	//$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']."  td\n";
	$str.= "{\n";
	$str.= " padding: ". $swmenupro['sub_padding']." !important;  \n";
	$str.= " font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.= " font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.= " text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.= " font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.= "} \n";
	
	$str.= "#subwrap". $swmenupro['id']." \n";
	$str.= "{ \n";
	$str.= " text-align: left ; \n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']."  .item.hover td\n";
	$str.= "{ \n";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .item { \n";
	$str.= $swmenupro['sub_height']?" height: ". $swmenupro['sub_height']."px;":"";
	$str.= " text-decoration: none ; \n";
	$str.= " cursor:pointer; \n";
	//$str.= " cursor:hand; \n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .background {\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.= " position:absolute ; \n";
	$str.= " left:0px; top:0px; \n";
	$str.= " z-index:1; \n";
	$str.= " opacity:". ($swmenupro['specialA']/100)."; \n";
	$str.= " filter:alpha(opacity=". ($swmenupro['specialA']).") \n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .shadowRight { \n";
	$str.= " position:absolute ; \n";
	$str.= " z-index:3; \n";
	//if($show_shadow){
	$str.= " top:3px; width:2px; \n";
	//}else{
	//	$str.= " top:-3000px; width:2px; \n";
	//}
	$str.= " opacity:". ($swmenupro['specialA']/100)."; \n";
	$str.= " filter:alpha(opacity=". ($swmenupro['specialA']).")\n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .shadowBottom { \n";
	$str.= " position:absolute ; \n";
	$str.= " z-index:1; \n";
	//if($show_shadow){
	$str.= " left:3px; height:2px; \n";
	//}else{
	//$str.= " left:-3000px; height:2px; \n";	
	//}
	$str.= " opacity:". ($swmenupro['specialA']/100)."; \n";
	$str.= " filter:alpha(opacity=". ($swmenupro['specialA']).")\n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .item.hover {\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .item img { \n";
	$str.= " margin-left:10px !important ; \n";
	$str.= "}\n";
	
	//$str.= "#menu". $swmenupro['id']." a img.seq1,\n";
	$str.= ".transMenu". $swmenupro['id']." img.seq1\n";
	$str.= "{\n";
	$str.= " display:    inline; \n";
	$str.= "}\n";

	//$str.= "#menu". $swmenupro['id']." a.hover img.seq2,\n";
	$str.= ".transMenu". $swmenupro['id']." .item.hover img.seq2 \n";
	$str.= "{\n";
	$str.= " display:   inline; \n";
	$str.= "}\n";

	//$str.= "#menu". $swmenupro['id']." a.hover img.seq1,\n";
	//$str.= "#menu". $swmenupro['id']." a img.seq2,\n";
	$str.= ".transMenu". $swmenupro['id']." img.seq2,\n";
	$str.= ".transMenu". $swmenupro['id']." .item.hover img.seq1\n";
	$str.= "{\n";
	$str.= " display:   none; \n";
	$str.= "}\n";
	
	$str.="#trans-active". $swmenupro['id']." a img.seq1\n";
	$str.="{\n";
	$str.=" display: none;\n";
	$str.="}\n";

	$str.="#trans-active". $swmenupro['id']." a img.seq2\n";
	$str.="{\n";
	$str.=" display: inline;\n";
	$str.="}\n";
	
	//-->
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			if($ordered[$i]['indent']>1){
			$str.=clicktrans_customcss($ordered,$ordered[$i],$swmenupro['id'],"");	
			}else{
			$str.=click_customcss($ordered,$ordered[$i],$swmenupro['id'],"");
			}
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			if($ordered[$i]['indent']>1){
			$str.=clicktrans_customcss($ordered,$ordered[$i],$swmenupro['id'],":hover");	
			}else{
			$str.=click_customcss($ordered,$ordered[$i],$swmenupro['id'],":hover");
			}
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}

function clicktrans_customcss($ordered,$item,$id,$class){
	$str="";
	
		$itemtop=$item;
		$subcount=0;
		$subcount2=0;
		$clname="";
		for($i=0;$i<(count($ordered)-1);$i++){
			if(($item['PARENT']==$ordered[$i]['PARENT'])&&($ordered[$i]['ORDER']==1)){
			    	$itemtop=$ordered[$i];	   
				}
				if($ordered[$i]["ID"]==$itemtop['ID']){
				$subcount2=$subcount;
				}
				if ((@$ordered[($i+1)]['PARENT']==$ordered[$i]['ID']&&$ordered[$i+1]['indent']>1 )){
			    	$subcount++;
				}
			}
		$clname="#TransMenu".($subcount2-1)."-".($item['ORDER']-1);	
		if($class){
		$str.=$clname.$class." {\n";
		}else{
		$str.=$clname.$class." {\n";
		}
	
	return $str;
}



function AccordianStyle($swmenupro,$ordered){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site =  JURI::base();
  if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}


	//<style type="text/css">
	//<!--
	$str="";
	$str.="#click-menu". $swmenupro['id']." { \n";
	//$str.=" position:relative; \n";
	$str.=" top: ".$swmenupro['main_top']."px  ; \n";
	$str.=" left: ".$swmenupro['main_left']."px; \n";
	$str.=" border: ". $swmenupro['main_border']." !important ; \n";
	//if ($swmenupro['main_width']!=0 && $swmenupro['orientation']=="vertical" ){$str.= "width:".$swmenupro['main_width']."px  !important  ; \n";}
	$str.="}\n";

	$str.="#click-menu". $swmenupro['id']." .inbox1 {\n";
	$str.=" position:relative; \n";
	$str.=" border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.=" padding: ". $swmenupro['main_padding']."  !important  ; \n";
	if ($swmenupro['main_width']!=0){$str.= "width:".$swmenupro['main_width']."px  !important  ; \n";}
	if ($swmenupro['main_height']!=0){$str.= "height:".$swmenupro['main_height']."px  !important  ; \n";}
	$str.=" font-size: ". $swmenupro['main_font_size']."px   ; \n";
	$str.=" font-family: ". $swmenupro['font_family']."  !important  ; \n";
	$str.=" text-align: ". $swmenupro['main_align']."  !important  ; \n";
	$str.=" font-weight: ". $swmenupro['font_weight']."  !important  ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']."  !important  ; \n":"";
	$str.=" text-decoration: none  !important  ; \n";
	$str.=" margin-bottom:0px  !important  ; \n";
	$str.=" display:block  !important  ; \n";
	$str.=" white-space:nowrap  !important  ; \n";
	$str.="}\n";

	
	//$str.=".click-menu". $swmenupro['id']." .inbox1-active,\n";
	$str.="#click-menu". $swmenupro['id']." .inbox1:hover{ \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']."  !important  ; \n":"";
	//$str.=" border: ". $swmenupro['main_border_over']."  !important  ; \n";
	$str.="}\n";

	
$str.="#click-menu". $swmenupro['id']." .inbox1-active, \n";
$str.="#click-menu". $swmenupro['id']."  div.active .inbox1{ \n";
	$str.=" border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.=" padding: ". $swmenupro['main_padding']."  !important  ; \n";
	if ($swmenupro['main_width']!=0){$str.= "width:".$swmenupro['main_width']."px  !important  ; \n";}
	if ($swmenupro['main_height']!=0){$str.= "height:".$swmenupro['main_height']."px  !important  ; \n";}
	$str.=" font-size: ". $swmenupro['main_font_size']."px   ; \n";
	$str.=" font-family: ". $swmenupro['font_family']."  !important  ; \n";
	$str.=" text-align: ". $swmenupro['main_align']."  !important  ; \n";
	$str.=" font-weight: ". $swmenupro['font_weight']."  !important  ; \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']."  !important  ; \n":"";
	$str.=" text-decoration: none  !important  ; \n";
	$str.=" margin:0px  !important  ; \n";
	$str.=" display:block  !important  ; \n";
	$str.=" white-space:nowrap  !important  ; \n";
	$str.="}\n";


	
	$str.=" .section". $swmenupro['id']." { \n";
	//$str.=" clear:both; \n";
	//$str.=" /float:left ; \n";
	$str.=" margin: 0px ; \n";
	$str.=" padding: 0px ; \n";
	$str.=" border: ". $swmenupro['sub_border']." !important ; \n";
	//$str.=" position:relative; \n";
	$str.=" top: ".$swmenupro['level1_sub_top']."px  ; \n";
	$str.=" left: ".$swmenupro['level1_sub_left']."px; \n";
	//$str.=" height:300px ; \n";
	//$str.=" display:   none; \n";
//$str.=" line-height: 0px ; \n";
	//$str.=" filter: alpha(opacity=". $swmenupro['specialA'].");\n";
	$str.="opacity:". ($swmenupro['specialA']/100).";\n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .inbox2  {\n";
	if ($swmenupro['sub_width']!=0){$str.= "width:".$swmenupro['sub_width']."px  !important  ; \n";}
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.=" padding: ". $swmenupro['sub_padding']." !important ; \n";
	$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.=" text-decoration: none !important ; \n";
	$str.=" margin:0px  !important  ; \n";
	$str.=" display:    block; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .inbox2-active, \n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:hover { \n";
	if ($swmenupro['sub_width']!=0){$str.= "width:".$swmenupro['sub_width']."px  !important  ; \n";}
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.=" padding: ". $swmenupro['sub_padding']." !important ; \n";
	$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.=" text-decoration: none !important ; \n";
	$str.=" margin:0px  !important  ; \n";
	$str.=" display:    block; \n";
	$str.="}\n";


	$str.=".click-menu". $swmenupro['id']." div.active .inbox1 .seq2,\n";
    $str.=".click-menu". $swmenupro['id']." .inbox1:hover .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:hover .seq2, \n";
	$str.=".click-menu". $swmenupro['id']." .inbox1 .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2 .seq1 \n";
	$str.="{\n";
	$str.=" display:    block; \n";
	$str.="}\n";

	//$str.=".click-menu". $swmenupro['id']." .inbox1:hover .seq2,\n";
	//$str.=".click-menu". $swmenupro['id']." .inbox2:hover .seq2 \n";
	//$str.="{ \n";
	//$str.=" display:    block; \n";
	//$str.="} \n";
	$str.=".click-menu". $swmenupro['id']." div.active .inbox1-active .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." div.active .inbox1 .seq1,\n";
    $str.=".click-menu". $swmenupro['id']." .inbox1-active .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2-active .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox1:hover .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox1 .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:hover .seq1, \n";
	$str.=".click-menu". $swmenupro['id']." .inbox2 .seq2  \n";
	$str.="{ \n";
	$str.=" display:    none; \n";
	$str.="} \n";
	//-->
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			$str.=clickslide_customcss($ordered,$ordered[$i],$swmenupro['id'],"");
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			$str.=clickslide_customcss($ordered,$ordered[$i],$swmenupro['id'],":hover");
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}





function SlideClickStyle($swmenupro,$ordered){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site = JURI::base();
  if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}


	//<style type="text/css">
	//<!--
	$str="";
	$str.="#click-menu". $swmenupro['id']." { \n";
	$str.=" position:relative; \n";
	$str.=" top: ".$swmenupro['main_top']."px  ; \n";
	$str.=" left: ".$swmenupro['main_left']."px; \n";
	$str.=" border: ". $swmenupro['main_border']." !important ; \n";
	//if ($swmenupro['main_width']!=0 && $swmenupro['orientation']=="vertical" ){$str.= "width:".$swmenupro['main_width']."px  !important  ; \n";}
	$str.="}\n";

	$str.="#click-menu". $swmenupro['id']." .inbox1 {\n";
	$str.=" border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.=" padding: ". $swmenupro['main_padding']."  !important  ; \n";
	if ($swmenupro['main_width']!=0){$str.= "width:".$swmenupro['main_width']."px  !important  ; \n";}
	if ($swmenupro['main_height']!=0){$str.= "height:".$swmenupro['main_height']."px  !important  ; \n";}
	$str.=" font-size: ". $swmenupro['main_font_size']."px   ; \n";
	$str.=" font-family: ". $swmenupro['font_family']."  !important  ; \n";
	$str.=" text-align: ". $swmenupro['main_align']."  !important  ; \n";
	$str.=" font-weight: ". $swmenupro['font_weight']."  !important  ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']."  !important  ; \n":"";
	$str.=" text-decoration: none  !important  ; \n";
	$str.=" margin-bottom:0px  !important  ; \n";
	$str.=" display:block  !important  ; \n";
	$str.=" white-space:nowrap  !important  ; \n";
	$str.="}\n";

	
	//$str.=".click-menu". $swmenupro['id']." .inbox1-active,\n";
	$str.="#click-menu". $swmenupro['id']." .inbox1:hover{ \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']."  !important  ; \n":"";
	//$str.=" border: ". $swmenupro['main_border_over']."  !important  ; \n";
	$str.="}\n";

	
$str.="#click-menu". $swmenupro['id']." .inbox1-active{ \n";
	$str.=" border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.=" padding: ". $swmenupro['main_padding']."  !important  ; \n";
	if ($swmenupro['main_width']!=0){$str.= "width:".$swmenupro['main_width']."px  !important  ; \n";}
	if ($swmenupro['main_height']!=0){$str.= "height:".$swmenupro['main_height']."px  !important  ; \n";}
	$str.=" font-size: ". $swmenupro['main_font_size']."px   ; \n";
	$str.=" font-family: ". $swmenupro['font_family']."  !important  ; \n";
	$str.=" text-align: ". $swmenupro['main_align']."  !important  ; \n";
	$str.=" font-weight: ". $swmenupro['font_weight']."  !important  ; \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']."  !important  ; \n":"";
	$str.=" text-decoration: none  !important  ; \n";
	$str.=" margin:0px  !important  ; \n";
	$str.=" display:block  !important  ; \n";
	$str.=" white-space:nowrap  !important  ; \n";
	$str.="}\n";


	
	$str.=" .section". $swmenupro['id']." { \n";
	//$str.=" clear:both; \n";
	//$str.=" /float:left ; \n";
	$str.=" margin: 0px ; \n";
	$str.=" padding: 0px ; \n";
	//$str.=" border: ". $swmenupro['sub_border']." !important ; \n";
	$str.=" position:relative; \n";
	$str.=" top: ".$swmenupro['level1_sub_top']."px  ; \n";
	$str.=" left: ".$swmenupro['level1_sub_left']."px; \n";
	$str.=" height:0px ; \n";
//$str.=" line-height: 0px ; \n";
	$str.=" filter: alpha(opacity=". $swmenupro['specialA'].");\n";
	$str.="opacity:". ($swmenupro['specialA']/100).";\n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .inbox2  {\n";
	if ($swmenupro['sub_width']!=0){$str.= "width:".$swmenupro['sub_width']."px  !important  ; \n";}
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" font-weight: ". $swmenupro['font_weight']." !important ; \n";
	$str.=" font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.=" text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.=" padding: ". $swmenupro['sub_padding']." !important ; \n";
	$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.=" text-decoration: none !important ; \n";
	$str.=" margin:0px  !important  ; \n";
	$str.=" display:    block; \n";
	$str.="}\n";

	$str.=".click-menu". $swmenupro['id']." .inbox2:hover { \n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.=" text-decoration: none !important ; \n";
	$str.=" border: ". $swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";



    $str.=".click-menu". $swmenupro['id']." .inbox1:hover .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:hover .seq2, \n";
	$str.=".click-menu". $swmenupro['id']." .inbox1 .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2 .seq1 \n";
	$str.="{\n";
	$str.=" display:    block; \n";
	$str.="}\n";

	//$str.=".click-menu". $swmenupro['id']." .inbox1:hover .seq2,\n";
	//$str.=".click-menu". $swmenupro['id']." .inbox2:hover .seq2 \n";
	//$str.="{ \n";
	//$str.=" display:    block; \n";
	//$str.="} \n";
    $str.=".click-menu". $swmenupro['id']." .inbox1-active .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2-active .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox1:hover .seq1,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox1 .seq2,\n";
	$str.=".click-menu". $swmenupro['id']." .inbox2:hover .seq1, \n";
	$str.=".click-menu". $swmenupro['id']." .inbox2 .seq2  \n";
	$str.="{ \n";
	$str.=" display:    none; \n";
	$str.="} \n";
	//-->
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			$str.=clickslide_customcss($ordered,$ordered[$i],$swmenupro['id'],"");
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			$str.=clickslide_customcss($ordered,$ordered[$i],$swmenupro['id'],":hover");
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}

function clickslide_customcss($ordered,$item,$id,$class){
	$str="";
	
	if($item['indent']==0){
		
		$topcount=0;	
		
			for($i=0;$i<(count($ordered));$i++){
				if($ordered[$i]['ID']==$item['ID']){
				$topcount=$i+1;
				}
			}
		
		
		if($class){
			$str.="#topclick".$id.($topcount)." a.inbox1:hover, #topclick".$id.($topcount)." a.inbox1-active {\n";
		}else{
			$str.=" #topclick".$id.($topcount)." a.inbox1{\n";
		}
	}else{
		
		if($class){
		$str.="#subclick".$id.$item['ID'].":hover {\n";
		}else{
		$str.="#subclick".$id.$item['ID']." {\n";
		}
	}
	return $str;
}


function cssTabMenuStyle($swmenupro,$ordered){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
    $live_site =  JURI::base();
    if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}


	//<style type="text/css" media="screen,print">

	$str="#tabtop2".$swmenupro['id']." {\n";
	$str.=" padding:0 !important ; \n";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	if ($swmenupro['main_width']!=0){
		$str.= " width:".$swmenupro['main_width']."px; \n";
	}else{
		$str.= " width: 100%; \n";
	}
	$str.="}  \n";

	$str.="#menu".$swmenupro['id']." { \n";
	$str.=" margin: 0 !important ; \n";
	$str.=" padding: 0 !important ; \n";
	$str.=" padding-top : ".$swmenupro['level1_sub_top']."px !important ; \n";
	$str.=" padding-left : ".$swmenupro['level1_sub_left']."px !important ; \n";
	$str.=" padding-right : ".$swmenupro['level2_sub_left']."px !important ; \n";
	$str.=" padding-bottom : ".$swmenupro['level2_sub_top']."px !important ; \n";
	$str.=" border-bottom : ".$swmenupro['main_border']." !important ; \n";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." ul, #menu".$swmenupro['id']." li    { \n";
	$str.=" display : inline; \n";
	$str.=" list-style-type : none !important ; \n";
	$str.=" margin : 0 !important ; \n";
	$str.=" padding: 0 !important ; \n";
	$str.="} \n";

	$str.="#menu".$swmenupro['id']." a:link, #menu".$swmenupro['id']." a:visited {\n";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=" border : ".$swmenupro['main_border']." !important ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']." !important ; \n":"";
	$str.=" font-weight: ".$swmenupro['font_weight']." !important ; \n";
	$str.=" font-size: ".$swmenupro['main_font_size']."px !important ; \n";
	$str.=" font-family: ".$swmenupro['font_family']." !important ; \n";
	$str.=" white-space:nowrap; \n";
	$str.=" line-height : ".$swmenupro['main_top']."px !important ; \n";
	$str.=" margin-right : ".$swmenupro['main_left']."px !important ; \n";
	$str.=" padding : ".$swmenupro['main_padding']." !important ; \n";
	$str.=" text-decoration : none; \n";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." a:link.active, #menu".$swmenupro['id']." a:visited.active   { \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." a:hover    {\n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." li#here".$swmenupro['id']." a {\n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=" border-bottom : ".$swmenupro['main_border_over']." !important ; \n";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']."  img.seq1,\n";
	$str.="#submenu".$swmenupro['id']."  img.seq1\n";
	$str.="{\n";
	$str.=" display:    inline; \n";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." img.seq2,\n";
	$str.="#menu".$swmenupro['id']." a:hover img.seq1, \n";
	$str.="#submenu".$swmenupro['id']." img.seq2,\n";
	$str.="#submenu".$swmenupro['id']." a:hover img.seq1, \n";
	$str.="#menu".$swmenupro['id']." li#here".$swmenupro['id']." a img.seq1 \n";
	$str.="{\n";
	$str.=" display:    none; \n";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." a:hover img.seq2, \n";
	$str.="#submenu".$swmenupro['id']." a:hover img.seq2, \n";
	$str.="#menu".$swmenupro['id']." li#here".$swmenupro['id']." a img.seq2 \n";
	$str.="{ \n";
	$str.=" display:    inline; \n";
	$str.="}\n";

	$str.="#tabsub2".$swmenupro['id']." { \n";
	$str.=" padding:".$swmenupro['sub_padding']." !important ; \n";
	$str.=" clear:both; \n";
	$str.="border-bottom : ".$swmenupro['sub_border_over']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	if ($swmenupro['sub_width']!=0){
		$str.= "width:".$swmenupro['sub_width']."px;";
	}else{
		// echo "width:100%;";
	}
	$str.="} \n";

	$str.=" #submenu".$swmenupro['id']." \n";
	$str.="{  \n";
	$str.=" clear:both; \n";
	$str.=" margin-top :0px !important ; \n";
	$str.="} \n";

	$str.="ul#submenu".$swmenupro['id']." {  \n";
	$str.=" display : inline; \n";
	$str.=" padding :0 !important ; \n";
	$str.=" clear:both; \n";
	$str.="} \n";

	$str.="ul#submenu".$swmenupro['id']." li { \n";
	$str.="display : inline; \n";
	$str.="padding :0 !important ; \n";
	$str.=" list-style-type : none !important ; \n";
	$str.="}\n";

	$str.=" ul#submenu".$swmenupro['id']." a { \n";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" font-weight: ".$swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ".$swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ".$swmenupro['font_family']." !important ; \n";
	$str.=" line-height : ".$swmenupro['sub_height']."px !important ; \n";
	$str.=" margin-right : ".$swmenupro['level1_sub_left']."px !important ; \n";
	$str.=" padding : ".$swmenupro['sub_padding']." !important ; \n";
	$str.=" text-decoration : none !important ; \n";
	$str.=" border : none !important ; \n";
	$str.=" border-left : ".$swmenupro['sub_border']." !important ; \n";
	$str.=" white-space:nowrap; \n";
	$str.="} \n";

	$str.="ul#submenu".$swmenupro['id']." a:hover, #css-tab-sub-active".$swmenupro['id']." { \n";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.="} \n";

	$str.="ul#submenu".$swmenupro['id']." #last".$swmenupro['id']." a {\n";
	$str.=" border-right : ".$swmenupro['sub_border']." !important ; \n";
	$str.="}\n";

	$str.=" #menu".$swmenupro['id']." ul a:hover , #css-tab-sub-active".$swmenupro['id']."{ \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";
	//$str.="</style> \n";
	
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			
			$str.=(!$ordered[$i]['indent']?"#menu".$swmenupro['id']:"ul#submenu".$swmenupro['id'])." #swdyntab".$swmenupro['id'].$ordered[$i]['ID']." {\n";
			
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			
				if($ordered[$i]['indent']){
					$str.="ul#submenu".$swmenupro['id']." #swdyntab".$swmenupro['id'].$ordered[$i]['ID'].":hover ,\n";
					$str.="ul#submenu".$swmenupro['id']." #css-tab-sub-active".$swmenupro['id']." #swdyntab".$swmenupro['id'].$ordered[$i]['ID']." {\n";
				}else{
					$str.="#menu".$swmenupro['id']." #here".$swmenupro['id']." #swdyntab".$swmenupro['id'].$ordered[$i]['ID'].", \n";
					$str.="#menu".$swmenupro['id']." #swdyntab".$swmenupro['id'].$ordered[$i]['ID'].":hover { \n";
				}
			
			
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}


function dynamicTabMenuStyle($swmenupro,$ordered,$subwrap=0){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site =  JURI::base();
  if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}

  //$subwrap=1;
	//<style type="text/css" media="screen,print">

	$str="#tabtop2".$swmenupro['id']." {\n";
	$str.=" padding:0 !important ; \n";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	if ($swmenupro['main_width']!=0){
		$str.= "width:".$swmenupro['main_width']."px; \n";
	}else{
		$str.= "width: 100%; \n";
	}
	$str.=" padding-top : ".$swmenupro['level1_sub_top']."px !important ; \n";
	$str.=" padding-left : ".$swmenupro['level1_sub_left']."px !important ; \n";
	$str.=" padding-right : ".$swmenupro['level2_sub_left']."px !important ; \n";
	$str.=" padding-bottom : ".$swmenupro['level2_sub_top']."px !important ; \n";
	$str.=" border-bottom : ".$swmenupro['main_border']." !important ; \n";
	$str.="}  \n";

	$str.="#menu".$swmenupro['id']." { \n";
	$str.=" margin: 0 !important ; \n";
	$str.=" padding: 0 !important ; \n";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." table, #menu".$swmenupro['id']." td    { \n";
	$str.=" height : ".$swmenupro['main_top']."px; \n";
	$str.=" margin : 0 !important ; \n";
	$str.=" padding: 0 !important ; \n";
	$str.="} \n";

	$str.="#menu".$swmenupro['id']." a {\n";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=" border : ".$swmenupro['main_border']." !important ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']." !important ; \n":"";
	$str.=" font-weight: ".$swmenupro['font_weight']." !important ; \n";
	$str.=" font-size: ".$swmenupro['main_font_size']."px !important ; \n";
	$str.=" font-family: ".$swmenupro['font_family']." !important ; \n";
	$str.=" white-space:nowrap !important ; \n";
	$str.=" margin-right : ".$swmenupro['main_left']."px !important ; \n";
	$str.=" padding : ".$swmenupro['main_padding']." !important ; \n";
	$str.=" text-decoration : none; \n";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." a:hover    {\n";
    $str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=" border-bottom : ".$swmenupro['main_border_over']." !important ; \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." td a.here".$swmenupro['id']." {\n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=" border-bottom : ".$swmenupro['main_border_over']." !important ; \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";
	
	$str.="#menu".$swmenupro['id']."  td#dyn-tab-top-active".$swmenupro['id']."  a{\n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=" border-bottom : ".$swmenupro['main_border_over']." !important ; \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";
	
	//$str.="#menu".$swmenupro['id']." td#active a img.seq1, \n";
	$str.="#menu".$swmenupro['id']."  img.seq1,\n";
	$str.=".submenu".$swmenupro['id']."  img.seq1\n";
	$str.="{\n";
	$str.=" display:    inline; \n";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." img.seq2,\n";
	$str.="#menu".$swmenupro['id']." a:hover img.seq1, \n";
	$str.="#menu".$swmenupro['id']." a.here".$swmenupro['id']." img.seq1, \n";
	$str.=".submenu".$swmenupro['id']." img.seq2,\n";
	$str.=".submenu".$swmenupro['id']." a:hover img.seq1, \n";
	$str.="#menu".$swmenupro['id']." td#dyn-tab-top-active".$swmenupro['id']." a img.seq1 \n";
	$str.="{\n";
	$str.=" display:    none; \n";
	$str.="}\n";

	$str.="#menu".$swmenupro['id']." a:hover img.seq2, \n";
	$str.=".submenu".$swmenupro['id']." a:hover img.seq2, \n";
	$str.="#menu".$swmenupro['id']." a.here".$swmenupro['id']." img.seq2, \n";
	$str.="#menu".$swmenupro['id']." td#dyn-tab-top-active".$swmenupro['id']." a img.seq2 \n";
	$str.="{ \n";
	$str.=" display:    inline; \n";
	$str.="}\n";

	$str.="#tabsub2".$swmenupro['id']." { \n";
	$str.=" padding:".$swmenupro['sub_padding']." !important ; \n";
	$str.=" clear:both; \n";
	$str.=" border-bottom : ".$swmenupro['sub_border_over']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	if ($swmenupro['sub_width']!=0){
		$str.= " width:".$swmenupro['sub_width']."px;";
	}else{
		// echo "width:100%;";
	}
	if ($swmenupro['sub_height']!=0){
    $str.= " height:".$swmenupro['sub_height']."px; \n";
    }
	$str.="} \n";

	$str.=" .submenu".$swmenupro['id']." \n";
	$str.="{  \n";
	$str.=" clear:both; \n";
	$str.=" margin-top :0px !important ; \n";
	
	$str.="} \n";

	if($subwrap){
	$str.="ul.submenu".$swmenupro['id']." {  \n";	
	}else{
	$str.="table.submenu".$swmenupro['id']." {  \n";
	}
	//$str.=" display : inline !important ; \n";
	$str.=" padding :0 !important ; \n";
	$str.=" clear:both; \n";
	$str.="} \n";

	
	if($subwrap){
	$str.="ul.submenu".$swmenupro['id']." li { \n";
	$str.="display : inline; \n";
	}else{
	$str.="table.submenu".$swmenupro['id']." td { \n";
	}
	
	$str.=" background-image: none; \n";
	$str.="padding :0 !important ; \n";
	// $str.= "width:".$swmenupro['sub_width']."px !important ; \n";
	$str.="}\n";

	if($subwrap){
	$str.=" ul.submenu".$swmenupro['id']." a { \n";
	}else{
	$str.=" table.submenu".$swmenupro['id']." a { \n";	
	}
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.=" font-weight: ".$swmenupro['font_weight_over']." !important ; \n";
	$str.=" font-size: ".$swmenupro['sub_font_size']."px !important ; \n";
	$str.=" font-family: ".$swmenupro['font_family']." !important ; \n";
	$str.=" height : ".$swmenupro['sub_height']."px; \n";
	$str.=" margin-right : ".$swmenupro['level1_sub_left']."px !important ; \n";
	$str.=" padding : ".$swmenupro['sub_padding']." !important ; \n";
	$str.=" text-decoration : none; \n";
	$str.=" border : none !important ; \n";
	$str.=" border-left : ".$swmenupro['sub_border']." !important ; \n";
	$str.=" white-space:nowrap; \n";
	$str.="} \n";

	if($subwrap){
	$str.="ul.submenu".$swmenupro['id']." a:hover, #dyn-tab-sub-active".$swmenupro['id']." a{ \n";
	}else{
	$str.="table.submenu".$swmenupro['id']." a:hover, #dyn-tab-sub-active".$swmenupro['id']." a{ \n";	
	}
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.="} \n";

	if($subwrap){
	$str.="ul.submenu".$swmenupro['id']." .last".$swmenupro['id']." a {\n";
	}else{
	$str.="table.submenu".$swmenupro['id']." .last".$swmenupro['id']." a {\n";
	}
	$str.=" border-right : ".$swmenupro['sub_border']." !important ; \n";
	$str.="}\n";

	if($subwrap){
	$str.="#menu".$swmenupro['id']." ul a:hover { \n";
	}else{
	$str.="#menu".$swmenupro['id']." table a:hover { \n";	
	}
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.="}\n";
	
	$str.=".swtabcontent".$swmenupro['id']." {\n";
 if ($swmenupro['sub_height']!=0){
 $str.= " height:".$swmenupro['sub_height']."px; \n";
 }
   if ($swmenupro['sub_width']!=0){
 $str.= " width:".$swmenupro['sub_width']."px; \n";
 }else{
  $str.= " width:100%; \n";
 }
 // $str.="border-bottom :".$swmenupro['sub_border_over']." !important ; \n";
	$str.=" display:none;\n";
$str.="}\n";
	//$str.="</style> \n";
	
	
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			if($subwrap){
			$str.=(!$ordered[$i]['indent']?"#menu".$swmenupro['id']:"ul.submenu".$swmenupro['id'])." #swdyntab".$swmenupro['id'].$ordered[$i]['ID']." {\n";
			}else{
			$str.=(!$ordered[$i]['indent']?"#menu".$swmenupro['id']:"table.submenu".$swmenupro['id'])." #swdyntab".$swmenupro['id'].$ordered[$i]['ID']." {\n";	
			}
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			if($subwrap){
				if($ordered[$i]['indent']){
					$str.="ul.submenu".$swmenupro['id']." #swdyntab".$swmenupro['id'].$ordered[$i]['ID'].":hover ,\n";
					$str.="ul.submenu".$swmenupro['id']." #dyn-tab-sub-active".$swmenupro['id']." #swdyntab".$swmenupro['id'].$ordered[$i]['ID']." {\n";
				}else{
					$str.="#menu".$swmenupro['id']." a.here".$swmenupro['id']."#swdyntab".$swmenupro['id'].$ordered[$i]['ID'].", \n";
					$str.="#menu".$swmenupro['id']." #swdyntab".$swmenupro['id'].$ordered[$i]['ID'].":hover { \n";
				}
			
			}else{
			if($ordered[$i]['indent']){
					$str.="table.submenu".$swmenupro['id']." #swdyntab".$swmenupro['id'].$ordered[$i]['ID'].":hover ,\n";
					$str.="table.submenu".$swmenupro['id']." #dyn-tab-sub-active".$swmenupro['id']." #swdyntab".$swmenupro['id'].$ordered[$i]['ID']." {\n";
				}else{
					$str.="#menu".$swmenupro['id']." a.here".$swmenupro['id']."#swdyntab".$swmenupro['id'].$ordered[$i]['ID'].", \n";
					$str.="#menu".$swmenupro['id']." #swdyntab".$swmenupro['id'].$ordered[$i]['ID'].":hover { \n";
				}
			}
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}




function columnMenuStyle($swmenupro,$ordered,$subwrap=0){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site =  JURI::base();
  if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}

	//$str="<style type=\"text/css\">\n";
	//$str.="<!--\n";
	
	$str="html body ul.sw-sf".$swmenupro['id']." ul,\n";
	$str.="html body ul.sw-sf".$swmenupro['id']." ul li{\n";
	$str.="width:180px; \n";
	$str.="}\n";
	
	$str.="html body ul.sw-sf".$swmenupro['id']." ul ul{\n";
	$str.="margin:0 0 0 180px; \n";
	$str.="}\n";
	
	
	$str.="ul.sw-sf".$swmenupro['id'].", ul.sw-sf".$swmenupro['id']." * {\n";
	//$str.="border:".$swmenupro['main_border']." !important ; \n";
	$str.="margin: 0  ; \n";
	$str.="padding: 0  ; \n";
	//$str.="list-style: none !important ; \n";
	//$str.="height:auto; \n";
	//$str.="position: relative; \n";
	//$str.=" border: ".$swmenupro['main_border']." !important ; \n";
	//$str.="z-index: 200; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." {\n";
	$str.="display: block; \n";
	$str.="position: relative; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." hr {display: block; clear: left; margin: -0.66em 0; visibility: hidden;}\n";
	
	
	$str.="ul.sw-sf".$swmenupro['id']." li{\n";
	$str.="position: relative; \n";
	$str.="list-style: none !important ; \n";
	//$str.=" border: ".$swmenupro['main_border']." !important ; \n";
	//if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}else{$str.= " width:100%; \n";}
	$str.="float:left; \n";
	$str.="display: block; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." li:hover {\n";
	$str.="visibility: inherit ; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." a {\n";
	$str.="display: block; \n";
	$str.="position: relative; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." ul  {\n";
	$str.="position: absolute; \n";
	$str.="left: 0; \n";
	$str.="width: 150px; \n";
	$str.="top: auto ; \n";
	$str.="left:-99999px; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." ul a {\n";
	$str.="zoom:1; \n";
	$str.="}\n";
	
	
	
	$str.="ul.sw-sf".$swmenupro['id']." ul li {\n";
	//$str.=" top: ".$swmenupro['main_top']."px !important ; \n";
	//$str.=" left: ".$swmenupro['main_left']."px; \n";
	$str.="float: left; \n";
	//$str.="float: none ; \n";
	//$str.="z-index: 200; \n";
	//$str.="outline: 0 !important ; \n";
	$str.="width:150px; \n";
	//$str.="display: inline; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." ul ul{\n";
	$str.="margin:0 0 0 150px; \n";
	$str.="top:0; \n";
	//$str.="float: left; \n";
	//$str.="position: relative; \n";
	$str.="}\n";
	
	$str.= "ul.sw-sf".$swmenupro['id']." li:hover ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." li:focus ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." li.sf-hover ul {\n";
	$str.= "left:auto ; \n";
	$str.="}\n";
	
	$str.= "ul.sw-sf".$swmenupro['id']." li:hover ul ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." li:focus ul ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." li.sf-hover ul ul{\n";
	$str.= "left:-99999px ; \n";
	$str.="}\n";
	
	$str.= "ul.sw-sf".$swmenupro['id']." ul li:hover ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul li:focus ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul li.sf-hover ul {\n";
	$str.= "left:auto ; \n";
	$str.="}\n";
	
	$str.= "ul.sw-sf".$swmenupro['id']." ul li:hover ul ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul li:focus ul ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul li.sf-hover ul ul{\n";
	$str.= "left:-99999px ; \n";
	$str.="}\n";
	
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul li:hover ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul li:focus ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul li.sf-hover ul {\n";
	$str.= "left:auto ; \n";
	$str.="}\n";
	
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul li:hover ul ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul li:focus ul ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul li.sf-hover ul ul{\n";
	$str.= "left:-99999px ; \n";
	$str.="}\n";
	
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul ul li:hover ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul ul li:focus ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul ul li.sf-hover ul {\n";
	$str.= "left:auto ; \n";
	$str.="}\n";
	
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul ul li:hover ul ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul ul li:focus ul ul,\n";
	$str.= "ul.sw-sf".$swmenupro['id']." ul ul ul li.sf-hover ul ul{\n";
	$str.= "left:-99999px ; \n";
	$str.="}\n";
	
	
	
	
	/*
	$str.=".sw-sf".$swmenupro['id']." a {\n";
	$str.="display: block; \n";
	$str.="position: relative; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." li:hover ul ,\n";
	$str.=".sw-sf".$swmenupro['id']." li.sf-hover ul {\n";
	$str.="left: 0; \n";
	$str.="top: 2.5em; \n";
	$str.="z-index: 400; \n";
	$str.="width:100%; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." li:hover li ul ,\n";
	$str.="ul.sw-sf".$swmenupro['id']." li.sf-hover li ul {\n";
	$str.="top: -999em; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." li li:hover ul ,\n";
	$str.="ul.sw-sf".$swmenupro['id']." li li.sf-hover ul {\n";
	$str.="left: 10em; \n";
//	if ($swmenupro['main_width']!=0){$str.= " left:".$swmenupro['main_width']."px; \n";}else{$str.= " left:100%; \n";}
	$str.="top: 0; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." li li:hover li ul ,\n";
	$str.="ul.sw-sf".$swmenupro['id']." li li.sf-hover li ul {\n";
	$str.="top: -999em; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." li li li:hover ul ,\n";
	$str.="ul.sw-sf".$swmenupro['id']." li li li.sf-hover ul {\n";
	$str.="left: 10em; \n";
//	if ($swmenupro['main_width']!=0){$str.= " left:".$swmenupro['main_width']."px; \n";}else{$str.= " left:100%; \n";}
	$str.="top: 0; \n";
	$str.="}\n";
	
	$str.="#sfmenu".$swmenupro['id']." {\n";
	$str.="position: relative; \n";
	$str.="border: ".$swmenupro['main_border']." !important ; \n";
	$str.="top: ".$swmenupro['main_top']."px !important ; \n";
	$str.="left: ".$swmenupro['main_left']."px; \n";
	//$str.="width:auto; \n";
	//$str.="width:auto; \n";
	$str.="}\n";
	
	$str.="#sf-section".$swmenupro['id']." {\n";
	//$str.="position: relative; \n";
	$str.="border: ".$swmenupro['sub_border']." !important ; \n";
	//$str.="top: ".$swmenupro['main_top']."px !important ; \n";
	//$str.="left: ".$swmenupro['main_left']."px; \n";
	//$str.="width:auto; \n";
	//$str.="width:auto; \n";
	$str.="}\n";
	
	if ($swmenupro['orientation']=="vertical"){
	
	
	$str.=".sw-sf".$swmenupro['id'].".sf-vertical, .sw-sf".$swmenupro['id'].".sf-vertical li {\n";
	//if ($swmenupro['main_width']==0){$str.= "float:none; \n";}
	//$str.="float:none !important; \n";
	$str.="display:block !important; \n";
	//$str.="outline:0; \n";
	//$str.=" border: ".$swmenupro['main_border']." !important ; \n";
	$str.="margin: 0 !important ; \n";
	if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}else{$str.= "width:100%; \n";}
	//if ($swmenupro['main_height']!=0){$str.= " height:".$swmenupro['main_height']."px; \n";}
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id'].".sf-vertical li:hover ul, .sw-sf".$swmenupro['id'].".sf-vertical li.sf-hover ul {\n";
	//$str.="width:auto; \n";
	if ($swmenupro['main_width']!=0){$str.= " left:".($swmenupro['main_width']+$swmenupro['level1_sub_left'])."px; \n";}else{$str.= " left:100%; \n";}
	$str.="top:".$swmenupro['level1_sub_top']."px !important ; \n";
	$str.="}\n";
	
	//$str.=".sw-sf".$swmenupro['id'].".sf-vertical li li:hover ul, .sw-sf".$swmenupro['id'].".sf-vertical li li.sf-hover ul {\n";
	//$str.="left: 10em !important ; \n";
	//$str.="left:".$swmenupro['level2_sub_left']."px !important; \n";
	//$str.="top:0 !important; \n";
	//$str.="}\n";

}else{
	
	$str.=".sw-sf".$swmenupro['id']." li.sf-hover li , .sw-sf".$swmenupro['id']." li:hover li {\n";
	//$str.="width:auto; \n";
	//if ($swmenupro['main_width']!=0){$str.= " left:".($swmenupro['main_width']+$swmenupro['level1_sub_left'])."px; \n";}else{$str.= " left:100%; \n";}
	$str.="top:".$swmenupro['level1_sub_top']."px !important ; \n";
	$str.="left:".$swmenupro['level1_sub_left']."px !important ; \n";
	$str.="}\n";
	
	//$str.=".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li {\n";
	//$str.="left: 10em !important ; \n";
	//$str.="left: 0px !important; \n";
	//$str.="top:0 !important; \n";
	//$str.="}\n";
	
	
	
}

$str.=".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li {\n";
	//$str.="left: 10em !important ; \n";
	$str.="top:".$swmenupro['level2_sub_top']."px !important; \n";
	$str.="left:".$swmenupro['level2_sub_left']."px !important; \n";
	$str.="}\n";
	
*/
	
	$str.=".sw-sf".$swmenupro['id']." a.item1 {\n";
	
	$str.=" padding: ".$swmenupro['main_padding']." !important ; \n";
	//$str.=" top: ".$swmenupro['main_top']."px !important ; \n";
	//$str.=" left: ".$swmenupro['main_left']."px; \n";
	$str.=" font-size: ".$swmenupro['main_font_size']."px !important ; \n";
	$str.=" font-family: ".$swmenupro['font_family']." !important ; \n";
	$str.=" text-align: ".$swmenupro['main_align']." !important ; \n";
	$str.=" font-weight: ".$swmenupro['font_weight']." !important ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']." !important ; \n":"";
	$str.=" text-decoration: none !important ; \n";
	$str.=" border-top: ".$swmenupro['main_border_over']." !important ; \n";
	$str.=" border-left: ". $swmenupro['main_border_over']." !important ; \n";
	if($swmenupro['orientation']=="vertical"){
		$str.= " border-right: ".$swmenupro['main_border_over']." !important ; \n";
		$str.= " border-bottom: 0 !important ; \n";
	}else{
		$str.= " border-bottom: ".$swmenupro['main_border_over']." !important;\n" ;
		$str.= " border-right: 0 !important ; \n";
	}
	//$str.=" display: block; \n";
	//$str.=" white-space: nowrap; \n";
	//$str.=" position: relative; \n";
	//$str.="z-index: 100; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}
	if ($swmenupro['main_height']!=0){$str.= " height:".$swmenupro['main_height']."px; \n";}
	//$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." a.item1.last {\n";
	
	if($swmenupro['orientation']=="vertical"){
		$str.= " border-bottom: ".$swmenupro['main_border_over']." !important ; \n";
		//$str.= " border-right: 0 !important ; \n";
	}else{
		$str.= " border-right: ".$swmenupro['main_border_over']." !important;\n" ;
		//$str.= " border-bottom: 0 !important ; \n";
	}
	$str.="}\n";
	
	$str.= ".sw-sf".$swmenupro['id']." .current a.item1,\n";
	//$str.= ".sw-sf".$swmenupro['id']." li:hover,\n";
	//$str.= ".sw-sf".$swmenupro['id']." li.sf-hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover a.item1,\n";
	$str.= ".sw-sf".$swmenupro['id']." a:focus,\n";
	$str.= ".sw-sf".$swmenupro['id']." a:hover ,\n";
	$str.= ".sw-sf".$swmenupro['id']." a:active {\n";
	
	$str.= is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? "background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.="}\n";
	
	
	$str.= ".sw-sf".$swmenupro['id']."  a.item2 {\n";
	//$str.= ".sw-sf".$swmenupro['id']." a.item2:hover,\n";
	//$str.= ".sw-sf".$swmenupro['id']." a.item2-active,\n";
	//$str.= ".sw-sf".$swmenupro['id']." a.item2-active:hover {\n";
	$str.= " padding: ".$swmenupro['sub_padding']." !important ; \n";
	$str.= " font-size: ".$swmenupro['sub_font_size']."px !important ; \n";
	$str.= " font-family: ".$swmenupro['sub_font_family']." !important ; \n";
	$str.= " text-align: ".$swmenupro['sub_align']." !important ; \n";
	$str.= " font-weight: ".$swmenupro['font_weight_over']." !important ; \n";
	$str.= " text-decoration: none !important ; \n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.= " display: block; \n";
	$str.= " white-space: nowrap; \n";
	//$str.= " position: relative; \n";
	$str.= " border-top: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.= " border-left: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.= " border-right: ".$swmenupro['sub_border_over']." !important ; \n";
	if ($swmenupro['sub_width']!=0){$str.= " width:".$swmenupro['sub_width']."px; \n";}
	if ($swmenupro['sub_height']!=0){$str.= " height:".$swmenupro['sub_height']."px; \n";}
	//$str.= " opacity:".($swmenupro['specialA']/100)."; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." a.item2.last {\n";
	$str.= " border-bottom: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";
	
	
	//$str.= ".sw-sf".$swmenupro['id']." li li a:hover,\n";
	//$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sw-sf-subactive a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li.sf-hover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover  li.sf-hover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover  li.sf-hover li.sf-hover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover  li.sf-hover li.sf-hover li.sf-hover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover  li.sf-hover li.sf-hover li.sf-hover li.sf-hover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover  li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover  li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']."  a.item2:hover {\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	//$str.= " border-top: ".$swmenupro['sub_border_over']." !important ; \n";
	//$str.= " border-left: ".$swmenupro['sub_border_over']." !important ; \n";
	//$str.= " border-right: ".$swmenupro['sub_border_over']." !important ; \n";
	//$str.="display:block !important; \n";
	$str.= "}\n";
	
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li.sf-hover li a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover li a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover li a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover li.sf-hover li a.item2{\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	//$str.= " border-bottom: ".$swmenupro['sub_border_over']." !important ; \n";
	//$str.= " z-index:500; \n";
	$str.="}\n";
	
	$str.= ".sw-sf".$swmenupro['id']." li.current img.seq2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover img.seq2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover img.seq2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover img.seq2,\n";
	//$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." img.seq1\n";
	$str.= "{\n";
	$str.= " display:    inline; \n";
	$str.="}\n";

	

	$str.= ".sw-sf".$swmenupro['id']." img.seq2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li img.seq2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sf-hover li.sf-hover li.sf-hover li img.seq2,\n";
	//$str.= ".sw-sf".$swmenupro['id']." a.item2:hover img.seq1,\n";
	//$str.= ".sw-sf".$swmenupro['id']." a.item1:hover img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.current img.seq1\n";
	$str.= "{\n";
	$str.= " display:   none; \n";
	$str.="}\n";
/*
	$str.="a.sf-with-ul{\n";
	$str.="padding-right:2.25em;\n";
	$str.="min-width:1px; \n";
	$str.="}\n";
	
	$str.=".sf-sub-indicator{\n";
	$str.="position: absolute;\n";
	$str.="display: block; \n";
	$str.="right: .75em; \n";
	$str.="top: 1.05em; \n";
	$str.="width: 10px; \n";
	$str.="height: 10px; \n";
	$str.="text-indent: -999em; \n";
	$str.="overflow:hidden; \n";
	$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-ffffff.png') no-repeat -10px -100px; \n";
	$str.="}\n";
	
	$str.="a > .sf-sub-indicator{\n";
	$str.="top: .8em;\n";
	$str.="background-position: 0 -100px; \n";
	$str.="}\n";
	
	$str.="a:focus > .sf-sub-indicator,\n";
	$str.="a:hover > .sf-sub-indicator,\n";
	$str.="a:active > .sf-sub-indicator,\n";
	$str.="li:hover > a > .sf-sub-indicator,\n";
	$str.="li.sfHover > a > .sf-sub-indicator{\n";
	$str.="top: .8em;\n";
	$str.="background-position: 0 -100px; \n";
	$str.="}\n";
	
	$str.="ul .sf-sub-indicator{ background-position: -10px 0;}\n";
	$str.="ul a > .sf-sub-indicator{ background-position: 0 0;}\n";
	
	$str.="ul a:focus > .sf-sub-indicator,\n";
	$str.="ul a:hover > .sf-sub-indicator,\n";
	$str.="ul a:active > .sf-sub-indicator,\n";
	$str.="ul li:hover > a > .sf-sub-indicator,\n";
	$str.="ul li.sfHover > a > .sf-sub-indicator{\n";
	//$str.="top: .8em;\n";
	$str.="background-position: -10px 0; \n";
	$str.="}\n";
	
/*
	$str.=".sf-shadow ul{\n";
	$str.="background:	url('http://swmenu/j1518/modules/mod_swmenupro/images/superfish/shadow.png') no-repeat bottom right;\n";
	$str.="padding: 0 8px 9px 0; \n";
	$str.="-moz-border-radius-bottomleft: 17px; \n";
	$str.="-moz-border-radius-topright: 17px; \n";
	$str.="-webkit-border-top-right-radius: 17px; \n";
	$str.="-webkit-border-bottom-left-radius: 17px; \n";
	$str.="}\n";
	
	$str.=".sf-shadow ul.sf-shadow-off{\n";
	$str.="background:	transparent;\n";
	//$str.="padding: 0 8px 9px 0; \n";
	//$str.="-moz-border-radius-bottomleft: 17px; \n";
	//$str.="-moz-border-radius-topright: 17px; \n";
	//$str.="-webkit-border-top-right-radius: 17px; \n";
	//$str.="-webkit-border-bottom-left-radius: 17px; \n";
	$str.="}\n";
	
	
	*/
	
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			$str.=superfish_customcss($ordered,$ordered[$i],$swmenupro['id'],"");
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			$str.=superfish_customcss($ordered,$ordered[$i],$swmenupro['id'],":hover");
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}


function TigraMenuStyle($swmenupro,$ordered){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site =  JURI::base();
  if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}


	//<style type="text/css">
	//<!--
	$str="";
	$str.= ".m0l0iout".$swmenupro['id']." { \n";
	$str.= " font-family: ".$swmenupro['font_family']." !important ; \n";
	$str.= " font-size: ".$swmenupro['main_font_size'].'px'." !important ; \n";
	$str.= " text-decoration: none !important ; \n";
	$str.= " padding: ".$swmenupro['main_padding']." !important ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']." !important ; \n":"";
	$str.= " font-weight: ".$swmenupro['font_weight']." !important ; \n";
	$str.= " text-align: ".$swmenupro['main_align']." !important ; \n";
	$str.= "}\n";

	$str.= ".m0l0iover".$swmenupro['id']." {\n";
	$str.= " font-family: ".$swmenupro['font_family']." !important ; \n";
	$str.= " font-size: ".$swmenupro['main_font_size'].'px'." !important ; \n";
	$str.= " text-decoration: none !important ; \n";
	$str.= " padding: ".$swmenupro['main_padding']." !important ; \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.= " font-weight: ".$swmenupro['font_weight_over']." !important ; \n";
	$str.= " text-align: ".$swmenupro['main_align']." !important ; \n";
	$str.= "}\n";

	$str.= ".m0l0oout".$swmenupro['id']." { \n";
	$str.= " text-decoration : none !important ; \n";
	$str.= " border: ".$swmenupro['main_border']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.= "}\n";

	$str.= ".m0l0oover".$swmenupro['id']." { \n";
	$str.= " text-decoration : none !important ; \n";
	$str.= " border: ".$swmenupro['main_border_over']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.= "}\n";
	

	$str.= ".m0l1iout".$swmenupro['id']." {\n";
	$str.= " font-family: ".$swmenupro['sub_font_family']." !important ; \n";
	$str.= " font-size: ".$swmenupro['sub_font_size'].'px'." !important ; \n";
	$str.= " text-decoration: none !important ; \n";
	$str.= " padding: ".$swmenupro['sub_padding']." !important ; \n";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.= " font-weight: ".$swmenupro['font_weight']." !important ; \n";
	$str.= " text-align: ".$swmenupro['sub_align']." !important ; \n";
	$str.= "}\n";

	$str.= ".m0l1iover".$swmenupro['id']." { \n";
	$str.= " font-family: ".$swmenupro['sub_font_family']." !important ; \n";
	$str.= " font-size: ".$swmenupro['sub_font_size'].'px'." !important ; \n";
	$str.= " text-decoration: none !important ; \n";
	$str.= " padding: ".$swmenupro['sub_padding']." !important ; \n";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.= " font-weight: ".$swmenupro['font_weight_over']." !important ; \n";
	$str.= " text-align: ".$swmenupro['sub_align']." !important ; \n";
	$str.= "}  \n";

	$str.= ".m0l1oout".$swmenupro['id']." { \n";
	$str.= " text-decoration : none !important ; \n";
	$str.= " border: ".$swmenupro['sub_border']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.= " opacity:". ($swmenupro['specialA']/100)."; \n";
	$str.= " filter:alpha(opacity=". ($swmenupro['specialA']).") \n";
	$str.= "} \n";

	$str.= ".m0l1oover".$swmenupro['id']." {\n";
	$str.= " text-decoration : none !important ; \n";
	$str.= " border: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.= "}\n";

	
	$str.= "#swactive_menu".$swmenupro['id']." a,\n";
	$str.= "#swactive_menu".$swmenupro['id']." div{ \n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.= " font-weight: ".$swmenupro['font_weight_over']." !important ; \n";
	$str.= " text-decoration : none !important ; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? "background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.= "}\n";
	
	
	$str.= ".m0l0oout".$swmenupro['id']." img.seq1, \n";
	$str.= ".m0l1oout".$swmenupro['id']." img.seq1 \n";
	$str.= "{ \n";
	$str.= " display:    inline; \n";
	$str.= "} \n";

	$str.= ".m0l1oover".$swmenupro['id']."Hover seq2,\n";
	$str.= ".m0l1oover".$swmenupro['id']."Active seq2, \n";
	$str.= ".m0l0oover".$swmenupro['id']."Hover seq2,\n";
	$str.= ".m0l0oover".$swmenupro['id']."Active seq2 \n";
	$str.= "{ \n";
	$str.= " display:    inline; \n";
	$str.= "}\n";

	$str.= ".m0l0oout".$swmenupro['id']." .seq2, \n";
	$str.= ".m0l0oout".$swmenupro['id']." .seq1,  \n";
	$str.= ".m0l0oover".$swmenupro['id']." .seq1, \n";
	$str.= ".m0l1oout".$swmenupro['id']." .seq2,   \n";
	$str.= ".m0l1oout".$swmenupro['id']." .seq1,   \n";
	$str.= ".m0l1oover".$swmenupro['id']." .seq1   \n";
	$str.= "{                                                 \n";
	$str.= " display:    none; \n";
	$str.= "} \n";
	
	$str.="#swactive_menu". $swmenupro['id']." a img.seq1\n";
	$str.="{\n";
	$str.=" display: none;\n";
	$str.="}\n";

	$str.="#swactive_menu". $swmenupro['id']." a img.seq2\n";
	$str.="{\n";
	$str.=" display: inline;\n";
	$str.="}\n";
	//-->
	//</style>
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			//$str.="#e0_".$i."i ,\n";
			$str.="#e0_".$i."o {\n";
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			//$str.="#swactive_menu".$swmenupro['id']." #e0_".$i."i ,\n";
			$str.="#swactive_menu".$swmenupro['id']." #e0_".$i."o ,\n";
			$str.="#e0_".$i."o:hover {\n";
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}



function TreeMenuStyle($swmenupro,$ordered){
	global $mainframe;
	jimport( 'joomla.application.component.controller' );
	$absolute_path=JPATH_ROOT;
    
	
	$live_site =  JURI::base();
	if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}

	//<style type="text/css">
	//<!--
	$str=".dtree".$swmenupro['id']." {\n";
	if ($swmenupro['main_width']!=0){$str.= "width:".$swmenupro['main_width']."px; \n";}
	$str.=" border: ".$swmenupro['main_border']." !important ; \n";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.=" margin: 0 !important ; \n";
	$str.=" padding: ".$swmenupro['main_padding']." !important ; \n";
	$str.="}\n";
	
	$str.=".dtree".$swmenupro['id']." img {\n";
	$str.=" border: 0px !important ; \n";
	$str.=" vertical-align: middle !important ; \n";
	$str.="}\n";

	$str.=".dtree".$swmenupro['id']." a {\n";
	$str.=" font-family: ".$swmenupro['font_family']." !important ; \n";
	$str.=" font-size: ".$swmenupro['main_font_size'].'px'." !important ; \n";
	$str.=" text-decoration: none !important ; \n";
	$str.=" font-weight: ".$swmenupro['font_weight']." !important ; \n";
	//$str.=" text-align: ".$swmenupro['main_align']." !important ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']." !important ; \n":"";
	$str.=" text-decoration: none !important ; \n";
	//$str.=" padding: ".$swmenupro['main_padding']." !important ; \n";
	$str.="}\n";

	$str.=".dtree".$swmenupro['id']." a.node, .dtree".$swmenupro['id']." a.nodeSel {\n";
	$str.=" white-space: nowrap; \n";
	$str.=" padding: ".$swmenupro['sub_padding']." !important ; \n";
	$str.="}\n";

	$str.=".dtree".$swmenupro['id']." a.node:hover, .dtree".$swmenupro['id']." a.nodeSel:hover {\n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=" font-weight: ".$swmenupro['font_weight_over']." !important ; \n";
	//$str.=" text-align: ".$swmenupro['main_align']." !important ; \n";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.="}\n";

	$str.=".dtree".$swmenupro['id']." a.nodeSel {\n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=" font-weight: ".$swmenupro['font_weight_over']." !important ; \n";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.="}\n";

	$str.=".dtree".$swmenupro['id']." .clip {\n";
	$str.=" overflow: hidden; \n";
	$str.="}\n";
	//-->
	//</style>
	
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			$str.=" #sd".$swmenupro['id'].($i+1)." {\n";
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			$str.=" a.nodeSel#sd".$swmenupro['id'].($i+1).",\n";
			$str.=" #sd".$swmenupro['id'].($i+1).":hover {\n";
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}


function gosuMenuStyle($swmenupro,$ordered,$sub_indicator){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site =  JURI::base();
  if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}

	//$str="<style type=\"text/css\">\n";
	//$str.="<!--\n";
	$str=".ddmx".$swmenupro['id']."{\n";
	$str.="border:".$swmenupro['main_border']." !important ; \n";
	$str.="}\n";
	
	$str.=".ddmx".$swmenupro['id']." a.item1,\n";
	$str.=".ddmx".$swmenupro['id']." a.item1:hover,\n";
	$str.=".ddmx".$swmenupro['id']." a.item1-active,\n";
	$str.=".ddmx".$swmenupro['id']." a.item1-active:hover {\n";
	$str.=" padding: ".$swmenupro['main_padding']." !important ; \n";
	$str.=" top: ".$swmenupro['main_top']."px !important ; \n";
	$str.=" left: ".$swmenupro['main_left']."px; \n";
	$str.=" font-size: ".$swmenupro['main_font_size']."px !important ; \n";
	$str.=" font-family: ".$swmenupro['font_family']." !important ; \n";
	$str.=" text-align: ".$swmenupro['main_align']." !important ; \n";
	$str.=" font-weight: ".$swmenupro['font_weight']." !important ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']." !important ; \n":"";
	$str.=" text-decoration: none !important ; \n";
	$str.=" display: block; \n";
	$str.=" white-space: nowrap; \n";
	$str.=" position: relative; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	//$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}
	if ($swmenupro['main_height']!=0){$str.= " height:".$swmenupro['main_height']."px; \n";}
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.="}\n";

	$str.=".ddmx".$swmenupro['id']." td.item11 {\n";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.=" padding:0 !important ; \n";
	$str.=" border-top: ".$swmenupro['main_border_over']." !important ; \n";
	$str.=" border-left: ". $swmenupro['main_border_over']." !important ; \n";
	if($swmenupro['orientation']=="vertical/left" || $swmenupro['orientation']=="vertical/right"){
		$str.= " border-right: ".$swmenupro['main_border_over']." !important ; \n";
		$str.= " border-bottom: 0 !important ; \n";
	}else{
		$str.= " border-bottom: ".$swmenupro['main_border_over'].";\n" ;
		$str.= " border-right: 0 !important ; \n";
	}
	$str.=" white-space: nowrap !important ; \n";
	if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}
	if ($swmenupro['main_height']!=0){$str.= " height:".$swmenupro['main_height']."px; \n";}
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." td.item11-last {\n";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.= " padding:0 !important ; \n";
	$str.= " border: ". $swmenupro['main_border_over']." !important ; \n";
	$str.= " white-space: nowrap; \n";
	if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}
	if ($swmenupro['main_height']!=0){$str.= " height:".$swmenupro['main_height']."px; \n";}
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." td.item11-acton {\n";
	$str.= " padding:0 !important ; \n";
	$str.= " border-top: ".$swmenupro['main_border_over']." !important ; \n";
	$str.= " border-left: ".$swmenupro['main_border_over']." !important ; \n";
	$str.= " white-space: nowrap; \n";
	if($swmenupro['orientation']=="vertical/left" || $swmenupro['orientation']=="vertical/right"){
		$str.= " border-right: ".$swmenupro['main_border_over']." !important ; \n";
	}else{
		$str.= " border-bottom: ".$swmenupro['main_border_over'].";\n" ;
	}
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." td.item11-acton-last:hover,\n";
	$str.= ".ddmx".$swmenupro['id']." td.item11-acton-last {\n";
	$str.= " border: ".$swmenupro['main_border_over']." !important ; \n";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." .item11:hover,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11-acton:hover,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11-last:hover,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11-acton-last a.item1,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11-acton a.item1,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11-acton-last a:hover,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11-acton a:hover,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11 a:hover,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11-last a:hover,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item1-active,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item1-active:hover {\n";
	$str.= is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? "background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." a.item2,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2:hover,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2-active,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2-active:hover {\n";
	$str.= " padding: ".$swmenupro['sub_padding']." !important ; \n";
	$str.= " font-size: ".$swmenupro['sub_font_size']."px !important ; \n";
	$str.= " font-family: ".$swmenupro['sub_font_family']." !important ; \n";
	$str.= " text-align: ".$swmenupro['sub_align']." !important ; \n";
	$str.= " font-weight: ".$swmenupro['font_weight_over']." !important ; \n";
	$str.= " text-decoration: none !important ; \n";
	$str.= " display: block; \n";
	$str.= " white-space: nowrap; \n";
	//$str.= " position: relative; \n";
	//$str.= " width:100%; \n";
	if ($swmenupro['sub_width']!=0){$str.= " width:".$swmenupro['sub_width']."px; \n";}
	if ($swmenupro['sub_height']!=0){$str.= " height:".$swmenupro['sub_height']."px; \n";}
	$str.= " opacity:".($swmenupro['specialA']/100)."; \n";
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." a.item2 {\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"background-image: none;";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.= " border-top: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.= " border-left: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.= " border-right: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." a.item2-last {\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"background-image: none;";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.= " border-bottom: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.= " z-index:500; \n";
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." a.item2:hover,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2-active,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2-active:hover {\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"background-image: none;";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.= " border-top: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.= " border-left: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.= " border-right: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.= "}\n";

	$str.= ".ddmx".$swmenupro['id']." .section {\n";
	$str.= " border: ".$swmenupro['sub_border']." !important ; \n";
	$str.= " position: absolute; \n";
	$str.= " visibility: hidden; \n";
	$str.= " display: block; \n";
	$str.= " z-index: -1; \n";
	$str.="}\n";
	
	//$str.= ".ddmx".$swmenupro['id']." .section".$swmenupro['id']." {\n";
	//$str.= " display: block; \n";
	//$str.= " width:100%; \n";
	//$str.="}\n";
	
	$str.= ".ddmx".$swmenupro['id']." .subsection a{\n";
	//$str.= " border: ".$swmenupro['sub_border']." !important ; \n";
	//$str.= " position: absolute; \n";
	if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}
	//$str.= " display: block; \n";
	$str.= " white-space:normal !important; \n";
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']."frame {\n";
	$str.= " border: ".$swmenupro['sub_border']." !important ; \n";
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." .item11-acton-last .item1 img.seq2,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11-acton .item1 img.seq2,\n";
	$str.= ".ddmx".$swmenupro['id']." img.seq1\n";
	$str.= "{\n";
	$str.= " display:    inline; \n";
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." a.item1:hover img.seq2,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item1-active img.seq2,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item1-active:hover img.seq2,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2:hover img.seq2,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2-active img.seq2,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2-active:hover img.seq2\n";
	$str.= "{\n";
	$str.= " display:    inline; \n";
	$str.="}\n";

	$str.= ".ddmx".$swmenupro['id']." img.seq2,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11-acton-last .item1 img.seq1,\n";
	$str.= ".ddmx".$swmenupro['id']." .item11-acton .item1 img.seq1,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2:hover img.seq1,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2-active img.seq1,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item2-active:hover img.seq1,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item1:hover img.seq1,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item1-active img.seq1,\n";
	$str.= ".ddmx".$swmenupro['id']." a.item1-active:hover img.seq1\n";

	$str.= "{\n";
	$str.= " display:   none; \n";
	$str.="}\n";

	$str.= "* html .ddmx".$swmenupro['id']." td { position: relative; } /* ie 5.0 fix */\n";
	//$str.="-->\n";
	//$str.="</style>\n";
	
	
	
	if($sub_indicator){
	$str.=".ddmx".$swmenupro['id']." a:hover.sw-arrow,\n";
	$str.=".ddmx".$swmenupro['id']." a.sw-arrow{\n";
	//$str.="position: absolute;\n";
	//$str.="display: block; \n";
	//$str.="right: .75em; \n";
	//$str.="top: 1.05em; \n";
	//$str.="width: 10px; \n";
	//$str.="height: 10px; \n";
	//$str.="text-indent: -999em; \n";
	//$str.="overflow:hidden; \n";
	
	
	
	if($swmenupro['orientation']=="vertical/left"||$swmenupro['orientation']=="horizontal/left"){
	switch ($sub_indicator) {
			// cases are slightly different
			case 2:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/white-on.gif') no-repeat center left; \n";
			break;

			case 3:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/black-on.gif') no-repeat center left; \n";
			break;

			case 4:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/grey-on.gif') no-repeat center left; \n";
			break;
			
			case 5:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/blue-on.gif') no-repeat center left; \n";
			break;

			case 6:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/red-on.gif') no-repeat center left; \n";
			break;

			case 7:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/green-on.gif') no-repeat center left; \n";
			break;
			
			case 8:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/yellow-on.gif') no-repeat center left; \n";
			break;

			default:	// formerly case 2
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/submenuleft-on.gif') no-repeat center left; \n";
			break;
		}
		
	}else{
		
		switch ($sub_indicator) {
			// cases are slightly different
			case 2:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/whiteleft-on.gif') no-repeat center right; \n";
			break;

			case 3:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/blackleft-on.gif') no-repeat center right; \n";
			break;

			case 4:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/greyleft-on.gif') no-repeat center right; \n";
			break;
			
			case 5:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/blueleft-on.gif') no-repeat center right; \n";
			break;

			case 6:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/redleft-on.gif') no-repeat center right; \n";
			break;

			case 7:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/greenleft-on.gif') no-repeat center right; \n";
			break;
			
			case 8:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/yellowleft-on.gif') no-repeat center right; \n";
			break;

			default:	// formerly case 2
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/transmenu/submenu-on.gif') no-repeat center right; \n";
			break;
		}
		
		
		
		
		
	}

	//$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-white.png') no-repeat -10px -100px; \n";
	$str.="}\n";
	
	
	}
	
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			$str.=mygosu_customcss($ordered,$ordered[$i],$swmenupro['id'],"");
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			$str.=mygosu_customcss($ordered,$ordered[$i],$swmenupro['id'],":hover");
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}

function mygosu_customcss($ordered,$item,$id,$class){
	
	$str="";
	if($item['indent']==0){
		if($class){
		$str.=".ddmx".$id." td.item11-acton-last #menu".$id."-".($item['ORDER']-1).",\n";
		$str.=".ddmx".$id." td.item11-acton #menu".$id."-".($item['ORDER']-1).",\n";
		$str.=".ddmx".$id." a.item1-active#menu".$id."-".($item['ORDER']-1).",\n";
		$str.=".ddmx".$id." #menu".$id."-".($item['ORDER']-1).$class." {\n";
		}else{
		$str.=".ddmx".$id." #menu".$id."-".($item['ORDER']-1)." {\n";
		}
		}else{
		$k="-".($item['ORDER']-1);
		$cid=$item['PARENT'];
		for($j=0;$j<($item['indent']);$j++){
			for($i=0;$i<(count($ordered));$i++){
				if($cid==$ordered[$i]['ID']){
			    	$k ="-".($ordered[$i]['ORDER']-1).$k;
				    $cid=$ordered[$i]['PARENT'];
				    $i=count($ordered);
				}
			}
		}
		if($class){
		$str.=".ddmx".$id." td.item11-acton #menu".$id.$k.$class." ,\n";
	    $str.=".ddmx".$id." a.item1-active#menu".$id.$k.$class." ,\n";
		$str.=".ddmx".$id." #menu".$id.$k.$class." {\n";
		}else{
		$str.=".ddmx".$id." #menu".$id.$k." {\n";
		}
	}
	return $str;
}



function superfishMenuStyle($swmenupro,$ordered,$sub_indicator){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site = JURI::base();
  if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}

	//$str="<style type=\"text/css\">\n";
	//$str.="<!--\n";
	$str=".sw-sf".$swmenupro['id'].", .sw-sf".$swmenupro['id']." * {\n";
	//$str.="border:".$swmenupro['main_border']." !important ; \n";
	$str.="margin: 0 !important ; \n";
	$str.="padding: 0 !important ; \n";
	$str.="list-style: none !important ; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." {\n";
	//$str.="height:auto; \n";
	//$str.=" border: ".$swmenupro['main_border']." !important ; \n";
	$str.="line-height: 1.0 !important ; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." hr {display: block; clear: left; margin: -0.66em 0; visibility: hidden;}\n";
	
	
	$str.=".sw-sf".$swmenupro['id']." ul{\n";
	$str.="position: absolute; \n";
	$str.="top: -999em; \n";
	//$str.=" border: ".$swmenupro['main_border']." !important ; \n";
	//if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}else{$str.= " width:100%; \n";}
	$str.="width: 10em; \n";
	$str.="display: block; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." ul li {\n";
	//$str.="display:block; \n";
	$str.="width: 100% !important ; \n";
	//$str.="height: 1px !important ; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." li:hover {\n";
	$str.="z-index:300 ; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." li:hover {\n";
	$str.="visibility: inherit ; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." li {\n";
	$str.="float: left; \n";
	$str.="position: relative; \n";
	//$str.="display: inline; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." li li{\n";
	$str.=" top: 0 !important ; \n";
	$str.=" left: 0; \n";
	//$str.="float: left; \n";
	$str.="position: relative; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." a {\n";
	$str.="display: block; \n";
	$str.="position: relative; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." li:hover ul ,\n";
	$str.=".sw-sf".$swmenupro['id']." li.sfHover ul {\n";
	$str.="left: 0; \n";
	$str.="top: 2.5em; \n";
	$str.="z-index: 400; \n";
	$str.="width:100%; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." li:hover li ul ,\n";
	$str.="ul.sw-sf".$swmenupro['id']." li.sfHover li ul {\n";
	$str.="top: -999em; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." li li:hover ul ,\n";
	$str.="ul.sw-sf".$swmenupro['id']." li li.sfHover ul {\n";
	$str.="left: 10em; \n";
//	if ($swmenupro['main_width']!=0){$str.= " left:".$swmenupro['main_width']."px; \n";}else{$str.= " left:100%; \n";}
	$str.="top: 0; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." li li:hover li ul ,\n";
	$str.="ul.sw-sf".$swmenupro['id']." li li.sfHover li ul {\n";
	$str.="top: -999em; \n";
	$str.="}\n";
	
	$str.="ul.sw-sf".$swmenupro['id']." li li li:hover ul ,\n";
	$str.="ul.sw-sf".$swmenupro['id']." li li li.sfHover ul {\n";
	$str.="left: 10em; \n";
//	if ($swmenupro['main_width']!=0){$str.= " left:".$swmenupro['main_width']."px; \n";}else{$str.= " left:100%; \n";}
	$str.="top: 0; \n";
	$str.="}\n";
	
	$str.="#sfmenu".$swmenupro['id']." {\n";
	$str.="position: relative; \n";
	$str.="border: ".$swmenupro['main_border']." !important ; \n";
	$str.="top: ".$swmenupro['main_top']."px !important ; \n";
	$str.="left: ".$swmenupro['main_left']."px; \n";
	$str.="}\n";
	
	$str.="#sf-section".$swmenupro['id']." {\n";
	//$str.="position: relative; \n";
	$str.="border: ".$swmenupro['sub_border']." !important ; \n";
	$str.="}\n";
	
	if ($swmenupro['orientation']=="vertical"){
	
	
	$str.=".sw-sf".$swmenupro['id'].".sf-vertical, .sw-sf".$swmenupro['id'].".sf-vertical li {\n";
	//if ($swmenupro['main_width']==0){$str.= "float:none; \n";}
	//$str.="float:none !important; \n";
	$str.="display:block !important; \n";
	//$str.="outline:0; \n";
	//$str.=" border: ".$swmenupro['main_border']." !important ; \n";
	$str.="margin: 0 !important ; \n";
	if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}else{$str.= "width:100%; \n";}
	//if ($swmenupro['main_height']!=0){$str.= " height:".$swmenupro['main_height']."px; \n";}
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id'].".sf-vertical li:hover ul, .sw-sf".$swmenupro['id'].".sf-vertical li.sfHover ul {\n";
	//$str.="width:auto; \n";
	if ($swmenupro['main_width']!=0){$str.= " left:".($swmenupro['main_width']+$swmenupro['level1_sub_left'])."px; \n";}else{$str.= " left:100%; \n";}
	$str.="top:".$swmenupro['level1_sub_top']."px !important ; \n";
	$str.="}\n";
	
}else{
	
	$str.=".sw-sf".$swmenupro['id']." li.sfHover li , .sw-sf".$swmenupro['id']." li:hover li {\n";
	//$str.="width:auto; \n";
	//if ($swmenupro['main_width']!=0){$str.= " left:".($swmenupro['main_width']+$swmenupro['level1_sub_left'])."px; \n";}else{$str.= " left:100%; \n";}
	$str.="top:".$swmenupro['level1_sub_top']."px !important ; \n";
	$str.="left:".$swmenupro['level1_sub_left']."px !important ; \n";
	$str.="}\n";
	
}

$str.=".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li {\n";
	//$str.="left: 10em !important ; \n";
	$str.="top:".$swmenupro['level2_sub_top']."px !important; \n";
	$str.="left:".$swmenupro['level2_sub_left']."px !important; \n";
	$str.="}\n";
	

	
	$str.=".sw-sf".$swmenupro['id']." a.item1 {\n";
	
	$str.=" padding: ".$swmenupro['main_padding']." !important ; \n";
	//$str.=" top: ".$swmenupro['main_top']."px !important ; \n";
	//$str.=" left: ".$swmenupro['main_left']."px; \n";
	$str.=" font-size: ".$swmenupro['main_font_size']."px !important ; \n";
	$str.=" font-family: ".$swmenupro['font_family']." !important ; \n";
	$str.=" text-align: ".$swmenupro['main_align']." !important ; \n";
	$str.=" font-weight: ".$swmenupro['font_weight']." !important ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']." !important ; \n":"";
	$str.=" text-decoration: none !important ; \n";
	$str.=" border-top: ".$swmenupro['main_border_over']." !important ; \n";
	$str.=" border-left: ". $swmenupro['main_border_over']." !important ; \n";
	if($swmenupro['orientation']=="vertical"){
		$str.= " border-right: ".$swmenupro['main_border_over']." !important ; \n";
		$str.= " border-bottom: 0 !important ; \n";
	}else{
		$str.= " border-bottom: ".$swmenupro['main_border_over']." !important;\n" ;
		$str.= " border-right: 0 !important ; \n";
	}
	$str.=" display: block; \n";
	$str.=" white-space: nowrap; \n";
	$str.=" position: relative; \n";
	//$str.="z-index: 100; \n";
	$str.=is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}
	if ($swmenupro['main_height']!=0){$str.= " height:".$swmenupro['main_height']."px; \n";}
	//$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." a.item1.last {\n";
	
	if($swmenupro['orientation']=="vertical"){
		$str.= " border-bottom: ".$swmenupro['main_border_over']." !important ; \n";
		//$str.= " border-right: 0 !important ; \n";
	}else{
		$str.= " border-right: ".$swmenupro['main_border_over']." !important;\n" ;
		//$str.= " border-bottom: 0 !important ; \n";
	}
	$str.="}\n";
	
	$str.= ".sw-sf".$swmenupro['id']." .current a.item1,\n";
	//$str.= ".sw-sf".$swmenupro['id']." li:hover,\n";
	//$str.= ".sw-sf".$swmenupro['id']." li.sfHover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover a.item1,\n";
	$str.= ".sw-sf".$swmenupro['id']." a:focus,\n";
	$str.= ".sw-sf".$swmenupro['id']." a:hover ,\n";
	$str.= ".sw-sf".$swmenupro['id']." a:active {\n";
	
	$str.= is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? "background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.="}\n";
	
	
	$str.= ".sw-sf".$swmenupro['id']."  a.item2 {\n";
	$str.= " padding: ".$swmenupro['sub_padding']." !important ; \n";
	$str.= " font-size: ".$swmenupro['sub_font_size']."px !important ; \n";
	$str.= " font-family: ".$swmenupro['sub_font_family']." !important ; \n";
	$str.= " text-align: ".$swmenupro['sub_align']." !important ; \n";
	$str.= " font-weight: ".$swmenupro['font_weight_over']." !important ; \n";
	$str.= " text-decoration: none !important ; \n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.= " display: block; \n";
	$str.= " white-space: nowrap; \n";
	$str.= " position: relative; \n";
	$str.= " border-top: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.= " border-left: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.= " border-right: ".$swmenupro['sub_border_over']." !important ; \n";
	if ($swmenupro['sub_width']!=0){$str.= " width:".$swmenupro['sub_width']."px; \n";}
	if ($swmenupro['sub_height']!=0){$str.= " height:".$swmenupro['sub_height']."px; \n";}
	$str.= " opacity:".($swmenupro['specialA']/100)."; \n";
	$str.= " filter:alpha(opacity=". ($swmenupro['specialA']).") \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." a.item2.last {\n";
	$str.= " border-bottom: ".$swmenupro['sub_border_over']." !important ; \n";
	$str.="}\n";
	
	//$str.= ".sw-sf".$swmenupro['id']." li li a:hover,\n";
	//$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sw-sf-subactive a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li.sfHover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover  li.sfHover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover  li.sfHover li.sfHover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover  li.sfHover li.sfHover li.sfHover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover  li.sfHover li.sfHover li.sfHover li.sfHover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover  li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover  li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover a.item2:hover,\n";
	$str.= ".sw-sf".$swmenupro['id']."  a.item2:hover {\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	//$str.= " filter:alpha(opacity=". ($swmenupro['specialA']).") \n";
	$str.= "}\n";
	
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li.sfHover li a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li a.item2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li a.item2{\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	//$str.= " filter:alpha(opacity=". ($swmenupro['specialA']).") \n";
	$str.="}\n";
	
	$str.= ".sw-sf".$swmenupro['id']." li.current img.seq2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover img.seq2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover img.seq2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover img.seq2,\n";
	//$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." img.seq1\n";
	$str.= "{\n";
	$str.= " display:    inline; \n";
	$str.="}\n";

	

	$str.= ".sw-sf".$swmenupro['id']." img.seq2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li img.seq2,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.sfHover li.sfHover li.sfHover li img.seq2,\n";
	//$str.= ".sw-sf".$swmenupro['id']." a.item2:hover img.seq1,\n";
	//$str.= ".sw-sf".$swmenupro['id']." a.item1:hover img.seq1,\n";
	$str.= ".sw-sf".$swmenupro['id']." li.current img.seq1\n";
	$str.= "{\n";
	$str.= " display:   none; \n";
	$str.="}\n";
	
	
	if($sub_indicator>1){

	$str.=".sw-sf".$swmenupro['id']." a.sf-with-ul{\n";
	$str.="padding-right:2.25em;\n";
	$str.="min-width:1px; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." .sf-sub-indicator{\n";
	$str.="position: absolute;\n";
	$str.="display: block; \n";
	$str.="right: .75em; \n";
	$str.="top: 1.05em; \n";
	$str.="width: 10px; \n";
	$str.="height: 10px; \n";
	$str.="text-indent: -999em; \n";
	$str.="overflow:hidden; \n";
	
	switch ($sub_indicator) {
			// cases are slightly different
			case 2:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-white.png') no-repeat -10px -100px; \n";
			break;

			case 3:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-black.png') no-repeat -10px -100px; \n";
			break;

			case 4:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-grey.png') no-repeat -10px -100px; \n";
			break;
			
			case 5:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-blue.png') no-repeat -10px -100px; \n";
			break;

			case 6:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-red.png') no-repeat -10px -100px; \n";
			break;

			case 7:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-green.png') no-repeat -10px -100px; \n";
			break;
			
			case 8:
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-yellow.png') no-repeat -10px -100px; \n";
			break;

			default:	// formerly case 2
			$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-white.png') no-repeat -10px -100px; \n";
			break;
		}

	//$str.="background:	url('".$live_site."/modules/mod_swmenupro/images/superfish/arrows-white.png') no-repeat -10px -100px; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." a > .sf-sub-indicator{\n";
	$str.="top: .8em;\n";
	$str.="background-position: 0 -100px; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." a:focus > .sf-sub-indicator,\n";
	$str.=".sw-sf".$swmenupro['id']." a:hover > .sf-sub-indicator,\n";
	$str.=".sw-sf".$swmenupro['id']." a:active > .sf-sub-indicator,\n";
	$str.=".sw-sf".$swmenupro['id']." li:hover > a > .sf-sub-indicator,\n";
	$str.=".sw-sf".$swmenupro['id']." li.sfHover > a > .sf-sub-indicator{\n";
	$str.="top: .8em;\n";
	$str.="background-position: 0 -100px; \n";
	$str.="}\n";
	
	$str.=".sw-sf".$swmenupro['id']." ul .sf-sub-indicator{ background-position: -10px 0;}\n";
	$str.=".sw-sf".$swmenupro['id']." ul a > .sf-sub-indicator{ background-position: 0 0;}\n";
	
	$str.=".sw-sf".$swmenupro['id']." ul a:focus > .sf-sub-indicator,\n";
	$str.=".sw-sf".$swmenupro['id']." ul a:hover > .sf-sub-indicator,\n";
	$str.=".sw-sf".$swmenupro['id']." ul a:active > .sf-sub-indicator,\n";
	$str.=".sw-sf".$swmenupro['id']." ul li:hover > a > .sf-sub-indicator,\n";
	$str.=".sw-sf".$swmenupro['id']." ul li.sfHover > a > .sf-sub-indicator{\n";
	//$str.="top: .8em;\n";
	$str.="background-position: -10px 0; \n";
	$str.="}\n";
	
	}
	
/*
	$str.=".sf-shadow ul{\n";
	$str.="background:	url('http://swmenu/j1518/modules/mod_swmenupro/images/superfish/shadow.png') no-repeat bottom right;\n";
	$str.="padding: 0 8px 9px 0; \n";
	$str.="-moz-border-radius-bottomleft: 17px; \n";
	$str.="-moz-border-radius-topright: 17px; \n";
	$str.="-webkit-border-top-right-radius: 17px; \n";
	$str.="-webkit-border-bottom-left-radius: 17px; \n";
	$str.="}\n";
	
	$str.=".sf-shadow ul.sf-shadow-off{\n";
	$str.="background:	transparent;\n";
	//$str.="padding: 0 8px 9px 0; \n";
	//$str.="-moz-border-radius-bottomleft: 17px; \n";
	//$str.="-moz-border-radius-topright: 17px; \n";
	//$str.="-webkit-border-top-right-radius: 17px; \n";
	//$str.="-webkit-border-bottom-left-radius: 17px; \n";
	$str.="}\n";
	
	
	*/
	
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			$str.=superfish_customcss($ordered,$ordered[$i],$swmenupro['id'],"");
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			$str.=superfish_customcss($ordered,$ordered[$i],$swmenupro['id'],":hover");
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}

function superfish_customcss($ordered,$item,$id,$class){
	
	$str="";
	if($item['indent']==0){
	if($class){
		$str.="#sf-".$id.$item['ID'].".current a.item1,\n";
	    $str.="#sf-".$id.$item['ID'].".sfHover a.item1{\n";
		
	}else{
	$str.="#sf-".$id.$item['ID']." a.item1{\n";
	}
	}else{
	if($class){
		$str.="#sf-".$id.$item['ID'].".current a.item2,\n";
	    $str.="#sf-".$id.$item['ID'].".sfHover a.item2{\n";
		
	}else{
	$str.="#sf-".$id.$item['ID']." a.item2{\n";
	}	
				
	}
	return $str;
}

function transMenuStyle($swmenupro,$ordered,$show_shadow){
	global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site=JURI::base();
	if(substr($live_site,(strlen($live_site)-1),1)=="/"){$live_site=substr($live_site,0,(strlen($live_site)-1));}
	if(substr($live_site,(strlen($live_site)-13),13)=="administrator"){$live_site=substr($live_site,0,(strlen($live_site)-14));}
//$str=$live_site;
	//<style type="text/css">
	//<!--
	$str= ".transMenu".$swmenupro['id']." {\n";
	$str.= " position:absolute ; \n";
	$str.= " overflow:hidden; \n";
	$str.= " left:-1000px; \n";
	$str.= " top:-1000px; \n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .content {\n";
	$str.= " position:absolute  ; \n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .items {\n";
	$str.= $swmenupro['sub_width']?" width: ". $swmenupro['sub_width']."px;":"";
	$str.= " border: ". $swmenupro['sub_border']." ; \n";
	$str.= " position:relative ; \n";
	$str.= " left:0px; top:0px; \n";
	$str.= " z-index:2; \n";
	$str.= "}\n";

	//$str.= ".transMenu". $swmenupro['id'].".top .items {\n";
	//$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']."  td\n";
	$str.= "{\n";
	$str.= " padding: ". $swmenupro['sub_padding']." !important;  \n";
	$str.= " font-size: ". $swmenupro['sub_font_size']."px !important ; \n";
	$str.= " font-family: ". $swmenupro['sub_font_family']." !important ; \n";
	$str.= " text-align: ". $swmenupro['sub_align']." !important ; \n";
	$str.= " font-weight: ". $swmenupro['font_weight_over']." !important ; \n";
	$str.=$swmenupro['sub_font_color']?" color: ".$swmenupro['sub_font_color']." !important ; \n":"";
	$str.= "} \n";
	
	$str.= "#subwrap". $swmenupro['id']." \n";
	$str.= "{ \n";
	$str.= " text-align: left ; \n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']."  .item.hover td\n";
	$str.= "{ \n";
	$str.=$swmenupro['sub_font_color_over']?" color: ".$swmenupro['sub_font_color_over']." !important ; \n":"";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .item { \n";
	$str.= $swmenupro['sub_height']?" height: ". $swmenupro['sub_height']."px;":"";
	$str.= " text-decoration: none ; \n";
	$str.= " cursor:pointer; \n";
	$str.= " cursor:hand; \n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .background {\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image']."\") ;\n":"";
	$str.=$swmenupro['sub_back']?" background-color: ".$swmenupro['sub_back']." !important ; \n":"";
	$str.= " position:absolute ; \n";
	$str.= " left:0px; top:0px; \n";
	$str.= " z-index:1; \n";
	$str.= " opacity:". ($swmenupro['specialA']/100)."; \n";
	$str.= " filter:alpha(opacity=". ($swmenupro['specialA']).") \n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .shadowRight { \n";
	$str.= " position:absolute ; \n";
	$str.= " z-index:3; \n";
	if($show_shadow){
	$str.= " top:3px; width:2px; \n";
	}else{
		$str.= " top:-3000px; width:2px; \n";
	}
	$str.= " opacity:". ($swmenupro['specialA']/100)."; \n";
	$str.= " filter:alpha(opacity=". ($swmenupro['specialA']).")\n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .shadowBottom { \n";
	$str.= " position:absolute ; \n";
	$str.= " z-index:1; \n";
	if($show_shadow){
	$str.= " left:3px; height:2px; \n";
	}else{
	$str.= " left:-3000px; height:2px; \n";	
	}
	$str.= " opacity:". ($swmenupro['specialA']/100)."; \n";
	$str.= " filter:alpha(opacity=". ($swmenupro['specialA']).")\n";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .item.hover {\n";
	$str.= is_file($absolute_path."/".$swmenupro['sub_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['sub_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['sub_over']?" background-color: ".$swmenupro['sub_over']." !important ; \n":"";
	$str.= "}\n";

	$str.= ".transMenu". $swmenupro['id']." .item img { \n";
	$str.= " margin-left:10px !important ; \n";
	$str.= "}\n";

	$str.= "table.menu". $swmenupro['id']." {\n";
	$str.= " top: ". $swmenupro['main_top']."px; \n";
	$str.= " left: ". $swmenupro['main_left']."px; \n";
	$str.= " position:relative ; \n";
	$str.= " margin:0px !important ; \n";
	$str.= " border: ". $swmenupro['main_border']." ; \n";
	$str.= " z-index: 1; \n";
	$str.= "}\n";

	$str.= "table.menu". $swmenupro['id']." a{\n";
	$str.= " margin:0px !important ; \n";
	$str.= " padding: ". $swmenupro['main_padding']." !important ; \n";
	$str.= " display:block !important; \n";
	$str.= " position:relative !important ; \n";
	$str.= "}\n";

	$str.= "div.menu". $swmenupro['id']." a,\n";
	$str.= "div.menu". $swmenupro['id']." a:visited,\n";
	$str.= "div.menu". $swmenupro['id']." a:link {\n";
	if ($swmenupro['main_width']!=0){$str.= " width:".$swmenupro['main_width']."px; \n";}
	if ($swmenupro['main_height']!=0){$str.= " height:".$swmenupro['main_height']."px; \n";}
	$str.= " font-size: ". $swmenupro['main_font_size']."px !important ; \n";
	$str.= " font-family: ". $swmenupro['font_family']." !important ; \n";
	$str.= " text-align: ". $swmenupro['main_align']." !important ; \n";
	$str.= " font-weight: ". $swmenupro['font_weight']." !important ; \n";
	$str.=$swmenupro['main_font_color']?" color: ".$swmenupro['main_font_color']." !important ; \n":"";
	$str.= " text-decoration: none !important ; \n";
	$str.= " margin-bottom:0px !important ; \n";
	$str.= " display:block !important; \n";
	$str.= " white-space:nowrap ; \n";
	$str.= "}\n";

	$str.= "div.menu". $swmenupro['id']." td {\n";
	if (substr($swmenupro['orientation'],0,8)=="vertical"){
		$str.= " border-right: ".$swmenupro['main_border_over']." ; \n";
	}else{
		$str.= " border-bottom: ".$swmenupro['main_border_over']." ; \n";
	}
	$str.= " border-top: ". $swmenupro['main_border_over']." ; \n";
	$str.= " border-left: ". $swmenupro['main_border_over']." ; \n";
	$str.= is_file($absolute_path."/".$swmenupro['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image']."\") ;\n":"";
	$str.=$swmenupro['main_back']?" background-color: ".$swmenupro['main_back']." !important ; \n":"";
	$str.= "} \n";

	$str.= "div.menu". $swmenupro['id']." td.last".$swmenupro['id']." {\n";
	if (substr($swmenupro['orientation'],0,8)=="vertical"){
		$str.= " border-bottom: ".$swmenupro['main_border_over']." ; \n";
	}else{
		$str.= " border-right: ".$swmenupro['main_border_over']." ; \n";
	}
	$str.= "} \n";
	
	$str.= "#trans-active".$swmenupro['id']." a{\n";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.= is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.= "} \n";

	

	$str.= "#menu". $swmenupro['id']." a.hover   { \n";
	$str.= is_file($absolute_path."/".$swmenupro['main_back_image_over']) ? " background-image: URL(\"".$live_site."/".$swmenupro['main_back_image_over']."\") ;\n":"";
	$str.=$swmenupro['main_font_color_over']?" color: ".$swmenupro['main_font_color_over']." !important ; \n":"";
	$str.=$swmenupro['main_over']?" background-color: ".$swmenupro['main_over']." !important ; \n":"";
	$str.= "}\n";

	$str.= "#menu". $swmenupro['id']." span {\n";
	$str.= " display:none; \n";
	$str.= "}\n";

	$str.= "#menu". $swmenupro['id']." a img.seq1,\n";
	$str.= ".transMenu". $swmenupro['id']." img.seq1\n";
	$str.= "{\n";
	$str.= " display:    inline; \n";
	$str.= "}\n";

	$str.= "#menu". $swmenupro['id']." a.hover img.seq2,\n";
	$str.= ".transMenu". $swmenupro['id']." .item.hover img.seq2 \n";
	$str.= "{\n";
	$str.= " display:   inline; \n";
	$str.= "}\n";

	$str.= "#menu". $swmenupro['id']." a.hover img.seq1,\n";
	$str.= "#menu". $swmenupro['id']." a img.seq2,\n";
	$str.= ".transMenu". $swmenupro['id']." img.seq2,\n";
	$str.= ".transMenu". $swmenupro['id']." .item.hover img.seq1\n";
	$str.= "{\n";
	$str.= " display:   none; \n";
	$str.= "}\n";
	
	$str.="#trans-active". $swmenupro['id']." a img.seq1\n";
	$str.="{\n";
	$str.=" display: none;\n";
	$str.="}\n";

	$str.="#trans-active". $swmenupro['id']." a img.seq2\n";
	$str.="{\n";
	$str.=" display: inline;\n";
	$str.="}\n";
	
	
	for($i=0;$i<count($ordered);$i++){
		
		if($ordered[$i]['NCSS']){
			$str.=trans_customcss($ordered,$ordered[$i],$swmenupro['id'],"");
			$str.=$ordered[$i]['NCSS'];
			$str.="\n}\n"; 				
			}
		if($ordered[$i]['OCSS']){
			$str.=trans_customcss($ordered,$ordered[$i],$swmenupro['id'],":hover");
			$str.=$ordered[$i]['OCSS'];
			$str.="\n}\n"; 				
			}
	}
	return $str;
}

function trans_customcss($ordered,$item,$id,$class){
	$str="";
	if($item['indent']==0){
		if($class){
			$str.="#trans-active".$id." a#menu".$id.$item['ID'].",\n";
			$str.="a.hover#menu".$id.$item['ID'].",\n";
			$str.="a#menu".$id.$item['ID'].$class."{\n";
		}else{
			$str.="#menu".$id.$item['ID']."{\n";
		}
	}else{
		$itemtop=$item;
		$subcount=0;
		$subcount2=0;
		$clname="";
		for($i=0;$i<(count($ordered)-1);$i++){
			if(($item['PARENT']==$ordered[$i]['PARENT'])&&($ordered[$i]['ORDER']==1)){
			    	$itemtop=$ordered[$i];	   
				}
				if($ordered[$i]["ID"]==$itemtop['ID']){
				$subcount2=$subcount;
				}
				if ((@$ordered[($i+1)]['PARENT']==$ordered[$i]['ID'] )){
			    	$subcount++;
				}
			}
		$clname="#TransMenu".($subcount2-1)."-".($item['ORDER']-1);	
		if($class){
		$str.=$clname.$class." {\n";
		}else{
		$str.=$clname.$class." {\n";
		}
	}
	return $str;
}




?>
