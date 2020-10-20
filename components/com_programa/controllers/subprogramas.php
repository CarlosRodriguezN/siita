<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Programas Controller
 */
class ProgramaControllerSubProgramas extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_SUB_PROGRAMA_SUBPROGRAMA';
    
    public function getModel( $name = 'SubPrograma', $prefix = 'ProgramaModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }

    
} 