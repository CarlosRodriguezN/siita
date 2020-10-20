<?php

/**
 * @version		$Id: helloworld.php 59 2010-11-27 14:17:52Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined( '_JEXEC' ) or die;

/**
 * HelloWorld component helper.
 */
abstract class UnidadGestionHelper
{

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu( $submenu )
    {
        JSubMenuHelper::addEntry( JText::_( 'COM_UNIDADGESTION_SUBMENU_MESSAGES' ), 'index.php?option=com_unidadgestion', $submenu == 'messages' );
        JSubMenuHelper::addEntry( JText::_( 'COM_UNIDADGESTION_SUBMENU_CATEGORIES' ), 'index.php?option=com_categories&view=categories&extension=com_unidadgestion', $submenu == 'categories' );
        // set some global property
        $document = JFactory::getDocument();
        $document->addStyleDeclaration( '.icon-48-helloworld {background-image: url(../media/com_unidadgestion/images/tux-48x48.png);}' );
        if( $submenu == 'categories' ){
            $document->setTitle( JText::_( 'COM_UNIDADGESTION_ADMINISTRATION_CATEGORIES' ) );
        }
    }

    /**
     * Get the actions
     */
    public static function getActions( $messageId = 0 )
    {
        jimport( 'joomla.access.access' );
        $user = JFactory::getUser();
        $result = new JObject;

        if( empty( $messageId ) ){
            $assetName = 'com_unidadgestion';
        } else{
            $assetName = 'com_unidadgestion.message.' . ( int ) $messageId;
        }

        $actions = JAccess::getActions( 'com_unidadgestion', 'component' );

        foreach( $actions as $action ){
            $result->set( $action->name, $user->authorise( $action->name, $assetName ) );
        }

        return $result;
    }

}
