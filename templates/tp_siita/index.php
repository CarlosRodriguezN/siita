<?php
/**
 * @package Joomla.Site
 * @subpackage IQ_Gtel
 * @copyright Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
JHtml::_('behavior.framework', true);

defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__) . DS . 'ja_vars.php');

JHTML::_('behavior.modal');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >

    <head>
        <jdoc:include type="head" />
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/system/css/system.css" type="text/css" media="screen,projection" />
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/layout.css" type="text/css" media="screen,projection" />
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/modules.css" type="text/css" media="screen,projection" />
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/components.css" type="text/css" media="screen,projection" />
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" media="screen,projection" />
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/print.css" type="text/css" media="print" />
    </head>
    <body>
        <?php $mivar = JRequest::getVar("option", ''); ?>
        <?php if (($tmpTools->isFrontPage()) && ($mivar != "com_search") && ($mivar != "com_programa")) : ?> 
            <div id="header">
                <div id="header-middle">
                    <div id="logos">
                        <jdoc:include type="modules" name="logo" />
                    </div>
                    <div id="social">
                        <jdoc:include type="modules" name="social" style="lhtml"/>
                    </div>
                </div>
            </div>
            <div id="menu-principal">
                <div id="menu-middle">
                    <jdoc:include type="modules" name="menu" />
                </div>
            </div>
            <div id="system-tot">
                <!-- AGREGADO PARA GESTION DE MENSAJES -->
                <jdoc:include type="message" />
            </div>
            <div id="slide">
                <div id="slide-middle">
                    <jdoc:include type="modules" name="slide" />
                </div>
            </div>
            <div id="content-system">
                <?php if ($this->countModules('left-map')) : ?>
                <div id="colum-left">
                    <jdoc:include type="modules" name="left-map" />
                </div>
                <?php endif; ?>
                <div id="content-map">
                    <jdoc:include type="component" />
                </div>
            </div>
        <?php endif; ?>   
        <?php if (!($tmpTools->isFrontPage()) && ($mivar == "com_content") && ($mivar != "com_programa") ) : ?>
            <div id="header">
                <div id="header-middle">
                    <div id="logo">
                        <jdoc:include type="modules" name="logo" />
                    </div>
                    <div id="social">
                        <jdoc:include type="modules" name="social" />
                    </div>
                    <div id="planes">
                        <jdoc:include type="modules" name="planes" />
                    </div>
                </div>
            </div>
            <div id="menu-principal">
                <div id="menu-middle">
                    <jdoc:include type="modules" name="menu" />
                </div>
            </div>
            <div id="system-tot">
                <!-- AGREGADO PARA GESTION DE MENSAJES -->
                <jdoc:include type="message" />
            </div>
            <div id="content-inter">
                <div id="content">
                    <jdoc:include type="component" />
                </div>
            </div>
        <?php endif; ?>

        <?php if( !($tmpTools->isFrontPage()) && ($mivar != "com_content") && ($mivar == "com_programa") OR ($mivar == "com_adminmapas")OR ($mivar == "com_conflictos") OR ($mivar == "com_unidadgestion") OR ($mivar == "com_reporte") OR ( $mivar == "com_indicadores" ) OR ( $mivar == "com_proyectos" ) OR ( $mivar == "com_mantenimiento" ) OR ($mivar == "com_contratos") OR ($mivar == "com_canastaproy") OR ($mivar == "com_pei") OR ($mivar == "com_poa") OR ($mivar == "com_panel") OR ($mivar == "com_apirest") OR ($mivar == "com_funcionarios") OR ($mivar == "com_agendas")) : ?>
            <div id="header">
                <div id="logo">
                    <jdoc:include type="modules" name="logo" />
                </div>
                <div id="social">
                    <jdoc:include type="modules" name="social" />
                </div>
                <div id="planes">
                        <jdoc:include type="modules" name="planes" />
                </div>
            </div>

            <div id="menu-principal-component">
                <div id="menu-middle-component">
                    <jdoc:include type="modules" name="menu" />
                </div>
            </div>

            <div id="system-tot">
                <!-- AGREGADO PARA GESTION DE MENSAJES -->
                <jdoc:include type="message" />
                <jdoc:include type="component" />
            </div>
        <?php endif; ?>

        <div id="footer-center">
            <div id="bg_left">
            </div>
            <div id="footer">
                <jdoc:include type="modules" name="positionf-1" />
            </div>
            <div id="bg_right">
            </div>
        </div>
    </body>
</html>