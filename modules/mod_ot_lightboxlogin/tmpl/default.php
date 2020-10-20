<?php
/* ----------------------------------------------------------------------
  # mod_ot_lightboxlogin - OT Lightbox Login Module For Joomla! 1.7
  #----------------------------------------------------------------------
  # author OmegaTheme.com
  # copyright Copyright(C) 2008 - 2011 OmegaTheme.com. All Rights Reserved.
  # @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Website: http://omegatheme.com
  # Technical support: Forum - http://omegatheme.com/forum/
  ------------------------------------------------------------------------ */

// no direct access
defined( '_JEXEC' ) or die;
JHtml::_( 'behavior.keepalive' );
JHtml::_( 'behavior.formvalidation' );

$doc = & JFactory::getDocument();
$doc->addStyleSheet( 'modules/mod_ot_lightboxlogin/assets/css/ot_lightbox_login.css' );
$doc->addCustomTag( '   <!--[if IE 9]>
                        <style type="text/css">
                                .ot-tab-area span.ot-tab {
                                        padding-bottom: 4px;
                                }
                        </style>
                        <![endif]-->' );
?>
<?php if( $type == 'logout' ) :?>
    <form action="<?php echo JRoute::_( 'index.php', true, $params->get( 'usesecure' ) ); ?>" method="post" id="ot-logout-form">
        <div class="ot-logout-wrap">
            <?php if( $params->get( 'greeting' ) ) : ?>
                <div class="ot-greeting">
                    <div class="ot-logged-greeting">
                        <div class="ot-logged-greeting-inner">
                            <?php
                            if( $params->get( 'name' ) == 0 ) :{
                                    echo JText::sprintf( 'MOD_OTLIGHTBOXLOGIN_HINAME', $user->get( 'name' ) );
                                } else :{
                                    echo JText::sprintf( 'MOD_OTLIGHTBOXLOGIN_HINAME', $user->get( 'username' ) );
                                } endif;
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php   $logoutTitle = JString::trim( $params->get( 'logout_title' ) );
                    if( $logoutTitle == '' ){
                        $logoutTitle = JText::_( 'JLOGOUT' );
                    }?>
            
            <div class="ot-logout-button">
                <div class="ot-logout-button-inner">
                    <input type="submit" name="Submit" class="button" value="<?php echo $logoutTitle; ?>" />
                </div>
            </div>

            <input type="hidden" name="option" value="com_users" />
            <input type="hidden" name="task" value="user.logout" />
            <input type="hidden" name="return" value="<?php echo $return; ?>" />

            <?php echo JHtml::_( 'form.token' ); ?>
        </div>
    </form>

    <div style="clear: both;"></div>
<?php else : ?>
    <?php   JHTML::_( 'behavior.mootools' );
            $doc->addScript( 'modules/mod_ot_lightboxlogin/assets/js/otscript.js' );
            $usersConfig = JComponentHelper::getParams( 'com_users' );  ?>
    
    <div id="ot-login-popup-wrap">
        <span id="ot-login-popup-link" class="modal ot-login-button" title="<?php echo JText::_( 'OT_LOGIN_TITLE' ) ?>">
            <span class="ot-login-popup-inner1">
                <span class="ot-login-popup-inner2">
                    <?php   $loginText      = htmlspecialchars( JString::trim( $params->get( 'login_title' ) ) );
                            $registerText   = htmlspecialchars( JString::trim( $params->get( 'register_title' ) ) );
                            $seperator      = htmlspecialchars( JString::trim( $params->get( 'seperator' ) ) );
                    
                            if( $usersConfig->get( 'allowUserRegistration' ) ){
                                echo '  <span id="ot-login-label"> '
                                . '         <img src="images/btn_siita_1.png" border="0" alt="btn siita" width="82" height="21" /> '
                                . '     </span>';
                            } else{
                                echo '<span id="ot-login-label">' . $loginText . '</span>';
                            }?>
                </span>
            </span>
        </span>
    </div>
    
    <div style="clear: both;"></div>
    
    <div id="ot-lightbox-wrapper" style="display:none;">
        <div class="ot-lightbox-tl">
            <div class="ot-lightbox-tr">
                <div class="ot-lightbox-tm">&nbsp;</div>
            </div>
        </div>
        <div id="ot-lightbox-wrap" style="display: none;clear: both;">
            <div class="ot-tab-wrap">
                <div class="ot-tab-area">
                    <span id ="ot-login-tab" class="ot-tab"><?php echo JText::_( 'JLOGIN' ) ?></span>    
                </div>

                <div id="ot-tab-login-main" class="ot-tab-content">
                    <form action="<?php echo JRoute::_( 'index.php', true, $params->get( 'usesecure' ) ); ?>" method="post" id="ot-login-form" >
                        <div class="ot-login-form-custom">
                            <div class="userdata">

                                <p class="form-login-username">
                                    <label for="modlgn-username"><?php echo JText::_( 'MOD_OTLIGHTBOXLOGIN_VALUE_USERNAME' ) ?></label><br />
                                    <input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
                                </p>

                                <p class="form-login-password">
                                    <label for="modlgn-passwd"><?php echo JText::_( 'JGLOBAL_PASSWORD' ) ?></label><br />
                                    <input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
                                </p>

                                <p class="form-login-remember">&nbsp;</p>

                                <p class="ot-submit">
                                    <input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'MOD_OTLIGHTBOXLOGIN_VALUE_LOGIN' ) ?>" />
                                    <input type="hidden" name="option" value="com_users" />
                                    <input type="hidden" name="task" value="user.login" />
                                    <input type="hidden" name="return" value="<?php echo $return; ?>" />
                                    <?php echo JHtml::_( 'form.token' ); ?>
                                </p>
                            </div>

                            <ul>&nbsp;</ul>
                        </div>
                    </form>
                </div>

                <div id="ot-tab-signup-main" class="ot-tab-content">
                    <?php if( $usersConfig->get( 'allowUserRegistration' ) ) : ?>
                        <div class="ot-registration">
                            <form id="ot-member-registration" action="<?php echo JRoute::_( 'index.php?option=com_users&task=registration.register' ); ?>" method="post" class="form-validate">
                                <div class="ot-user-signup">
                                    <p style="margin-top: 0;"><strong class="red">*</strong> <?php echo JText::_( 'MOD_OTLIGHTBOXLOGIN_REQUIRED' ); ?></p>
                                    <p class="form-signup form-signup-name">
                                        <label class="required" for="jform_name" id="jform_name-lbl">Nombre:<span class="star">&nbsp;*</span></label><br />
                                        <input type="text" size="30" class="required" value="" id="jform_name" name="jform[name]" />
                                    </p>
                                    <p class="form-signup form-signup-username">
                                        <label class="required" for="jform_username" id="jform_username-lbl">Nombre de usuario:<span class="star">&nbsp;*</span></label><br />
                                        <input type="text" size="30" class="validate-username required" value="" id="jform_username" name="jform[username]" />
                                    </p>
                                    <p class="form-signup form-signup-password">
                                        <label class="required" for="jform_password1" id="jform_password1-lbl">Contrase�a:<span class="star">&nbsp;*</span></label><br />
                                        <input type="password" size="30" class="validate-password required" autocomplete="off" value="" id="jform_password1" name="jform[password1]" />
                                    </p>
                                    <p class="form-signup form-signup-confirm-password">
                                        <label class="required" for="jform_password2" id="jform_password2-lbl">Confirmar contrase�a:<span class="star">&nbsp;*</span></label><br />
                                        <input type="password" size="30" class="validate-password required" autocomplete="off" value="" id="jform_password2" name="jform[password2]" />
                                    </p>
                                    <p class="form-signup form-signup-email">
                                        <label class="required" for="jform_email1" id="jform_email1-lbl">Email:<span class="star">&nbsp;*</span></label><br />
                                        <input type="text" size="30" value="" id="jform_email1" class="validate-email required" name="jform[email1]" />
                                    </p>
                                    <p class="form-signup form-signup-confirm-email">
                                        <label class="required" for="jform_email2" id="jform_email2-lbl">Confirmar email:<span class="star">&nbsp;*</span></label><br />
                                        <input type="text" size="30" value="" id="jform_email2" class="validate-email required" name="jform[email2]" />
                                    </p>
                                </div>
                                <div>
                                    <button type="submit" class="validate"><?php echo JText::_( 'JREGISTER' ); ?></button>
                                    <?php echo JText::_( 'COM_USERS_OR' ); ?>
                                    <a href="<?php echo JRoute::_( '' ); ?>" title="<?php echo JText::_( 'JCANCEL' ); ?>"><?php echo JText::_( 'JCANCEL' ); ?></a>
                                    <input type="hidden" name="option" value="com_users" />
                                    <input type="hidden" name="task" value="registration.register" />
                                    <?php echo JHtml::_( 'form.token' ); ?>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="ot-lightbox-bl">
            <div class="ot-lightbox-br">
                <div class="ot-lightbox-bm">
                    <div class="ot-lightbox-login"><?php echo JText::_( 'MOD_OTLIGHTBOXLOGIN_VALUE_ABOUT' ); ?></div>
                </div>
            </div>
        </div>
        
    </div>
<?php endif; ?>