<?php

/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined( 'JPATH_PLATFORM' ) or die;
jimport( 'joomla.database.tablenested' );

/**
 * Category table 
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class JTableTipoGestion extends JTable
{

    /**
     * Constructor
     *
     * @param   JDatabase  &$db  A database connector object
     *
     * @since   11.1
     */
    public function __construct( &$db )
    {
        parent::__construct( '#__gen_tipo_gestion', 'intIdTipoGestion_tpg', $db );

        $this->access = (int) JFactory::getConfig()->get( 'access' );
    }

    function getTiposGestion()
    {
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );

            $query->select( 'tg.intIdTipoGestion_tpg AS id, 
                            upper( tg.strNombre_tpg ) AS nombre' );
            $query->from( '#__gen_tipo_gestion AS tg' );
            $query->where( 'tg.published = 1' );

            $db->setQuery( (string) $query );

            $tiposGestion = ($db->loadObjectList()) ? $db->loadObjectList() : false;

            return $tiposGestion;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.programas.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}

