<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TableUser extends JTable
{

    var $id = null;
    var $cmp_name = null;

    function __construct( &$db )
    {
        parent::__construct( 'rds_users', 'id', $db );
    }

//lista de datos de la tabla User

    function getClientList( $cmp_id )
    {
        $result = null;

        try{
            $db = & JFactory::getDBO();
            //$query = "SELECT u.id, u.name AS item FROM rds_users AS u INNER JOIN rds_vts_clients AS c ON c.usr_id = u.id WHERE c.cmp_id = '{$cmp_id}' AND u.owner='0'";
            $query = $db->getQuery( true );
            $query->select( 'u.id, u.name AS item' );
            $query->from( '#__users AS u' );
            $query->join( 'inner', '#__vts_clients AS c ON c.usr_id = u.id' );
            $query->where( "c.cmp_id = '{$cmp_id}' AND u.owner='0'" );
            $db->setQuery( (string)$query );

            $clients = $db->loadObjectList();
            $result = $this->_rdsClientObjectConvert( $clients );
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'mod_rdsTree.tables.users.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }

        return $result;
    }

    function _rdsClientObjectConvert( $clients )
    {
        $clientsJSON = array ();

        try{
            foreach ( $clients as $client ){
                $clientJSON = (object)null;
                $clientJSON->property = (object)null;
                $clientJSON->property->name = $client->item;
                $clientJSON->property->hasCheckbox = false;
                $clientJSON->property->id = $client->id;
                $clientJSON->type = "user";

                $clientsJSON[$client->id] = $clientJSON;
            }
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'mod_rdsTree.tables.users.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }

        return $clientsJSON;
    }

    function getUserList( $cmp_id, $owner )
    {
        $result = null;

        try{
            $db = & JFactory::getDBO();

            //$query = "SELECT u.id, u.name AS item FROM rds_users AS u INNER JOIN rds_vts_clients AS c ON c.usr_id = u.id WHERE c.cmp_id = '{$cmp_id}' AND u.owner='{$owner}'";
            $query = $db->getQuery( true );
            $query->select( 'u.id, u.name AS item' );
            $query->from( '#__users AS u' );
            $query->join( 'inner', '#__vts_clients AS c ON c.usr_id = u.id' );
            $query->where( "c.cmp_id = '{$cmp_id}' AND u.owner='{$owner}'" );
            $db->setQuery( (string)$query );

            $users = $db->loadObjectList();
            $result = $this->_rdsUserObjectConvert( $users );
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'mod_rdsTree.tables.users.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }

        return $result;
    }

    function _rdsUserObjectConvert( $users )
    {
        $usersJSON = array ();

        try{
            foreach ( $users as $user ){
                $userJSON = (object)null;
                $userJSON->property = (object)null;
                $userJSON->property->name = $user->item;
                $userJSON->property->hasCheckbox = false;
                $userJSON->property->id = $user->id;
                $userJSON->type = "user";

                $usersJSON[$user->id] = $userJSON;
            }
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'mod_rdsTree.tables.users.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }

        return $usersJSON;
    }

}