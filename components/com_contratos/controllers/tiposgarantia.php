<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Contratos Controller
 */
class contratosControllerTiposGarantia extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_CONTRATO_TPOGARANTIA';
    
    public function getModel( $name = 'TiposGarantia', $prefix = 'contratosModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );        
        return $model;
    }
}