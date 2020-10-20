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
class ProyectosControllerReporteToExcel extends JControllerForm
{
    protected $view_list = 'ReporteToExcel';

    public function excel()
    {
        $view = $this->getView( 'reportetoexcel', 'html' );
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