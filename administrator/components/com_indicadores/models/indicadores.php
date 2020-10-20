<?php

/**
 * @version		$Id: helloworlds.php 59 2010-11-27 14:17:52Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import the Joomla modellist library
jimport( 'joomla.application.component.modellist' );

/**
 * HelloWorlds Model
 */
class IndicadoresModelIndicadores extends JModelList
{
    /**
     * Method to build an SQL query to load the list data.
     *
     * @return	string	An SQL query
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery( true );

        // Select some fields
        $query->select( '   intCodigo_ind AS id,
                            strNombre_ind AS greeting' );

        // From the hello table
        $query->from( '#__ind_indicador' );

        return $query;
    }

}
