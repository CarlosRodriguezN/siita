<?php/* ----------------------------------------------------------------------  # mod_ot_lightboxlogin - OT Lightbox Login Module For Joomla! 1.7  #----------------------------------------------------------------------  # author OmegaTheme.com  # copyright Copyright(C) 2008 - 2011 OmegaTheme.com. All Rights Reserved.  # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL  # Website: http://omegatheme.com  # Technical support: Forum - http://omegatheme.com/forum/  ------------------------------------------------------------------------ */// no direct accessdefined( '_JEXEC' ) or die;// Include the Login functions only oncerequire_once dirname( __FILE__ ) . DS . 'helper.php';$params->def( 'greeting', 1 );$type   = modOtlightboxLoginHelper::getType();$return = modOtlightboxLoginHelper::getReturnURL( $params, $type );$user   = JFactory::getUser();require JModuleHelper::getLayoutPath( 'mod_ot_lightboxlogin', $params->get( 'layout', 'default' ) );