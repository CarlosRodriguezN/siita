<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controlleradmin library
jimport( 'joomla.application.component.controlleradmin' );

/**
 * Conflictos Controller
 */
class ConflictosControllerTemas extends JControllerAdmin
{

    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_CONFLICTOS_TEMAS';

    public function getModel( $name = 'Tema', $prefix = 'ConflictosModel' )
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }

}