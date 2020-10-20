<?php

/**
 * @version		$Id: helloworld.php 60 2010-11-27 18:45:40Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * Hello Table class
 */
class ProgramaTablePrograma extends JTable
{

    /**
     * Constructor
     *
     * @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pfr_programa', 'intCodigo_prg', $db );
    }

    /**
     * Overloaded bind function
     *
     * @param       array           named array
     * @return      null|string     null is operation was satisfactory, otherwise returns an error
     * @see JTable:bind
     * @since 1.5
     */
    public function bind( $array, $ignore = '' )
    {
        if( isset( $array['params'] ) && is_array( $array['params'] ) ){
            // Convert the params field to a string.
            $parameter = new JRegistry;
            $parameter->loadArray( $array['params'] );
            $array['params'] = ( string ) $parameter;
        }

        // Bind the rules.
        if( isset( $array['rules'] ) && is_array( $array['rules'] ) ){
            $rules = new JAccessRules( $array['rules'] );
            $this->setRules( $rules );
        }

        return parent::bind( $array, $ignore );
    }

    /**
     * Overloaded load function
     *
     * @param       int $pk primary key
     * @param       boolean $reset reset data
     * @return      boolean
     * @see JTable:load
     */
    public function load( $pk = null, $reset = true )
    {
        if( parent::load( $pk, $reset ) ){
            // Convert the params field to a registry.
            $params = new JRegistry;
            $params->loadJSON( $this->params );
            $this->params = $params;
            return true;
        } else{
            return false;
        }
    }

    /**
     * Method to compute the default name of the asset.
     * The default name is in the form `table_name.id`
     * where id is the value of the primary key of the table.
     *
     * @return      string
     * @since       2.5
     */
    protected function _getAssetName()
    {
        $k = $this->_tbl_key;
        return 'com_programa.message.' . ( int ) $this->$k;
    }

    /**
     * Method to return the title to use for the asset table.
     *
     * @return      string
     * @since       2.5
     */
    protected function _getAssetTitle()
    {
        return $this->greeting;
    }

    /**
     * Method to get the asset-parent-id of the item
     *
     * @return      int
     */
    protected function _getAssetParentId()
    {
        // We will retrieve the parent-asset from the Asset-table
        $assetParent = JTable::getInstance( 'Asset' );

        // Default: if no asset-parent can be found we take the global asset
        $assetParentId = $assetParent->getRootId();

        // Find the parent-asset
        if( ($this->catid) && !empty( $this->catid ) ){
            // The item has a category as asset-parent
            $assetParent->loadByName( 'com_programa.category.' . ( int ) $this->catid );
        } else{
            // The item has the component as asset-parent
            $assetParent->loadByName( 'com_programa' );
        }

        // Return the found asset-parent-id
        if( $assetParent->id ){
            $assetParentId = $assetParent->id;
        }

        return $assetParentId;
    }

}
