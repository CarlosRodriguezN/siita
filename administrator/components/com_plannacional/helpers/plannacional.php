<?php

/**
 * @package	Joomla.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license	License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

class PlanNacionalHelper
{
    public static function addSubmenu( $submenu )
    {
        //  Categorias
        JSubMenuHelper::addEntry(   JText::_('COM_PLANNACIONAL_SUBMENU_CATEGORIA'), 
                                    'index.php?option=com_plannacional&view=categorias', 
                                    $submenu == 'proyectos' );
        
        
        //  Tipos de Indicador
        JSubMenuHelper::addEntry(   JText::_('COM_PLANNACIONAL_SUBMENU_TIPOINDICADOR'), 
                                    'index.php?option=com_plannacional&view=tiposindicador', 
                                    $submenu == 'proyectos' );
        
        
        //  Indicadores Nacionales
        JSubMenuHelper::addEntry(   JText::_('COM_PLANNACIONAL_SUBMENU_INDICADORNACIONAL'), 
                                    'index.php?option=com_plannacional&view=indicadoresnacionales', 
                                    $submenu == 'proyectos' );
        
        
        //  Periodicidades
        JSubMenuHelper::addEntry(   JText::_('COM_PLANNACIONAL_SUBMENU_PERIODICIDAD'), 
                                    'index.php?option=com_plannacional&view=periodicidades', 
                                    $submenu == 'proyectos' );
        
        //  Planes Nacionales
        JSubMenuHelper::addEntry(   JText::_('COM_PLANNACIONAL_SUBMENU_PLANNACIONAL'), 
                                    'index.php?option=com_plannacional&view=planesnacionales', 
                                    $submenu == 'proyectos' );
        
        //  Objetivos Nacionales
        JSubMenuHelper::addEntry(   JText::_('COM_PLANNACIONAL_SUBMENU_OBJETIVONACIONAL'), 
                                    'index.php?option=com_plannacional&view=objetivosnacionales', 
                                    $submenu == 'proyectos' );
        
        //  Politica Nacional
        JSubMenuHelper::addEntry(   JText::_('COM_PLANNACIONAL_SUBMENU_POLITICANACIONAL'), 
                                    'index.php?option=com_plannacional&view=politicasnacionales', 
                                    $submenu == 'proyectos' );
        
        //  Linea Base
        JSubMenuHelper::addEntry(   JText::_('COM_PLANNACIONAL_SUBMENU_LINEABASE'), 
                                    'index.php?option=com_plannacional&view=lineasbase', 
                                    $submenu == 'proyectos' );
        
        
        $document = JFactory::getDocument();

        if ( $submenu == 'proyectos' ){ 
            $document->setTitle(JText::_('COM_COSTOS_ADMINISTRATION_PROYECTO'));
        }
    }
}