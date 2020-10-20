<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');
 
/**
 * Clase que muestra un conjunto de Proyectos
 */
class ProyectosViewReporteToExcel extends JView
{
    protected $_items;

    /**
    * HelloWorlds view display method
    * @return void
    */
    function display( $tpl = null )
    {
        $modProyecto = $this->getModel();

        //  Obtengo Informacion de un Indicadores asociados a una entidad
        $this->_items = $modProyecto->getDtaIndicador( JRequest::getVar( 'idIndEntidad' ) );
        
        //  Carga Indicadores en formato XML
        $valueToXls .= $this->loadTemplate();
        
        //  Genera y descarga archivo PDF de los Indicadores de un proyecto
        $this->_generarExcel( $this->_items["nombreIndicador"], trim( $valueToXls, " \t\n\r\0\x0B" ) );
    }
    
    private function _generarExcel( $nombreArchivo, $dta )
    {
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=". $nombreArchivo .".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        
        print preg_replace("/\r\n+|\r+|\n+|\t+/i", " ", $dta);
        
        exit;
    }
}