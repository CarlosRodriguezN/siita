<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controlleradmin library
jimport( 'joomla.application.component.controlleradmin' );

/**
 * Conflictos Controller
 */
class ConflictosControllerFuentes extends JControllerAdmin
{

    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_CONFLICTOS_FUENTES';

    public function getModel( $name = 'Fuente', $prefix = 'ConflictosModel' )
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }

}