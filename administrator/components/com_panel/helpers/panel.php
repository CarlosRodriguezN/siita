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
abstract class PanelHelper
{

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu( $submenu )
    {
        JSubMenuHelper::addEntry( JText::_( 'COM_PANEL_SUBMENU_MESSAGES' ), 'index.php?option=com_panel', $submenu == 'messages' );
        JSubMenuHelper::addEntry( JText::_( 'COM_PANEL_SUBMENU_CATEGORIES' ), 'index.php?option=com_categories&view=categories&extension=com_panel', $submenu == 'categories' );
        // set some global property
        $document = JFactory::getDocument();
        $document->addStyleDeclaration( '.icon-48-helloworld {background-image: url(../media/com_panel/images/tux-48x48.png);}' );
        if( $submenu == 'categories' ){
            $document->setTitle( JText::_( 'COM_PANEL_ADMINISTRATION_CATEGORIES' ) );
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
            $assetName = 'com_panel';
        } else{
            $assetName = 'com_panel.message.' . ( int ) $messageId;
        }

        $actions = JAccess::getActions( 'com_panel', 'component' );

        foreach( $actions as $action ){
            $result->set( $action->name, $user->authorise( $action->name, $assetName ) );
        }

        return $result;
    }

}
