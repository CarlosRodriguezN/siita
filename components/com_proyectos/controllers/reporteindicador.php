<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');


//  Adjunto libreria para la creacion de archivos Excel
jimport( 'excel.PHPExcel' );

//  Adjunto libreria para la creacion de archivos Excel
jimport( 'word.PHPWord' );

//  Adjunto libreria para creacion de archivos PDF
require_once( JPATH_LIBRARIES.DS.'dompdf'.DS.'dompdf_config.inc.php' );


/**
 * 
 *  Controlador Fases
 * 
 */



class ProyectosControllerReporteIndicador extends JControllerForm
{
    protected $view_list = 'reporteindicador';

    public function pdf()
    {
        $view = $this->getView( 'reporteindicador', 'html' );
        $view->setModel( $this->getModel( 'proyecto' ), true );

        $view->display();
    }

    public function cancel()
    {
        $this->setRedirect(
            JRoute::_(  'index.php?option=' . $this->option . '&view=panel', false )
        );
    }
}