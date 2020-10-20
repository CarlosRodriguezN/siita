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
 * Conflictos component helper.
 */
abstract class ConflictosHelper
{

    /**
     * Get the actions
     */
    public static function getActions( $messageId = 0 )
    {
        jimport( 'joomla.access.access' );
        $user = JFactory::getUser();
        $result = new JObject;

        if( empty( $messageId ) ){
            $assetName = 'com_conflictos';
        } else{
            $assetName = 'com_conflictos.message.' . ( int ) $messageId;
        }

        $actions = JAccess::getActions( 'com_conflictos', 'component' );
        
        foreach( $actions as $action ){
            $result->set( $action->name, $user->authorise( $action->name, $assetName ) );
        }

        return $result;
    }

}
