<?php

/**
* swmenupro v4.5
* http://swonline.biz
* Copyright 2006 Sean White
**/

defined('_JEXEC') or die('Restricted access');

class com_swmenuproInstallerScript {

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {
	
	 $module_installer = new JInstaller;
        if($module_installer->install(dirname(__FILE__).DS.'admin'.DS.'module')){
            echo 'Module install success', '<br />';
        } else{
          echo 'Module install failed', '<br />';
        }
	
	
	
	$msg="<div align=\"center\">\n";
	$msg.="<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\"> \n";
	$msg.="<tr><td align=\"center\"><img src=\"components/com_swmenupro/images/swmenupro_logo_small.gif\" border=\"0\"/></td></tr>\n";
	//$msg.="<tr><td align=\"center\"><br /> <b>Module Status: ".$module_msg."</b><br /></td></tr>\n";
	$msg.="<tr><td align=\"center\">swMenuPro has been sucessfully installed.  Thankyou for purchasing. <br /> For support, please see the forums at <a href=\"http://www.swmenupro.com\">www.swmenupro.com</a> </td></tr>\n";
    $msg.="<tr> \n";
    $msg.="<td width=\"100%\" align=\"center\">\n";
	$msg.="<a href=\"http://www.swmenupro.com\" target=\"_blank\">	\n";
	$msg.="<img src=\"components/com_swmenupro/images/swmenupro_footer.png\" alt=\"swmenupro.com\" border=\"0\" />\n";
	$msg.="</a><br/> SWmenuPro &copy;2005 by Sean White\n";
	$msg.="</td> \n";
    $msg.="</tr> \n";
    $msg.="</table> \n";
    $msg.="</div> \n";	
	echo $msg;
	
	
}


 function uninstall($parent) {
        // $parent is the class calling this method
        //echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';

        $db =& JFactory::getDBO();

        // uninstalling jumi module
        $db->setQuery("select extension_id from #__extensions where name = 'swMenuPro' and type = 'module' and element = 'mod_swmenupro'");
        $swmenupro_module = $db->loadObject();
        $module_uninstaller = new JInstaller;
        if($module_uninstaller->uninstall('module', $swmenupro_module->extension_id))
            echo 'Module uninstall success', '<br />';
        else {
            echo 'Module uninstall failed', '<br />';
        }

}
}
?>
